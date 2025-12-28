<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="name">
                <p>Nome</p>
                <input
                    :value="group.name"
                    @input="e => group.name = (e.target as HTMLInputElement).value"
                >
            </div>
            <div class="members">
                <p>Membros</p>
                <ul>
                    <li
                        v-for="u in groupMaker.users"
                        @click.stop="() => selectUser(u.id)"
                        :key="u.id"
                    >
                        <div class="img">
                            <img :src="u.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                        </div>
                        <label :for="`user-${u.id}`" @click.prevent>{{ u.name }}</label>
                        <input
                            type="checkbox"
                            :id="`user-${u.id}`"
                            :checked="u.id in group.members"
                        >
                    </li>
                </ul>
            </div>
        </div>
        <div class="buttons">
            <button class="create" @click="send">Criar</button>
            <button class="cancel" @click="goBack">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        group: {
            type: Object,
            required: true
        },
        groupMaker: {
            type: Object,
            required: true
        },
        send: {
            type: Function,
            required: true
        }
    });

    const group = ref<{
        name: string,
        members: Record<number, undefined>
    }>({
        ...props.group as { name: string },
        members: (props.group.members as number[]).reduce((acc, cur) => {
            acc[cur] = undefined;

            return acc;
        }, {} as Record<number, undefined>)
    });

    function selectUser(id: number) {
        if (id in group.value.members)
            delete group.value.members[id];
        else
            group.value.members[id] = undefined;
    }

    function send() {
        props.send({
            ...group.value,
            members: Object.keys(group.value.members)
        });
    }

    function goBack() {
        window.history.back();
    }
</script>

<style scoped>
    .manage {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: hidden;
    }

    .infos {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: hidden;
    }

    p {
        font-size: 1.5em;
    }

    .name input {
        width: 100%;
        padding: .2em .5em;
        border-radius: .5em;
        font-size: 1.25em;
        box-shadow: inset 0px 0px 3px black;
        outline: none;
    }

    .members {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    ul {
        height: 100%;
        padding: .5em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        background-color: #b4b4b4;
        overflow: auto;
    }

    li {
        padding: .5em;
        border-radius: 1em;
        display: flex;
        align-items: center;
        gap: .5em;
        cursor: pointer;
    }

    li:hover {
        background-color: #e1e1e1;
    }

    .img {
        width: 40px;
        height: 40px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    label {
        width: 100%;
        font-size: 1.25em;
        cursor: pointer;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 1em;
    }

    .create, .cancel {
        padding-inline: .5em;
        border-radius: .5em;
        font-size: 1.5em;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .create {
        background-color: green;
    }

    .cancel {
        background-color: red;
    }
</style>