<?php

namespace App\Http\Controllers\DioceseActivityControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('diocese.dashboard.dashboard');
    }
}
