<?php

namespace App\Http\Controllers\Careers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CandidateDashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Candidate/Dashboard');
    }
}
