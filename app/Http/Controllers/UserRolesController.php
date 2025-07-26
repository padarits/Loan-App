<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserRolesController extends Controller
{
    public function getUserRoles(Request $request)
    {
        // Validē ienākošo pieprasījumu
        try {
            // Validē ienākošo pieprasījumu
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            // Atgriez validācijas kļūdas atbildi
            return response()->json([
                'success' => false,
                'message' => 'Validācijas kļūda.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Atrodi lietotāju pēc e-pasta
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Lietotājs netika atrasts.'
            ], 404);
        }

        // Pārbaudi vai ievadītā parole ir pareiza
        if (Hash::check($request->password, $user->password)) {
            if ($user->hasVerifiedEmail()) {
                if(!$user->hasRole('not_active')) {
                    // Atgriez lietotāja lomas
                    $roles = $user->getRoleNames(); // Pieņemot, ka tiek izmantots spatie/laravel-permission pakotne
                    $allPermissions = [];
                    foreach ($user->roles as $role) {                   
                        foreach ($role->permissions as $permission) {
                            array_push($allPermissions, $permission->name);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'user' => $user->name,
                        'guid' => $user->id,
                        'message' => 'Parole ir pareiza.',
                        'roles' => $roles,
                        'permissions' => $allPermissions,
                        'information' => self::getInformation($user->id)  
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'user' => $user->name,
                        'message' => 'Lietotājs ir neaktīvs.'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is not verified.'
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Nepareiza parole.'
            ], 401);
        }
    }
    private static function getInformation($userId){
        $postions = \App\Models\EmployeeForPosition::leftJoin('employee_for_position as pos', function ($join) {
                        $join->on('employee_for_position.department_id', '=', 'pos.department_id')
                            ->where('pos.is_head', true);
                    })
                    ->select('employee_for_position.*', 'pos.employee_id as employee_id_is_head')
                    ->where('employee_for_position.employee_id', $userId) // Pārliecinies, ka lieto pareizu kolonnas nosaukumu
                    ->with('department') // Pārliecinies, ka attiecība "department" ir definēta modelī
                    ->get();
        $departments = [];
        $headsByDepartments = [];
        foreach ($postions as $position){
            $positionDepartmentId = $position->department ? $position->department->id : 'without-department';
            if (!isset($departments[$positionDepartmentId])){
                $departments[$positionDepartmentId] = [];
                $headsByDepartments[$positionDepartmentId] = []; 
            }
            if(!in_array($position->id, $departments[$positionDepartmentId])){
                array_push($departments[$positionDepartmentId], $position->id);                
            }
            if(!in_array($position->id, $headsByDepartments[$positionDepartmentId]) and $position->employee_id_is_head){
                array_push($headsByDepartments[$positionDepartmentId], $position->employee_id_is_head);                
            }
        }

        return ["is-head" => $headsByDepartments, "departments" => $departments];
    }
}
