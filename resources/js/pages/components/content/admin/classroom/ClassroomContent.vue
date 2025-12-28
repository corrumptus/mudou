<template>
    <ContentHeader>
        <button class="edit" @click="toEditClassroom">
            <img src="https://www.svgrepo.com/show/42233/pencil-edit-button.svg" alt="pencil">
        </button>
    </ContentHeader>
    <div class="infos">
        <div class="grid">
            <div class="name">
                <span class="title">Nome</span>
                <span>{{ classroom.subject.name }}</span>
            </div>

            <div class="teachers">
                <span class="title">Professor(es)</span>
                <ul>
                    <li v-for="teacher in classroom.teachers" :key="teacher.id">
                        <span class="img"><img :src="teacher.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <span>{{ teacher.name }}</span>
                    </li>
                </ul>
            </div>

            <div class="period">
                <span class="title">Horário</span>
                <span v-for="period in classroom.periods" :key="JSON.stringify(period)">
                    {{ period.dayOfTheWeek }}({{ period.beginTime }}-{{ period.closeTime }})
                </span>
            </div>

            <div class="begin-date">
                <span class="title">Data de início</span>
                <span>{{ classroom.beginDate }}</span>
            </div>

            <div class="close-date">
                <span class="title">Data de término</span>
                <span>{{ classroom.closeDate }}</span>
            </div>

            <div class="course">
                <span class="title">Curso</span>
                <span>{{ classroom.subject.course.name }}</span>
            </div>

            <div class="monitors">
                <span class="title">Monitores</span>
                <ul>
                    <li v-for="monitor in classroom.monitors" :key="monitor.id">
                        <span class="img"><img :src="monitor.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <span>{{ monitor.name }}</span>
                    </li>
                </ul>
            </div>

            <div class="students">
                <span class="title">Alunos</span>
                <ul>
                    <li v-for="student in classroom.students" :key="student.id">
                        <span class="img"><img :src="student.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <span>{{ student.name }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        }
    });

    function toEditClassroom() {
        router.visit(`/course/${props.classroom.subject.course.name}/subject/${props.classroom.subject.name}/classroom/${props.classroom.id}/edit`);
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
        overflow: auto;
    }

    .grid {
        width: 100%;
        padding: 1em;
        border-radius: 1em;
        margin: auto;
        display: grid;
        grid-template-areas: '_ _';
        grid-template-columns: repeat(2, calc(50% - .5em));
        gap: 1em;
        background-color: #e1e1e1;
    }

    .name, .teachers, .period, .begin-date, .close-date, .course, .monitors, .students {
        padding: .5em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #b4b4b4;
        position: relative;
    }

    .teachers, .monitors, .students {
        aspect-ratio: 1/1;
    }

    .teachers ul, .monitors ul, .students ul {
        height: 100%;
        overflow: auto;
    }

    .title {
        position: absolute;
        top: 0;
        transform: translate(5px, -14px);
        font-weight: bold;
    }

    li {
        display: flex;
        align-items: center;
        gap: .5em;
    }

    .img {
        width: 40px;
        height: 40px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (width < 850px) {
        .title {
            transform: translate(3px, -18px);
            font-size: 0.8em;
        }
    }
</style>