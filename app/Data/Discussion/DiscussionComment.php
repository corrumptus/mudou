<?php

namespace App\Data\Discussion;

use App\Data\User\User as UserDTO;
use App\Models\discussionComment as DiscussionCommentModel;

class DiscussionComment {
    public static function fromDiscussionCommentModel(DiscussionCommentModel $discussionComment) {
        return [
            "id" => $discussionComment->id,
            "comment" => $discussionComment->comment,
            "user" => UserDTO::fromUserModelIncomplete($discussionComment->owner)
        ];
    }
}