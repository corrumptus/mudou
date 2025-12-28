<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="courseSubject?.name || ''" to="/admin/subjects" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <CourseSubjectContent
                        v-if="courseSubject !== null"
                        :courseSubject="courseSubject"
                    />
                    <WithoutCourseSubject v-else />
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
    import CourseSubjectContent from '@content/admin/courseSubject/CourseSubjectContent.vue';
    import WithoutCourseSubject from '@content/admin/courseSubject/WithoutCourseSubject.vue';

    const page = usePage();

    const { courseSubject } = page.props;
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>