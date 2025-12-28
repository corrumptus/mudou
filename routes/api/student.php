<?php

use App\Http\Controllers\Api\Student\DiscussionController;
use App\Http\Controllers\Api\Student\GroupController;
use App\Http\Controllers\Api\Student\HomeworkController;

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/homework/{classElementId}/response',
    HomeworkController::class
)->name('api.sendHomeworkResponse');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}',
    [ GroupController::class, 'create' ]
)->name('api.createGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/cancel/{userId}',
    [ GroupController::class, 'cancelInvite' ]
)->name('api.acceptGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/request/{groupName}',
    [ GroupController::class, 'request' ]
)->name('api.acceptGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/request/{groupName}/cancel',
    [ GroupController::class, 'cancel' ]
)->name('api.acceptGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/accept',
    [ GroupController::class, 'accept' ]
)->name('api.acceptGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/decline',
    [ GroupController::class, 'decline' ]
)->name('api.declineGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/accept/{userId}',
    [ GroupController::class, 'acceptRequest' ]
)->name('api.acceptGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/decline/{userId}',
    [ GroupController::class, 'declineRequest' ]
)->name('api.declineGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/class/{classId}/group/{classElementId}/leave',
    [ GroupController::class, 'leave' ]
)->name('api.leaveGroup');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/forun',
    DiscussionController::class
)->name('api.createDiscussion');