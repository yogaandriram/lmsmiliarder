<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    protected $table = 'quiz_options';

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}