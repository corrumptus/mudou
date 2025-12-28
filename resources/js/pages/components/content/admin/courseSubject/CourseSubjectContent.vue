<template>
    <ContentHeader>
        <button class="edit" @click="toEditCourseSubject">
            <img src="https://www.svgrepo.com/show/42233/pencil-edit-button.svg" alt="pencil">
        </button>
    </ContentHeader>
    <div class="infos">
        <div class="grid">
            <div class="name">
                <span class="title">Nome</span>
                <span>{{ courseSubject.name }}</span>
            </div>

            <div class="code">
                <span class="title">Código</span>
                <span>{{ courseSubject.code }}</span>
            </div>

            <div class="course">
                <span class="title">Curso</span>
                <span>{{ courseSubject.course.name }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        courseSubject: {
            type: Object,
            required: true
        }
    });

    function toEditCourseSubject() {
        router.visit(`/course/${props.courseSubject.course.name}/subject/${props.courseSubject.name}/edit`);
    }
</script>

<style scoped>
    .edit {
        padding: .5em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .edit img {
        width: 24px;
    }

    .infos {
        height: 100%;
        padding-inline: 3em;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1em;
    }

    .grid {
        width: 100%;
        padding: 1em;
        border-radius: 1em;
        grid-template-areas: '_ _';
        grid-template-columns: 1fr 1fr;
        gap: .5em;
        background-color: #e1e1e1;
    }

    .name, .code, .course {
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
    }

    @media (width < 850px) {
        .title {
            transform: translate(3px, -18px);
            font-size: 0.8em;
        }
    }
</style>