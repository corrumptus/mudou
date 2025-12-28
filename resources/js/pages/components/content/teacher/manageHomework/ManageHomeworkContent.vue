<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="title-attr">
                <label for="title" class="title">Nome</label>
                <input id="title" :value="homework.title" @input="change">
            </div>
            <div class="description">
                <label for="description" class="title">Descrição</label>
                <textarea
                    id="description"
                    rows="3"
                    :value="homework.description"
                    @input="change"
                ></textarea>
            </div>
            <div class="dateTimes">
                <div class="begin">
                    <div class="date">
                        <label for="beginDate" class="title">Data de abertura</label>
                        <input
                            type="date"
                            id="beginDate"
                            :value="homework.beginDate"
                            @change="change"
                        >
                    </div>
                    <div class="time">
                        <label for="beginTime" class="title">Hora de abertura</label>
                        <input
                            type="time"
                            id="beginTime"
                            :value="homework.beginTime"
                            @change="change"
                        >
                    </div>
                </div>
                <div class="close">
                    <div class="date">
                        <label for="closeDate" class="title">Data de fechamento</label>
                        <input
                            type="date"
                            id="closeDate"
                            :value="homework.closeDate"
                            @change="change"
                        >
                    </div>
                    <div class="time">
                        <label for="closeTime" class="title">Hora de fechamento</label>
                        <input
                            type="time"
                            id="closeTime"
                            :value="homework.closeTime"
                            @change="change"
                        >
                    </div>
                </div>
            </div>
            <div class="can-accept-after-close">
                <input type="checkbox" id="canSendAfterClose" :checked="homework.canAcceptAfterClose" @change="change">
                <label for="canSendAfterClose">Permitir envio após fechamento</label>
            </div>
            <div class="group">
                <div class="has-group">
                    <input type="checkbox" id="hasGroup" :checked="homework.hasGroup" @change="change">
                    <label for="hasGroup">Grupo</label>
                </div>
                <div v-if="homework.hasGroup" class="group-select">
                    <select id="group" :value="homework.group" @change="change">
                        <option value=""></option>
                        <option
                            v-for="group in groups"
                            :value="group.id"
                        >
                            {{ group.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="text">
                <div class="is-text">
                    <input type="checkbox" id="isText" :checked="homework.isText" @change="change">
                    <label for="isText">Texto</label>
                </div>
                <div v-if="homework.isText" class="amount-text">
                    <input
                        type="number"
                        placeholder="Máx. caracteres"
                        id="maxAmountCaracteres"
                        :value="homework.maxAmountCaracteres"
                        @input="change"
                    >
                </div>
            </div>
            <div class="file">
                <div class="is-file">
                    <input type="checkbox" id="isFile" :checked="homework.isFile" @change="change">
                    <label for="isFile">Arquivo</label>
                </div>
                <div v-if="homework.isFile" class="amount-file">
                    <input
                        type="number"
                        placeholder="Máx. arquivos"
                        id="maxAmountFiles"
                        :value="homework.maxAmountFiles"
                        @input="change"
                    >
                </div>
            </div>
            <div v-if="homework.isFile" class="file-types">
                <label for="fileTypes" class="title">Extensões de Arquivo</label>
                <input id="fileTypes" :value="homework.fileTypes" @input="change">
            </div>
        </div>
        <div class="buttons">
            <button class="confirm" @click="sendHomework">Salvar</button>
            <button class="cancel">Cancelar</button>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        homework: {
            type: Object
        },
        groups: {
            type: Array,
            required: true
        },
        send: {
            type: Function,
            required: true
        }
    });

    const homework = ref({
        ...props.homework,
        hasGroup: false
    });

    function change(e) {
        const { id, value, checked } = e.target;

        if (["canSendAfterClose", "hasGroup", "isText", "isFile"].includes(id)) {
            homework.value[id] = checked;
            return;
        }

        if (["maxAmountCaracteres", "maxAmountFiles"].includes(id)) {
            homework.value[id] = Number(value);
            return;
        }
    
        homework.value[id] = value;
    }

    function sendHomework() {
        props.send(homework.value);
    }
</script>

<style scoped>
    .manage {
        height: 100%;
        padding-inline: .5em;
        display: flex;
        flex-direction: column;
        overflow: auto;
    }

    .infos {
        width: 100%;
        padding: 1em;
        border-radius: 1em;
        display: grid;
        grid-template-areas: '_ _';
        grid-template-columns: repeat(2, calc(50% - .5em));
        gap: 1em;
        background-color: #e1e1e1;
    }

    .title-attr, .description, .dateTimes, .can-accept-after-close, .group, .text, .file, .file-types {
        height: fit-content;
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

    .title input {
        outline: none;
    }

    textarea {
        outline: none;
        resize: none;
    }

    .dateTimes {
        flex-direction: row;
        justify-content: space-evenly;
        flex-wrap: wrap;
        gap: 1.5em;
    }

    .begin, .close {
        display: flex;
        flex-direction: column;
        gap: .5em;
    }

    .date, .time {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .dateTimes .title {
        position: unset;
        transform: none;
    }

    .can-accept-after-close {
        flex-direction: row;
        gap: .5em;
    }

    .group {
        flex-direction: row;
        gap: 1em;
    }

    .has-group {
        display: flex;
        gap: .5em;
    }

    select {
        border-radius: .3em;
        outline: none;
        background-color: #2c2c2c;
        color: white;
    }

    .text {
        flex-direction: row;
        gap: 1em;
    }

    .is-text {
        display: flex;
        gap: .5em;
    }

    .text input {
        padding-left: .3em;
        border-radius: .3em;
        outline: none;
        background-color: #2c2c2c;
        color: white;
    }

    .file {
        flex-direction: row;
        gap: 1em;
    }

    .is-file {
        display: flex;
        gap: .5em;
    }

    .file input {
        padding-left: .3em;
        border-radius: .3em;
        outline: none;
        background-color: #2c2c2c;
        color: white;
    }

    .file-types input {
        outline: none;
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