<template>
    <ContentHeader />
    <div v-if="messages !== undefined" class="chat">
        <div class="messages">
            <div
                v-for="m in messages"
                :key="m.id"
                class="message"
                :style="{ flexDirection: m.sender.name === userName ? 'row-reverse' : '' }"
            >
                <div class="img">
                    <img :src="m.sender.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                </div>
                <div class="text">
                    <span class="messageText">{{ m.text }}</span>
                    <span class="messageTime">{{ formatDate(m.createdAt) }}</span>
                </div>
            </div>
        </div>
        <div class="sendMessage">
            <button class="file">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/File_alt_font_awesome.svg/512px-File_alt_font_awesome.svg.png" alt="file icon(paper with a folded tip)">
            </button>
            <input placeholder="Digite uma mensagem">
            <button class="send">
                <img src="https://static.thenounproject.com/png/105496-200.png" alt="airplane paper">
            </button>
        </div>
    </div>
</template>

<script setup lang="js">
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        messages: {
            type: Array
        },
        userName: {
            type: String,
            required: true
        }
    });

    function formatDate(date) {
        const d = new Date(date);

        return `${d.getDate()}-${d.getMonth()+1}-${d.getFullYear()}, ${d.getMinutes()}:${d.getHours()}`;
    }
</script>

<style scoped>
    .chat {
        height: 100%;
        padding: .5em;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: .5em;
        overflow: hidden;
    }

    .messages {
        display: flex;
        flex-direction: column-reverse;
        gap: 1em;
        overflow: auto;
    }

    .message {
        display: flex;
        align-items: start;
        gap: .5em;
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

    .text {
        max-width: 70%;
        padding: .5em;
        border-radius: .5em;
        display: flex;
        flex-direction: column;
        gap: .1em;
        background-color: #b4b4b4;
    }

    .messageTime {
        font-size: .8em;
        text-align: end;
    }

    .sendMessage {
        padding: .3em .4em;
        border-radius: 1.6em;
        display: flex;
        align-items: center;
        gap: .5em;
        background-color: #2c2a2a;
    }

    button {
        width: 40px;
        height: 40px;
        border-radius: 100%;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        cursor: pointer;
    }

    button img {
        filter: invert(1);
    }

    button:hover {
        background-color: #b4b4b4;
    }

    button:hover img {
        filter: invert(0);
    }

    .file img {
        width: 36px;
        transform: translate(1px, -1px);
    }

    .send img {
        width: 34px;
        transform: translate(3px);
    }

    input {
        width: 100%;
        font-size: 1.25em;
    }

    input::placeholder {
        opacity: 100%;
        color: #b4b4b4;
    }
</style>