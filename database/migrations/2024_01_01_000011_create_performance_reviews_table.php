<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->date('review_date');
            $table->string('review_period');
            $table->integer('overall_rating'); // 1-5 scale
            $table->integer('technical_skills_rating')->nullable();
            $table->integer('communication_rating')->nullable();
            $table->integer('teamwork_rating')->nullable();
            $table->integer('productivity_rating')->nullable();
            $table->integer('punctuality_rating')->nullable();
            $table->text('strengths')->nullable();
            $table->text('areas_of_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->text('comments')->nullable();
            $table->text('employee_comments')->nullable();
            $table->enum('status', ['draft', 'submitted', 'acknowledged', 'completed'])->default('draft');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'review_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
