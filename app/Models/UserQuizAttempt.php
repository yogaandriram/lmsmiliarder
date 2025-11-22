<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAttempt extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'started_at',
        'completed_at',
        'time_spent_minutes'
    ];

    protected $casts = [
        'score' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent_minutes' => 'integer'
    ];

    /**
     * Get the user that made this attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz for this attempt.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}