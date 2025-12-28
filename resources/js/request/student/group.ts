type NewGroup = {
    name: string;
    members: number[];
}

export async function create(newGroup: NewGroup) {
    let response;

    // @ts-expect-error
    const url = `/api/course/${newGroup.courseId}/subject/${newGroup.subjectId}/classroom/${newGroup.id}/class/${newGroup.classId}/group/${newGroup.elementId}`;

    try {
        response = await fetch(url, {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
            },
            body: JSON.stringify(newGroup)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

type Group = {
    id: number;
    courseId: number;
    subjectId: number;
    classroomId: number;
    classId: number;
    elementId: number;
}

export async function cancelInvite(group: Group & { userId: number }) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/cancel/${group.userId}`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function request(group: Group & { name: string }) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/request/${group.name}`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function cancel(group: Group & { name: string }) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/request/${group.name}/cancel`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function accept(group: Group) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/accept`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function decline(group: Group) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/decline`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function acceptRequest(group: Group & { userId: number }) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/accept/${group.userId}`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function declineRequest(group: Group & { userId: number }) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/decline/${group.userId}`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}

export async function leave(group: Group) {
    let response;

    try {
        response = await fetch(`/api/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/class/${group.classId}/group/${group.elementId}/leave`, {
            method: 'POST'
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}