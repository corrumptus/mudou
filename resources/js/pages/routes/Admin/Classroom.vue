<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="classroom?.name || ''" to="/admin/classrooms" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <ClassroomContent
                        v-if="classroom !== null"
                        :classroom="classroom"
                    />
                    <WithoutClassroom v-else />
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
    import ClassroomContent from '@content/admin/classroom/ClassroomContent.vue';
    import WithoutClassroom from '@content/admin/classroom/WithoutClassroom.vue';

    const page = usePage();

    const { classroom } = page.props;
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>