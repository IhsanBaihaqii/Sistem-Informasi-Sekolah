<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where('name', '2026/2027')->firstOrFail();

        $semester = Semester::where('school_year_id', $schoolYear->id)
            ->where('name', 'Ganjil')
            ->firstOrFail();

        $teacher = Teacher::firstOrFail();

        $classroom = Classroom::where('school_year_id', $schoolYear->id)
            ->where('name', 'X TKJ 1')
            ->firstOrFail();

        $subjects = Subject::orderBy('id')->take(2)->get();

        if ($subjects->isEmpty()) {
            return;
        }

        foreach ($subjects as $index => $subject) {
            $exam = Exam::updateOrCreate(
                [
                    'school_year_id' => $schoolYear->id,
                    'semester_id' => $semester->id,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroom->id,
                    'title' => 'Ujian Tengah Semester ' . $subject->name,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'description' => 'Ujian Tengah Semester untuk mata pelajaran ' . $subject->name,
                    'start_at' => now()->subHour(),
                    'end_at' => now()->addDays(7),
                    'duration_minutes' => 60,
                    'passing_score' => 75,
                    'is_published' => true,
                    'shuffle_questions' => false,
                    'shuffle_options' => false,
                ]
            );

            if ($exam->questions()->count() > 0) {
                continue;
            }

            $questions = [
                [
                    'question_text' => 'Apa fungsi utama dari sistem operasi?',
                    'points' => 20,
                    'options' => [
                        ['A', 'Mengelola sumber daya komputer', true],
                        ['B', 'Membuat kabel jaringan', false],
                        ['C', 'Mengganti perangkat keras', false],
                        ['D', 'Mencetak dokumen', false],
                    ],
                ],
                [
                    'question_text' => 'Perangkat yang digunakan untuk menghubungkan beberapa komputer dalam jaringan LAN adalah?',
                    'points' => 20,
                    'options' => [
                        ['A', 'Scanner', false],
                        ['B', 'Switch', true],
                        ['C', 'Printer', false],
                        ['D', 'Speaker', false],
                    ],
                ],
                [
                    'question_text' => 'Manakah yang termasuk sistem operasi?',
                    'points' => 20,
                    'options' => [
                        ['A', 'Windows', true],
                        ['B', 'HTML', false],
                        ['C', 'MySQL', false],
                        ['D', 'Laravel', false],
                    ],
                ],
                [
                    'question_text' => 'Apa kepanjangan dari IP?',
                    'points' => 20,
                    'options' => [
                        ['A', 'Internet Protocol', true],
                        ['B', 'Internal Program', false],
                        ['C', 'Internet Program', false],
                        ['D', 'Internal Protocol', false],
                    ],
                ],
                [
                    'question_text' => 'Perangkat yang digunakan untuk menghubungkan jaringan yang berbeda adalah?',
                    'points' => 20,
                    'options' => [
                        ['A', 'Keyboard', false],
                        ['B', 'Mouse', false],
                        ['C', 'Router', true],
                        ['D', 'Monitor', false],
                    ],
                ],
            ];

            foreach ($questions as $questionIndex => $questionData) {
                $question = Question::create([
                    'exam_id' => $exam->id,
                    'question_text' => $questionData['question_text'],
                    'question_type' => 'multiple_choice',
                    'points' => $questionData['points'],
                    'question_order' => $questionIndex + 1,
                ]);

                foreach ($questionData['options'] as $optionIndex => $optionData) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_label' => $optionData[0],
                        'option_text' => $optionData[1],
                        'is_correct' => $optionData[2],
                        'option_order' => $optionIndex + 1,
                    ]);
                }
            }
        }
    }
}
