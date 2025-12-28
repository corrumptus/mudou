<template>
    <ContentHeader v-if="type === 'student'" />
    <ContentHeader v-else>
        <button @click="toNewAnnouncement" dusk="add">
            <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
        </button>
    </ContentHeader>
    <div class="announcement">
        <ul>
            <li
                v-for="(a, i) in announcements"
                dusk="announcement"
                :key="a.id"
            >
                <div
                    class="title"
                    @click="() => switchCollapse(i)"
                    :style="{
                        backgroundColor: a.saw ? '#808080' : '#2c2a2a',
                        color: a.saw ? 'black' : 'white'
                    }"
                >
                    {{ a.title }}
                </div>
                <div
                    class="description"
                    :style="{
                        height: collapsedAnnouncements[i] ?
                            0
                            :
                            `${announcementDescriptions[i].scrollHeight}px`,
                    }"
                    ref="announcementDescriptions"
                >
                    <p>{{ a.description }}</p>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        announcements: {
            type: Array,
            required: true
        },
        type: {
            type: String,
            required: true
        },
        see: {
            type: Function,
            required: true
        },
        toNewAnnouncement: {
            type: Function,
            required: true
        }
    });

    const collapsedAnnouncements = ref(props.announcements.map(() => true));

    const announcementDescriptions = ref();

    function switchCollapse(i) {
        collapsedAnnouncements.value[i] = !collapsedAnnouncements.value[i];

        const announcement = props.announcements[i];

        if (announcement.saw === false)
            see(i, announcement.id);
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

    .announcement {
        height: 100%;
        padding: 1em;
        overflow: auto;
    }

    ul {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .title {
        padding: 1em;
        border-radius: 1em;
        color: white;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .description {
        border-bottom-left-radius: 1em;
        border-bottom-right-radius: 1em;
        background-color: #b4b4b4;
        transform: translateY(-1em);
        overflow: hidden;
        transition: height .3s ease;
    }

    p {
        margin: 1em;
        margin-top: 1.5em;
    }
</style>