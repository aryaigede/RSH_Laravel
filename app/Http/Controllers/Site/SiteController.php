<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home');
    }

    public function layananUmum()
    {
        return view('site.layanan_umum');
    }

    public function struktur()
    {
        return view('site.struktur');
    }

    public function visiMisi()
    {
        return view('site.visi_misi');
    }
}
