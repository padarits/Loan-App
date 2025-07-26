<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Return all users

    public function index(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'email_verified_at',
            4 => 'created_at',
            // Add more columns as per your users table
        ];
    
        $totalData = User::count();
        $totalFiltered = $totalData;
    
        $limit = intval($request->input('length'));
        $start = intval($request->input('start'));
        $order = $columns[intval($request->input('order.0.column'))];
        $dir = ($request->input('order.0.dir') === 'desc') ? 'desc' : 'asc';
    
        if(empty($request->input('search.value')))
        {
            $users = User::offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value');
    
            $users = User::where('name','LIKE',"%{$search}%")
                         ->orWhere('email', 'LIKE',"%{$search}%")
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
    
            $totalFiltered = User::where('name','LIKE',"%{$search}%")
                                 ->orWhere('email', 'LIKE',"%{$search}%")
                                 ->count();
        }
    
        $data = [];
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $nestedData['id'] = $user->id;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['email_verified_at'] = ($user->email_verified_at) ? $user->email_verified_at->format('d.m.Y H:i:s') : 'Nav apstiprināts'; // Format as needed
                $nestedData['created_at'] = $user->created_at->format('d.m.Y H:i:s'); // Format as needed
                // Add more fields as necessary
    
                $data[] = $nestedData;
            }
        }
    
        $json_data = [
            "draw"            => intval($request->input('draw')),  // For security, use intval
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];
    
        return response()->json($json_data);
    }

    // Store a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = User::create($validated);

        return response()->json($post, 201);
    }

    // Show a single post
    public function show($id)
    {
        $post = User::find($id);

        if ($post) {
            return response()->json($post, 200);
        }

        return response()->json(['message' => 'Post not found'], 404);
    }

    // Update a post
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        //$post->update($validated);

        return response()->json($user, 200);
    }

    // Delete a post
    public function destroy($id)
    {
        /*$post = User::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();*/

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}

