<?php

namespace Database\Seeders;

use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [
            [
                'test_code' => 'TC001',
                'test_name' => 'Sample Test 1',
                'duration' => '03:00:00',
                'instructor_id' => 1,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
            ],
            [
                'test_code' => 'TC002',
                'test_name' => 'Sample Test 2',
                'duration' => '03:00:00',
                'instructor_id' => 2,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(8),
            ],
        ];

        // Insert the sample data into the database
        foreach ($tests as $test) {
            Test::create($test);
        }
    }
}
