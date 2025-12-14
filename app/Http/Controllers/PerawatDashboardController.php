<?php

namespace App\Http\Controllers;

class PerawatDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.perawat.index');
    }
}
