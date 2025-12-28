<?php

namespace App\Http\Controllers\Api\Student;

use App\Data\Group\Group as GroupDTO;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController
{
    public function create(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $newGroup = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'members' => ['required', 'array', 'min:1'],
                'members.*' => ['required', 'integer', 'exists:users,id'],
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if ($group->members()->wherePivot('group_name', $newGroup["name"])->exists())
                throw ValidationException::withMessages([ "name" => "validation.exists" ]);

            if (\count($newGroup["members"]) > $group->max_members)
                throw ValidationException::withMessages([ "members" => "validation.max_members" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "members" => "validation.exists" ]);
        
            if ($group->members()->where('user_id', $user->id)->exists())
                throw ValidationException::withMessages([ "members" => "validation.in_group" ]);

            foreach ($newGroup["members"] as $member) {
                if (!$classroom->students()->where('id', $member)->exists())
                    throw ValidationException::withMessages([ "members" => "validation.exists" ]);
            
                if ($group->members()->where('user_id', $member)->exists())
                    throw ValidationException::withMessages([ "members" => "validation.in_group" ]);
            }
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->attach($user->id, [
            'group_name' => $newGroup['name'],
            'status' => 'inGroup'
        ]);

        $group->members()->attach($newGroup["members"], [
            'group_name' => $newGroup['name'],
            'status' => 'invited'
        ]);

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function cancelInvite(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "userId" => $userId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if (!$group->members()->where('id', $userId)->exists())
                throw ValidationException::withMessages([ "requestUser" => "validation.is_participant" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->find($userId)->pivot->delete();

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function request(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "groupName" => $groupName
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if (!$group->members()->wherePivot('group_name', $groupName)->exists())
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if ($group->members()->wherePivot('group_name', $groupName)->count() == $group->max_members)
                throw ValidationException::withMessages([ "group" => "validation.full" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->attach($user->id, [
            'group_name' => $groupName,
            'status' => 'requesting'
        ]);

        assert($group->members()->find($user->id));

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function cancel(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "groupName" => $groupName
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if (!$group->members()->wherePivot('group_name', $groupName)->exists())
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->attach($user->id, [
            'group_name' => $groupName,
            'status' => 'requesting'
        ]);

        $group->members()->find($user->id)->pivot->delete();

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function accept(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->find($user->id)->pivot->update([
            'status' => 'inGroup'
        ]);

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function decline(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->find($user->id)->pivot->delete();

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function acceptRequest(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "userId" => $userId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if (!$group->members()->where('id', $userId)->exists())
                throw ValidationException::withMessages([ "requestUser" => "validation.is_participant" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->find($userId)->pivot->update([
            'status' => 'inGroup'
        ]);

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function declineRequest(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "userId" => $userId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            if (!$group->members()->where('id', $userId)->exists())
                throw ValidationException::withMessages([ "requestUser" => "validation.is_participant" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->find($userId)->pivot->delete();

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }

    public function leave(Request $request) {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);

            $group = $classroom
                ->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'group']
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->where('id', $user->id)->first()->pivot->delete();

        return response()->json(GroupDTO::fromGroupModel($group, $user));
    }
}