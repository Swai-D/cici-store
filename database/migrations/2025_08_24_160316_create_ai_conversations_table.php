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
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('session_id')->nullable(); // For anonymous sessions
            $table->text('user_message');
            $table->text('ai_response');
            $table->string('provider')->default('openai'); // AI provider used
            $table->string('model')->nullable(); // AI model used
            $table->json('business_data')->nullable(); // Snapshot of business data used
            $table->string('range')->nullable(); // Time range used for analysis
            $table->integer('tokens_used')->nullable(); // Token usage for cost tracking
            $table->decimal('cost', 10, 6)->nullable(); // Cost in USD
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('session_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
