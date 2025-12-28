<?php

use App\Http\Controllers\Web\Teacher\AnnouncementController;
use App\Http\Controllers\Web\Teacher\ClassroomController;
use App\Http\Controllers\Web\Teacher\CourseSubjectController;
use App\Http\Controllers\Web\Teacher\GroupController;
use App\Http\Controllers\Web\Teacher\HomeworkController;

Route::get(
    '/teacher/course-subject',
    CourseSubjectController::class
)->name('teacher.courseSubject');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}',
    ClassroomController::class
)->name('teacher.classroom');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/homework/new',
    [ HomeworkController::class, 'create' ]
)->name('teacher.homework.create');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/new',
    [ GroupController::class, 'create' ]
)->name('teacher.group.create');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/{classElementId}',
    [ GroupController::class, 'show' ]
)->name('teacher.group.create');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/{classElementId}/edit',
    [ GroupController::class, 'edit' ]
)->name('teacher.group.edit');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/{classElementId}/new',
    [ GroupController::class, 'newGroup' ]
)->name('teacher.group.newGroup');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/{classElementId}/group/{groupName}',
    [ GroupController::class, 'editGroup' ]
)->name('teacher.group.editGroup');

Route::get(
    '/teacher/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/announcements/new',
    AnnouncementController::class
)->name('teacher.announcement.create');