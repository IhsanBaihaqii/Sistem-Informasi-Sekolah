### `docs/07-routing-structure.md`

# Routing Structure

## Prinsip

Route dikelompokkan berdasarkan authentication, role, dan modul.

## Admin

```text
/admin/dashboard

/admin/users

/admin/students
/admin/teachers
/admin/parents

/admin/school-years
/admin/semesters
/admin/majors
/admin/classrooms
/admin/rooms
/admin/subjects

/admin/schedules

/admin/reports
```

## Teacher

```text
/teacher/dashboard

/teacher/schedule
/teacher/classes

/teacher/attendance

/teacher/materials

/teacher/assignments
/teacher/submissions

/teacher/exams

/teacher/grades
```

## Student

```text
/student/dashboard

/student/profile
/student/schedule
/student/attendance

/student/materials

/student/assignments
/student/submissions

/student/exams

/student/grades
```

## Parent

```text
/parent/dashboard

/parent/children
/parent/attendance
/parent/grades
```

## Principal

```text
/principal/dashboard

/principal/students
/principal/teachers
/principal/classes

/principal/attendance
/principal/grades
/principal/reports
```

## Naming

Gunakan named routes.

Contoh:

```text
admin.students.index
admin.students.create
admin.students.store
admin.students.show
admin.students.edit
admin.students.update
admin.students.destroy
```

Hindari route tanpa nama untuk halaman utama aplikasi.
