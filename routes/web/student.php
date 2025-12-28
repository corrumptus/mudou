<?php

use App\Http\Controllers\Web\Student\AnotationController;
use App\Http\Controllers\Web\Student\CourseSubjectController;
use App\Http\Controllers\Web\Student\ClassroomController;
use App\Http\Controllers\Web\Student\DiscussionController;
use App\Http\Controllers\Web\Student\GroupController;
use App\Http\Controllers\Web\Student\HomeworkController;
use App\Http\Controllers\Web\Student\QuizController;

Route::get(
    '/course-subject',
    CourseSubjectController::class
)->name('student.courseSubject');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}',
    ClassroomController::class
)->name('student.classroom');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/homework/{classElementId}',
    HomeworkController::class
)->name('student.homework');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/quiz/{classElementId}',
    [ QuizController::class, 'index' ]
)->name('student.quiz');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/quiz/{classElementId}/{quizTryId}',
    [ QuizController::class, 'show' ]
)->name('student.quizTry');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/class/{classId}/group/{classElementId}',
    GroupController::class
)->name('student.group');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/forun/new',
    DiscussionController::class
)->name('student.createDiscussion');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/anotations',
    [ AnotationController::class, 'indexClassroom' ]
)->name('student.classroomAnotations');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/anotations/self',
    [ AnotationController::class, 'self' ]
)->name('student.selfAnotation');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/anotations/self/edit',
    [ AnotationController::class, 'selfEdit' ]
)->name('student.editSelfAnotation');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/anotations/monitor/{userEmail}',
    [ AnotationController::class, 'monitor' ]
)->name('student.monitorAnotation');

Route::get(
    '/course/{courseName}/anotations/{userId}',
    [ AnotationController::class, 'public' ]
)->name('student.publicAnotation');