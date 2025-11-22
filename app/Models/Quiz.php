<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'time_limit_minutes'
    ];

    protected $casts = [
        'time_limit_minutes' => 'integer'
    ];

    /**
     * Get the lesson that this quiz belongs to.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the questions for this quiz.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the attempts for this quiz.
     */
    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }
}