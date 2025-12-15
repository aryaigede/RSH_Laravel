<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TemuDokter;

class AddRowModal extends Component
{
    public $model;
    public $fillable = [];
    public $formData = [];
    public $relationships = [];
    public $manyToManyRelationships = [];

    // Model-specific validation rules
    protected $validationRules = [
        \App\Models\Pet::class => [
            'nama' => 'required|string|max:255',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Jantan,Betina',
        ],
        \App\Models\Pemilik::class => [
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'iduser' => 'required|exists:users,id',
        ],
        \App\Models\JenisHewan::class => [
            'nama_jenis_hewan' => 'required|string|max:255',
        ],
        \App\Models\RasHewan::class => [
            'nama_ras' => 'required|string|max:255',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
        ],
        \App\Models\Kategori::class => [
            'nama_kategori' => 'required|string|max:255',
        ],
        \App\Models\KategoriKlinis::class => [
            'nama_kategori_klinis' => 'required|string|max:255',
            'idkategori' => 'required|exists:kategori,idkategori',
        ],
        \App\Models\KodeTindakanTerapi::class => [
            'kode' => 'required|string|max:50',
            'deskripsi_tindakan_terapi' => 'required|string|max:255',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
            'harga' => 'nullable|numeric|min:0',
        ],
        \App\Models\RekamMedis::class => [
            'idpet' => 'required|exists:pet,idpet',
            'dokter_pemeriksa' => 'required|exists:role_user,idrole_user',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'nullable|string',
        ],
        \App\Models\DetailRekamMedis::class => [
            'idrekam_medis' => 'required|exists:rekam_medis,idrekam_medis',
            'idkode_tindakan_terapi' => 'nullable|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'jumlah' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ],
        \App\Models\TemuDokter::class => [
            'no_urut' => 'required|integer|min:1',
            'waktu_daftar' => 'required|date',
            'status' => 'required|in:N,F',
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user' => 'required|exists:role_user,idrole_user',
        ],
        \App\Models\User::class => [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ],
        \App\Models\Role::class => [
            'nama_role' => 'required|string|max:255',
        ],
        \App\Models\Dokter::class => [
            'id_user' => 'required|exists:users,id',
            'alamat' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:45',
            'bidang_dokter' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        ],
        \App\Models\Perawat::class => [
            'id_user' => 'required|exists:users,id',
            'alamat' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:45',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pendidikan' => 'nullable|string|max:100',
        ],
    ];

    public function mount($model)
    {
        $this->model = $model;
        $instance = new $model;
        $this->fillable = $instance->getFillable();
        $this->relationships = $this->getRelationships($instance);
        $this->manyToManyRelationships = $this->getManyToManyRelationships($instance);
        foreach ($this->fillable as $field) {
            $this->formData[$field] = '';
        }
        foreach ($this->manyToManyRelationships as $relationship) {
            $this->formData[$relationship] = [];
        }

        // Prefill queue data for TemuDokter
        if ($this->model === TemuDokter::class) {
            $date = now()->toDateString();
            $this->formData['waktu_daftar'] = $date;
            $this->formData['no_urut'] = $this->computeNextQueueNumber($date);
            $this->formData['status'] = TemuDokter::STATUS_NEW;
        }
    }

    private function getRelationships($instance)
    {
        $relationships = [];
        $reflection = new \ReflectionClass($instance);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->class === get_class($instance) && !$method->isStatic()) {
                $returnType = $method->getReturnType();
                if ($returnType && $returnType->getName() === 'Illuminate\Database\Eloquent\Relations\Relation') {
                    $relationships[] = $method->getName();
                }
            }
        }

