<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This method should return the admin dashboard view
        return view('admin.dashboard');
    }

    public function showRegistrationForm()
    {
        return view('admin.register');
    }

}
