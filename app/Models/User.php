<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'bio',
        'job_title',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the courses created by this user (mentor).
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'author_id');
    }

    /**
     * Get the ebooks created by this user (mentor).
     */
    public function ebooks()
    {
        return $this->hasMany(Ebook::class, 'author_id');
    }

    /**
     * Get the enrollments for this user.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the mentor verification for this user.
     */
    public function mentorVerification()
    {
        return $this->hasOne(MentorVerification::class);
    }

    /**
     * Get the transactions for this user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user ebook library entries for this user.
     */
    public function userEbookLibraries()
    {
        return $this->hasMany(UserEbookLibrary::class);
    }
}
