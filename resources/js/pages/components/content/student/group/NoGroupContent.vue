<template>
    <ContentHeader />
    <div class="no-group">
        <div class="create-group">
            <h1>Criar Grupo</h1>
            <div class="name">
                <input
                    v-if="group.groupMaker.themes === null"
                    placeholder="Nome"
                    :value="newGroup.name"
                    @input="e => newGroup.name = (e.target as HTMLInputElement).value"
                />
                <select
                    v-else
                    :value="newGroup.name"
                    @change="e => newGroup.name = (e.target as HTMLSelectElement).value"
                >
                    <option
                        v-for="t in group.groupMaker.themes"
                        :value="t"
                        :key="t"
                    >
                        {{ t }}
                    </option>
                </select>
            </div>
            <div class="users">
                <p>{{ Object.keys(newGroup.members).length }}/{{ group.groupMaker.maxMembers }}</p>
                <ul>
                    <li
                        v-for="u in group.groupMaker.users"
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
                            :checked="u.id in newGroup.members"
                        >
                    </li>
                </ul>
            </div>
            <div class="create">
                <button @click="create">Criar</button>
            </div>
        </div>
        <div class="enter-group">
            <h1>Entrar em um grupo</h1>
            <ul>
                <li v-for="(g, name) in (group.existingGroups as Groups)" :key="name">
                    <h2>{{ name }}</h2>
                    <ul>
                        <li v-for="member in g" :key="member.id">
                            <div class="img">
                                <img :src="member.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                            </div>
                            <span>{{ member.name }}</span>
                        </li>
                    </ul>
                    <button
                        v-if="g.length < group.groupMaker.maxMembers"
                        @click="() => requestEnter(name)"
                        class="requestEnter"
                        :id="`request-${name}`"
                    >
                        Pedir para entrar
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';

    type Groups = {
        [name: string]: {
            id: number,
            name: string,
            img: string
        }[]
    };

    const props = defineProps({
        group: {
            type: Object,
            required: true
        },
        create: {
            type: Function,
            required: true
        },
        requestEnter: {
            type: Function,
            required: true
        }
    });

    const newGroup = ref<{ name: string; members: Record<string, undefined> }>({
        name: "",
        members: {}
    });

    function selectUser(id: number) {
        if (id in newGroup.value.members) {
            delete newGroup.value.members[id];
            return;
        }

        if (Object.keys(newGroup.value.members).length === props.group.groupMaker.maxMembers)
            return;

        newGroup.value.members[id] = undefined;
    }

    function create() {
        props.create({
            name: newGroup.value.name,
            members: Object.keys(newGroup.value.members)
        });
    }
</script>

<style scoped>
    .no-group {
        height: 100%;
        overflow: auto;
    }

    .create-group {
        height: calc(100% - 6em);
        padding: 1em;
        border-radius: 1em;
        box-shadow: inset 0px 0px 5px black;
        margin: 1em;
        display: flex;
        flex-direction: column;
        overflow: auto;
        gap: .5em;
    }

    h1 {
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
    }

    .name input {
        width: 100%;
        padding: .3em .5em;
        border-radius: 10px;
        font-size: 1.25em;
        box-shadow: inset 0px 0px 4px #868585;
    }

    select {
        width: 100%;
        padding: .3em .5em;
        border-radius: 10px;
        font-size: 1.25em;
        text-overflow: ellipsis;
        box-shadow: inset 0px 0px 4px #868585;
    }

    .users {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: .5em;
        overflow: hidden;
    }

    p {
        font-size: 1.25em;
        font-weight: bold;
    }

    .users ul {
        padding: .5em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        background-color: #b4b4b4;
        overflow: auto;
    }

    .users li {
        padding: .5em;
        display: flex;
        align-items: center;
        gap: .5em;
        cursor: pointer;
        border-radius: .5em;
    }

    .users li:hover {
        background-color: #d7d7d7;
    }

    .img {
        width: 50px;
        height: 50px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    label {
        width: 100%;
        font-size: 1.25em;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .users input {
        cursor: pointer;
    }

    .create {
        display: flex;
        justify-content: center;
    }

    button {
        padding: .2em 1.5em;
        border-radius: 1em;
        font-size: 1.2em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }

    .enter-group {
        padding: 1em;
        border-radius: 1em;
        box-shadow: inset 0px 0px 5px black;
        margin: 1em;
    }

    .enter-group > ul {
        width: 100%;
        display: flex;
        align-items: start;
        flex-wrap: wrap;
        gap: 1em;
        overflow: auto;
    }

    .enter-group > ul > li {
        max-width: 250px;
        padding: 1em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        background-color: #32a9ff;
    }

    h2 {
        font-size: 1.25em;
        text-align: center;
    }

    .enter-group > ul ul {
        display: flex;
        flex-direction: column;
        gap: .5em;
        cursor: initial;
        user-select: none;
    }

    .enter-group > ul ul li {
        padding: .3em;
        border-radius: .8em;
        display: flex;
        align-items: center;
        gap: .5em;
    }

    .enter-group > ul ul li span {
        font-size: 1.15em;
    }

    .enter-group button {
        padding: .2em 1.5em;
        border-radius: 1em;
        margin: auto;
        font-size: 1.2em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }
</style>