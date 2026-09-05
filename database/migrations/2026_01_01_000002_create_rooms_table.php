<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('floor')->nullable();
            $table->string('room_type');
            $table->unsignedInteger('capacity')->default(1);
            $table->unsignedInteger('current_occupancy')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['available', 'partially_occupied', 'full', 'maintenance'])
                ->default('available');
            $table->text('facilities')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
