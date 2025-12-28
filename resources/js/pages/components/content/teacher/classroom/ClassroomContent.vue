<template>
    <ContentHeader>
        <div class="classroom-buttons">
            <button @click="toAvisos" dusk="header-announcements" class="header-button">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/584/584648.png"
                    alt="megaphone"
                    class="announcement"
                >
            </button>
            <button @click="toForums" dusk="header-forums" class="header-button">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1166/1166128.png"
                    alt="2 people discussing"
                    class="forun"
                >
            </button>
            <button @click="toAnotacoes" dusk="header-anotations" class="header-button">
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
            :toNew="toNew"
        />
        <button
            v-if="!isNewClassVisible"
            @click="() => isNewClassVisible = true"
            class="to-new-class"
        >
            <img
                src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png"
                alt="plus"
            >
        </button>
        <div v-else class="new-class">
            <span>Aula {{ classes.length + 1 }} - </span>
            <input class="name" :value="newClass.name" @input="e => newClass.name = e.target.value">
            <input class="date" type="date" :value="newClass.date" @input="e => newClass.date = e.target.value">
            <button class="create" @click="() => { create(newClass); cancel(); }">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/3388/3388530.png"
                    alt="check"
                >
            </button>
            <button class="cancel" @click="cancel">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png"
                    alt="x"
                >
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';
    import ClassElement from '@content/teacher/classroom/ClassElement.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        },
        classes: {
            type: Array,
            required: true
        },
        create: {
            type: Function,
            required: true
        }
    });

    const isNewClassVisible = ref(false);
    const newClass = ref({
        name: "",
        date: ""
    });

    function toElement(type, id) {
        router.visit(`/teacher/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/${type}/${id}`);
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

    function toNew(classId, type) {
        router.visit(`/teacher/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${classId}/${type}/new`);
    }

    function cancel() {
        isNewClassVisible.value = false;

        newClass.value = {
            name: "",
            date: ""
        };
    }
</script>

<style scoped>
    .classroom-buttons {
        display: flex;
        gap: .5em;
    }

    .header-button {
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

    .to-new-class {
        width: 100%;
        padding: .2em;
        border-radius: .6em;
        display: flex;
        justify-content: center;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .to-new-class img {
        width: 27px;
    }

    .new-class {
        width: 100%;
        display: flex;
        align-items: center;
        gap: .5em;
    }

    .new-class span {
        font-size: 1.25em;
        font-weight: bold;
        flex-shrink: 0;
    }

    .new-class input {
        font-size: 1.25em;
        font-weight: bold;
        border-bottom: 1px solid black;
        outline: none;
    }

    .new-class .name {
        width: 100%;
    }

    .new-class button {
        width: 37px;
        height: 37px;
        border-radius: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
    }

    .create img {
        width: 33px;
        transform: translate(0px, 1px);
        filter: invert(68%) sepia(64%) saturate(5709%) hue-rotate(87deg) brightness(124%) contrast(118%);
    }

    .cancel img {
        width: 23px;
        filter: invert(20%) sepia(96%) saturate(7245%) hue-rotate(358deg) brightness(94%) contrast(115%);
    }
</style>