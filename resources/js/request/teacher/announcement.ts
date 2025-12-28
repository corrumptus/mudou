type Announcement = {
    title: string,
    description: string
}

export async function create(announcement: Announcement) {
    let response;

    // @ts-ignore
    const url = `/api/course/${announcement.courseId}/subject/${announcement.subjectId}/classroom/${announcement.id}/announcement`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: JSON.stringify(announcement)
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}