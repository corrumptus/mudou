<template>
    <ContentHeader />
    <div class="group">
        <h1>Pedido aguardando</h1>
        <div class="infos">
            <p>{{ group.group.name }}</p>
            <div class="users">
                <div
                    class="scroll toLeft"
                    @click="() => listRef.scrollBy({ left: -146, behavior: 'smooth' })"
                >
                    <img
                        src="https://cdn-icons-png.freepik.com/512/32/32213.png"
                        alt="minimalist arrow(without shaft) pointing to the left"
                    />
                </div>
                <ul
                    ref="listRef"
                    @mousedown="e => {isClicking = true; firstPosition = e.clientX}"
                    @mouseup="() => {isClicking = false; firstPosition = undefined;}"
                    @mouseleave="() => isClicking = false"
                    @mousemove="e => {
                        if (!isClicking)
                            return;
                        
                        listRef.scrollBy((firstPosition! - e.clientX)*acceleration, 0);
                        firstPosition = e.clientX;
                    }"
                >
                    <li
                        v-for="u in group.group.members"
                        :key="u.id"
                        :style="{ backgroundColor: colorFromStatus(u.status) }"
                    >
                        <div class="img"><img :src="u.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></div>
                        <span>{{ u.name }}</span>
                    </li>
                </ul>
                <div
                    class="scroll toRight"
                    @click="() => listRef.scrollBy({ left: 146, behavior: 'smooth' })"
                >
                    <img
                        src="https://cdn-icons-png.freepik.com/512/32/32213.png"
                        alt="minimalist arrow(without shaft) pointing to the right"
                    >
                </div>
            </div>
        </div>
        <div class="buttons">
            <button class="cancel" @click="() => cancel()">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const listRef = ref();

    let isClicking: boolean;
    let firstPosition: number | undefined;
    const acceleration = 1.4;

    defineProps({
        group: {
            type: Object,
            required: true
        },
        cancel: {
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
    .group {
        height: 100%;
        padding: .5em;
        display: flex;
        flex-direction: column;
    }

    h1 {
        font-size: 2em;
        font-weight: bold;
        text-align: center;
    }

    .infos {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1em;
    }

    p {
        font-size: 1.5em;
        text-align: center;
    }

    .users {
        display: flex;
        gap: .5em;
    }

    .scroll {
        display: flex;
        place-items: center;
        cursor: pointer;
        user-select: none;
    }

    .toLeft {
        transform: rotate(180deg);
    }

    .scroll img {
        width: 30px;
        max-width: unset;
        flex-shrink: 0;
    }

    ul {
        width: 100%;
        display: flex;
        gap: 1em;
        overflow: auto;
    }

    li {
        width: 130px;
        padding: 1em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1em;
        flex-shrink: 0;
        background-color: #32a9ff;
        user-select: none;
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
        width: 100%;
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
        justify-content: center;
        gap: 1em;
    }

    button {
        padding: .2em 1.5em;
        border-radius: 1em;
        font-size: 1.2em;
        color: white;
        cursor: pointer;
    }

    .cancel {
        background-color: #fc0000;
    }
</style>