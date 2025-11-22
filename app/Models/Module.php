<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    /**
     * Get the course that this module belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for this module.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}