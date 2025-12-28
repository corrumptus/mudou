<?php

namespace App\Models;

use Database\Factories\ClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DayClass extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ClassFactory::new();
    }

    protected $table = 'classes';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'date',
        'classroom_id'
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(ClassElement::class, 'class_id');
    }
}
