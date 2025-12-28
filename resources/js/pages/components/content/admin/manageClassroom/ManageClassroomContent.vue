<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div v-if="!isEdit" class="course">
                <label for="course" class="title">Curso</label>
                <select
                    :value="classroom.subject.course.id"
                    @change="e => classroom.subject.course.id = e.target.value"
                    id="course"
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
            <div v-if="!isEdit && classroom.subject.course.id !== ''" class="course-subject">
                <label for="courseSubject" class="title">Disciplina</label>
                <select
                    :value="classroom.subject.id"
                    @change="e => classroom.subject.id = e.target.value"
                    id="courseSubject"
                >
                    <option value=""></option>
                    <option
                        v-for="courseSubject in courseSubjects[classroom.subject.course.id]"
                        :value="courseSubject.id"
                        :key="courseSubject.id"
                    >
                        {{ courseSubject.name }}
                    </option>
                </select>
            </div>
            <div class="begin-date">
                <label for="beginDate" class="title">Data de início</label>
                <input
                    type="date"
                    id="beginDate"
                    :value="classroom.beginDate"
                    @input="change"
                >
            </div>
            <div class="close-date">
                <label for="closeDate" class="title">Data de término</label>
                <input
                    type="date"
                    id="closeDate"
                    :value="classroom.closeDate"
                    @input="change"
                >
            </div>
            <div class="period">
                <label class="title">Horário</label>
                <div class="period-grid">
                    <div class="day-of-the-week">
                        <label for="dayOfTheWeek" class="title">Dia da semana</label>
                        <select
                            :value="newPeriod.dayOfTheWeek"
                            @change="e => newPeriod.dayOfTheWeek = e.target.value"
                            id="dayOfTheWeek"
                        >
                            <option value=""></option>
                            <option value="segunda-feira">Segunda-feira</option>
                            <option value="terça-feira">Terça-feira</option>
                            <option value="quarta-feira">Quarta-feira</option>
                            <option value="quinta-feira">Quinta-feira</option>
                            <option value="sexta-feira">Sexta-feira</option>
                            <option value="sábado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="times">
                        <div class="begin-time">
                            <label for="beginTime" class="title">Começo</label>
                            <input
                                type="time"
                                :value="newPeriod.beginTime"
                                @change="e => newPeriod.beginTime = e.target.value"
                                id="beginTime"
                            >
                        </div>
                        <div class="close-time">
                            <label for="closeTime" class="title">Término</label>
                            <input
                                type="time"
                                :value="newPeriod.closeTime"
                                @change="e => newPeriod.closeTime = e.target.value"
                                id="closeTime"
                            >
                        </div>
                    </div>
                    <div class="button">
                        <button @click="addPeriod" class="add-period">Adicionar</button>
                    </div>
                </div>
                <div v-for="(period, i) in classroom.periods" :key="JSON.stringify(period)" class="period-show">
                    <button class="remove-period" @click="() => classroom.periods.splice(i, 1)">
                        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </button>
                    <div class="period-dayOfTheWeek">{{ period.dayOfTheWeek }}</div>
                    <div class="period-times">
                        <span>{{ period.beginTime }}</span>
                        <span>{{ period.closeTime }}</span>
                    </div>
                </div>
            </div>
            <div class="teachers">
                <span class="title">Professores</span>
                <input placeholder="filtro de professores" :value="typedTeacher" @input="typeTeacher">
                <ul>
                    <li v-for="teacher in renderedTeachers" :key="teacher.id" @click="() => select('teacher', teacher.id)">
                        <span class="img"><img :src="teacher.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <label>{{ teacher.name }}</label>
                        <input
                            type="checkbox"
                            :checked="teacher.id in classroom.teachers"
                        >
                    </li>
                </ul>
            </div>
            <div class="monitors">
                <span class="title">Monitores</span>
                <input placeholder="filtro de monitores" :value="typedMonitor" @input="typeMonitor">
                <ul>
                    <li v-for="monitor in renderedMonitors" :key="monitor.id" @click="() => select('monitor', monitor.id)">
                        <span class="img"><img :src="monitor.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <label>{{ monitor.name }}</label>
                        <input
                            type="checkbox"
                            :checked="monitor.id in classroom.monitors"
                        >
                    </li>
                </ul>
            </div>
            <div class="students">
                <span class="title">Alunos</span>
                <input placeholder="filtro de alunos" :value="typedStudent" @input="typeStudent">
                <ul>
                    <li v-for="student in renderedStudents" :key="student.id" @click="() => select('student', student.id)">
                        <span class="img"><img :src="student.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';"></span>
                        <label>{{ student.name }}</label>
                        <input
                            type="checkbox"
                            :checked="student.id in classroom.students"
                        >
                    </li>
                </ul>
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
        classroom: {
            type: Object
        },
        courses: {
            type: Array,
            required: true
        },
        courseSubjects: {
            type: Array,
            required: true
        },
        teachers: {
            type: Array,
            required: true
        },
        monitors: {
            type: Array,
            required: true
        },
        students: {
            type: Array,
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

    const classroom = ref({
        ...props.classroom,
        subject: {
            id: props.classroom.subject.id,
            course: {
                id: props.classroom.subject.course.id
            }
        },
        teachers: props.classroom.teachers.reduce((acc, cur) => {
            acc[cur.id] = undefined;

            return acc;
        }, {}),
        monitors: props.classroom.monitors.reduce((acc, cur) => {
            acc[cur.id] = undefined;

            return acc;
        }, {}),
        students: props.classroom.students.reduce((acc, cur) => {
            acc[cur.id] = undefined;

            return acc;
        }, {})
    });

    const renderedTeachers = ref([ ...props.teachers ]);
    const renderedMonitors = ref([ ...props.monitors ]);
    const renderedStudents = ref([ ...props.students ]);

    const typedTeacher = ref("");
    const typedMonitor = ref("");
    const typedStudent = ref("");

    let teacherDebouncer = undefined;

    function typeTeacher(e) {
        typedTeacher.value = e.target.value;

        if (teacherDebouncer !== undefined)
            clearTimeout(teacherDebouncer);

        teacherDebouncer = setTimeout(() => {
            teacherDebouncer = undefined;

            renderedTeachers.value = [ ...props.teachers ];

            if (typedTeacher.value !== "")
                renderedTeachers.value = renderedTeachers.value
                    .filter(t => t.name.includes(typedTeacher.value));
        }, 500);
    }

    let monitorDebouncer = undefined;

    function typeMonitor(e) {
        typedMonitor.value = e.target.value;

        if (monitorDebouncer !== undefined)
            clearTimeout(monitorDebouncer);

        monitorDebouncer = setTimeout(() => {
            monitorDebouncer = undefined;

            renderedMonitors.value = [ ...props.monitors ];

            if (typedMonitor.value !== "")
                renderedMonitors.value = renderedMonitors.value
                    .filter(t => t.name.includes(typedMonitor.value));
        }, 500);
    }

    let studentDebouncer = undefined;

    function typeStudent(e) {
        typedStudent.value = e.target.value;

        if (studentDebouncer !== undefined)
            clearTimeout(studentDebouncer);

        studentDebouncer = setTimeout(() => {
            studentDebouncer = undefined;

            renderedStudents.value = [ ...props.students ];

            if (typedStudent.value !== "")
                renderedStudents.value = renderedStudents.value
                    .filter(t => t.name.includes(typedStudent.value));
        }, 500);
    }

    const newPeriod = ref({
        dayOfTheWeek: "",
        beginTime: "",
        closeTime: ""
    });

    function addPeriod() {
        if (
            newPeriod.value.dayOfTheWeek === ""
            ||
            newPeriod.value.beginTime === ""
            ||
            newPeriod.value.closeTime === ""
        ) {
            alert("todos os campos de um novo horário devem ser preenchidos");
            return;
        }

        classroom.value.periods.splice(-1, 0, { ...newPeriod.value });

        newPeriod.value = {
            dayOfTheWeek: "",
            beginTime: "",
            closeTime: ""
        };
    }

    function change(e) {
        const { id, value } = e.target;
        
        classroom.value[id] = value;
    }

    function select(type, id) {
        const reference = {
            "teacher": classroom.value.teachers,
            "monitor": classroom.value.monitors,
            "student": classroom.value.students,
        }[type];

        if (id in reference)
            delete reference[id];
        else
            reference[id] = undefined;
    }

    function sendCourse() {
        props.send({
            ...classroom.value,
            teachers: Object.keys(classroom.value.teachers),
            monitors: Object.keys(classroom.value.monitors),
            students: Object.keys(classroom.value.students)
        });
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

    .course, .course-subject, .begin-date, .close-date, .period, .teachers, .monitors, .students {
        height: fit-content;
        padding: .5em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        background-color: #b4b4b4;
        position: relative;
    }

    .period, .teachers, .monitors, .students {
        aspect-ratio: 1/1;
    }

    .teachers, .monitors, .students {
        gap: .5em;
    }

    .teachers input, .monitors input, .students input {
        border-bottom: 1px solid black;
        outline: none;
    }

    .teachers ul, .monitors ul, .students ul {
        height: calc(100% - 25px - .5em);
        overflow: auto;
    }

    .title {
        position: absolute;
        font-weight: bold;
        transform: translate(5px, -22px);
        cursor: text;
    }

    .period {
        padding: 1em;
        gap: 1em;
    }

    .period > .title {
        transform: translate(0px, -29px);
    }

    .period-grid {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .day-of-the-week, .begin-time, .close-time {
        width: 100%;
        background-color: #e1e1e1;
        padding: .5em;
        border-radius: .5em;
    }

    .day-of-the-week .title, .begin-time .title, .close-time .title {
        font-weight: normal;
    }

    .day-of-the-week select, .begin-time input, .close-time input {
        width: 100%;
        outline: none;
    }

    .times {
        display: flex;
        gap: .5em;
    }

    .button {
        display: flex;
        justify-content: center;
    }

    .button button {
        padding-inline: 1em;
        border-radius: .5em;
        font-size: 1.25em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }

    .period-show {
        border-radius: .5em;
        background-color: #e1e1e1;
        position: relative;
    }

    .remove-period {
        width: 25px;
        position: absolute;
        right: 0;
        transform: translate(40%, -40%);
        filter: invert(20%) sepia(96%) saturate(7245%) hue-rotate(358deg) brightness(94%) contrast(115%);
        cursor: pointer;
    }

    .period-dayOfTheWeek {
        text-align: center;
    }

    .period-times {
        display: flex;
        justify-content: space-evenly;
    }

    ul {
        display: flex;
        flex-direction: column;
        gap: .5em;
    }

    li {
        display: flex;
        gap: .5em;
    }

    .img {
        width: 25px;
        height: 25px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    li label {
        width: 100%;
        cursor: pointer;
    }

    li input {
        cursor: pointer;
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
        background-color: #00ff03;
    }

    .cancel {
        background-color: #fc0000;
    }
</style>