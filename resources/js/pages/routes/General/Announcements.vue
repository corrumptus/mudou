<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="announcements !== null" :pageName="`${classroom.name} - Avisos`" />
            <HomeHeader v-else pageName="" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: '', elementId: '' }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <AnnouncementsContent
                        v-if="announcements !== null"
                        :announcements="announcements"
                        :type="type"
                        :see="send"
                        :toNewAnnouncement="toNewAnnouncement"
                    />
                    <WithoutAnnouncements v-else />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { ref } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import HomeHeader from '@header/HomeHeader.vue';
    import ClassroomNavBar from '@navbar/class/ClassroomNavBar.vue';
    import AnnouncementsContent from '@content/general/announcement/AnnouncementsContent.vue';
    import WithoutAnnouncements from '@content/general/announcement/WithoutAnnouncements.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { see } from '@request/student/announcement'; 

    const page = usePage();

    const { classroom, classes, announcements, type } = page.props;

    const announcementsRef = ref({ ...announcements });

    function send(i, id) {
        see(classroom, id)
            .then(() => {
                announcementsRef.value[i].saw = true;
            });
    }

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    function toNewAnnouncement() {
        router.visit(`/teacher/course/${classroom.courseName}/subject/${classroom.subjectName}/classroom/${classroom.id}/announcements/new`);
    }
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>