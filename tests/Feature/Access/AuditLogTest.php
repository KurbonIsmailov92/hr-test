<?php

use App\Modules\Audit\Audit;
use App\Modules\Audit\Models\AuditLog;
use Illuminate\Database\QueryException;

it('records an audit entry with the actor', function () {
    $user = makeUser('admin');

    Audit::record('test.action', $user, ['k' => 'v'], $user);

    $log = AuditLog::where('action', 'test.action')->firstOrFail();
    expect($log->actor_id)->toBe($user->id)
        ->and($log->subject_id)->toBe($user->id)
        ->and($log->metadata)->toBe(['k' => 'v']);
});

// Последний тест файла: ошибка внутри транзакции RefreshDatabase помечает её как прерванную.
it('forbids changing audit entries at the database level', function () {
    Audit::record('test.immutable');
    $log = AuditLog::firstOrFail();

    expect(fn () => $log->update(['action' => 'tampered']))->toThrow(QueryException::class);
});
