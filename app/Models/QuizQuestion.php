<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'points'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer'
    ];

    /**
     * Get the quiz that this question belongs to.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}