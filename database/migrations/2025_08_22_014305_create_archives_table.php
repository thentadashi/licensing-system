<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('application_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('archived_by')->constrained('users')->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique('application_id'); // one active archive record per application
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_archives');
    }
};
