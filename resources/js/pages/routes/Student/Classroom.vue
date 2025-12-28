<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="classroom.name" to="/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        v-if="classes !== null"
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: '', elementId: '' }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ClassroomContent
                        v-if="classes !== null"
                        :classroom="classroom"
                        :classes="classes"
                    />
                    <WithoutClassroom v-else />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import HomeHeader from '@header/HomeHeader.vue';
    import ClassroomNavBar from '@navbar/class/ClassroomNavBar.vue';
    import ClassroomContent from '@content/student/classroom/ClassroomContent.vue';
    import WithoutClassroom from '@content/student/classroom/WithoutClassroom.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>