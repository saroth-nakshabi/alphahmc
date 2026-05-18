<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = [];
        $data['users'] = User::all();

        return view('dashboard.user_management.all_users.index', $data);
    }
}
