<?php

use App\Modules\Identity\Enums\PermissionName;
use App\Modules\Identity\Enums\RoleName;

it('gives admin every permission', function () {
    expect(RoleName::Admin->permissions())->toEqual(PermissionName::cases());
});

it('keeps recruiters and HR out of the employee portal and out of user management', function (RoleName $role) {
    expect($role->permissions())
        ->toContain(PermissionName::AdminAccess)
        ->not->toContain(PermissionName::PortalAccess)
        ->not->toContain(PermissionName::UsersManage);
})->with([RoleName::Hr, RoleName::Recruiter]);

it('limits candidates to their own cabinet', function () {
    expect(RoleName::Candidate->permissions())->toEqual([PermissionName::CandidateAccess]);
});
