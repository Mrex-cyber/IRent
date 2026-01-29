<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('city')->default('Kyiv');
            $table->timestamps();
        });

        Schema::create('entrances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('name'); 
            $table->string('code')->nullable();
            $table->timestamps();
        });

        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrance_id')->constrained()->onDelete('cascade');
            $table->string('number');
            $table->integer('floor')->nullable();
            $table->float('area')->nullable();

       
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });

        Schema::create('entrance_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrance_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrance_user');
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('entrances');
        Schema::dropIfExists('buildings');
    }
};
