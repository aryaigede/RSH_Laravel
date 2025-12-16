<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\RekamMedis;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Auth;

class DokterDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $roleUser = RoleUser::with('role')
            ->where('iduser', $user->id)
            ->whereHas('role', function ($q) {
                $q->where('nama_role', 'Dokter');
            })
            ->first();

        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'dokter', 'dokter.user', 'details.kodeTindakanTerapi'])
            ->when($roleUser, function ($q) use ($roleUser) {
                $q->where('dokter_pemeriksa', $roleUser->idrole_user);
            })
            ->orderByDesc('idrekam_medis')
            ->get();

        $patients = $rekamMedis
            ->pluck('pet')
            ->filter()
            ->unique('idpet')
            ->values();

        $doctorProfile = Dokter::with('user')->where('id_user', $user->id)->first();

        return view('dashboard.dokter.index', [
            'user' => $user,
            'doctorProfile' => $doctorProfile,
            'patients' => $patients,
            'rekamMedis' => $rekamMedis,
        ]);
    }
}
