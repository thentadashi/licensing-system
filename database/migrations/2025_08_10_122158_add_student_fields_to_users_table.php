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
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('middle_name')->nullable()->after('last_name');
            $table->string('contact_number')->after('email');
            $table->string('student_id')->after('contact_number');
            $table->string('program')->after('student_id');
            $table->string('username')->unique()->after('program');
            $table->enum('gender', ['Male', 'Female'])->after('username');
            $table->string('address')->after('birthdate');
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
