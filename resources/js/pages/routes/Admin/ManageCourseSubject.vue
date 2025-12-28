<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="courseSubject !== undefined" :pageName="courseSubject?.name" to="/admin/subjects" />
            <HomeHeader v-else pageName="Criar nova disciplina" to="/admin/subjects" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <ManageCourseSubjectContent
                        v-if="courseSubjectRef !== null"
                        :courseSubject="courseSubjectRef"
                        :courses="courses"
                        :send="send"
                        :isEdit="isEdit"
                    />
                    <WithoutCourseSubject v-else />
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
    import ManageCourseSubjectContent from '@content/admin/manageCourseSubject/ManageCourseSubjectContent.vue';
    import WithoutCourseSubject from '@content/admin/manageCourseSubject/WithoutCourseSubject.vue';
    import { create, edit } from '@request/admin/courseSubject';

    const page = usePage();

    const { courseSubject, courses } = page.props;

    const isEdit = courseSubject !== undefined;

    const courseSubjectRef = ref(isEdit ?
        courseSubject
        :
        {
            name: "",
            code: "",
            course: {
                id: "",
                name: ""
            }
        }
    );

    function send(s) {
        if (isEdit)
            edit(s)
                .then(() => {
                    alert("Disciplina atualizada com sucesso");
                })
                .catch(alert);
        else
            create(s)
                .then(s => {
                    alert("Disciplina criada com sucesso");
                    courseSubjectRef.value = {
                        name: "",
                        code: "",
                        course: {
                            id: "",
                            name: ""
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