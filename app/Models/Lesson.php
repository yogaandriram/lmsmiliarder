<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'video_url',
        'material_file_url',
        'material_files',
        'duration_minutes',
        'order'
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order' => 'integer',
        'material_files' => 'array',
        
    ];

    /**
     * Get the module that this lesson belongs to.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the quiz for this lesson.
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    
}