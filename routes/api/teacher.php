<?php

use App\Http\Controllers\Api\Teacher\AnnouncementController;
use App\Http\Controllers\Api\Teacher\ClassController;
use App\Http\Controllers\Api\Teacher\GroupController;
use App\Http\Controllers\Api\Teacher\HomeworkController;

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class',
    ClassController::class
)->name('api.createClass');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/homework/new',
    [ HomeworkController::class, 'create' ]
)->name('api.createHomework');

Route::post(
    '/teacher/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/new',
    [ GroupController::class, 'create' ]
)->name('api.createGroup');

Route::post(
    '/teacher/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}',
    [ GroupController::class, 'edit' ]
)->name('api.editGroup');

Route::post(
    '/teacher/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/new',
    [ GroupController::class, 'createGroup' ]
)->name('api.createGroup');

Route::post(
    '/teacher/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/{groupName}',
    [ GroupController::class, 'editGroup' ]
)->name('api.editGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/announcement',
    AnnouncementController::class
)->name('api.createAnnouncement');