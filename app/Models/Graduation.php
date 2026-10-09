<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Graduation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'graduation_date',
        'certificate_number',
        'status',
        'notes',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'graduation_date' => 'date',
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
