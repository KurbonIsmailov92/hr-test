<?php

namespace App\Modules\Identity\Models;

use App\Modules\Recruitment\Models\CandidateProfile;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'persons';

    protected $fillable = ['last_name', 'first_name', 'middle_name', 'phone'];

    protected static function newFactory(): PersonFactory
    {
        return PersonFactory::new();
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->last_name} {$this->first_name} {$this->middle_name}"));
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function candidateProfile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }
}
