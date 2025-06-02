<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added for DB::getDriverName()

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('proposal_id_string')->unique()->comment('User-friendly proposal ID, e.g., PROP-2024-00001');
            $table->string('title')->nullable();

            // Foreign key to leads table
            $table->foreignId('lead_id')->comment('Foreign key to leads table');
            // Constraint will be added below if 'leads' table exists

            $table->date('proposal_date');
            $table->date('valid_until')->nullable();

            // Foreign key to currencies table
            $table->string('currency_code', 10)->nullable()->comment('Foreign key to currencies table code column');
            // Constraint will be added below if 'currencies' table and 'code' column exist

            $table->json('items')->comment('Stores the array of line items with all their details');

            // Financial summary fields
            $table->decimal('sub_total', 15, 2)->default(0.00)->comment('Sum of (item_qty * item_rate) after item discounts');
            $table->string('discount_type')->nullable()->comment('Overall proposal discount: percentage, fixed');
            $table->decimal('discount_value', 15, 2)->nullable()->comment('Value for overall discount (percentage or fixed amount)');
            $table->decimal('discount_amount', 15, 2)->default(0.00)->comment('Calculated overall discount amount');
            $table->decimal('shipping_amount', 15, 2)->default(0.00);
            $table->decimal('total_tax_amount', 15, 2)->default(0.00)->comment('Sum of all item_tax_amount');
            $table->decimal('grand_total', 15, 2)->default(0.00)->comment('Final payable amount');

            $table->text('terms_and_conditions')->nullable();
            $table->text('notes')->nullable()->comment('Internal or client-facing notes');

            $table->string('status', 50)->default('Draft')->index()->comment('e.g., Draft, Sent, Accepted, Declined, Revised');

            // Foreign key to users table for creator
            $table->foreignId('created_by')->nullable()->comment('Foreign key to users table');
            // Constraint will be added below if 'users' table exists

            $table->timestamps();
            $table->softDeletes(); // Optional: if you want soft delete functionality
        });

        // Add foreign key constraints separately to allow for table existence checks
        // This makes the migration more robust, especially if tables are created in a specific order.
        Schema::table('proposals', function (Blueprint $table) {
            if (Schema::hasTable('leads')) {
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            }
            // The Currency model implies a 'currencies' table with a 'code' column.
            if (Schema::hasTable('currencies') && Schema::hasColumn('currencies', 'code')) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('set null');
            }
            if (Schema::hasTable('users')) {
                 $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys first if the database driver supports it and they were added.
        // This order helps prevent errors when dropping the table.
        if (DB::getDriverName() !== 'sqlite') { // SQLite has limitations with dropping foreign keys this way.
            Schema::table('proposals', function (Blueprint $table) {
                // Check if foreign keys exist before attempting to drop them
                // This requires knowing the conventional names Laravel assigns or custom names if used.
                // Example: $table->dropForeign('proposals_lead_id_foreign');
                // For simplicity and broader compatibility, we'll just rely on Schema::dropIfExists for rollback.
                // If specific foreign key drop is needed, ensure names are correct.
                // For this generator, assuming simple drop is sufficient for rollback.
            });
        }
        Schema::dropIfExists('proposals');
    }
};
