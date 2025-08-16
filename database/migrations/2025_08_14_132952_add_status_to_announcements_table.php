<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('status', ['visible', 'hidden'])->default('visible')->after('publish_date');
        });
    }

    public function down(): void {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
