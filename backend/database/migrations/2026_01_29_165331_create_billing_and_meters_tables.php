<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utility_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['water', 'electricity', 'gas', 'heating']);
            $table->string('serial_number')->nullable();
            $table->string('unit')->default('m3'); // m3, kWh

            $table->timestamps();
        });

        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utility_meter_id')->constrained()->onDelete('cascade');

            $table->decimal('value', 10, 2); // Наприклад: 224.40
            $table->date('reading_date');

            $table->enum('status', ['pending', 'verified'])->default('pending');

            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('paid_at')->nullable();

            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('meter_readings');
        Schema::dropIfExists('utility_meters');
    }
};
