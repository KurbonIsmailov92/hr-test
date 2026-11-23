<?php

namespace App\Modules\Identity\Enums;

enum PermissionName: string
{
    case AdminAccess = 'admin.access';
    case PortalAccess = 'portal.access';
    case CandidateAccess = 'candidate.access';
    case UsersManage = 'users.manage';
    case RolesManage = 'roles.manage';
}
