<template>
    <div class="filters">
        <select
            :value="courseFilter"
            @select="e => selectCourse(e.target.value)"

        >
            <option value=""></option>
            <option
                v-for="course in courses"
                :value="course"
                :key="course.id"
            >
                {{ course }}
            </option>
        </select>
        <input
            :value="nameFilter"
            @input="e => typeName(e.target.value)"

        >
        <input
            :value="teacherFilter"
            @input="e => typeTeacher(e.target.value)"

        >
        <input
            type="time"
            :value="timeFilter"
            @input="e => typeTime(e.target.value)"

        >
    </div>
    <div class="classrooms">
        <div v-for="(classrooms, course) in filteredClassrooms" :key="course">
            <h1>{{ course }}</h1>
            <div
                v-for="classroom in classrooms"
                class="card"
                :key="classroom.id"
            >
                <span class="code">{{ classroom.code }}</span>
                <div class="name">
                    <h3>Nome</h3>
                    <span>{{ classroom.name }}</span>
                </div>
                <div class="teacher">
                    <h3>Professor(es)</h3>
                    <span
                        v-for="teacher in classroom.teachers"
                        :key="teacher.id"
                    >
                        {{ teacher }}
                    </span>
                </div>
                <div class="time">
                    <h3>Horário</h3>
                    <span>{{ classroom.time }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';

    const props = defineProps({
        classrooms: {
            type: Object,
            required: true
        }
    });

    const filteredClassrooms = ref({ ...props.classrooms });

    const courseFilter = ref("");
    const nameFilter = ref("");
    const teacherFilter = ref("");
    const timeFilter = ref("");

    function selectCourse(course) {
        courseFilter.value = course;

        filter();
    }

    let nameDebouncer = undefined;

    function typeName(name) {
        nameFilter.value = name;

        if (nameDebouncer !== undefined)
            clearTimeout(nameDebouncer);

        nameDebouncer = setTimeout(() => {
            nameDebouncer = undefined;

            filter();
        }, 500);
    }

    let teacherDebouncer = undefined;

    function typeTeacher(teacher) {
        teacherFilter.value = teacher;

        if (teacherDebouncer !== undefined)
            clearTimeout(teacherDebouncer);

        teacherDebouncer = setTimeout(() => {
            teacherDebouncer = undefined;

            filter();
        }, 500);
    }

    function typeTime(time) {
        timeFilter.value = time;

        filter();
    }

    function filter() {
        filteredClassrooms.value = { ...props.classrooms };

        if (courseFilter.value !== "")
            filteredClassrooms.value = { [courseFilter.value]: props.classrooms[courseFilter.value] };

        if (nameFilter.value !== "")
            filteredClassrooms.value = Object.entries(filteredClassrooms.value)
                .map(([course, classrooms]) => [
                    course,
                    classrooms.filter(c => c.name.includes(nameFilter.value))
                ])
                .reduce((acc, cur) => {
                    acc[cur[0]] = cur[1];

                    return acc;
                }, {});

        if (teacherFilter.value !== "")
            filteredClassrooms.value = Object.entries(filteredClassrooms.value)
                .map(([course, classrooms]) => [
                    course,
                    classrooms.filter(c =>
                        c.teachers
                            .filter(t => t.includes(teacherFilter.value))
                            .length > 0
                    )
                ])
                .reduce((acc, cur) => {
                    acc[cur[0]] = cur[1];

                    return acc;
                }, {});

        if (timeFilter.value !== "")
            filteredClassrooms.value = Object.entries(filteredClassrooms.value)
                .map(([course, classrooms]) => [
                    course,
                    classrooms.filter(c => c.period.includes(timeFilter.value))
                ])
                .reduce((acc, cur) => {
                    acc[cur[0]] = cur[1];

                    return acc;
                }, {});
    }
</script>

<style scoped>

</style>