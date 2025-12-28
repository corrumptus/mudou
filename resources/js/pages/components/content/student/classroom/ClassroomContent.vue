<template>
    <ContentHeader>
        <div class="classroom-buttons">
            <button @click="toAvisos" dusk="header-announcements">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/584/584648.png"
                    alt="megaphone"
                    class="announcement"
                >
            </button>
            <button @click="toForums" dusk="header-forums">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1166/1166128.png"
                    alt="2 people discussing"
                    class="forun"
                >
            </button>
            <button @click="toAnotacoes" dusk="header-anotations">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1821/1821189.png"
                    alt="block of notes with a pencil"
                    class="anotation"
                >
            </button>
        </div>
    </ContentHeader>
    <div class="classes">
        <ClassElement
            v-for="[ i, c ] of classes.entries()"
            :index="i"
            :classElement="c"
            :toElement="toElement"
        />
    </div>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';
    import ClassElement from '@content/student/classroom/ClassElement.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        },
        classes: {
            type: Array,
            required: true
        },
        type: {
            type: String,
            required: true
        }
    });

    function toElement(classId, type, id) {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${classId}/${type}/${id}`);
    }

    function toAvisos() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/announcements`);
    }

    function toForums() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/forun`);
    }

    function toAnotacoes() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/anotations`);
    }
</script>

<style scoped>
    .classroom-buttons {
        display: flex;
        gap: .5em;
    }

    button {
        width: 40px;
        height: 40px;
        padding: 5px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: gray;
        overflow: hidden;
        cursor: pointer;
    }

    .anotation {
        width: 30px;
        transform: translateX(1px);
    }

    .forun {
        width: 27px;
    }

    .announcement {
        width: 36px;
        max-width: unset;
    }

    .classes {
        height: 100%;
        padding: .5em 1em;
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: .5em;
        overflow: auto;
    }
</style>