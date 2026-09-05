<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->string('bed_number')->nullable();
            $table->date('allocation_date');
            $table->date('checkout_date')->nullable();
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->timestamps();

            $table->index(['room_id', 'status']);
            $table->index(['resident_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_allocations');
    }
};
