<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="name">
                <label for="name" class="title">Nome</label>
                <input id="name" :value="courseSubject.name" @input="change">
            </div>
            <div class="code">
                <label for="code" class="title">Código</label>
                <input id="code" :value="courseSubject.code" @input="change">
            </div>
            <div class="course" v-if="!isEdit">
                <label for="course" class="title">Curso</label>
                <select
                    id="course"
                    :value="courseSubject.course.id"
                    @change="change"
                >
                    <option value=""></option>
                    <option
                        v-for="course in courses"
                        :value="course.id"
                        :key="course.id"
                    >
                        {{ course.name }}
                    </option>
                </select>
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
        courseSubject: {
            type: Object
        },
        courses: {
            type: Object,
            required: true
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

    const courseSubject = ref(props.courseSubject);

    function change(e) {
        const { id, value } = e.target;

        if (id === "course") {
            if (value === "")
                courseSubject.value[id] = { id: "", name: "" };
            else
                courseSubject.value[id] = { ...props.courses[value] };
        } else
            courseSubject.value[id] = value;
    }

    function sendCourse() {
        props.send(courseSubject.value);
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

    .name, .code, .course {
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