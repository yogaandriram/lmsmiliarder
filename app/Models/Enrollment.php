<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','course_id'];

    /**
     * Get the course that this enrollment belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user that this enrollment belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}