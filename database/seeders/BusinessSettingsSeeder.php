<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_settings')->insert([
            'type' => '',
            'value' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
