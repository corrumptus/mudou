type Group = {
    id?: number,
    title: string,
    themes: string[]
};

export async function create(group: Group) {
    let response;

    // @ts-expect-error
    const url = `/api/teacher/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.classroomId}/class/${group.classId}/group/${group.id}/new`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(group)
        });
    } catch (e) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}

export async function edit(group: Group, name: string) {
    let response;

    // @ts-expect-error
    const url = `/api/teacher/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.classroomId}/class/${group.classId}/group/${group.id}/${name}`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(group)
        });
    } catch (e) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        throw new Error(data.error);

    return data;
}