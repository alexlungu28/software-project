<?php

namespace Database\Seeders;

use App\Models\Rubric;
use App\Models\RubricEntry;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 6; $i++) {
            Rubric::insert([
                'name' => 'General',
                'course_edition_id' => $i,
                'week' => null,
            ]);
            for ($j = 1; $j <= 10; $j++) {
                Rubric::insert([
                    'name' => 'Contributions',
                    'course_edition_id' => $i,
                    'week' => $j,
                ]);
            }
        }
        for ($i = 1; $i <= 6; $i++) {
            for ($j = 1; $j <= 11; $j++) {
                RubricEntry::insert([
                    'rubric_id' => (($i - 1) * 11) + $j,
                    'distance' => 0,
                    'is_row' => 0,
                    'description' => 'Bad'
                ]);
                RubricEntry::insert([
                    'rubric_id' => (($i - 1) * 11) + $j,
                    'distance' => 1,
                    'is_row' => 0,
                    'description' => 'Sufficient'
                ]);
                RubricEntry::insert([
                    'rubric_id' => (($i - 1) * 11) + $j,
                    'distance' => 2,
                    'is_row' => 0,
                    'description' => 'Good'
                ]);
                if ($j == 1) {
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 0,
                        'is_row' => 1,
                        'description' => 'The product is of high quality'
                    ]);
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 1,
                        'is_row' => 1,
                        'description' => 'The group shares tasks evenly'
                    ]);
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 2,
                        'is_row' => 1,
                        'description' => 'Code is well documented with comments and a README'
                    ]);
                } else {
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 0,
                        'is_row' => 1,
                        'description' => 'Every student participates in coding'
                    ]);
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 1,
                        'is_row' => 1,
                        'description' => 'Every student participates in the meetings'
                    ]);
                    RubricEntry::insert([
                        'rubric_id' => (($i - 1) * 11) + $j,
                        'distance' => 2,
                        'is_row' => 1,
                        'description' => 'Every student participates in the project overall'
                    ]);
                }
            }
        }
    }
}
