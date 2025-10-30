<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userWithRoles = User::with('role')->find(Auth::id());

        $modelName = $request->get('model', 'JenisHewan');
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
        
        return redirect()->route('dashboard', ['model' => $modelName])
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
        
        return redirect()->route('dashboard', ['model' => $modelName])
            ->with('success', $modelName . ' updated successfully!');
    }

    // DELETE - Delete record
    public function destroy($id, Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = "App\\Models\\{$modelName}";
        
        $record = $modelClass::findOrFail($id);
        $record->delete();
        
        return redirect()->route('dashboard', ['model' => $modelName])
            ->with('success', $modelName . ' deleted successfully!');
    }
}