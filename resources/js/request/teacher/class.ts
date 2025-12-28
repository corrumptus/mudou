type Class = {
    name: string,
    date: string,
    courseId: number,
    subjectId: number,
    classroomId: number
};

export async function create(newClass: Class) {
    let response;

    try {
        response = await fetch(`/api/course/${newClass.courseId}/subject/${newClass.subjectId}/classroom/${newClass.classroomId}/class`, {
            method: 'POST',
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(newClass)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const value = await response.json();

    if (!response.ok)
        throw new Error(value.error);

    return value;
}