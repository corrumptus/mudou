<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Dono do arquivo.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    // public function scopeCollection($query, string $collection)
    // {
    //     return $query->where('collection', $collection);
    // }
}
