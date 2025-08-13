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
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'first_name')) {
            $table->string('first_name')->after('id');
        }
        if (!Schema::hasColumn('users', 'last_name')) {
            $table->string('last_name')->after('first_name');
        }
        if (!Schema::hasColumn('users', 'middle_name')) {
            $table->string('middle_name')->nullable()->after('last_name');
        }
        if (!Schema::hasColumn('users', 'contact_number')) {
            $table->string('contact_number')->after('email');
        }
        if (!Schema::hasColumn('users', 'student_id')) {
            $table->string('student_id')->after('contact_number');
        }
        if (!Schema::hasColumn('users', 'program')) {
            $table->string('program')->after('student_id');
        }
        if (!Schema::hasColumn('users', 'username')) {
            $table->string('username')->after('program');
        }
        if (!Schema::hasColumn('users', 'gender')) {
            $table->enum('gender', ['Male', 'Female'])->after('username');
        }
        if (!Schema::hasColumn('users', 'address')) {
            $table->string('address')->after('birthdate');
        }
    });
}


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'middle_name', 'contact_number', 
                'student_id', 'program', 'username', 'gender', 'address'
            ]);
        });
    }

};
