<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@peaceinstitute.com'],
            [
                'name'      => 'Admin',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // ── Courses ────────────────────────────────────────────────────────
        $courseData = [
            ['name' => 'Qaida',          'level' => 'beginner',     'price_per_session' => 15, 'monthly_price' => 50,  'description' => 'Learn the Arabic alphabet and basic Quran reading from scratch.'],
            ['name' => 'Nazra',          'level' => 'beginner',     'price_per_session' => 18, 'monthly_price' => 60,  'description' => 'Fluent reading of the Holy Quran with correct pronunciation.'],
            ['name' => 'Tajweed',        'level' => 'intermediate', 'price_per_session' => 20, 'monthly_price' => 70,  'description' => 'Master the rules of Tajweed for beautiful Quran recitation.'],
            ['name' => 'Hifz',           'level' => 'advanced',     'price_per_session' => 25, 'monthly_price' => 90,  'description' => 'Memorize the Holy Quran with expert guidance and revision.'],
            ['name' => 'Tafseer',        'level' => 'advanced',     'price_per_session' => 25, 'monthly_price' => 90,  'description' => 'Deep understanding of Quranic verses and their meanings.'],
            ['name' => 'Arabic Grammar', 'level' => 'intermediate', 'price_per_session' => 22, 'monthly_price' => 75,  'description' => 'Learn Arabic grammar (Nahw & Sarf) to understand the Quran directly.'],
        ];

        foreach ($courseData as $data) {
            Course::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // ── Sample Teachers ────────────────────────────────────────────────
        $teachers = [
            ['name' => 'Sheikh Yusuf Ali',   'email' => 'yusuf@peaceinstitute.com',  'rate' => 20, 'exp' => 10, 'spec' => 'Hifz & Tajweed',    'subjects' => ['Hifz','Tajweed','Nazra']],
            ['name' => 'Ustadha Fatima Khan', 'email' => 'fatima@peaceinstitute.com', 'rate' => 18, 'exp' => 7,  'spec' => 'Qaida & Nazra',     'subjects' => ['Qaida','Nazra','Tajweed']],
            ['name' => 'Qari Hassan Malik',   'email' => 'hassan@peaceinstitute.com', 'rate' => 22, 'exp' => 12, 'spec' => 'Tajweed & Tafseer', 'subjects' => ['Tajweed','Tafseer','Arabic Grammar']],
        ];

        foreach ($teachers as $t) {
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name'      => $t['name'],
                    'password'  => Hash::make('password'),
                    'role'      => 'teacher',
                    'is_active' => true,
                ]
            );

            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'hourly_rate'      => $t['rate'],
                    'experience_years' => $t['exp'],
                    'specialization'   => $t['spec'],
                    'subjects'         => $t['subjects'],
                    'bio'              => 'Certified Quran teacher with ' . $t['exp'] . ' years of experience teaching students worldwide. Specialized in ' . $t['spec'] . '.',
                    'education'        => 'Islamic Studies, Al-Azhar University',
                    'certification'    => 'Ijazah in Quranic Recitation',
                    'status'           => 'approved',
                    'is_featured'      => true,
                    'rating'           => rand(40, 50) / 10,
                    'total_reviews'    => rand(10, 50),
                ]
            );

            // Set Mon-Fri 9am-5pm availability
            foreach (range(1, 5) as $day) {
                TeacherAvailability::updateOrCreate(
                    [
                        'teacher_id'  => $teacher->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'start_time'    => '09:00',
                        'end_time'      => '17:00',
                        'is_available'  => true,
                    ]
                );
            }
        }

        // ── Sample Students ────────────────────────────────────────────────
        $student_user = User::updateOrCreate(
            ['email' => 'student@peaceinstitute.com'],
            [
                'name'      => 'Ahmed Student',
                'password'  => Hash::make('password'),
                'role'      => 'student',
                'is_active' => true,
            ]
        );
        Student::updateOrCreate(['user_id' => $student_user->id], []);

        $this->command->info('✓ Database seeded successfully!');
        $this->command->info('  Admin:   admin@peaceinstitute.com / password');
        $this->command->info('  Teacher: yusuf@peaceinstitute.com / password');
        $this->command->info('  Student: student@peaceinstitute.com / password');
    }
}
