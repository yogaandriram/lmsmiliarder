<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['author_id','title','slug','description','thumbnail_url','price','status','category_id','verification_status','verified_at','intro_video_url'];

    protected $casts = [
        'verified_at' => 'datetime',
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
}