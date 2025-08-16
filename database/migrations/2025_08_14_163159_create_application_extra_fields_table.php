<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_extra_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('field_name');  // e.g. issuance_type, aircraft, gi_subjects
            $table->text('field_value')->nullable(); // string or comma-separated values
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_extra_fields');
    }
};
