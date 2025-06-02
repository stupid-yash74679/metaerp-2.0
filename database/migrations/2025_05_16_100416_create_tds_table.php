<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tds', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Professional Services"
            $table->string('section')->nullable(); // e.g., "194J"
            $table->decimal('rate', 5, 2); // e.g., 10.00 for 10%
            $table->decimal('threshold_limit', 15, 2)->nullable(); // e.g., ₹30,000
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tds');
    }
};
