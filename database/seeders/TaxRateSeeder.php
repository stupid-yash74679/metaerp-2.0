<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System\TaxRate; // Your TaxRate model
use App\Models\User;
use Illuminate\Support\Facades\DB; // If you prefer direct DB interaction
use Carbon\Carbon;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstUser = User::first();
        $createdBy = $firstUser ? $firstUser->id : null;

        $taxRatesData = [
            // India GST Examples
            [
                'name' => 'IGST 0%',
                'rate_percentage' => 0.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_default' => false,
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'GST 0% (Domestic)',
                'rate_percentage' => 0.00,
                'tax_type' => 'GST',
                'region' => 'IN', // Assuming specific state logic is handled elsewhere or by product/service
                'is_default' => true, // Making this a default, can be changed
                'is_active' => true,
                'collective_tax' => true,
                'components' => json_encode([
                    ['name' => 'CGST', 'rate' => 0.00],
                    ['name' => 'SGST', 'rate' => 0.00]
                ]),
            ],
            [
                'name' => 'IGST 5%',
                'rate_percentage' => 5.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'GST 5% (Domestic)',
                'rate_percentage' => 5.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => true,
                'components' => json_encode([
                    ['name' => 'CGST', 'rate' => 2.50],
                    ['name' => 'SGST', 'rate' => 2.50]
                ]),
            ],
            [
                'name' => 'IGST 12%',
                'rate_percentage' => 12.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'GST 12% (Domestic)',
                'rate_percentage' => 12.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => true,
                'components' => json_encode([
                    ['name' => 'CGST', 'rate' => 6.00],
                    ['name' => 'SGST', 'rate' => 6.00]
                ]),
            ],
            [
                'name' => 'IGST 18%',
                'rate_percentage' => 18.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'GST 18% (Domestic)',
                'rate_percentage' => 18.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => true,
                'components' => json_encode([
                    ['name' => 'CGST', 'rate' => 9.00],
                    ['name' => 'SGST', 'rate' => 9.00]
                ]),
            ],
            [
                'name' => 'IGST 28%',
                'rate_percentage' => 28.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'GST 28% (Domestic)',
                'rate_percentage' => 28.00,
                'tax_type' => 'GST',
                'region' => 'IN',
                'is_active' => true,
                'collective_tax' => true,
                'components' => json_encode([
                    ['name' => 'CGST', 'rate' => 14.00],
                    ['name' => 'SGST', 'rate' => 14.00]
                ]),
            ],

            // General Examples
            [
                'name' => 'No Tax',
                'rate_percentage' => 0.00,
                'tax_type' => 'General',
                'region' => null, // Applicable globally or where no specific tax applies
                'is_default' => false,
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
            [
                'name' => 'Standard VAT',
                'rate_percentage' => 20.00, // Example rate
                'tax_type' => 'VAT',
                'region' => 'GB', // Example for UK
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
             [
                'name' => 'Sales Tax (US)',
                'rate_percentage' => 7.00, // Example average rate, varies by state/county
                'tax_type' => 'Sales Tax',
                'region' => 'US',
                'is_active' => true,
                'collective_tax' => false,
                'components' => null,
            ],
        ];

        foreach ($taxRatesData as $rateData) {
            TaxRate::updateOrCreate(
                ['name' => $rateData['name']], // Find by name to avoid duplicates
                [
                    'rate_percentage' => $rateData['rate_percentage'],
                    'tax_type' => $rateData['tax_type'],
                    'region' => $rateData['region'] ?? null,
                    'is_default' => $rateData['is_default'] ?? false,
                    'is_active' => $rateData['is_active'] ?? true,
                    'compound_tax' => $rateData['compound_tax'] ?? false,
                    'collective_tax' => $rateData['collective_tax'] ?? false,
                    'components' => $rateData['components'], // Already json_encoded or null
                    'created_by' => $createdBy,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        $this->command->info('Tax rates seeded successfully!');
    }
}
