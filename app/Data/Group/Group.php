<?php

namespace App\Data\Group;

use App\Data\User\User as UserDTO;
use App\Models\Classroom;
use App\Models\Group as GroupModel;
use App\Models\User;

class Group
{
    public static function fromGroupModel(GroupModel $group, User $user)
    {
        $member = $group->members()->withPivot(['group_name', 'status'])->find($user->id);

        if ($member == null)
            return Group::fromNoGroup($group, $user);

        return Group::fromGroup(
            $group,
            $user,
            $member->pivot->status,
            $member->pivot->group_name
        );
    }

    private static function fromNoGroup(GroupModel $group, User $user) {
        $hasThemes = $group->themes->count() > 0;

        return [
            "id" => $group->id,
            "title" => $group->title,
            "status" => "noGroup",
            "groupMaker" => [
                "maxMembers" => $group->max_members,
                "users" => array_map(
                    fn ($u) => UserDTO::fromUserModelIncomplete($u),
                    $group->classElement->class->classroom->students()
                        ->whereNot('id', $user->id)
                        ->whereNotIn('id', $group->members()->select('id'))
                        ->get()
                        ->all()
                ),
                "themes" => $hasThemes ?
                    array_map(
                        fn ($t) => $t->theme,
                        $group->themes->all()
                    )
                    :
                    null
            ],
            "existingGroups" => $group->members()
                ->withPivot(['group_name'])
                ->wherePivotNotNull('group_name')
                ->get()
                ->reduce(function ($carry, $item) {
                    $group_name = $item->pivot->group_name;
                    
                    if (!\array_key_exists($group_name, $carry))
                        $carry[$group_name] = [];

                    $carry[$group_name][] = UserDTO::fromUserModelIncomplete($item);

                    return $carry;
                }, []),
            "classId" => $group->classElement->class->id
        ];
    }

    private static function fromGroup(
        GroupModel $group,
        User $user,
        string $status,
        string $group_name
    ) {
        return [
            "id" => $group->id,
            "title" => $group->title,
            "status" => $status,
            "group" => [
                "name" => $group_name,
                "members" => array_map(
                    fn ($u) => [
                        ...UserDTO::fromUserModelIncomplete($u),
                        "status" => $u->pivot->status
                    ],
                    $group->members()
                        ->whereNot('id', $user->id)
                        ->wherePivot(
                            'group_name',
                            $group_name
                        )
                        ->withPivot(['status'])
                        ->get()
                        ->all()
                )
            ],
            "classId" => $group->classElement->class->id
        ];
    }

    public static function forTeacher(GroupModel $group) {
        $hasThemes = $group->themes->count() > 0;

        return [
            "id" => $group->id,
            "title" => $group->title,
            "groupMaker" => [
                "maxMembers" => $group->max_members,
                "themes" => $hasThemes ?
                    array_map(
                        fn ($t) => $t->theme,
                        $group->themes->all()
                    )
                    :
                    null
            ],
            "members" => array_map(
                function ($m) {
                    // $status = $m->pivot->status;
                    $status = $m->getRawOriginal()["pivot_status"]; // since im not accessing $m as a pure classroom_students/users row, i dont have the ability to use $m as it

                    return [
                        ...UserDTO::fromUserModelIncomplete($m),
                        "groupName" => $m->getRawOriginal()["pivot_group_name"],
                        "status" => $status != null ? $status : "noGroup"
                    ];
                },
                $group->classElement->class->classroom->students()
                    ->leftJoin('group_members as members', [
                        [ 'group_id', $group->id ],
                        [ 'members.user_id', 'classroom_students.user_id' ]
                    ])
                    ->addSelect([
                        'users.*',
                        'members.group_name as pivot_group_name',
                        'members.status as pivot_status',
                    ])
                    ->get()
                    ->all()
            ),
            "classId" => $group->classElement->class->id
        ];
    }

    public static function forTeacherEdit(GroupModel $group) {
        return [
            "id" => $group->id,
            "title" => $group->title,
            "maxMembers" => $group->max_members,
            "themes" => array_map(
                fn ($t) => $t->theme,
                $group->themes->all()
            ),
            "classId" => $group->classElement->class->id
        ];
    }
}

