<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_allocation_id')->nullable()->constrained('room_allocations')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('for_month'); // e.g. "2026-08" — the rent period this payment covers
            $table->enum('method', ['cash', 'bank_transfer', 'card', 'mobile_wallet', 'other'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'for_month']);
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
