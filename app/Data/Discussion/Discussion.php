<?php

namespace App\Data\Discussion;

use App\Data\User\User as UserDTO;
use App\Models\Discussion as DiscussionModel;

class Discussion {
    public static function listElementFromDiscussionModel(DiscussionModel $discussion) {
        return [
            "id" => $discussion->id,
            "title" => $discussion->title,
            "owner" => UserDTO::fromUserModelIncomplete($discussion->owner)
        ];
    }

    public static function fromDiscussionModel(DiscussionModel $discussion) {
        return [
            "id" => $discussion->id,
            "title" => $discussion->title,
            "description" => $discussion->description,
            "owner" => UserDTO::fromUserModelIncomplete($discussion->owner),
            "comments" => array_map(
                fn ($c) => DiscussionComment::fromDiscussionCommentModel($c),
                $discussion->comments->all()
            )
        ];
    }
}