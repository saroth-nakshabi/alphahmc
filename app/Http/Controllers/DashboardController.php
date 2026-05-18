<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Method to show the dashboard
    public function index()
    {
        $data = [];
        $current_user = User::find(Auth::user()->id);

        return view('dashboard.index', $data);
    }
}