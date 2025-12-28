<template>
    <ContentHeader />
    <div class="participants">
        <div class="teachers">
            <h1>Professores</h1>
            <ul>
                <li v-for="t in participants.teachers" :key="t.id" dusk="teacher">
                    <button class="chat" dusk="chat">
                        <img src="https://cdn-icons-png.flaticon.com/512/151/151793.png" alt="a speech balloon" />
                    </button>
                    <div class="img">
                        <img :src="t.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                    </div>
                    <span>{{ t.name }}</span>
                </li>
            </ul>
        </div>
        <div class="monitors">
            <h1>Monitores</h1>
            <ul>
                <li v-for="m in participants.monitors" :key="m.id" dusk="monitor">
                    <button class="chat" dusk="chat">
                        <img src="https://cdn-icons-png.flaticon.com/512/151/151793.png" alt="a speech balloon" />
                    </button>
                    <div class="img">
                        <img :src="m.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                    </div>
                    <span>{{ m.name }}</span>
                </li>
            </ul>
        </div>
        <div class="students">
            <h1>Alunos</h1>
            <ul>
                <li v-for="s in participants.students" :key="s.id" dusk="student">
                    <button class="chat" dusk="chat">
                        <img src="https://cdn-icons-png.flaticon.com/512/151/151793.png" alt="a speech balloon" />
                    </button>
                    <div class="img">
                        <img :src="s.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                    </div>
                    <span>{{ s.name }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        participants: {
            type: Object,
            required: true
        }
    });
</script>

<style scoped>
    .participants {
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: auto;
    }

    .teachers, .monitors, .students {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    h1 {
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
    }

    ul {
        display: flex;
        gap: 1em;
        flex-wrap: wrap;
    }

    li {
        width: 130px;
        padding: 1em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1em;
        flex-shrink: 0;
        background-color: #32a9ff;
        user-select: none;
        position: relative;
    }

    .chat {
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #b4b4b4;
        cursor: pointer;
        width: 34px;
        height: 34px;
        position: absolute;
        right: 0;
        top: 0;
        transform: translate(50%, -50%);
    }

    .chat img {
        width: 24px;
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
        margin: auto;
        display: -webkit-box;
        text-overflow: ellipsis;
        font-size: 1.25em;
        text-align: center;
        overflow: hidden;
        line-clamp: 1;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
</style>