<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($request)
    {
        if ($request == 'active') $users = User::paginate(10);
        else if ($request == 'deleted') $users = User::onlyTrashed()->paginate(10);
        else if ($request == 'all') $users = User::withTrashed()->paginate(10);
        else $users = User::paginate(10);
        return response()->json($users, 200);
    }

    public function indexTrashed()
    {
        $users = User::onlyTrashed()->paginate(10);
        return response()->json($users, 200);
    }

    public function indexAll()
    {
        $users = User::withTrashed()->paginate(10);
        return response()->json($users, 200);
    }

    public function show(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $user = User::find($request->id);
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist'
            ], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            "id" => 'required',
            "name" => 'required|string',
            "email" => 'required|email',
            "password" => 'required|string|min:6|max:10'
        ]);
        $user = User::find($request->id)->update($request->all());
        return response()->json([
            'success' => true,
            'data' => User::find($request->id)
        ], 201);
    }


    public function delete(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $user = User::find($request->id);
        $user->update([
            "email" => ''
        ]);
        $user->delete();
        return response()->json([
            "message" => 'User deleted successfully'
        ], 200);
    }
}
