<template>
    <main>
        <form @submit.prevent="submit">
            <h2>Entrar</h2>
            <div class="email">
                <input
                    type="text"
                    placeholder="Email"
                    ref="email"
                    dusk="email"
                />
            </div>
            <div class="password">
                <input
                    :type="show_password ? 'text' : 'password'"
                    placeholder="Senha"
                    ref="password"
                    dusk="password"
                />
                <img
                    class="eye open"
                    :style="{ opacity: `${show_password ? '100' : '0'}%` }"
                    @click="show_password = !show_password"
                    src="https://cdn-icons-png.flaticon.com/512/4893/4893771.png"
                    alt="open eye"
                    dusk="eye.open"
                >
                <img
                    class="eye closed"
                    :style="{ opacity: `${show_password ? '0' : '100'}%` }"
                    @click="show_password = !show_password"
                    src="https://cdn-icons-png.flaticon.com/512/9726/9726597.png"
                    alt="closed eye"
                    dusk="eye.closed"
                >
            </div>
            <div v-if="error !== undefined" class="error" dusk="error">
                {{ error }}
            </div>
            <div>
                <button dusk="submit">Entrar</button>
            </div>
            <div>
                <p>duvidas? entre em contato pelo email:</p>
                <span class="school-email">email-instituicao</span>
            </div>
        </form>
    </main>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { login } from '@request/general/login';

    const email = ref();
    const password = ref();
    const show_password = ref(false);
    const error = ref();

    function submit() {
        login(email.value.value, password.value.value)
            .then(() => {
                router.visit("/course-subject");
            })
            .catch(e => error.value = e.message);
    }
</script>

<style scoped>
    * {
        color: white;
    }

    main {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 1em;
        padding: .5em;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            inset 0 0 0px 0px rgba(255, 255, 255, 0);
    }

    h2 {
        font-size: 1.5em;
    }

    input {
        font-size: 1.25em;
        outline: none;
        padding: .3em;
        padding-right: 40px;
        border-bottom: 1px solid white;
    }

    input::placeholder {
        color: white;
    }

    .password {
        position: relative;
    }

    .eye {
        width: 40px;
        filter: invert(1);
        position: absolute;
        top: 0;
        right: 0;
        transition: opacity .3s linear;
        cursor: pointer;
    }

    .error {
        width: 100%;
        padding: .5em;
        border: 1px solid red;
        border-radius: 1em;
        color: red;
        background-color: #f08080b0;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    button {
        font-size: 1.25em;
        cursor: pointer;
        padding-top: 8px;
        padding-bottom: 7px;
        line-height: 20px;
    }

    div:has(p) {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    p {
        line-height: 6px;
    }

    .school-email {
        color: blue;
        cursor: pointer;
    }
</style>