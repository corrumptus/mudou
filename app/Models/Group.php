<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "title",
        "max_members"
    ];

    public function classElement(): HasOne
    {
        return $this->hasOne(ClassElement::class, 'element_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members');
    }

    public function themes(): HasMany
    {
        return $this->hasMany(GroupTheme::class);
    }
}
