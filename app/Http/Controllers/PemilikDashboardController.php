<?php

namespace App\Http\Controllers;

use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\Auth;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pemilik = Pemilik::with('user')->where('iduser', $user->id)->first();

        $pets = Pet::with(['rasHewan', 'pemilik.user'])
            ->where('idpemilik', optional($pemilik)->idpemilik)
            ->get();

        $temuDokter = TemuDokter::with(['pet', 'roleUser.user', 'rekamMedis'])
            ->whereHas('pet', function ($q) use ($pemilik) {
                $q->where('idpemilik', optional($pemilik)->idpemilik);
            })
            ->orderBy('waktu_daftar')
            ->get();

        $rekamMedis = RekamMedis::with(['pet', 'dokter.user', 'details.kodeTindakanTerapi'])
            ->whereHas('pet', function ($q) use ($pemilik) {
                $q->where('idpemilik', optional($pemilik)->idpemilik);
            })
            ->orderByDesc('idrekam_medis')
            ->get();

        return view('dashboard.pemilik.index', [
            'user' => $user,
            'pemilik' => $pemilik,
            'pets' => $pets,
            'temuDokter' => $temuDokter,
            'rekamMedis' => $rekamMedis,
        ]);
    }
}
