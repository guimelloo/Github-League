<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'min_score',
        'max_score',
        'color',
        'icon',
        'order',
    ];

    public function profiles()
    {
        return $this->hasMany(GithubProfile::class);
    }
}
