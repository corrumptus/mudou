<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="name">
                <label for="name" class="title">Nome</label>
                <input id="name" :value="course.name" @input="change">
            </div>
            <div class="code">
                <label for="code" class="title">Código</label>
                <input id="code" :value="course.code" @input="change">
            </div>
            <div class="amount-semesters">
                <label for="amountSemesters" class="title">Quantidade de semestres</label>
                <input type="number" id="amountSemesters" :value="course.amountSemesters" @input="change">
            </div>
        </div>
        <div class="buttons">
            <button class="confirm" @click="sendCourse">Salvar</button>
            <button class="cancel">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        course: {
            type: Object
        },
        send: {
            type: Function,
            required: true
        },
        isEdit: {
            type: Boolean,
            required: true
        }
    });

    const course = ref(props.course);

    function change(e) {
        const { id, value } = e.target;

        if (id === "amountSemesters")
            course.value[id] = Number(value);
        else
            course.value[id] = value;
    }

    function sendCourse() {
        props.send(course.value);
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

    .name, .code, .amount-semesters {
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