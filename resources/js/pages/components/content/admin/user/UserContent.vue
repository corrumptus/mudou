<template>
    <ContentHeader>
        <button v-if="route === 'info'" class="edit" dusk="edit" @click="toEditUser">
            <img src="https://www.svgrepo.com/show/42233/pencil-edit-button.svg" alt="pencil">
        </button>
    </ContentHeader>
    <div class="user">
        <MainInfo v-if="route === 'info'" :user="user" />
        <ClassroomInfo
            v-if="route === 'disc' && user.studentClassrooms !== undefined"
            :classrooms="user.studentClassrooms"
        />
        <ClassroomInfo
            v-if="route === 'turm' && user.teacherClassrooms !== undefined"
            :classrooms="user.teacherClassrooms"
        />
        <ClassroomInfo
            v-if="route === 'moni' && user.monitorClassrooms !== undefined"
            :classrooms="user.monitorClassrooms"
        />
        <PermissionsInfo v-if="route === 'perm'" :permissions="user.permissions" />
    </div>
</template>

<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';
    import MainInfo from '@content/admin/user/MainInfo.vue';
    import ClassroomInfo from '@content/admin/user/ClassroomInfo.vue';

    const props = defineProps({
        user: {
            type: Object,
            required: true
        },
        route: {
            type: String,
            required: true
        }
    });

    function toEditUser() {
        router.visit(`/user/${props.user.email}/edit`);
    }
</script>

<style scoped>
    .edit {
        padding: .5em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .edit img {
        width: 24px;
    }

    .user {
        height: 100%;
        padding: .5em;
        padding-top: 0;
        display: flex;
        flex-direction: column;
        gap: .5em;
        overflow: hidden;
    }
</style>