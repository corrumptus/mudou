<?php

use App\Http\Controllers\Api\General\AnnouncementController;
use App\Http\Controllers\Api\General\LoginController;
use App\Http\Controllers\Api\General\DiscussionCommentController;

Route::post(
    '/login',
    [ LoginController::class, 'login' ]
)->name('api.login');

Route::get(
    '/logout',
    [ LoginController::class, 'logout' ]
)->name('api.logout');

Route::delete(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/announcements/{announcementId}/notSaw',
    AnnouncementController::class
)->name('api.logout');

Route::post(
    '/course/{courseId}/subject/{subjectId}/classroom/{classroomId}/forun/{discussionId}/comments',
    DiscussionCommentController::class
)->name('api.createDiscussionComment');