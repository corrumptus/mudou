<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "title",
        "description",
        "begin_date_time",
        "close_date_time",
        "group_id",
        "can_accept_after_close",
        "is_text",
        "max_amount_caracteres",
        "is_file",
        "max_amount_files",
        "file_types"
    ];

    public function classElement(): HasOne
    {
        return $this->hasOne(ClassElement::class, 'element_id');
    }

    public function textResponse(): HasMany
    {
        return $this->hasMany(HomeworkTextResponse::class);
    }

    // public function fileResponse(): HasMany
    // {
    //     return $this->hasMany(HomeworkFileResponse::class);
    // }
}
