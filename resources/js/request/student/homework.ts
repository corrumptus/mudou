type HomeworkResponse = {
    text?: string,
    files?: Blob[]
}

export async function answer(homeworkResponse: HomeworkResponse) {
    let response;

    const formData = new FormData();

    if (homeworkResponse.text !== undefined) 
        formData.append('text', homeworkResponse.text);

    if (homeworkResponse.files !== undefined)
        homeworkResponse.files.forEach(f => formData.append('files[]', f));

    // @ts-expect-error
    const url = `/api/course/${homeworkResponse.courseId}/subject/${homeworkResponse.subjectId}/classroom/${homeworkResponse.id}/class/${homeworkResponse.classId}/homework/${homeworkResponse.elementId}/response`;

    try {
        response = await fetch(url, {
            method: "POST",
            body: formData
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}