<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubProfile extends Model
{
    protected $fillable = [
        'user_id',
        'github_username',
        'github_token',
        'avatar_url',
        'verified_at',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
