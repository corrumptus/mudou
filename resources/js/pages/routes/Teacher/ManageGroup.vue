<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="isEdit" :pageName="group.name" to="/teacher/course-subject" />
            <HomeHeader v-else pageName="Criar novo grupo" to="/teacher/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: group?.classId, elementId: group?.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ManageGroupContent
                        v-if="groupRef !== null"
                        :group="groupRef"
                        :send="send"
                    />
                    <WithoutGroup v-else />
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
    import ManageGroupContent from '@content/teacher/manageGroup/ManageGroupContent.vue';
    import WithoutGroup from '@content/teacher/manageGroup/WithoutGroup.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create, edit } from '@request/teacher/group';

    const page = usePage();

    const { classroom, classes, group } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const isEdit = group !== undefined;

    const groupRef = ref(isEdit ?
        group
        :
        {
            title: "",
            maxMembers: 2,
            themes: []
        }
    );

    function send(g) {
        if (isEdit)
            edit({
                ...classroom,
                classroomId: classroom.id,
                ...g
            })
                .then(() => {
                    alert("Grupo atualizado com sucesso");
                })
                .catch(alert);
        else
            create({
                ...classroom,
                ...g
            })
                .then(g => {
                    alert("Grupo criado com sucesso");
                    groupRef.value = {
                        name: "",
                        maxMembers: 2,
                        themes: []
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