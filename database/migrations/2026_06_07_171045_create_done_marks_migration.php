<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('done_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->cascadeOnDelete();
            $table->foreignId('learning_material_id')->nullable()->constrained('learning_materials')->cascadeOnDelete();
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'assignment_id']);
            $table->unique(['student_id', 'attendance_id']);
            $table->unique(['student_id', 'learning_material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('done_marks');
    }
};
