<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreHistory extends Model
{
    protected $table = 'score_history';

    protected $fillable = [
        'github_profile_id',
        'score',
        'position',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'date',
    ];

    public function githubProfile()
    {
        return $this->belongsTo(GithubProfile::class);
    }
}
