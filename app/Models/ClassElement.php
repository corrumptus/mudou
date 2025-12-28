<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassElement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'element_id',
        'class_id'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(DayClass::class, 'class_id');
    }

    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class, 'element_id');
    }

    // public function quiz(): BelongsTo
    // {
    //     return $this->belongsTo(Quiz::class, 'element_id');
    // }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'element_id');
    }
}
