type Group = {
    id?: number,
    title: string,
    maxMembers: number,
    themes: string[],
    classId: number
};

export async function create(group: Group) {
    let response;

    const path = window.location.pathname.slice(9);

    const classPath = path.slice(path.indexOf('/class/')+1);

    // @ts-expect-error
    const url = `/api/teacher/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.id}/${classPath}`;

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

export async function edit(group: Group) {
    let response;

    // @ts-expect-error
    const url = `/api/teacher/course/${group.courseId}/subject/${group.subjectId}/classroom/${group.classroomId}/class/${group.classId}/group/${group.id}`;

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