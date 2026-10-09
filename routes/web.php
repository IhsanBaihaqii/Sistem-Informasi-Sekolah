<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teacher\ExamController as TeacherExamController;
use App\Http\Controllers\Student\ExamController as StudentExamController;

use App\Http\Controllers\Admin\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'role:super_admin|admin'])
    ->prefix('admin/siswa')
    ->name('admin.students.')
    ->controller(StudentController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('show');
        Route::get('/{id}/edit', 'edit')->whereNumber('id')->name('edit');
        Route::put('/{id}', 'update')->whereNumber('id')->name('update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('destroy');
    });

Route::middleware(['auth', 'role:teacher'])
    ->prefix('guru')
    ->name('teacher.')
    ->group(function () {
        Route::get('/ujian', [TeacherExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/ujian/buat', [TeacherExamController::class, 'create'])
            ->name('exams.create');

        Route::post('/ujian', [TeacherExamController::class, 'store'])
            ->name('exams.store');

        Route::get('/ujian/{exam}', [TeacherExamController::class, 'show'])
            ->name('exams.show');

        Route::post('/ujian/{exam}/soal', [TeacherExamController::class, 'storeQuestion'])
            ->name('exams.questions.store');

        Route::post('/ujian/{exam}/publikasikan', [TeacherExamController::class, 'publish'])
            ->name('exams.publish');

        Route::get('/ujian/{exam}/hasil', [TeacherExamController::class, 'results'])
            ->name('exams.results');
    });

Route::middleware(['auth', 'role:student'])
    ->prefix('siswa')
    ->name('student.')
    ->group(function () {
        Route::get('/ujian', [StudentExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/ujian/{exam}', [StudentExamController::class, 'show'])
            ->name('exams.show');

        Route::post('/ujian/{exam}/mulai', [StudentExamController::class, 'start'])
            ->name('exams.start');

        Route::get('/percobaan-ujian/{attempt}', [StudentExamController::class, 'take'])
            ->name('exams.take');

        Route::post('/percobaan-ujian/{attempt}/jawaban', [StudentExamController::class, 'answer'])
            ->name('exams.answer');

        Route::post('/percobaan-ujian/{attempt}/kumpulkan', [StudentExamController::class, 'submit'])
            ->name('exams.submit');
    });

require __DIR__ . '/auth.php';
