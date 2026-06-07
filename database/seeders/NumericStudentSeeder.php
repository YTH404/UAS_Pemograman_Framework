<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumericStudentSeeder extends Seeder
{
    /**
     * Seed numeric-username students and their class relation.
     */
    public function run(): void
    {
        $classes = [
            [
                'class_name' => 'Informatika 6A',
                'class_code' => 'IF6A',
            ],
            [
                'class_name' => 'Informatika 6B',
                'class_code' => 'IF6B',
            ],
        ];

        $students = [
            ['name' => 'Alviano Diego Ozbar', 'username' => '123111001', 'class_code' => 'IF6A'],
            ['name' => 'Bayu Anggara Putra', 'username' => '123111003', 'class_code' => 'IF6A'],
            ['name' => 'Cherry Cardinawati Sitohang', 'username' => '123111004', 'class_code' => 'IF6A'],
            ['name' => 'Dara Erliana', 'username' => '123111005', 'class_code' => 'IF6A'],
            ['name' => 'Fachrizal Ardiansyah', 'username' => '123111007', 'class_code' => 'IF6A'],
            ['name' => 'Fitri Kinkin', 'username' => '123111008', 'class_code' => 'IF6A'],
            ['name' => 'Ibrahim Aziz', 'username' => '123111010', 'class_code' => 'IF6A'],
            ['name' => 'Idham Azis Pangestu', 'username' => '132111011', 'class_code' => 'IF6A'],
            ['name' => 'Ilham Ahmad Firdaus', 'username' => '123111012', 'class_code' => 'IF6A'],
            ['name' => 'Mochamad Akbarsin', 'username' => '123111013', 'class_code' => 'IF6A'],
            ['name' => 'Muhammad Ikhsan Nur', 'username' => '123111014', 'class_code' => 'IF6A'],
            ['name' => 'Muhamad Rahadiansyah Syauqi', 'username' => '123111015', 'class_code' => 'IF6A'],
            ['name' => 'Muhammad Andika Dwi Chandra', 'username' => '123111016', 'class_code' => 'IF6A'],
            ['name' => 'Muhammad Azhar Ruddiya Ilmalyaqin', 'username' => '123111017', 'class_code' => 'IF6A'],
            ['name' => 'Naufal Fajar Saputra', 'username' => '123111019', 'class_code' => 'IF6A'],
            ['name' => 'Raka Deny Abdi Putra', 'username' => '123111021', 'class_code' => 'IF6A'],
            ['name' => 'Rina Mulyani', 'username' => '123111022', 'class_code' => 'IF6A'],
            ['name' => 'Teguh Akbarudin', 'username' => '123111023', 'class_code' => 'IF6A'],
            ['name' => 'Yesaya Teofilus Hendrawan', 'username' => '123111024', 'class_code' => 'IF6A'],
            ['name' => 'Adam Yunizar', 'username' => '123111025', 'class_code' => 'IF6B'],
            ['name' => 'Dika Ratu Anisa', 'username' => '123111029', 'class_code' => 'IF6B'],
            ['name' => 'Erik Parlindungan Sihaloho', 'username' => '123111030', 'class_code' => 'IF6B'],
            ['name' => 'Faisal Yafi Hisyam', 'username' => '123111031', 'class_code' => 'IF6B'],
            ['name' => 'Fajar Zaini Syam', 'username' => '123111032', 'class_code' => 'IF6B'],
            ['name' => 'Gerry Muhamad Wafi', 'username' => '123111034', 'class_code' => 'IF6B'],
            ['name' => 'Indra Permana', 'username' => '123111036', 'class_code' => 'IF6B'],
            ['name' => 'Muhammad Hasanuddin', 'username' => '123111038', 'class_code' => 'IF6B'],
            ['name' => 'Muhammad Rangga Sugi Syahputra', 'username' => '123111040', 'class_code' => 'IF6B'],
            ['name' => 'Muhammad Rozan Aqila', 'username' => '123111041', 'class_code' => 'IF6B'],
            ['name' => 'Nayla Nurul Azkiya', 'username' => '123111042', 'class_code' => 'IF6B'],
            ['name' => 'Rhenaldi Naufal Putra', 'username' => '123111043', 'class_code' => 'IF6B'],
            ['name' => 'Riyan Apriansyah', 'username' => '123111044', 'class_code' => 'IF6B'],
            ['name' => 'Ryan Rangga Pratama', 'username' => '123111045', 'class_code' => 'IF6B'],
            ['name' => 'Yohanes Christian Andrianus', 'username' => '123111047', 'class_code' => 'IF6B'],
            ['name' => 'Anggi Dewi Nurcahyani', 'username' => '123111048', 'class_code' => 'IF6B'],
            ['name' => 'Gerald Lopumeten', 'username' => '124111031', 'class_code' => 'IF6B'],
            ['name' => 'Fabyan Rafi Syuja Ismail', 'username' => '9882405122111007', 'class_code' => 'IF6B'],
        ];

        DB::transaction(function () use ($classes, $students) {
            $classIds = collect($classes)
                ->mapWithKeys(function (array $class) {
                    $model = Classes::withTrashed()->firstOrNew([
                        'class_code' => $class['class_code'],
                    ]);

                    $model->fill([
                        'class_name' => $class['class_name'],
                    ]);

                    if ($model->trashed()) {
                        $model->restore();
                    }

                    $model->save();

                    return [$model->class_code => $model->id];
                });

            foreach ($students as $studentData) {
                $student = Student::withTrashed()->firstOrNew([
                    'username' => $studentData['username'],
                ]);

                $student->fill([
                    'name' => $studentData['name'],
                ]);

                if (! $student->exists) {
                    $student->password = 'password';
                }

                if ($student->trashed()) {
                    $student->restore();
                }

                $student->save();

                StudentClass::updateOrCreate(
                    ['student_id' => $student->id],
                    ['class_id' => $classIds[$studentData['class_code']]]
                );
            }
        });
    }
}
