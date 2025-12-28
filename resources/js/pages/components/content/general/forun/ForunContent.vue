<template>
    <ContentHeader v-if="type === 'student'">
        <button @click="toNewDiscussion" dusk="add">
            <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
        </button>
    </ContentHeader>
    <ContentHeader v-else />
    <div class="forun">
        <ul>
            <li
                v-for="d in discussions"
                @click="() => toDiscussion(d.id)"
                dusk="discussion"
                :key="d.id"
            >
                <div class="owner">
                    <div class="img">
                        <img :src="d.owner.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                    </div>
                    <span>{{ d.owner.name }}</span>
                </div>
                <div class="title">{{ d.title }}</div>
            </li>
        </ul>
    </div>
</template>

<script setup lang="js">
    import { router } from '@inertiajs/vue3';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        classroom: {
            type: Object,
            required: true
        },
        discussions: {
            type: Array,
            required: true
        },
        type: {
            type: String,
            required: true
        }
    });

    function toNewDiscussion() {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/forun/new`);
    }

    function toDiscussion(id) {
        router.visit(`/course/${props.classroom.courseName}/subject/${props.classroom.subjectName}/classroom/${props.classroom.id}/forun/${id}`);
    }
</script>

<style scoped>
    button {
        width: 40px;
        height: 40px;
        padding: .2em;
        border-radius: 100%;
        overflow: hidden;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .forun {
        height: 100%;
        padding: 1em;
        overflow: hidden;
    }

    ul {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    li {
        padding: 1em;
        border-radius: 1em;
        display: flex;
        align-items: center;
        gap: 1em;
        background-color: #2c2c2c;
        cursor: pointer;
    }

    .owner {
        width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5em;
        flex-shrink: 0;
    }

    .img {
        width: 70px;
        height: 70px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    span {
        width: 100%;
        margin: auto;
        display: -webkit-box;
        color: white;
        text-align: center;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 1;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .title {
        width: 100%;
        display: -webkit-box;
        color: white;
        text-align: center;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 3;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
</style>