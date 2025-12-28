<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="`${classroom.name} - Minha Anotação`" />
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
                    <SelfAnotationContent
                        :anotation="selfAnotation"
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
    import SelfAnotationContent from '@content/selfAnotation/SelfAnotationContent.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes, selfAnotation } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>