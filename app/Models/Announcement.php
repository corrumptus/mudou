<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "title",
        "description",
        "created_at",
        "classroom_id",
        "user_id"
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('viewers');
    }
}
