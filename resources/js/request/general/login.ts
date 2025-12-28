export async function login(email: string, password: string) {
    let response;

    try {
        response = await fetch("/api/login", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: JSON.stringify({
                email,
                password
            })
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok) {
        throw new Error((await response.json()).error);
    }
}

export async function confirm(token: string, password: string, confirm_password: string) {
    if (password !== confirm_password)
        throw new Error("As senhas devem ser iguais");

    let response;

    try {
        response = await fetch(`/api/first-access/${token}`, {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: JSON.stringify({ password })
        });
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok) {
        throw new Error((await response.json()).error);
    }
}

export async function logout() {
    let response;

    try {
        response = await fetch(`/api/logout`);
    } catch (error) {
        throw new Error("Não foi possível se conectar ao servidor");
    }

    if (!response.ok)
        throw new Error((await response.json()).error);
}