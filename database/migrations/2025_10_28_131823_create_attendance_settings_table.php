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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('check_in_start')->default('00:00:00'); // Earliest allowed check-in
            $table->time('check_in_end')->default('23:59:59'); // Latest allowed check-in
            $table->time('check_out_start')->default('00:00:00'); // Earliest allowed check-out
            $table->time('check_out_end')->default('23:59:59'); // Latest allowed check-out
            $table->time('work_start_time')->default('09:00:00'); // Official work start time
            $table->time('work_end_time')->default('17:00:00'); // Official work end time
            $table->integer('late_threshold_minutes')->default(15); // Minutes after work_start_time to mark as late
            $table->integer('early_leave_threshold_minutes')->default(15); // Minutes before work_end_time to mark as early leave
            $table->integer('half_day_hours')->default(4); // Hours worked to consider as half day
            $table->integer('full_day_hours')->default(8); // Hours worked to consider as full day
            $table->boolean('allow_weekend_checkin')->default(false);
            $table->boolean('require_checkout')->default(true);
            $table->timestamps();
        });

        // Insert default settings
        DB::table('attendance_settings')->insert([
            'check_in_start' => '06:00:00',
            'check_in_end' => '12:00:00',
            'check_out_start' => '15:00:00',
            'check_out_end' => '23:59:59',
            'work_start_time' => '09:00:00',
            'work_end_time' => '17:00:00',
            'late_threshold_minutes' => 15,
            'early_leave_threshold_minutes' => 15,
            'half_day_hours' => 4,
            'full_day_hours' => 8,
            'allow_weekend_checkin' => false,
            'require_checkout' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
