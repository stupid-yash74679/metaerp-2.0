<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // e.g., 'create', 'update', 'delete', 'login', 'logout'
            $table->string('module'); // e.g., 'ContactGroup', 'Invoice'
            $table->string('action'); // e.g., 'Created contact group'
            $table->string('subject_type')->nullable(); // e.g., App\Models\ContactGroup
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable(); // Details of before/after or action context
            $table->unsignedInteger('duration')->nullable(); // Time taken in seconds
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
