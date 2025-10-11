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
        Schema::table('logs', function (Blueprint $table) {
            // Simple acknowledgment system for students
            $table->boolean('student_acknowledged')->default(false)->after('feedback_updated_at');
            $table->text('student_question')->nullable()->after('student_acknowledged');
            $table->timestamp('acknowledged_at')->nullable()->after('student_question');
            
            // Add index for better query performance
            $table->index(['student_acknowledged']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropIndex(['student_acknowledged']);
            $table->dropColumn([
                'student_acknowledged',
                'student_question',
                'acknowledged_at'
            ]);
        });
    }
};
