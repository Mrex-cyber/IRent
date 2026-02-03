<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('workload_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')->constrained('user_profiles')->onDelete('cascade');

            $table->integer('active_requests_count')->default(0);
            $table->integer('resolved_requests_count')->default(0);
            $table->float('avg_response_time')->default(0.0);

            $table->integer('max_capacity')->default(10);

            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->string('subject')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('workload_stats');
        Schema::dropIfExists('user_profiles');
    }
};
