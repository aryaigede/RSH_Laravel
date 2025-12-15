<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Sidebar extends Component
{
    public $model;

    public function mount($model = null)
    {
        $this->model = $model;
    }

    public function render()
    {
        $user = User::with('role')->find(Auth::id());
        $roleId = optional($user->role->first())->idrole;

        $menuVisibility = $this->menuVisibilityByRole($roleId);
        $homeRoute = $this->homeRouteByRole($roleId);

        return view('livewire.sidebar', [
            'user' => $user,
            'menuVisibility' => $menuVisibility,
            'homeRoute' => $homeRoute,
        ]);
    }

    private function menuVisibilityByRole(?int $roleId): array
    {
        $base = [
            'JenisHewan' => false,
            'RasHewan' => false,
            'Kategori' => false,
            'KategoriKlinis' => false,
            'KodeTindakanTerapi' => false,
            'Pet' => false,
            'Pemilik' => false,
            'RekamMedis' => false,
            'DetailRekamMedis' => false,
            'TemuDokter' => false,
            'Dokter' => false,
            'Perawat' => false,
            'Role' => false,
            'User' => false,
        ];

        $allowed = match ($roleId) {
            1 => array_keys($base), // Admin sees all
            2 => ['DetailRekamMedis', 'RekamMedis'], // Dokter
            3 => ['RekamMedis'], // Perawat
            4 => ['Pet', 'Pemilik', 'TemuDokter'], // Resepsionis
            5 => [], // Pemilik
            default => [],
        };

        foreach ($allowed as $key) {
            $base[$key] = true;
        }

        return $base;
    }

    private function homeRouteByRole(?int $roleId): string
    {
        return match ($roleId) {
            1 => route('admin.dashboard'),
            2 => route('dokter.dashboard'),
            3 => route('perawat.dashboard'),
            4 => route('resepsionis.dashboard'),
            5 => route('pemilik.dashboard'),
            default => route('home'),
        };
    }
}