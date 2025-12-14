<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['author_id','title','slug','description','thumbnail_url','price','mentor_share_percent','status','category_id','verification_status','verified_at','intro_video_url','subscription_type','subscription_start_date','subscription_end_date','subscription_duration_value','subscription_duration_unit'];

    protected $casts = [
        'verified_at' => 'datetime',
        'mentor_share_percent' => 'integer',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'subscription_duration_value' => 'integer',
    ];

    /**
     * Get the author (mentor) of the course.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category of the course.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags for the course.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tag');
    }

    /**
     * Get the modules for the course.
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the discussion group for the course.
     */
    public function discussionGroup()
    {
        return $this->hasOne(DiscussionGroup::class);
    }

    /**
     * Get the transaction details for the course.
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Get all lessons for the course through modules.
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }
}
