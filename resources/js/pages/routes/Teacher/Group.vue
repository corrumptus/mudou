<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="group !== null" :pageName="group.title" to="/teacher/course-subject" />
            <HomeHeader v-else pageName="" to="/teacher/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: group.classId, elementId: group.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <GroupContent
                        v-if="group !== null"
                        :classroom="classroom"
                        :group="group"
                    />
                    <WithoutGroup v-else />
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
    import GroupContent from '@content/teacher/group/GroupContent.vue';
    import WithoutGroup from '@content/teacher/group/WithoutGroup.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes, group } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>