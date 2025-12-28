<template>
    <BaseLayout>
        <template #header>
            <HomeHeader pageName="Criar novo aviso" to="/teacher/course-subject" />
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
                    <NewAnnouncementContent :key="switchBool" :send="send" />
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
    import NewAnnouncementContent from '@content/teacher/newAnnouncement/NewAnnouncementContent.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create } from '@request/teacher/announcement';

    const page = usePage();

    const { classroom, classes } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const switchBool = ref(false);

    function send(a) {
            create({
                ...a,
                ...classroom
            })
                .then(a => {
                    alert("Aviso criado com sucesso");

                    switchBool.value = !switchBool.value;
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