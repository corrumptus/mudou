<?php

use App\Http\Controllers\Web\General\AnnouncementController;
use App\Http\Controllers\Web\General\ChatController;
use App\Http\Controllers\Web\General\LoginController;
use App\Http\Controllers\Web\General\ParticipantsController;
use App\Http\Controllers\Web\General\DiscussionController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    LoginController::class
)->name('general.login');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/participants',
    ParticipantsController::class
)->name('general.participants');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/announcements',
    AnnouncementController::class
)->name('student.avisos');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/forun',
    [ DiscussionController::class, 'index' ]
)->name('student.forun');

Route::get(
    '/course/{courseName}/subject/{subjectName}/classroom/{classroomId}/forun/{discussionId}',
    [ DiscussionController::class, 'show' ]
)->name('student.discussion')
->where('discussionId', '^(?!new$)[^/]+$'); // já que este arquivo é declarado primeiro no router, essa rota sobrepõem a de criar uma nova discussão(student.php), então para que essa rota funcione sem interferir na outra rota, exclui a string 'new' via regex

Route::get(
    '/chat',
    ChatController::class
)->name('general.chat');