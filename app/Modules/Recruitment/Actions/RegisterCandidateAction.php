<?php

namespace App\Modules\Recruitment\Actions;

use App\Modules\Audit\Audit;
use App\Modules\Identity\Actions\CreateUserAction;
use App\Modules\Identity\Enums\RoleName;
use App\Modules\Identity\Models\User;
use App\Modules\Recruitment\Models\CandidateProfile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RegisterCandidateAction
{
    public function __construct(private CreateUserAction $createUser) {}

    public function __invoke(array $data, string $locale): User
    {
        return DB::transaction(function () use ($data, $locale) {
            $user = ($this->createUser)(
                person: Arr::only($data, ['last_name', 'first_name', 'middle_name', 'phone']),
                email: $data['email'],
                password: $data['password'],
                roles: [RoleName::Candidate],
                locale: $locale,
            );

            CandidateProfile::create([
                'person_id' => $user->person_id,
                'source' => 'career_portal',
                'consent_version' => config('hrms.consent_version'),
                'consent_given_at' => now(),
            ]);

            Audit::record('candidate.registered', $user, actor: $user);

            return $user;
        });
    }
}
