<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['start_date', 'end_date', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
