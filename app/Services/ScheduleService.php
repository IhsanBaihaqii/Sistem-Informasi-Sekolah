<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Validation\ValidationException;

class ScheduleService
{
    public function validateNoConflict(array $data, ?int $ignoreId = null): void
    {
        $baseQuery = Schedule::query()
            ->where('school_year_id', $data['school_year_id'])
            ->where('semester_id', $data['semester_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('is_active', true)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->where(function ($query) use ($data) {
                $query
                    ->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            });

        $teacherConflict = (clone $baseQuery)
            ->where('teacher_id', $data['teacher_id'])
            ->exists();

        if ($teacherConflict) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Guru sudah memiliki jadwal lain pada waktu tersebut.',
            ]);
        }

        $classroomConflict = (clone $baseQuery)
            ->where('classroom_id', $data['classroom_id'])
            ->exists();

        if ($classroomConflict) {
            throw ValidationException::withMessages([
                'classroom_id' => 'Kelas sudah memiliki jadwal lain pada waktu tersebut.',
            ]);
        }

        if (!empty($data['room_id'])) {
            $roomConflict = (clone $baseQuery)
                ->where('room_id', $data['room_id'])
                ->exists();

            if ($roomConflict) {
                throw ValidationException::withMessages([
                    'room_id' => 'Ruangan sudah digunakan pada waktu tersebut.',
                ]);
            }
        }
    }
}
