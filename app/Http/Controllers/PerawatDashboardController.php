<?php

namespace App\Http\Controllers;

use App\Models\DetailRekamMedis;
use App\Models\Perawat;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class PerawatDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'dokter.user', 'details.kodeTindakanTerapi'])
            ->orderByDesc('idrekam_medis')
            ->get();

        $patients = $rekamMedis
            ->pluck('pet')
            ->filter()
            ->unique('idpet')
            ->values();

        $detailRekamMedis = DetailRekamMedis::with(['rekamMedis.pet', 'kodeTindakanTerapi'])
            ->orderByDesc('iddetail_rekam_medis')
            ->get();

        $profile = Perawat::with('user')->where('id_user', $user->id)->first();

        return view('dashboard.perawat.index', [
            'user' => $user,
            'profile' => $profile,
            'patients' => $patients,
            'rekamMedis' => $rekamMedis,
            'detailRekamMedis' => $detailRekamMedis,
        ]);
    }
}
