<?php

namespace App\Modules\Recruitment\Models;

use App\Modules\Identity\Models\Person;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    use HasUuids;

    protected $fillable = ['person_id', 'source', 'consent_version', 'consent_given_at'];

    protected function casts(): array
    {
        return ['consent_given_at' => 'datetime'];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
