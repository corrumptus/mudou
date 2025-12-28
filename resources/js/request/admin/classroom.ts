type Classroom = {
    id?: number,
    subject: {
        id: number,
        name: string,
        course: {
            id: number,
            name: string
        }
    },
    beginDate: string,
    closeDate: string,
    periods: {
        dayOfTheWeek: string,
        beginTime: string,
        closeTime: string
    }[],
    teachers: {
        id: number,
        name: string,
        img: string
    },
    monitors: {
        id: number,
        name: string,
        img: string
    },
    students: {
        id: number,
        name: string,
        img: string
    }
};

export async function create(classroom: Classroom) {
    let response;

    try {
        response = await fetch(`/api/course/${classroom.subject.course.id}/subject/${classroom.subject.id}/classroom`, {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(classroom)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}

export async function edit(classroom: Classroom) {
    let response;

    try {
        response = await fetch(`/api/course/${classroom.subject.course.id}/subject/${classroom.subject.id}/classroom/${classroom.id}`, {
            method: "POST",
            headers: {
                "content-type": "application/json",
            },
            body: JSON.stringify(classroom)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}