<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModelAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleId = optional($user->role->first())->idrole;
        $allowedModels = $this->allowedModelsByRole($roleId);

        // If user has no allowed models, block access
        if (empty($allowedModels)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $requestedModel = $request->get('model');

        // Redirect to first allowed model when none provided
        if (!$requestedModel) {
            return redirect()->route('admin.dashboard.data', ['model' => $allowedModels[0]]);
        }

        // Enforce model authorization
        if (!in_array($requestedModel, $allowedModels, true)) {
            return redirect()
                ->route('admin.dashboard.data', ['model' => $allowedModels[0]])
                ->with('error', 'Anda tidak memiliki akses ke data tersebut.');
        }

        return $next($request);
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
            'TemuDokter',
            'Role',
            'User',
        ];

        return match ($roleId) {
            1 => $allModels, // Admin
            2 => ['DetailRekamMedis', 'RekamMedis'], // Dokter
            3 => ['RekamMedis'], // Perawat
            4 => ['Pet', 'Pemilik', 'TemuDokter'], // Resepsionis
            5 => [], // Pemilik 
            default => [],
        };
    }
}