<?php

namespace App\Http\Controllers;

class DokterDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dokter.index');
    }
}
