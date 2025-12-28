type User = {
    id?: number,
    email: string,
    name: string,
    password?: string,
    birthDate: string,
    cpf: string,
    img?: Blob,
    isAdmin: 0|1,
    isTeacher: 0|1,
    isStudent: 0|1,
    isMonitor: 0|1,
    permissions: string[]
}

export async function create(user: User) {
    let response;

    try {
        response = await fetch("/api/user", {
            method: "POST",
            body: Object.entries(user).reduce((acc, cur) => {
                if (cur[0] === "roles")
                    (cur[1] as string[]).forEach(t => {
                        acc.append(`${cur[0]}[]`, t);
                    });
                else
                    if (cur[1] !== undefined && cur[1] !== "")
                        acc.append(cur[0], (cur[1] as string | Blob));

                return acc;
            }, new FormData())
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);

    return (await response.json()).link;
}

export async function edit(user: User) {
    let response;

    try {
        response = await fetch(`/api/user/${user.id}`, {
            method: "POST",
            body: Object.entries(user).reduce((acc, cur) => {
                if (cur[0] === "roles")
                    (cur[1] as string[]).forEach(t => {
                        acc.append(`${cur[0]}[]`, t);
                    });
                else
                    if (cur[1] !== undefined && cur[1] !== "")
                        acc.append(cur[0], (cur[1] as string | Blob));

                return acc;
            }, new FormData())
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}