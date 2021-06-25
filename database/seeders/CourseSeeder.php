<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseEdition;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::insert([
            'course_number' => 'CSE1105',
            'description' => 'Object Oriented Programming Project'
        ]);
        Course::insert([
            'course_number' => 'CSE2115',
            'description' => 'Software Engineering Methods'
        ]);
        Course::insert([
            'course_number' => 'CSE2000',
            'description' => 'Software Project'
        ]);
        CourseEdition::insert([
            'course_id' => 1,
            'year' => 2020
        ]);
        CourseEdition::insert([
            'course_id' => 1,
            'year' => 2021
        ]);
        CourseEdition::insert([
            'course_id' => 2,
            'year' => 2019
        ]);
        CourseEdition::insert([
            'course_id' => 2,
            'year' => 2020
        ]);
        CourseEdition::insert([
            'course_id' => 3,
            'year' => 2019
        ]);
        CourseEdition::insert([
            'course_id' => 3,
            'year' => 2020
        ]);
    }
}
