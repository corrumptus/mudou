<template>
    <BaseLayout>
        <template #header>
            <LogoutHeader :pageName="'Administração'" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar :page="'Administração'" />
                </template>

                <template #content>
                    <AdminCourseSubjectsContent :courseSubjects="courseSubjects" />
                </template>

                <template #sidebar>
                    <AdminTabs route="subjects" />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import LogoutHeader from '@header/LogoutHeader.vue';
    import HomeNavBar from '@navbar/home/HomeNavBar.vue';
    import AdminCourseSubjectsContent from '@content/admin/adminCourseSubjects/AdminCourseSubjectsContent.vue';
    import AdminTabs from '@sidebar/admin/AdminTabs.vue';

    const page = usePage();

    const { courseSubjects } = page.props;
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>