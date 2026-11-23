<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Person — единая запись о человеке. Кандидат и сотрудник ссылаются на неё (без дублирования ПДн).
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('phone', 32)->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
