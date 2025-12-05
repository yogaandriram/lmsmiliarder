<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEbookLibrary extends Model
{
    use HasFactory;

    protected $table = 'user_ebook_library';
    public $timestamps = false;
    protected $fillable = ['user_id','ebook_id','purchased_at'];

    /**
     * Get the user that owns this library entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ebook for this library entry.
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}
