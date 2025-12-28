<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="isEdit" :pageName="homework.name" to="/teacher/course-subject" />
            <HomeHeader v-else pageName="Criar nova tarefa" to="/teacher/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: homework?.classId, elementId: homework?.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ManageHomeworkContent
                        v-if="homeworkRef !== null"
                        :homework="homeworkRef"
                        :groups="groups"
                        :send="send"
                    />
                    <WithoutHomework v-else />
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
    import ManageHomeworkContent from '@content/teacher/manageHomework/ManageHomeworkContent.vue';
    import WithoutHomework from '@content/teacher/manageHomework/WithoutHomework.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create } from '@request/teacher/homework';

    const page = usePage();

    const { classroom, classes, homework, groups } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const isEdit = homework !== undefined;

    const homeworkRef = ref(isEdit ?
        homework
        :
        {
            title: "",
            description: "",
            beginDate: "",
            beginTime: "",
            closeDate: "",
            closeTime: "",
            canAcceptAfterClose: true,
            group: "",
            isText: false,
            maxAmountCaracteres: 0,
            isFile: true,
            maxAmountFiles: 1,
            fileTypes: ""
        }
    );

    function send(h) {
        if (isEdit)
            edit({
                ...h,
                ...classroom
            })
                .then(() => {
                    alert("Tarefa atualizada com sucesso");
                })
                .catch(alert);
        else
            create({
                ...h,
                ...classroom
            })
                .then(h => {
                    alert("Tarefa criada com sucesso");
                    homeworkRef.value = {
                        name: "",
                        description: "",
                        beginDate: "",
                        beginTime: "",
                        closeDate: "",
                        closeTime: "",
                        canSendAfterClose: true,
                        group: "",
                        isText: false,
                        maxAmountCaracteres: 0,
                        isFile: true,
                        maxAmountFiles: 1,
                        fileTypes: ""
                    };
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