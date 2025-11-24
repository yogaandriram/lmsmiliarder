<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionMessage extends Model
{
    use HasFactory;

    protected $table = 'discussion_messages';
    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'user_id',
        'content',
        'file_url',
        'mime_type',
        'original_name',
    ];

    public function group()
    {
        return $this->belongsTo(DiscussionGroup::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}