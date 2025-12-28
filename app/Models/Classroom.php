<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'begin_date',
        'close_date',
        'course_subject_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'begin_date' => 'date',
            'close_date' => 'date'
        ];
    }

    public function courseSubject(): BelongsTo
    {
        return $this->belongsTo(CourseSubject::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(DayClass::class);
    }

    public function periods()
    {
        return DB::table('classroom_periods')
            ->where('classroom_id', $this->id);
    }

    public function getPeriodsAttribute()
    {
        return DB::table('classroom_periods')
            ->where('classroom_id', $this->id)
            ->get();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_teachers')
            ->as('teachers');
    }

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_monitors')
            ->as('monitors');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_students')
            ->as('students');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }
}
