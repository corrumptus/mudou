<template>
    <ContentHeader />
    <div class="newAnnouncement">
        <div class="infos">
            <div class="title">
                <p>Título</p>
                <input
                    :value="announcement.title"
                    @input="e => announcement.title = e.target.value"
                >
            </div>
            <div class="description">
                <p>Descriçao</p>
                <textarea
                    :value="announcement.description"
                    @input="e => announcement.description = e.target.value"
                >
                </textarea>
            </div>
        </div>
        <div class="buttons">
            <button class="create" @click="() => send(announcement)">Criar</button>
            <button class="cancel" @click="() => window.history.back()">Cancelar</button>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        send: {
            type: Function,
            required: true
        }
    });

    const announcement = ref({
        title: "",
        description: ""
    });
</script>

<style scoped>
    .newAnnouncement {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .infos {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    p {
        font-size: 1.5em;
    }

    input {
        width: 100%;
        padding: .2em .5em;
        border-radius: .5em;
        font-size: 1.25em;
        box-shadow: inset 0px 0px 3px black;
        outline: none;
    }

    .description {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    textarea {
        width: 100%;
        height: 100%;
        padding: .2em .5em;
        border-radius: .5em;
        font-size: 1.25em;
        box-shadow: inset 0px 0px 3px black;
        outline: none;
        resize: none;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 1em;
    }

    .create, .cancel {
        padding-inline: .5em;
        border-radius: .5em;
        font-size: 1.5em;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .create {
        background-color: green;
    }

    .cancel {
        background-color: red;
    }
</style>