        return $relationships;
    }

    private function getManyToManyRelationships($instance)
    {
        $manyToManyRelationships = [];
        $reflection = new \ReflectionClass($instance);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            // Only check methods defined on this class, not static, and with no required parameters
            if ($method->class === get_class($instance) && !$method->isStatic() && $method->getNumberOfRequiredParameters() === 0) {
                try {
                    $relation = $instance->{$method->getName()}();
                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        $manyToManyRelationships[] = $method->getName();
                    }
                } catch (\Throwable $e) {
                    // Skip if method is not a relationship
                }
            }
        }

        return $manyToManyRelationships;
    }

    public function save()
    {
        // Auto-fill queue fields for TemuDokter before validating
        if ($this->model === TemuDokter::class) {
            $date = $this->formData['waktu_daftar'] ?: now()->toDateString();
            $this->formData['waktu_daftar'] = $date;
            $this->formData['no_urut'] = $this->formData['no_urut'] ?: $this->computeNextQueueNumber($date);
            $this->formData['status'] = $this->formData['status'] ?: TemuDokter::STATUS_NEW;
        }

        $this->validateFormData();

        $newRecord = new $this->model;
        $newRecord->timestamps = false; // Disable timestamps

        // Separate fillable data from many-to-many relationships
        $fillableData = [];
        $manyToManyData = [];

        foreach ($this->formData as $key => $value) {
            if (in_array($key, $this->fillable)) {
                // Convert empty strings to null for nullable fields
                $fillableData[$key] = $value === '' ? null : $value;
            } elseif (in_array($key, $this->manyToManyRelationships)) {
                $manyToManyData[$key] = $value;
            }
        }

        // Hash password if present
        if (isset($fillableData['password']) && !empty($fillableData['password'])) {
            $fillableData['password'] = bcrypt($fillableData['password']);
        }

        $newRecord->fill($fillableData);
        $newRecord->save();

        // Handle many-to-many relationships
        foreach ($manyToManyData as $relationship => $ids) {
            if (!empty($ids)) {
                $newRecord->{$relationship}()->attach($ids);
            }
        }

        $this->dispatch('rowAdded');
        $this->reset('formData');
        return redirect()->route('admin.dashboard.data', ['model' => class_basename($this->model)]);
    }

    private function validateFormData()
    {
        $rules = [];
        $modelClass = $this->model;

        if (isset($this->validationRules[$modelClass])) {
            foreach ($this->validationRules[$modelClass] as $field => $rule) {
                $rules["formData.{$field}"] = $rule;
            }
        } else {
            // Fallback to generic required validation if no specific rules defined
            $rules['formData.*'] = 'required';
        }

        $this->validate($rules);
    }

    public function getRelatedModel($field)
    {
        $mappings = [
            'idpemilik' => \App\Models\Pemilik::class,
            'idras_hewan' => \App\Models\RasHewan::class,
            'idjenis_hewan' => \App\Models\JenisHewan::class,
            'idkategori' => \App\Models\Kategori::class,
            'idkategori_klinis' => \App\Models\KategoriKlinis::class,
            'iduser' => \App\Models\User::class,
            'idrole' => \App\Models\Role::class,
            'idpet' => \App\Models\Pet::class,
            'idkode_tindakan_terapi' => \App\Models\KodeTindakanTerapi::class,
            'idrekam_medis' => \App\Models\RekamMedis::class,
            'idrole_user' => \App\Models\RoleUser::class,
            'deleted_by' => \App\Models\User::class,
            'dokter_pemeriksa' => \App\Models\User::class,
        ];

        return $mappings[$field] ?? null;
    }

    public function getRelatedModelForManyToMany($relationship)
    {
        $mappings = [
            'role' => \App\Models\Role::class,
        ];

        return $mappings[$relationship] ?? null;
    }

    public function getDisplayName($option)
    {
        $nameFields = ['nama', 'nama_pemilik', 'nama_ras', 'nama_jenis_hewan', 'nama_kategori', 'nama_kategori_klinis', 'nama_role', 'nama_tindakan', 'deskripsi_tindakan_terapi', 'email', 'kode'];

        foreach ($nameFields as $field) {
            if (isset($option->$field)) {
                return $option->$field;
            }
        }

        // Check for related user model if it exists
        if (isset($option->user) && $option->user) {
            return $option->user->nama;
        }

        return $option->getKey();
    }

    // Live-update queue number when date changes on TemuDokter form
    public function updated($name, $value)
    {
        if ($this->model === TemuDokter::class && $name === 'formData.waktu_daftar') {
            $date = $value ?: now()->toDateString();
            $this->formData['no_urut'] = $this->computeNextQueueNumber($date);
        }
    }

    private function computeNextQueueNumber(string $date): int
    {
        $max = TemuDokter::whereDate('waktu_daftar', $date)->max('no_urut');
        return ($max ?? 0) + 1;
    }

    public function render()
    {
        return view('livewire.add-row-modal');
    }
}