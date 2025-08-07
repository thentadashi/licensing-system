<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('application_type'); // PPL, CPL, FI, etc.
        $table->string('form_541')->nullable();
        $table->string('picture')->nullable();
        $table->string('signature')->nullable();
        $table->string('receipt')->nullable();
        $table->enum('status', ['Pending', 'Under Review', 'Approved', 'Rejected'])->default('Pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
