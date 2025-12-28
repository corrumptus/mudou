type Course = {
    id?: number,
    name: string,
    code: string,
    numberSemesters: number
};

export async function create(course: Course) {
    let response;

    try {
        response = await fetch("/api/course", {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(course)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}

export async function edit(course: Course) {
    let response;

    try {
        response = await fetch(`/api/course/${course.id}`, {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(course)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}