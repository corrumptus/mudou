type Discussion = {
    title: string,
    description: string
}

export async function create(discussion: Discussion) {
    let response;

    // @ts-ignore
    const url = `/api/course/${discussion.courseId}/subject/${discussion.subjectId}/classroom/${discussion.id}/forun`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: JSON.stringify(discussion)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}