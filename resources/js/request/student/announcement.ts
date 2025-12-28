type Classroom = {
    id: number,
    courseId: number,
    subjectId: number
}

export async function see(classroom: Classroom, id: number) {
    let response;

    try {
        response = await fetch(`/api/course/${classroom.id}/subject/${classroom.subjectId}/classroom/${classroom.id}/announcements/${id}/notSaw`, {
            method: "DELETE"
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok) {
        throw new Error((await response.json()).error);
    }
}