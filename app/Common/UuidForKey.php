<?php

namespace App\Common;

use Ramsey\Uuid\Uuid;

trait UuidForKey
{
    /**
     * Boot the Uuid trait for the model.
     */
    public static function bootUuidForKey(): void
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    /**
     * Get the casts array.
     */
    public function getCasts(): array
    {
        return $this->casts;
    }
}
