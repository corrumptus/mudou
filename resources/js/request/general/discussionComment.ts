type DiscussionComment = {
    comment: string
}

export async function create(comment: DiscussionComment) {
    let response;

    // @ts-expect-error
    const url = `/api/course/${comment.courseId}/subject/${comment.subjectId}/classroom/${comment.id}/forun/${comment.discussionId}/comments`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(comment)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    const data = await response.json();

    if (!response.ok)
        return data.error;

    return data;
}