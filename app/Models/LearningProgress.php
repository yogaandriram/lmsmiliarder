<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    protected $table = 'learning_progress';
    public $timestamps = false;
    protected $fillable = ['enrollment_id','lesson_id','completed_at','updated_at'];

    protected $casts = [
        'completed_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

