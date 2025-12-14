<?php

namespace App\Http\Controllers;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.pemilik.index');
    }
}
