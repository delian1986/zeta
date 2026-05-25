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
        Schema::create('task_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained('emails')->cascadeOnDelete();
            $table->string('type', 100);
            $table->string('title', 255);
            $table->text('summary');
            $table->string('priority', 50);
            $table->string('suggested_project', 255)->nullable();
            $table->string('suggested_team', 255)->nullable();
            $table->integer('confidence');
            $table->text('missing_information')->nullable();
            $table->string('next_action', 255)->nullable();
            $table->json('raw_ai_response');
            $table->string('status', 50);
            $table->text('reviewer_notes')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_drafts');
    }
};
