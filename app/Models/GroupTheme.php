<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupTheme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'theme',
        'group_id'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
