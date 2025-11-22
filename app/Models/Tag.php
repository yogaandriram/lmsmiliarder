<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    /**
     * Get the courses for this tag.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tag');
    }
}