<?php

namespace App\Livewire;

use Livewire\Component;

class Table extends Component
{
    public $model;
    public $modelName;
    public $data;
    public $columns = [];
    public $relationships = [];

    public function mount($model, $columns = null, $relationships = [], $modelName = null)
    {
        $this->model = $model;
        $this->modelName = $modelName ?? $model;
        $this->relationships = $relationships;

        $query = $model::query();
        if (!empty($relationships)) {
            $query->with($relationships);
        }
        $this->data = $query->get();

        // Add computed columns for relationships
        $this->data = $this->data->map(function ($item) use ($relationships) {
            foreach ($relationships as $relationship) {
                if ($relationship === 'role') {
                    if ($item->role instanceof \Illuminate\Support\Collection) {
                        $item->roles = $item->role->pluck('nama_role')->join("\n");
                    } else {
                        $item->role = $item->role->nama_role ?? '';
                    }
                } elseif ($relationship === 'rasHewan') {
                    if ($item->rasHewan instanceof \Illuminate\Database\Eloquent\Collection) {
                        $item->ras_hewan = $item->rasHewan->pluck('nama_ras')->join("\n");
                    } else {
                        $item->ras_hewan = $item->rasHewan->nama_ras ?? '';
                    }
                } elseif ($relationship === 'jenisHewan') {
                    $item->jenis_hewan = $item->jenisHewan->nama_jenis_hewan ?? '';
                } elseif ($relationship === 'kategori') {
                    $item->kategori = $item->kategori->nama_kategori ?? '';
                } elseif ($relationship === 'kategoriKlinis') {
                    $item->kategori_klinis = $item->kategoriKlinis->nama_kategori_klinis ?? '';
                } elseif ($relationship === 'pemilik.user') {
                    $item->pemilik = $item->pemilik->user->nama ?? '';
                } elseif ($relationship === 'user') {
                    // For Pemilik, Dokter, Perawat
                    $item->nama_pemilik = $item->user->nama ?? '';
                    $item->nama_dokter = $item->user->nama ?? '';
                    $item->nama_perawat = $item->user->nama ?? '';
                } elseif ($relationship === 'pet') {
                    if ($item->pet) {
                        $petName = $item->pet->nama ?? '';
                        $item->pet = trim($petName, ' -');
                    } else {
                        $item->pet = '';
                    }
                } elseif ($relationship === 'roleUser.user' || $relationship === 'roleUser') {
                    $item->role_user = $item->roleUser && $item->roleUser->user 
                        ? $item->roleUser->user->nama . ' (Dokter)'
                        : '';
                } elseif ($relationship === 'rekamMedis') {
                    $item->rekam_medis = $item->rekamMedis->idrekam_medis ?? '';
                } elseif ($relationship === 'kodeTindakanTerapi') {
                    if ($item->kodeTindakanTerapi) {
                        $code = $item->kodeTindakanTerapi->kode ?? '';
                        $name = $item->kodeTindakanTerapi->nama_tindakan
                            ?? $item->kodeTindakanTerapi->deskripsi_tindakan_terapi
                            ?? '';
                        $item->kode_tindakan_terapi = trim($code . ' - ' . $name, ' -');
                    } else {
                        $item->kode_tindakan_terapi = '';
                    }
                } elseif ($relationship === 'dokter' || $relationship === 'dokter.user') {
                    // Some records may store dokter via role_user relation or via dokter() relation.
                    // Prefer the `dokter` relationship if it exists, otherwise try the related roleUser.
                    if (isset($item->dokter) && $item->dokter && isset($item->dokter->user)) {
                        $item->dokter = $item->dokter->user->nama ?? '';
                    } elseif (isset($item->roleUser) && $item->roleUser && isset($item->roleUser->user)) {
                        $item->dokter = $item->roleUser->user->nama ?? '';
                    } else {
                        $item->dokter = '';
                    }
                }
            }
            return $item;
        });

        if ($columns === null) {
            $this->columns = $this->data->first() ? $this->data->first()->getFillable() : [];
        } else {
            $this->columns = $columns;
        }
    }

    public function render()
    {
        return view('livewire.table');
    }
}