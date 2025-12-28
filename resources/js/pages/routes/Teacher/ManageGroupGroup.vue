<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="isEdit" :pageName="groupRef?.name" to="/teacher/course-subject" />
            <HomeHeader v-else pageName="Criar novo grupo" to="/teacher/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: groupMaker.classId, elementId: groupMaker.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <ManageGroupGroupContent
                        v-if="groupRef !== null"
                        :group="groupRef"
                        :groupMaker="groupMaker"
                        :send="send"
                    />
                    <WithoutGroupGroup v-else />
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
    import ManageGroupGroupContent from '@content/teacher/manageGroupGroup/ManageGroupGroupContent.vue';
    import WithoutGroupGroup from '@content/teacher/manageGroupGroup/WithoutGroupGroup.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create, edit } from '@request/teacher/groupGroup';

    const page = usePage();

    const { classroom, classes, groupMaker, group } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const isEdit = group !== undefined;

    const groupRef = ref(isEdit ?
        group == null ?
            null
            :
            {
                ...group,
                members: group.members.map(m => m.id)
            }
        :
        {
            name: "",
            members: []
        }
    );

    function send(g) {
        if (isEdit)
            edit({
                ...classroom,
                classroomId: classroom.id,
                ...groupMaker,
                ...g
            }, group.name)
                .then(g => {
                    groupRef.value = {
                        ...g,
                        members: g.members.map(m => m.id)
                    };
                    alert("Grupo atualizado com sucesso");
                })
                .catch(alert);
        else
            create({
                ...classroom,
                classroomId: classroom.id,
                ...groupMaker,
                ...g
            })
                .then(g => {
                    alert("Grupo criado com sucesso");
                    groupRef.value = {
                        name: "",
                        members: []
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