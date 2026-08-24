<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
    $table->string('student_number')->nullable()->unique()->after('id');
    $table->boolean('must_change_password')->default(false)->after('password');
});
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_number', 'must_change_password']);
        });
    }
};
