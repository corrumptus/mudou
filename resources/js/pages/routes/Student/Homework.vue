<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="homework !== null" :pageName="homework.title" to="/course-subject" />
            <HomeHeader v-else pageName="" to="/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="homework !== null ? 
                            { classId: homework.classId, elementId: homework.id }
                            :
                            { classId: '', elementId: '' }
                        "
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <HomeworkContent
                        v-if="homework !== null"
                        :homework="homework"
                        :response="response"
                        :answer="send"
                    />
                    <WithoutHomework v-else />
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
    import HomeworkContent from '@content/student/homework/HomeworkContent.vue';
    import WithoutHomework from '@content/student/homework/WithoutHomework.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { answer } from '@request/student/homework';

    const page = usePage();

    const { classroom, classes, homework, response } = page.props;

    if (homework !== null) {
        homework.beginDate = new Date(`${homework.beginDate} ${homework.beginTime}`);
        homework.closeDate = new Date(`${homework.closeDate} ${homework.closeTime}`);
    }

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    function send(r) {
        if (
            response.text !== null
            &&
            r.text === response.text
        )
            return;

        answer({ ...classroom,
            classId: homework.classId,
            elementId: homework.id,
            ...r
        })
            .then(() => alert("Resposta enviada com sucesso"))
            .catch(alert);
    }
</script>