<template>
    <nav>
        <div
            v-for="(c, i) in chats"
            class="chat"
            :style="{ backgroundColor: selectedChat === i ? '#32a9ff' : '' }"
            :onClick="() => select(i)"
        >
            <div class="img">
                <img v-if="c.type === 'user'" :src="c.entity.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';" :style="{ '--invert': 0 }">
                <img v-else src="https://cdn-icons-png.flaticon.com/512/11820/11820108.png" alt="group of  icon" :style="{ '--invert': 1 }">
            </div>
            <span>{{ c.entity.name }}</span>
        </div>
    </nav>
</template>

<script setup>
    defineProps({
        chats: {
            type: Array,
            required: true
        },
        selectedChat: {
            type: Number
        },
        select: {
            type: Function,
            required: true
        }
    });
</script>

<style scoped>
    nav {
        max-width: 250px;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: .5em;
        overflow: auto;
        flex-shrink: 0;
    }

    .chat {
        width: 100%;
        border-radius: .5em;
        display: flex;
        align-items: center;
        padding: .3em;
        cursor: pointer;
    }

    .chat:hover {
        background-color: #b4b4b4;
    }

    .img {
        width: 50px;
        height: 50px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: invert(var(--invert));
    }

    .chat:hover .img img {
        filter: invert(0);
    }

    span {
        width: 100%;
        margin: auto;
        display: -webkit-box;
        text-align: center;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 1;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .chat:hover span {
        color: black;
    }
</style>