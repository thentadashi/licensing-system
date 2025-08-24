<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('application_archives', function (Blueprint $table) {
            $table->string('previous_status')->nullable()->after('archived_by');
            $table->string('previous_progress_stage')->nullable()->after('previous_status');
        });
    }

    public function down(): void
    {
        Schema::table('application_archives', function (Blueprint $table) {
            $table->dropColumn(['previous_status', 'previous_progress_stage']);
        });
    }
};
