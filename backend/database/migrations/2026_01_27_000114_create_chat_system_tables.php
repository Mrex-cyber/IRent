<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['direct', 'group', 'broadcast'])->default('direct');

            $table->enum('category', [
                'general',
                'suggestions_complaints',
                'legal',
                'maintenance',
                'financial',
            ])->default('general');

            $table->string('subject')->nullable();

            $table->timestamp('last_message_at')->useCurrent();

            $table->timestamps();
        });

        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamp('last_read_at')->nullable();

            $table->unique(['conversation_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Хто написав

            $table->text('body');

            $table->string('attachment_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};
