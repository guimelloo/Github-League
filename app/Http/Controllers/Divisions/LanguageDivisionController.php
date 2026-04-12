<?php

namespace App\Http\Controllers\Divisions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Divisions\Service\LanguageDivisionService;
use Inertia\Inertia;

class LanguageDivisionController extends Controller
{
    private $languageDivisionService;

    public function __construct()
    {
        $this->languageDivisionService = new LanguageDivisionService();
    }

    public function index()
    {
        $languageDivisions = $this->languageDivisionService->getLanguageDivisions();

        return Inertia::render('LanguageDivisions', [
            'languageDivisions' => $languageDivisions,
        ]);
    }
}
