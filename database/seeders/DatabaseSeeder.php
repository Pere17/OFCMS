<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            'Academic / Service Issues',
            'Staff Conduct',
            'Facilities and Infrastructure',
            'Billing and Payments',
            'Product Quality',
            'Delivery and Logistics',
            'Website / System Issues',
            'General Enquiry',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        User::firstOrCreate(
            ['email' => 'superadmin@ofcms.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'phone' => '08000000001',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@ofcms.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '08000000002',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@ofcms.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'complainant',
                'phone' => '08000000003',
            ]
        );
    }
}
