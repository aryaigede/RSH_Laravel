<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function home()
    {
        $user = User::with('role')->find(Auth::id());
        return view('dashboard.home', ['user' => $user]);
    }

    public function index(Request $request)
    {
        $userWithRoles = User::with('role')->find(Auth::id());
        $roleId = optional($userWithRoles->role->first())->idrole;
        $allowedModels = $this->allowedModelsByRole($roleId);

        $requestedModel = $request->get('model');
        $modelName = $requestedModel ?? ($allowedModels[0] ?? 'JenisHewan');
        $modelClass = "App\\Models\\{$modelName}";

        $relationships = [];
        $columns = [];

        switch ($modelName) {
            case 'User':
                $relationships = ['role'];
                $columns = ['nama', 'email', 'roles'];
                break;
            case 'JenisHewan':
                $relationships = ['rasHewan'];
                $columns = ['nama_jenis_hewan', 'ras_hewan'];
                break;
            case 'RasHewan':
                $relationships = ['jenisHewan'];
                $columns = ['nama_ras', 'jenis_hewan'];
                break;
            case 'KodeTindakanTerapi':
                $relationships = ['kategori', 'kategoriKlinis'];
                $columns = ['kode', 'deskripsi_tindakan_terapi', 'kategori', 'kategori_klinis'];
                break;
            case 'Pet':
                $relationships = ['pemilik.user', 'rasHewan'];
                $columns = ['nama', 'tanggal_lahir', 'warna_tanda', 'jenis_kelamin', 'pemilik', 'ras_hewan'];
                break;
            case 'Pemilik':
                $relationships = ['user', 'pets'];
                $columns = ['nama_pemilik', 'no_wa', 'alamat'];
                break;
            case 'RekamMedis':
                $relationships = ['pet', 'dokter'];
                $columns = ['anamnesa', 'temuan_klinis', 'diagnosa', 'pet', 'dokter'];
                break;
            case 'DetailRekamMedis':
                $relationships = ['rekamMedis', 'kodeTindakanTerapi'];
                $columns = ['detail', 'rekam_medis', 'kode_tindakan_terapi'];
                break;
            default:
                $columns = (new $modelClass)->getFillable();
                break;
        }

        return view('dashboard.index', [
            'user' => $userWithRoles,
            'model' => $modelClass,
            'modelName' => $modelName,
            'relationships' => $relationships,
            'columns' => $columns,
            'title' => $modelName
        ]);
    }

    private function allowedModelsByRole(?int $roleId): array
    {
        $allModels = [
            'JenisHewan',
            'RasHewan',
            'Kategori',
            'KategoriKlinis',
            'KodeTindakanTerapi',
            'Pet',
            'Pemilik',
            'RekamMedis',
            'DetailRekamMedis',
            'Role',
            'User',
        ];

        return match ($roleId) {
            1 => $allModels,
            2 => ['DetailRekamMedis', 'RekamMedis'],
            3 => ['RekamMedis'],
            4 => ['Pet', 'Pemilik'],
            5 => [],
            default => [],
        };
    }

    // CREATE - Store new record
    public function store(Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = "App\\Models\\{$modelName}";
        
        // Get fillable fields from the model
        $model = new $modelClass;
        $data = $request->only($model->getFillable());
        
        // Create the record
        $modelClass::create($data);
        
        return redirect()->route('admin.dashboard.data', ['model' => $modelName])
            ->with('success', $modelName . ' created successfully!');
    }

    // UPDATE - Update existing record
    public function update(Request $request, $id)
    {
        $modelName = $request->input('model');
        $modelClass = "App\\Models\\{$modelName}";
        
        $record = $modelClass::findOrFail($id);
        
        // Get fillable fields from the model
        $data = $request->only($record->getFillable());
        
        // Update the record
        $record->update($data);
        
        return redirect()->route('admin.dashboard.data', ['model' => $modelName])
            ->with('success', $modelName . ' updated successfully!');
    }

    // DELETE - Delete record
    public function destroy($id, Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = "App\\Models\\{$modelName}";
        
        $record = $modelClass::findOrFail($id);
        $record->delete();
        
        return redirect()->route('admin.dashboard.data', ['model' => $modelName])
            ->with('success', $modelName . ' deleted successfully!');
    }
}