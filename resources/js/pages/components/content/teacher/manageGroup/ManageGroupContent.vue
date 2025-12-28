<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="title-attr">
                <label for="title" class="title">Nome</label>
                <input id="title" dusk="title" :value="group.title" @input="change">
            </div>
            <div class="maxMembers">
                <label for="maxMembers" class="title">Quantidade de membros</label>
                <input id="maxMembers" dusk="maxMembers" :value="group.maxMembers" @input="change">
            </div>
            <div class="themes">
                <label class="title">Temas</label>
                <div class="new-theme">
                    <input id="new-theme" dusk="new-theme" placeholder="Digite um novo tema" :value="newTheme" @input="e => newTheme = (e.target as HTMLInputElement).value">
                    <button @click="addTheme" class="bt-new-theme" dusk="bt-new-theme">Adicionar</button>
                </div>
                <ul>
                    <li v-for="theme in Object.keys(group.themes)" :key="theme" class="theme-show">
                        <button class="remove-theme" @click="() => removeTheme(theme)" :dusk="`remove-${theme}`">
                            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                        </button>
                        <div class="theme">{{ theme }}</div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="buttons">
            <button class="confirm" @click="sendHomework" dusk="confirm">Salvar</button>
            <button class="cancel" @click="goBack" dusk="cancel">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        group: {
            type: Object,
            required: true
        },
        send: {
            type: Function,
            required: true
        }
    });

    const group = ref<{
        title: string,
        maxMembers: number,
        themes: Record<string, undefined>
    }>({
        ...props.group as { title: string, maxMembers: number },
        themes: (props.group.themes as string[]).reduce((acc, cur) => {
            acc[cur] = undefined;

            return acc;
        }, {} as Record<string, undefined>)
    });

    const newTheme = ref("");

    function change(e: Event) {
        const { id, value } = e.target as HTMLInputElement;

        if (id === "maxMembers")
            group.value.maxMembers = Number(value);
        else
            group.value.title = value;
    }

    function addTheme() {
        group.value.themes[newTheme.value] = undefined;

        newTheme.value = "";
    }

    function removeTheme(theme: string) {
        delete group.value.themes[theme];
    }

    function sendHomework() {
        props.send({
            ...group.value,
            themes: Object.keys(group.value.themes)
        });
    }

    function goBack() {
        window.history.back();
    }
</script>

<style scoped>
    .manage {
        height: 100%;
        padding-inline: .5em;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: auto;
    }

    .infos {
        width: 100%;
        max-height: calc(100% - 3em);
        padding: 1em;
        border-radius: 1em;
        display: grid;
        grid-template-areas: '_ _';
        grid-template-columns: repeat(2, calc(50% - .5em));
        gap: 1em;
        background-color: #e1e1e1;
    }

    .title-attr, .maxMembers, .themes {
        height: fit-content;
        max-height: 100%;
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
        cursor: text;
    }

    input {
        outline: none;
    }

    .new-theme {
        display: flex;
        flex-direction: column;
        gap: .5em;
    }

    .new-theme input {
        width: 100%;
        padding-left: .8em;
        border-radius: .45em;
        font-size: 1.25em;
        background-color: #e1e1e1;
    }

    .bt-new-theme {
        padding-inline: 1em;
        border-radius: .5em;
        margin: auto;
        font-size: 1.25em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }

    ul {
        max-height: 100%;
    }

    .theme-show {
        border-radius: .5em;
        margin-top: .8em;
        background-color: #e1e1e1;
        position: relative;
    }

    .remove-theme {
        width: 25px;
        position: absolute;
        right: 0;
        transform: translate(40%, -40%);
        filter: invert(20%) sepia(96%) saturate(7245%) hue-rotate(358deg) brightness(94%) contrast(115%);
        cursor: pointer;
    }

    .theme {
        text-align: center;
        font-size: 1.25em;
    }

    .buttons {
        padding-block: .5em;
        display: flex;
        justify-content: center;
        gap: 1em;
    }

    .confirm, .cancel {
        padding-inline: .5em;
        border-radius: .5em;
        font-size: 1.5em;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .confirm {
        background-color: green;
    }

    .cancel {
        background-color: red;
    }
</style>