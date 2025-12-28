<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="classroom.name" to="/teacher/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classesRef"
                        :selectedClassElement="{ classId: '', elementId: '' }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ClassroomContent
                        :classroom="classroom"
                        :classes="classesRef"
                        :create="send"
                    />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import HomeHeader from '@header/HomeHeader.vue';
    import ClassroomNavBar from '@navbar/class/ClassroomNavBar.vue';
    import ClassroomContent from '@content/teacher/classroom/ClassroomContent.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create } from '@request/teacher/class';

    const page = usePage();

    const { classroom, classes } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
    
    const classesRef = ref(classes);

    function send(newClass) {
        create({
            ...newClass,
            courseId: classroom.courseId,
            subjectId: classroom.courseSubjectId,
            classroomId: classroom.id
        })
            .then(c => {
                classesRef.value = c.classes;
            })
            .catch(alert);
    }
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>