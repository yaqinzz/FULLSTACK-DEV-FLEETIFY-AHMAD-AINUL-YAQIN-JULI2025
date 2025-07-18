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
        // Drop existing foreign keys and recreate with cascade delete
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });

        Schema::table('attendance_histories', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['attendance_id']);
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->foreign('attendance_id')->references('attendance_id')->on('attendances')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original foreign keys without cascade
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });

        Schema::table('attendance_histories', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['attendance_id']);
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('attendance_id')->references('attendance_id')->on('attendances');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }
};
