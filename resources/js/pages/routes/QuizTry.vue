<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="`${quiz.name} - Tentativa ${tentativa+1}`" />
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
                    <QuizTryContent
                        :question="quizTry.questions[currentQuestion]"
                        :index="Object.keys(quizTry.questions).indexOf(currentQuestion)"
                        :selectOption="selectOption"
                        :selectedOption="selectedOptions[currentQuestion]"
                        :status="quizTry.status"
                        :nextIterator="nextIteratorRef"
                    />
                </template>

                <template #sidebar>
                    <QuizQuestions
                        :quizTry="quizTry"
                        :currentQuestion="currentQuestion"
                        :setCurrentQuestion="id => currentQuestion = id"
                        :selectedOptions="selectedOptions"
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
    import QuizTryContent from '@content/quizTry/QuizTryContent.vue';
    import QuizQuestions from '@sidebar/quizTry/QuizQuestions.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';

    const page = usePage();

    const { classroom, classes, quiz, quizTry } = page.props;

    const tentativa = quiz.trys.findIndex(t => t.id === quizTry.id);

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const selectedOptions = ref(quizTry.userOptions ?? {});
    const currentQuestion = ref();
    let nextIteratorRef;
    nextIteratorRef = ref(getNext());

    if (currentQuestion.value === undefined)
        currentQuestion.value = Object.keys(quizTry.questions)[0];

    function selectOption(optionId) {
        if (quizTry.status === "incomplete")
            selectedOptions.value[currentQuestion.value] = optionId;
    }

    function getNext() {
        if (quizTry.status !== "incomplete")
            return {
                hasNext: false,
                next: () => nextIteratorRef.value = getNext()
            };

        if (
            nextIteratorRef !== undefined
            &&
            nextIteratorRef.value !== undefined
            &&
            !nextIteratorRef.value.hasNext
        )
            return {
                hasNext: false,
                next: () => nextIteratorRef.value = getNext()
            };

        let sawCurrent = false;
        let next;
        const remainingQuestions = Object.keys(quizTry.questions)
            .filter(id => {
                if (
                    next === undefined
                    &&
                    sawCurrent
                    &&
                    selectedOptions.value[id] === undefined
                )
                    next = id;

                if (currentQuestion.value === id)
                    sawCurrent = true;

                return selectedOptions.value[id] === undefined;
            });

        if (currentQuestion.value === undefined || next === undefined)
            next = remainingQuestions[0];

        if (next !== undefined)
            currentQuestion.value = next;

        return {
            hasNext: remainingQuestions.length > 1,
            next: () => nextIteratorRef.value = getNext()
        };
    }
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>