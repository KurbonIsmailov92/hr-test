<?php

namespace App\Modules\Identity\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Http\Requests\RegisterCandidateRequest;
use App\Modules\Recruitment\Actions\RegisterCandidateAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterCandidateRequest $request, RegisterCandidateAction $register): RedirectResponse
    {
        $user = $register($request->validated(), app()->getLocale());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('candidate.dashboard');
    }
}
