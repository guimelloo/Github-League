<?php

namespace App\Http\Controllers\Divisions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Divisions\Service\DivisionService;
use Inertia\Inertia;

class DivisionController extends Controller
{
    private $divisionService;

    public function __construct()
    {
        $this->divisionService = new DivisionService();
    }

    public function index()
    {
        $divisions = $this->divisionService->getAllDivisionsWithProfiles();
        $users = $this->divisionService->getUsersFromDivision();

        return Inertia::render('Divisions', [
            'divisions' => $divisions,
            'users' => $users,
        ]);
    }
}
