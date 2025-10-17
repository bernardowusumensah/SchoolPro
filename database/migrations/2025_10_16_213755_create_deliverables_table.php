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
        Schema::create('deliverables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['milestone', 'document', 'presentation', 'code', 'final_project']);
            $table->enum('status', ['pending', 'submitted', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->date('due_date');
            $table->integer('weight_percentage')->default(0); // For grading weight
            $table->text('requirements')->nullable();
            $table->json('file_types_allowed')->nullable(); // ['pdf', 'docx', 'zip', etc.]
            $table->integer('max_file_size_mb')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliverables');
    }
};
