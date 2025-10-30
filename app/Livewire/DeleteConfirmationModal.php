<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteConfirmationModal extends Component
{
    public $model;
    public $rowId;

    public function mount($model, $rowId)
    {
        $this->model = $model;
        $this->rowId = $rowId;
    }

    public function delete()
    {
        $record = $this->model::findOrFail($this->rowId);
        $record->delete();

        $this->dispatch('rowDeleted');
        return redirect()->route('dashboard', ['model' => class_basename($this->model)]);
    }

    public function render()
    {
        return view('livewire.delete-confirmation-modal');
    }
}
