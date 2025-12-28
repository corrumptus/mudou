<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="course !== undefined" :pageName="course.name" to="/admin/courses" />
            <HomeHeader v-else pageName="Criar novo curso" to="/admin/courses" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <ManageCourseContent
                        v-if="courseRef !== null"
                        :course="courseRef"
                        :send="send"
                        :isEdit="isEdit"
                    />
                    <WithoutCourse v-else />
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
    import ManageCourseContent from '@content/admin/manageCourse/ManageCourseContent.vue';
    import WithoutCourse from '@content/admin/manageCourse/WithoutCourse.vue';
    import { create, edit } from '@request/admin/course';

    const page = usePage();

    const { course } = page.props;

    const isEdit = course !== undefined;

    const courseRef = ref(isEdit ?
        course
        :
        {
            name: "",
            code: "",
            amountSemesters: 0
        }
    );

    function send(c) {
        if (isEdit)
            edit(c)
                .then(() => {
                    alert("Curso atualizado com sucesso");
                })
                .catch(alert);
        else
            create(c)
                .then(c => {
                    alert("Curso criado com sucesso");
                    courseRef.value = {
                        name: "",
                        code: "",
                        amountSemesters: 0
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