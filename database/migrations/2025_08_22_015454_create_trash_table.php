<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('application_trashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trashed_by')->constrained('users')->cascadeOnDelete();
            $table->string('previous_status')->nullable();
            $table->string('previous_progress_stage')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique('application_id'); // one active trash record per application
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_trashes');
    }
};
