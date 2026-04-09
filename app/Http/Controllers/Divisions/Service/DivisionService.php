<?php

namespace App\Http\Controllers\Divisions\Service;

use App\Models\Division;

class DivisionService
{
    private $division;

    public function __construct()
    {
        $this->division = new Division();
    }

    public function getAllDivisionsWithProfiles()
    {
        return $this->division->with([
            'profiles' => fn($query) => $query->orderByDesc('score')
        ])->orderByDesc('order')->get();
    }

    public function getUsersFromDivision()
    {
        return $this->division->with('profiles')->get();
    }
}
