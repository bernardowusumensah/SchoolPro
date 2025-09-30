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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('final_document_path')->nullable()->after('status');
            $table->string('presentation_path')->nullable()->after('final_document_path');
            $table->string('source_code_path')->nullable()->after('presentation_path');
            $table->text('submission_note')->nullable()->after('source_code_path');
            $table->timestamp('submitted_at')->nullable()->after('submission_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'final_document_path',
                'presentation_path', 
                'source_code_path',
                'submission_note',
                'submitted_at'
            ]);
        });
    }
};
