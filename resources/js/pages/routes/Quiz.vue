<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="quiz.name" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: quiz.classId, elementId: quiz.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <QuizContent
                        :quiz="quiz"
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
    import QuizContent from '@content/quiz/QuizContent.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes, quiz } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);
</script>