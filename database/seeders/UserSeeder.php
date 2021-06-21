<?php

namespace Database\Seeders;

use App\Models\CourseEditionUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // This user is a lecturer in all 3 courses: OOPP, SEM and SP
        User::insert([
            'org_defined_id' => 'employee',
            'net_id' => 'employee',
            'last_name' => 'One',
            'first_name' => 'Employee',
            'email' => 'employee-fakesaml@tudelft.nl',
            'affiliation' => 'employee'
        ]);
        for ($i = 1; $i <= 6; $i++) {
            CourseEditionUser::insert([
                'user_id' => 1,
                'course_edition_id' => $i,
                'role' => 'lecturer'
            ]);
        }
        // This employee is only a lecturer in course 2: Software Engineering Methods
        User::insert([
            'org_defined_id' => 'employee2',
            'net_id' => 'employee2',
            'last_name' => 'Two',
            'first_name' => 'Employee',
            'email' => 'employee2-fakesaml@tudelft.nl',
            'affiliation' => 'employee'
        ]);
        for ($i = 3; $i <= 4; $i++) {
            CourseEditionUser::insert([
                'user_id' => 2,
                'course_edition_id' => $i,
                'role' => 'lecturer'
            ]);
        }
        // This employee is only a lecturer in course 3: Software Project
        User::insert([
            'org_defined_id' => 'employee3',
            'net_id' => 'employee3',
            'last_name' => 'Three',
            'first_name' => 'Employee',
            'email' => 'employee3-fakesaml@tudelft.nl',
            'affiliation' => 'employee'
        ]);
        for ($i = 5; $i <= 6; $i++) {
            CourseEditionUser::insert([
                'user_id' => 3,
                'course_edition_id' => $i,
                'role' => 'lecturer'
            ]);
        }
        // This student is a TA for all 3 courses and for both groups in each course
        User::insert([
            'org_defined_id' => '9000001',
            'net_id' => 'student1',
            'last_name' => 'the Surname1',
            'first_name' => 'Student',
            'email' => 'student1-fakesaml@student.tudelft.nl',
            'affiliation' => 'student'
        ]);
        for ($i = 1; $i <= 6; $i++) {
            CourseEditionUser::insert([
                'user_id' => 4,
                'course_edition_id' => $i,
                'role' => 'TA'
            ]);
        }
        // These 6 students are participating in all courses for a grade. They are linked to groups in the GroupSeeder
        for ($i = 2; $i <= 7; $i++) {
            User::insert([
                'org_defined_id' => '900000' . $i,
                'net_id' => 'student' . $i,
                'last_name' => 'the Surname' . $i,
                'first_name' => 'Student',
                'email' => 'student' . $i . '-fakesaml@student.tudelft.nl',
                'affiliation' => 'student'
            ]);
            for ($j = 1; $j <= 6; $j++) {
                CourseEditionUser::insert([
                    'user_id' => $i + 3,
                    'course_edition_id' => $j,
                    'role' => 'student'
                ]);
            }
        }
    }
}
