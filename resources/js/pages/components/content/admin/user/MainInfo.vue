<template>
    <div class="infos" dusk="infos">
        <div class="profile-picture">
            <div class="img">
                <img :src="user.img"  onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
            </div>
        </div>

        <div class="grid">
            <div class="email">
                <span class="title">Email</span>
                <span>{{ user.email }}</span>
            </div>

            <div class="birth-date">
                <span class="title">Data de nascimento</span>
                <span>{{ formatDate(user.birthDate) }}</span>
            </div>

            <div class="cpf">
                <span class="title">CPF</span>
                <span>{{ formatCPF(user.cpf) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="js">
    defineProps({
        user: {
            type: Object,
            required: true
        }
    });

    function formatDate(dateString) {
        const date = new Date(dateString);

        return `${date.getDate().toString().padStart(2, "0")}-${(date.getMonth()+1).toString().padStart(2, "0")}-${date.getFullYear().toString().padStart(2, "0")}`;
    }

    function formatCPF(cpf) {
        return `${cpf.slice(0, 3)}.${cpf.slice(3, 6)}.${cpf.slice(6, 9)}-${cpf.slice(9, 11)}`;
    }
</script>

<style scoped>
    .infos {
        height: 100%;
        padding-inline: 3em;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1em;
    }

    .profile-picture {
        width: 100%;
    }

    .img {
        width: 80px;
        height: 80px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .grid {
        width: 100%;
        padding: 1em;
        border-radius: 1em;
        grid-template-areas: '_ _';
        grid-template-columns: 1fr 1fr;
        gap: .5em;
        background-color: #e1e1e1;
    }

    .email, .birth-date, .cpf {
        padding: .5em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        background-color: #b4b4b4;
        position: relative;
    }

    .title {
        position: absolute;
        font-weight: bold;
        transform: translate(5px, -22px);
    }

    @media (width < 850px) {
        .title {
            transform: translate(3px, -18px);
            font-size: 0.8em;
        }
    }
</style>