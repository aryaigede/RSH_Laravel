<?php

namespace App\Livewire;

use Livewire\Component;

class EditRowModal extends Component
{
    public $model;
    public $rowId;
    public $fillable = [];
    public $formData = [];
    public $relationships = [];
    public $manyToManyRelationships = [];
    public $record;

    public function mount($model, $rowId)
    {
        $this->model = $model;
        $this->rowId = $rowId;
        $instance = new $model;
        $this->fillable = $instance->getFillable();
        $this->relationships = $this->getRelationships($instance);
        $this->manyToManyRelationships = $this->getManyToManyRelationships($instance);
        
        // Load the record
        $this->record = $model::findOrFail($rowId);
        
        // Populate form data
        foreach ($this->fillable as $field) {
            $this->formData[$field] = $this->record->$field ?? '';
        }
        
        // Populate many-to-many relationships
        foreach ($this->manyToManyRelationships as $relationship) {
            $relatedModel = $this->record->{$relationship}()->getRelated();
            $relatedKeyName = $relatedModel->getKeyName();
            $relatedTable = $relatedModel->getTable();
            
            // Use qualified column name to avoid ambiguity
            $this->formData[$relationship] = $this->record->{$relationship}()->pluck("{$relatedTable}.{$relatedKeyName}")->toArray();
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
            if ($method->class === get_class($instance) && !$method->isStatic()) {
                try {
                    $relation = $instance->{$method->getName()}();
                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        $manyToManyRelationships[] = $method->getName();
                    }
                } catch (\Exception $e) {
                    // Skip if method is not a relationship
                }
            }
        }

        return $manyToManyRelationships;
    }

    public function update()
    {
        $this->validate([
            'formData.*' => 'nullable',
        ]);

        $record = $this->model::findOrFail($this->rowId);
        
        // Disable timestamps
        $record->timestamps = false;

        // Separate fillable data from many-to-many relationships
        $fillableData = [];
        $manyToManyData = [];

        foreach ($this->formData as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $fillableData[$key] = $value;
            } elseif (in_array($key, $this->manyToManyRelationships)) {
                $manyToManyData[$key] = $value;
            }
        }

        $record->fill($fillableData);
        $record->save();

        // Handle many-to-many relationships
        foreach ($manyToManyData as $relationship => $ids) {
            $record->{$relationship}()->sync($ids);
        }

        $this->dispatch('rowUpdated');
        return redirect()->route('dashboard', ['model' => class_basename($this->model)]);
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
        $nameFields = ['nama', 'nama_ras', 'nama_jenis_hewan', 'nama_kategori', 'nama_kategori_klinis', 'nama_role', 'email'];

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

    public function render()
    {
        return view('livewire.edit-row-modal');
    }
}
