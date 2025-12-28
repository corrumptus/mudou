<?php

use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\ClassroomController;
use App\Http\Controllers\Web\Admin\CourseController;
use App\Http\Controllers\Web\Admin\CourseSubjectController;
use App\Http\Controllers\Web\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/admin/people',
    [ AdminController::class, 'showPeople' ]
)->name('admin.user.show.all');

Route::get(
    '/user/new',
    [ UserController::class, 'create' ]
)->name('admin.user.create');

Route::get(
    '/first-access/{token}',
    [ UserController::class, 'first_access' ]
)->name('admin.user.firstAccess');

Route::get(
    '/user/{email}',
    [ UserController::class, 'show' ]
)->name('admin.user.show');

Route::get(
    '/user/{email}/edit',
    [ UserController::class, 'edit' ]
)->name('admin.user.edit');

Route::get(
    '/admin/courses',
    [ AdminController::class, 'showCourses' ]
)->name('admin.course.show.all');

Route::get(
    '/course/new',
    [ CourseController::class, 'create' ]
)->name('admin.course.create');

Route::get(
    '/course/{courseName}',
    [ CourseController::class, 'show' ]
)->name('admin.course.show');

Route::get(
    '/course/{courseName}/edit',
    [ CourseController::class, 'edit' ]
)->name('admin.course.edit');

Route::get(
    '/admin/subjects',
    [ AdminController::class, 'showSubjects' ]
)->name('admin.courseSubject.show.all');

Route::get(
    '/subject/new',
    [ CourseSubjectController::class, 'create' ]
)->name('admin.courseSubject.create');

Route::get(
    '/course/{courseName}/subject/{subjectName}', 
    [ CourseSubjectController::class, 'show' ]
)->name('admin.courseSubject.show');

Route::get(
    '/course/{courseName}/subject/{subjectName}/edit',
    [ CourseSubjectController::class, 'edit' ]
)->name('admin.courseSubject.edit');

Route::get(
    '/admin/classrooms',
    [ AdminController::class, 'showClassrooms' ]
)->name('admin.classroom.show.all');

Route::get(
    '/classroom/new',
    [ ClassroomController::class, 'create' ]
)->name('admin.classroom.create');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/show',
    [ ClassroomController::class, 'show' ]
)->name('admin.classroom.show');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/edit',
    [ ClassroomController::class, 'edit' ]
)->name('admin.classroom.edit');