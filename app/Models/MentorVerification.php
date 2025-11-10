<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorVerification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','document_url','status','notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}