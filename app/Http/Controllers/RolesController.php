<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RolesController extends Controller
{
    public static function getRole()
    {
        return (Auth::user()->role === 1) ? 'Admin' : 'User';
    }
}
