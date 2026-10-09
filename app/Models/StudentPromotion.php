<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_classroom_id',
        'to_classroom_id',
        'from_school_year_id',
        'to_school_year_id',
        'status',
        'decision_date',
        'notes',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'decision_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'from_classroom_id');
    }

    public function toClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'to_classroom_id');
    }

    public function fromSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'from_school_year_id');
    }

    public function toSchoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'to_school_year_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
