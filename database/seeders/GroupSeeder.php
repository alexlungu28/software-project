<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Students with id 5 through 7 are in group 1 of each course as students
        // id 8 through 10 are in group 2 of each course as students.
        for ($i = 1; $i <= 6; $i++) {
            Group::insert([
                'group_name' => 'Group 1',
                'content' => "Email List: student2-fakesaml@student.tudelft.nl,
                            student3-fakesaml@student.tudelft.nl,
                            student4-fakesaml@student.tudelft.nl",
                'grade' => null,
                'course_edition_id' => $i,
            ]);
            Group::insert([
                'group_name' => 'Group 2',
                'content' => "Email List: student5-fakesaml@student.tudelft.nl,
                            student6-fakesaml@student.tudelft.nl,
                            student7-fakesaml@student.tudelft.nl",
                'grade' => null,
                'course_edition_id' => $i,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2 - 1,
                'user_id' => 4,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2,
                'user_id' => 4,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2 - 1,
                'user_id' => 5,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2 - 1,
                'user_id' => 6,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2 - 1,
                'user_id' => 7,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2,
                'user_id' => 8,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2,
                'user_id' => 9,
                'student_grade' => null,
            ]);
            GroupUser::insert([
                'group_id' => $i * 2,
                'user_id' => 10,
                'student_grade' => null,
            ]);
        }
    }
}
