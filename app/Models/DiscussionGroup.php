<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionGroup extends Model
{
    use HasFactory;

    protected $table = 'discussion_groups';
    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'group_name'
    ];

    /**
     * Get the course that this discussion group belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the threads for this discussion group.
     */
    public function threads()
    {
        return $this->hasMany(DiscussionThread::class, 'group_id');
    }
}