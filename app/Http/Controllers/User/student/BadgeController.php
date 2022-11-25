<?php

namespace App\Http\Controllers\User\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    //

    public function index()
    {
    	return view('User.Student.admin.mybadges');
    }
}
