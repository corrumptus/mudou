<template>
    <nav>
        <ClassMenu
            v-for="(c, i) in classes"
            :classElement="c"
            :selectedClassElement="selectedClassElement"
            :toElement="toElement"
            :isCollapsed="collapsedClasses[i]"
            :switchCollapse="() => switchCollapse(i)"
        />
        <button @click="toAvisos" dusk="announcements">Avisos</button>
        <button @click="toForum" dusk="forums">Fórum</button>
        <button @click="toParticipantes" dusk="participants">Participantes</button>
        <button @click="toAnotacoes" dusk="anotations">Anotações</button>
    </nav>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import ClassMenu from '@navbar/class/ClassMenu.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        },
        classes: {
            type: Array,
            required: true
        },
        selectedClassElement: {
            type: Object,
            required: true
        },
        collapsedClasses: {
            type: Array,
            required: true
        },
        switchCollapse: {
            type: Function,
            required: true
        },
        isTeacher: {
            type: Boolean,
            default: false
        }
    });

    function toElement(classId, type, id) {
        router.visit(`${props.isTeacher ? "/teacher" : ""}/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${classId}/${type}/${id}`);
    }

    function toAvisos() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/announcements`);
    }

    function toForum() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/forun`);
    }

    function toParticipantes() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/participants`);
    }

    function toAnotacoes() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/anotations`);
    }
</script>

<style scoped>
    nav {
        max-width: 200px;
        display: flex;
        flex-direction: column;
        gap: .2em;
        overflow: auto;
        flex-shrink: 0;
    }

    button {
        padding: .3em;
        border-radius: 10px;
        font-size: 1.25em;
        cursor: pointer;
        text-align: start;
    }

    button:hover {
        background-color: #777777;
    }
</style>