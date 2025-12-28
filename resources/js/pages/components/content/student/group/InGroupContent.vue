<template>
    <ContentHeader>
        <button class="toGroup message">
            <img src="https://cdn-icons-png.flaticon.com/512/151/151793.png" alt="a speech balloon">
        </button>
    </ContentHeader>
    <div class="group">
        <h1>{{ group.group.name }}</h1>
        <ul>
            <li
                v-for="u in group.group.members"
                :key="u.id"
                :style="{ backgroundColor: colorFromStatus(u.status) }"
            >
                <button
                    class="toUser message"
                >
                    <img src="https://cdn-icons-png.flaticon.com/512/151/151793.png" alt="a speech balloon"/>
                </button>
                <div class="img"><img :src="u.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></div>
                <span>{{ u.name }}</span>
                <div v-if="u.status === 'invited'" class="buttons">
                    <button class="cancel" @click="() => cancel(u.id)" :dusk="`cancel-${u.id}`">Cancelar</button>
                </div>
                <div v-if="u.status === 'requesting'" class="buttons">
                    <button class="accept" @click="() => accept(u.id)" title="Aceitar pedido" :dusk="`accept-${u.id}`">
                        <img src="https://cdn-icons-png.flaticon.com/512/3388/3388530.png" alt="check">
                    </button>
                    <button class="decline" @click="() => decline(u.id)" title="Recusar pedido" :dusk="`decline-${u.id}`">
                        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </button>
                </div>
            </li>
        </ul>
        <div class="button">
            <button class="leave" @click="() => leave()" dusk="leave">Sair do grupo</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        group: {
            type: Object,
            required: true
        },
        cancel: {
            type: Function,
            required: true
        },
        accept: {
            type: Function,
            required: true
        },
        decline: {
            type: Function,
            required: true
        },
        leave: {
            type: Function,
            required: true
        },
        colorFromStatus: {
            type: Function,
            required: true
        }
    });
</script>

<style scoped>
    .message {
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .toGroup {
        width: 40px;
        height: 40px;
    }

    .toGroup img {
        width: 28px;
    }

    .group {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1em;
        overflow: auto;
    }

    h1 {
        font-size: 1.5em;
        text-align: center;
    }

    ul {
        padding-top: 1.1em;
        display: flex;
        gap: 1em;
        flex-wrap: wrap;
        overflow: auto;
    }

    li {
        width: 180px;
        padding: 1em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1em;
        flex-shrink: 0;
        background-color: #32a9ff;
        user-select: none;
        position: relative;
    }

    .toUser {
        width: 34px;
        height: 34px;
        position: absolute;
        right: 0;
        top: 0;
        transform: translate(50%, -50%);
    }

    .toUser img {
        width: 24px;
    }

    .img {
        width: 70px;
        height: 70px;
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

    span {
        margin: auto;
        display: -webkit-box;
        text-align: center;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 2;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 2;
        cursor: text;
    }

    .buttons {
        display: flex;
        gap: 1em;
    }

    .cancel {
        padding: .1em 1em;
        border-radius: 1em;
        background-color: #fc0000;
        color: white;
        cursor: pointer;
    }

    .accept, .decline {
        border-radius: .5em;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .accept {
        padding: .1em;
        background-color: #00ff03;
    }

    .accept img {
        width: 35px;
        transform: translateY(1px);
    }

    .decline {
        padding: .4em;
        background-color: #fc0000;
    }

    .decline img {
        width: 25px;
    }

    .button {
        display: flex;
        justify-content: center;
    }

    .leave {
        padding: .2em 1.5em;
        border-radius: 1em;
        font-size: 1.2em;
        color: white;
        background-color: #fc0000;
        cursor: pointer;
    }
</style>