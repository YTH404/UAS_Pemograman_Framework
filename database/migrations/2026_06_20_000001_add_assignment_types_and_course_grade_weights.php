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
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('assignment_type')->default('tugas')->after('meeting');
        });

        Schema::create('course_grade_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->unique()->constrained('courses')->cascadeOnDelete();
            $table->unsignedTinyInteger('attendance_weight')->default(0);
            $table->unsignedTinyInteger('tugas_weight')->default(0);
            $table->unsignedTinyInteger('quiz_weight')->default(0);
            $table->unsignedTinyInteger('uts_weight')->default(0);
            $table->unsignedTinyInteger('uas_weight')->default(0);
            $table->timestamp('locked_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_grade_weights');

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('assignment_type');
        });
    }
};
