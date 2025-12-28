type CourseSubject = {
    id?: number,
    name: string,
    code: string,
    course: {
        id: number,
        name: string
    }
};

export async function create(subject: CourseSubject) {
    let response;

    try {
        response = await fetch(`/api/course/${subject.course.id}/subject`, {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(subject)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}

export async function edit(subject: CourseSubject) {
    let response;

    try {
        response = await fetch(`/api/course/${subject.course.id}/subject/${subject.id}`, {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(subject)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}