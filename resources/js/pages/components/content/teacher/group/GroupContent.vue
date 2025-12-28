<template>
    <ContentHeader>
        <button class="edit" dusk="edit" @click="toEdit">
            <img src="https://www.svgrepo.com/show/42233/pencil-edit-button.svg" alt="pencil">
        </button>
    </ContentHeader>
    <div class="group">
        <div class="buttons">
            <button @click="isGroups = !isGroups" :style="{ borderBottomColor: isGroups ? '#e1e1e1' : ''  }" dusk="to-groups">Grupos</button>
            <button @click="isGroups = !isGroups" :style="{ borderBottomColor: !isGroups ? '#e1e1e1' : ''  }" dusk="to-users">Usuários</button>
        </div>
        <div class="content">
            <Groups v-if="isGroups" :group="group" :toNew="toNewGroup" :toEdit="toEditGroup" />
            <Users v-if="!isGroups" :group="group" />
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';
    import Groups from '@content/teacher/group/Groups.vue';
    import Users from '@content/teacher/group/Users.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        },
        group: {
            type: Object,
            required: true
        }
    });

    const isGroups = ref(true);

    function toEdit() {
        router.visit(`/teacher/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${props.group.classId}/group/${props.group.id}/edit`);
    }

    function toNewGroup() {
        router.visit(`/teacher/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${props.group.classId}/group/${props.group.id}/new`);
    }

    function toEditGroup(groupName: string) {
        router.visit(`/teacher/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/class/${props.group.classId}/group/${props.group.id}/group/${groupName}`);
    }
</script>

<style scoped>
    .edit {
        padding: .5em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    img {
        width: 24px;
    }

    .group {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
    }

    .buttons {
        display: flex;
        gap: 1em;
        transform: translate(2em, 1px);
        font-size: 1.5em;
    }

    .buttons button {
        padding: .15em .4em 0px;
        border: 1px solid black;
        border-radius: 1em 1em 0px 0px;
        background-color: #e1e1e1;
        cursor: pointer;
    }

    .content {
        height: calc(100% - 7em);
        padding: 1em;
        border: 1px solid;
        border-radius: 1em;
        background-color: #e1e1e1;
    }
</style>