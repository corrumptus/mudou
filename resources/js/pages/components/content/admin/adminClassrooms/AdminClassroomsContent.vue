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
        <div class="teacher-filter">
            <span
                :style="{
                    transform: typedTeacher == '' ?
                        'translate(6px, 10px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: typedTeacher == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: typedTeacher == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: typedTeacher == '' ?
                        ''
                        :
                        '0s, .5s, .5s'
                }"
                @click="e => e.target.parentElement.querySelector('input').focus()"
            >
                Professor
            </span>
            <input :value="typedTeacher" @input="typeTeacher">
        </div>
        <div class="course-filter">
            <span
                :style="{
                    transform: typedCourse == '' ?
                        'translate(6px, 10px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: typedCourse == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: typedCourse == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: typedCourse == '' ?
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
        <button @click="toNewClassroom" class="add">
            <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
        </button>
    </header>
    <div class="courses">
        <table>
            <thead>
                <th>Nº</th>
                <th>Nome</th>
                <th>Professor(es)</th>
                <th>Horário</th>
                <th>Data de início</th>
                <th>Data de término</th>
                <th>Curso</th>
            </thead>
            <tbody>
                <tr v-for="(classroom, i) in renderedClassrooms" :key="classroom.id">
                    <td>{{ i+1 }}</td>
                    <td @click="() => toClassroom(classroom.subject.course.name, classroom.subject.name, classroom.id)">{{ classroom.subject.name }}</td>
                    <td><div class="multi-row"><span v-for="teacher in classroom.teachers" :key="teacher.id">{{ teacher.name }}</span></div></td>
                    <td><div class="multi-row"><span v-for="period in classroom.periods" :key="JSON.stringify(period)">{{ period.dayOfTheWeek }}({{ period.beginTime }}-{{ period.closeTime }})</span></div></td>
                    <td>{{ classroom.beginDate }}</td>
                    <td>{{ classroom.closeDate }}</td>
                    <td>{{ classroom.subject.course.name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        classrooms: {
            type: Array,
            required: true
        }
    });

    const typedName = ref("");
    const typedTeacher = ref("");
    const typedCourse = ref("");

    const renderedClassrooms = ref([ ...props.classrooms ]);

    let nameDebouncer = undefined;

    function typeName(e) {
        typedName.value = e.target.value;

        if (nameDebouncer !== undefined)
            clearTimeout(nameDebouncer);

        nameDebouncer = setTimeout(() => {
            nameDebouncer = undefined;

            filter();
        }, 500);
    }

    let teacherDebouncer = undefined;

    function typeTeacher(e) {
        typedTeacher.value = e.target.value;

        if (teacherDebouncer !== undefined)
            clearTimeout(teacherDebouncer);

        teacherDebouncer = setTimeout(() => {
            teacherDebouncer = undefined;

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
        renderedClassrooms.value = [ ...props.classrooms ];

        if (typedName.value !== "")
            renderedClassrooms.value = renderedClassrooms.value
                .filter(c => c.subject.name.includes(typedName.value));

        if (typedTeacher.value !== "")
            renderedClassrooms.value = renderedClassrooms.value
                .filter(c => c.teachers.filter(t => t.name.includes(typedTeacher.value)).length > 0);

        if (typedCourse.value !== "")
            renderedClassrooms.value = renderedClassrooms.value
                .filter(c => c.subject.course.name.includes(typedCourse.value));
    }

    function toNewClassroom() {
        router.visit("/classroom/new");
    }

    function toClassroom(courseName, subjectName, classroomId) {
        router.visit(`/course/${courseName}/subject/${subjectName}/classroom/${classroomId}/show`);
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

    .name-filter, .teacher-filter, .course-filter {
        position: relative;
    }

    .name-filter span, .teacher-filter span, .course-filter span {
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

    .courses {
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

    tr td:nth-child(2) {
        cursor: pointer;
    }

    .multi-row {
        display: flex;
        flex-direction: column;
        gap: .2em;
    }
</style>