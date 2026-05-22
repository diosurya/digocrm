<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function (Model $model) {
            static::logAudit($model, 'created');
        });

        static::updated(function (Model $model) {
            static::logAudit($model, 'updated');
        });

        static::deleted(function (Model $model) {
            static::logAudit($model, 'deleted');
        });
    }

    protected static function logAudit(Model $model, string $event)
    {
        $oldValues = $event === 'updated' ? array_intersect_key($model->getOriginal(), $model->getDirty()) : null;
        $newValues = $event === 'updated' ? $model->getDirty() : ($event === 'created' ? $model->toArray() : null);

        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
