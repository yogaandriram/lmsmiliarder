<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id','title','slug','description','cover_image_url','file_url','price','mentor_share_percent','status','verification_status','verified_at'
    ];

    protected $casts = [
        'mentor_share_percent' => 'integer',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the author (mentor) of the ebook.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the transaction details for the ebook.
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Get the user ebook library entries for this ebook.
     */
    public function userEbookLibraries()
    {
        return $this->hasMany(UserEbookLibrary::class);
    }
}
