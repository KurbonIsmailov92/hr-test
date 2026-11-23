<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('person_id')->unique()->constrained('persons')->restrictOnDelete();
            $table->string('source', 50)->default('career_portal');
            // Фиксация согласия на обработку ПДн: когда и какая версия текста принята.
            $table->string('consent_version', 20);
            $table->timestampTz('consent_given_at');
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
