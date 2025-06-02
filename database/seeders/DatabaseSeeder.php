<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            RolesPermissionsSeeder::class,
            MessageTemplateSeeder::class,
            CompanySettingsSeeder::class,
            ProjectTypeSeeder::class,
            TaxRateSeeder::class,
        ]);

        // You can keep or adjust these factory calls as needed.
        // If UsersSeeder already creates enough users, you might not need this User::factory line.
        // \App\Models\User::factory(20)->create();

        // Address::factory(20)->create(); // Keep if you have an Address factory and want to seed addresses.

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
