<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(300)->confirmed()->create();
        Booking::factory(50)->pending()->create();
        Booking::factory(50)->cancelled()->create();
    }
}
