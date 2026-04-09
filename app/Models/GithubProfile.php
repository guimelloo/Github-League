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
        'division_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function scoreHistory()
    {
        return $this->hasMany(ScoreHistory::class);
    }
}
