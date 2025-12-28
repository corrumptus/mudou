<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ClassroomController;
use App\Http\Controllers\Api\Admin\CourseController;
use App\Http\Controllers\Api\Admin\CourseSubjectController;

Route::post(
    '/user',
    [ UserController::class, 'create' ]
)->name('api.createUser');

Route::post(
    '/first-access/{token}',
    [ UserController::class, 'first_access']
)->name('api.first-access');

Route::post(
    '/user/{userId}',
    [ UserController::class, 'edit' ]
)->name('api.editUser');

Route::post(
    '/course',
    [ CourseController::class, 'create' ]
)->name('api.createCourse');

Route::post(
    '/course/{courseId}',
    [ CourseController::class, 'edit' ]
)->name('api.editCourse');

Route::post(
    '/course/{courseId}/subject',
    [ CourseSubjectController::class, 'create' ]
)->name('api.createCourseSubject');

Route::post(
    '/course/{courseId}/subject/{subjectId}',
    [ CourseSubjectController::class, 'edit' ]
)->name('api.editCourseSubject');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom',
    [ ClassroomController::class, 'create' ]
)->name('api.createClassroom');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}',
    [ ClassroomController::class, 'edit' ]
)->name('api.editClassroom');