<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('progress_stage', [
                'Submitted',
                'Under Review',
                'Processing License',
                'Ready for Release',
                'Completed'
            ])->default('Submitted')->after('status');

            $table->text('admin_notes')->nullable()->after('progress_stage');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['progress_stage', 'admin_notes']);
        });
    }
};
