<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="classroom !== undefined" :pageName="classroom?.name" to="/admin/classrooms" />
            <HomeHeader v-else pageName="Criar nova turma" to="/admin/classrooms" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <ManageClassroomContent
                        v-if="classroomRef !== null"
                        :classroom="classroomRef"
                        :courses="courses"
                        :courseSubjects="courseSubjects"
                        :teachers="teachers"
                        :monitors="monitors"
                        :students="students"
                        :send="send"
                        :isEdit="isEdit"
                    />
                    <WithoutClassroom v-else />
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
    import HomeNavBar from '@navbar/home/HomeNavBar.vue';
    import ManageClassroomContent from '@content/admin/manageClassroom/ManageClassroomContent.vue';
    import WithoutClassroom from '@content/admin/manageClassroom/WithoutClassroom.vue';
    import { create, edit } from '@request/admin/classroom';

    const page = usePage();

    const {
        classroom,
        courses,
        courseSubjects,
        teachers,
        monitors,
        students
    } = page.props;

    const isEdit = classroom !== undefined;

    const classroomRef = ref(isEdit ?
        classroom
        :
        {
            beginDate: "",
            closeDate: "",
            periods: [],
            teachers: [],
            monitors: [],
            students: [],
            subject: {
                id: "",
                name: "",
                course: {
                    id: "",
                    name: ""
                }
            }
        }
    );

    function send(c) {
        if (isEdit)
            edit(c)
                .then(() => {
                    alert("Turma atualizada com sucesso");
                })
                .catch(alert);
        else
            create(c)
                .then(c => {
                    alert("Turma criada com sucesso");
                    classroomRef.value = {
                        beginDate: "",
                        closeDate: "",
                        periods: [],
                        teachers: [],
                        monitors: [],
                        students: [],
                        subject: {
                            id: "",
                            course: {
                                id: ""
                            }
                        }
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