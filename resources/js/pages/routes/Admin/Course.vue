<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="course?.name || ''" to="/admin/courses" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <CourseContent
                        v-if="course !== null"
                        :course="course"
                    />
                    <WithoutCourse v-else />
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
    import HomeNavBar from '@navbar/home/HomeNavBar.vue';
    import CourseContent from '@content/admin/course/CourseContent.vue';
    import WithoutCourse from '@content/admin/course/WithoutCourse.vue';

    const page = usePage();

    const { course } = page.props;
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>