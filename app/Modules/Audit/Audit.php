<?php

namespace App\Modules\Audit;

use App\Modules\Audit\Models\AuditLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/** Запись критических действий в журнал аудита. Журнал защищён от UPDATE/DELETE триггером БД. */
class Audit
{
    public static function record(
        string $action,
        ?Model $subject = null,
        array $metadata = [],
        ?Authenticatable $actor = null,
    ): void {
        $actor ??= Auth::user();
        $request = request();

        AuditLog::create([
            'actor_id' => $actor?->getAuthIdentifier(),
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject ? (string) $subject->getKey() : null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent() ? Str::limit($request->userAgent(), 255, '') : null,
            'metadata' => $metadata ?: null,
        ]);
    }
}
