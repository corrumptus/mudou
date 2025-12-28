<template>
    <BaseLayout>
        <template #header>
            <LogoutHeader :pageName="type === 'student' ? 'Disciplinas' : 'Professor'"/>
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar :page="type === 'student' ? 'Disciplinas' : 'Professor'" />
                </template>

                <template #content>
                    <CourseSubjectCard
                        v-for="cs in courseSubjects"
                        :subject="cs"
                        :type="type"
                    />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { onMounted, onBeforeUnmount } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import LogoutHeader from '@header/LogoutHeader.vue';
    import HomeNavBar from '@navbar/home/HomeNavBar.vue';
    import CourseSubjectCard from '@content/general/courseSubject/CourseSubjectCard.vue';

    const page = usePage();

    const { courseSubjects, type } = page.props;

    onMounted(() => {
        document.querySelector("section").style.display = "flex";
        document.querySelector("section").style.flexWrap = "wrap";
        document.querySelector("section").style.alignContent = "baseline";
        document.querySelector("section").style.gap = "15px";
        document.querySelector("section").style.padding = "10px";
    });

    onBeforeUnmount(() => {
        document.querySelector("section").style.display = "";
        document.querySelector("section").style.flexWrap = "";
        document.querySelector("section").style.alignContent = "";
        document.querySelector("section").style.gap = "";
        document.querySelector("section").style.padding = "";
    });
</script>