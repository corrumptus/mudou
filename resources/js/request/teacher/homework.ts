type Homework = {
    id?: number,
    title: string,
    description: string,
    beginDate: string,
    beginTime: string,
    closeDate: string,
    closeTime: string,
    canAcceptAfterClose: boolean,
    isText: boolean,
    maxAmountCaracteres: number,
    isFile: boolean,
    maxAmountFiles: number,
    fileTypes: string
};

export async function create(homework: Homework) {
    let response;

    const path = window.location.pathname.slice(9);

    const classPath = path.slice(path.indexOf('/class/')+1);

    // @ts-expect-error
    const url = `/api/course/${homework.courseId}/subject/${homework.subjectId}/classroom/${homework.id}/${classPath}`;

    try {
        response = await fetch(url, {
            method: "POST",
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(homework)
        });
    } catch (e) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}