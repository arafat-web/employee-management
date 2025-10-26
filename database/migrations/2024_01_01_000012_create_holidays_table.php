<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->integer('year');
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['date', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
