<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "GST 18%", "VAT 5%"
            $table->decimal('rate_percentage', 8, 4)->default(0.0000); // e.g., 18.0000 for 18%
            $table->string('tax_type')->nullable(); // e.g., GST, VAT, Sales Tax, Service Tax
            $table->boolean('compound_tax')->default(false); // Calculated on top of other taxes?
            $table->boolean('collective_tax')->default(false); // Is this a group of taxes?
            $table->json('components')->nullable();
            // Example for components if collective_tax is true:
            // [{"name": "CGST", "rate": 9.00}, {"name": "SGST", "rate": 9.00}]
            $table->string('region')->nullable(); // Country code (ISO 3166-1 alpha-2) or state/province
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
