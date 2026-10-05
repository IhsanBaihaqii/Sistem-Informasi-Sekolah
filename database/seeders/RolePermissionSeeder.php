<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'students.view',
            'students.create',
            'students.update',
            'students.delete',

            'teachers.view',
            'teachers.create',
            'teachers.update',
            'teachers.delete',

            'parents.view',
            'parents.create',
            'parents.update',
            'parents.delete',

            'school-years.view',
            'school-years.create',
            'school-years.update',
            'school-years.delete',

            'semesters.view',
            'semesters.create',
            'semesters.update',
            'semesters.delete',

            'majors.view',
            'majors.create',
            'majors.update',
            'majors.delete',

            'classrooms.view',
            'classrooms.create',
            'classrooms.update',
            'classrooms.delete',

            'rooms.view',
            'rooms.create',
            'rooms.update',
            'rooms.delete',

            'subjects.view',
            'subjects.create',
            'subjects.update',
            'subjects.delete',

            'schedules.view',
            'schedules.create',
            'schedules.update',
            'schedules.delete',

            'attendance.view',
            'attendance.create',
            'attendance.update',

            'materials.view',
            'materials.create',
            'materials.update',
            'materials.delete',

            'assignments.view',
            'assignments.create',
            'assignments.update',
            'assignments.delete',

            'exams.view',
            'exams.create',
            'exams.update',
            'exams.delete',

            'grades.view',
            'grades.create',
            'grades.update',

            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $principal = Role::firstOrCreate([
            'name' => 'principal',
            'guard_name' => 'web',
        ]);

        $teacher = Role::firstOrCreate([
            'name' => 'teacher',
            'guard_name' => 'web',
        ]);

        $student = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'web',
        ]);

        $parent = Role::firstOrCreate([
            'name' => 'parent',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'dashboard.view',

            'users.view',

            'students.view',
            'students.create',
            'students.update',
            'students.delete',

            'teachers.view',
            'teachers.create',
            'teachers.update',
            'teachers.delete',

            'parents.view',
            'parents.create',
            'parents.update',
            'parents.delete',

            'school-years.view',
            'school-years.create',
            'school-years.update',

            'semesters.view',
            'semesters.create',
            'semesters.update',

            'majors.view',
            'majors.create',
            'majors.update',

            'classrooms.view',
            'classrooms.create',
            'classrooms.update',

            'rooms.view',
            'rooms.create',
            'rooms.update',

            'subjects.view',
            'subjects.create',
            'subjects.update',

            'schedules.view',
            'schedules.create',
            'schedules.update',

            'reports.view',
        ]);

        $principal->syncPermissions([
            'dashboard.view',
            'students.view',
            'teachers.view',
            'parents.view',
            'school-years.view',
            'semesters.view',
            'majors.view',
            'classrooms.view',
            'rooms.view',
            'subjects.view',
            'schedules.view',
            'attendance.view',
            'grades.view',
            'reports.view',
        ]);

        $teacher->syncPermissions([
            'dashboard.view',
            'students.view',
            'classrooms.view',
            'subjects.view',
            'schedules.view',
            'attendance.view',
            'attendance.create',
            'attendance.update',
            'materials.view',
            'materials.create',
            'materials.update',
            'materials.delete',
            'assignments.view',
            'assignments.create',
            'assignments.update',
            'assignments.delete',
            'exams.view',
            'exams.create',
            'exams.update',
            'exams.delete',
            'grades.view',
            'grades.create',
            'grades.update',
        ]);

        $student->syncPermissions([
            'dashboard.view',
            'schedules.view',
            'attendance.view',
            'materials.view',
            'assignments.view',
            'exams.view',
            'grades.view',
        ]);

        $parent->syncPermissions([
            'dashboard.view',
            'attendance.view',
            'grades.view',
        ]);
    }
}
