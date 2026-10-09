<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAcademicHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'semester_id',
        'classroom_id',
        'final_score',
        'rank',
        'attendance_summary',
        'academic_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'final_score' => 'decimal:2',
            'rank' => 'integer',
            'attendance_summary' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
