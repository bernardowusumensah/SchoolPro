<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('grades', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('project_id');
			$table->unsignedBigInteger('teacher_id');
			$table->string('grade', 10)->nullable();
			$table->text('feedback')->nullable();
			$table->timestamps();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('grades');
	}
};
