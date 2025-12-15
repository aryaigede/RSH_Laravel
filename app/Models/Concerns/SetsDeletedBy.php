<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Auth;

trait SetsDeletedBy
{
    protected static function bootSetsDeletedBy(): void
    {
        static::deleting(function ($model) {
            // Skip force deletes
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });
    }
}
