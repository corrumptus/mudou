<template>
    <header>
        <div class="name-filter">
            <span
                :style="{
                    transform: typedName == '' ?
                        'translate(6px, 10px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: typedName == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: typedName == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: typedName == '' ?
                        ''
                        :
                        '0s, .5s, .5s'
                }"
                @click="e => e.target.parentElement.querySelector('input').focus()"
            >
                Nome
            </span>
            <input :value="typedName" @input="typeName">
        </div>
        <div class="course-filter">
            <span
                :style="{
                    transform: typedName == '' ?
                        'translate(6px, 10px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: typedName == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: typedName == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: typedName == '' ?
                        ''
                        :
                        '0s, .5s, .5s'
                }"
                @click="e => e.target.parentElement.querySelector('input').focus()"
            >
                Curso
            </span>
            <input :value="typedCourse" @input="typeCourse">
        </div>
        <button @click="toNewSubject" class="add">
            <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
        </button>
    </header>
    <div class="courseSubjects">
        <table>
            <thead>
                <th>Nº</th>
                <th>Código</th>
                <th>Nome</th>
                <th>Nome do Curso</th>
            </thead>
            <tbody>
                <tr v-for="(subject, i) in renderedSubjects" :key="subject.id">
                    <td>{{ i+1 }}</td>
                    <td>{{ subject.code }}</td>
                    <td @click="() => toSubject(subject.course.name, subject.name)">{{ subject.name }}</td>
                    <td>{{ subject.course.name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        courseSubjects: {
            type: Array,
            required: true
        }
    });

    const renderedSubjects = ref([ ...props.courseSubjects ]);

    const typedName = ref("");
    const typedCourse = ref("");

    let namDebouncer = undefined;

    function typeName(e) {
        typedName.value = e.target.value;

        if (namDebouncer !== undefined)
            clearTimeout(namDebouncer);

        namDebouncer = setTimeout(() => {
            namDebouncer = undefined;

            filter();
        }, 500);
    }

    let courseDebouncer = undefined;

    function typeCourse(e) {
        typedCourse.value = e.target.value;

        if (courseDebouncer !== undefined)
            clearTimeout(courseDebouncer);

        courseDebouncer = setTimeout(() => {
            courseDebouncer = undefined;

            filter();
        }, 500);
    }

    function filter() {
        renderedSubjects.value = [ ...props.courseSubjects ];

        if (typedName.value !== "")
            renderedSubjects.value = renderedSubjects.value
                .filter(s => s.name.includes(typedName.value));

        if (typeCourse.value !== "")
            renderedSubjects.value = renderedSubjects.value
                .filter(s => s.subject.name.includes(typedCourse.value));
    }

    function toNewSubject() {
        router.visit("/subject/new");
    }

    function toSubject(courseName, subjectName) {
        router.visit(`/course/${courseName}/subject/${subjectName}`);
    }
</script>

<style scoped>
    header {
        padding: .3em;
        padding-top: .7em;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5em;
        overflow: hidden;
    }

    .name-filter {
        position: relative;
    }

    .name-filter span {
        padding-inline: 6px;
        position: absolute;
        z-index: 1;
        color: #4f4f4f;
        line-height: 1.2em;
        user-select: none;
        transition: transform linear .5s, border linear 0s, background-color linear 0s;
    }

    .course-filter {
        position: relative;
    }

    .course-filter span {
        padding-inline: 6px;
        position: absolute;
        z-index: 1;
        color: #4f4f4f;
        line-height: 1.2em;
        user-select: none;
        transition: transform linear .5s, border linear 0s, background-color linear 0s;
    }

    input {
        width: 100%;
        padding: 0.2em;
        padding-left: 0.4em;
        border: 1px solid #b4b4b4;
        border-radius: 1em;
        font-size: 1.25em;
        outline: none;
    }

    .add {
        padding: .2em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
        flex-shrink: 0;
    }

    .add img {
        width: 35px;
    }

    .courseSubjects {
        height: 100%;
        padding: .5em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: auto;
    }

    table {
        width: 100%;
    }

    th, td {
        text-align: center;
        padding: .2em;
    }

    tr:nth-child(even) td {
        background-color: #b4b4b4;
    }

    tr:hover td {
        background-color: lightblue;
        color: blue;
    }

    tr td:nth-child(3) {
        cursor: pointer;
    }
</style>