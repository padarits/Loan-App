<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordCheckController extends Controller
{
    public function checkPassword(Request $request)
    {
        // Validē ienākošo pieprasījumu
        try {
            // Validē ienākošo pieprasījumu
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'role' => 'required|string',
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
                if($user->hasRole($request->role)) {
                    return response()->json([
                        'success' => true,
                        'user' => $user->name,
                        'guid' => $user->id,
                        'message' => 'Parole ir pareiza. Lietotājam ir nepieciešamā loma.'
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lietotājam nav nepieciešamās lomas.'
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
}
