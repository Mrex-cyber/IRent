<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('content');

            $table->enum('type', [
                'general',
                'maintenance',
                'events',
                'financial',
                'safety',
                'updates',
            ])->default('general');

            $table->enum('status', [
                'draft',
                'published',
                'scheduled',
            ])->default('draft');

            $table->timestamp('scheduled_for')->nullable();

            $table->timestamps();
        });

        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');

            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('assignee_id')->nullable()->constrained('users');

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('announcements');
    }
};
