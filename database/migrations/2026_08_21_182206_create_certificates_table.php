<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->string('student_name');
            $table->string('student_phone')->nullable();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('issued_date');
            $table->string('issued_by')->default('Tinahls Triad Agro');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};