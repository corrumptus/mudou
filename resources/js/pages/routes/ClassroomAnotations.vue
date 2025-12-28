<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="`${classroom.name} - anotações`" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: '', elementId: '' }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ClassroomAnotationsContent
                        :existentAnotations="existentAnotations"
                    />
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
    import ClassroomAnotationsContent from '@content/classroomAnotations/ClassroomAnotationsContent.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes, existentAnotations } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>