<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

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
            "password" => 'required|string|min:8',
        ]);

        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ];

        $userAuth = User::where('email', Auth::user()->email)->first();
        if (Auth::user()->role === 0) {
            if (Str::is($userAuth->id, $request->id)) {
                $userAuth->update($user);
                JWTAuth::invalidate($request->token);
            } else return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if (Str::is($userAuth->id, $request->id)) {
                $userAuth->update($user);
                JWTAuth::invalidate($request->token);
            } else {
                User::find($request->id)->update($user);
            }
        }


        return response()->json([
            'success' => true,
            'data' => User::find($request->id)
        ], 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);

        $userAuth = User::where('email', Auth::user()->email)->first();
        if (Auth::user()->role === 0) {
            if (Str::is($userAuth->id, $request->id)) {
                $userAuth->update([
                    "email" => 'deleted'
                ]);
                $userAuth->delete();
                JWTAuth::invalidate($request->token);
            } else return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if (Str::is($userAuth->id, $request->id)) {
                $userAuth->update([
                    "email" => 'deleted',
                    "role" => '0'
                ]);
                $userAuth->delete();
                JWTAuth::invalidate($request->token);
            } else {
                $user = User::find($request->id);
                $user->update([
                    "email" => 'deleted'
                ]);
                $user->delete();
            }
        }

        return response()->json([
            "message" => 'User deleted successfully'
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        User::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Permanently deleted user successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        User::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored user successfully'
        ], 200);
    }


    public function indexQuery($request, $name = null, $email = null)
    {
        if ($request == 'active') $users = User::where('name', 'LIKE', '%' . $name . '%')->where('email', 'LIKE', '%' . $email . '%')->paginate(10);
        else if ($request == 'deleted') $users = User::onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%')->where('email', 'LIKE', '%' . $email . '%')->paginate(10);
        else if ($request == 'all') $users = User::withTrashed()->where('name', 'LIKE', '%' .  $name . '%')->where('email', 'LIKE', '%' . $email . '%')->paginate(10);
        else $users = User::paginate(10);
        return response()->json($users, 200);
    }
}
