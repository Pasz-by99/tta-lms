<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new'); // new, contacted, enrolled, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_inquiries');
    }
};