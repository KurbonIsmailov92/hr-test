<?php

namespace App\Modules\Identity\Enums;

enum RoleName: string
{
    case Admin = 'admin';
    case Hr = 'hr';
    case Recruiter = 'recruiter';
    case Manager = 'manager';
    case Employee = 'employee';
    case Candidate = 'candidate';

    /** @return list<PermissionName> */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => PermissionName::cases(),
            self::Hr, self::Recruiter => [PermissionName::AdminAccess],
            self::Manager, self::Employee => [PermissionName::PortalAccess],
            self::Candidate => [PermissionName::CandidateAccess],
        };
    }
}
