<template>
    <div class="groups">
        <header>
            <p>max: {{ group.groupMaker.maxMembers }}</p>
            <button class="add" @click="() => toNew()">
                <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
            </button>
        </header>
        <ul>
            <li v-for="(g, name) in groups" :key="name">
                <button @click="() => toEdit(name)">
                    <img src="https://www.svgrepo.com/show/42233/pencil-edit-button.svg" alt="pencil">
                </button>
                <span>{{ name }}</span>
                <ul>
                    <li v-for="member in g" :key="member.id">
                        <div class="img">
                            <img :src="member.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                        </div>
                        <span>{{ member.name }}</span>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
    const props = defineProps({
        group: {
            type: Object,
            required: true
        },
        toNew: {
            type: Function,
            required: true
        },
        toEdit: {
            type: Function,
            required: true
        }
    });

    type Member = {
        id: number,
        name: string,
        img: string,
        groupName: string
    }

    const groups = (props.group.members as Member[])
        .filter(m => m.groupName !== null)
        .reduce((acc, cur) => {
            if (!(cur.groupName in acc))
                acc[cur.groupName] = [];

            acc[cur.groupName].push(cur);

            return acc;
        }, {} as Record<string, Member[]>);
</script>

<style scoped>
    .groups {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    p {
        font-size: 1.5em;
        font-weight: bold;
        text-align: end;
    }

    .add {
        padding: .2em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .add img {
        width: 35px;
    }

    .groups > ul {
        height: 100%;
        padding: 1em 1.1em 0px 0px;
        display: flex;
        gap: 1.5em;
        flex-wrap: wrap;
        overflow: auto;
    }

    .groups > ul > li {
        width: 180px;
        height: fit-content;
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

    .groups > ul > li > button {
        padding: .5em;
        border-radius: 100%;
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(43%, -35%);
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .groups > ul > li > button img {
        width: 24px;
    }

    .groups > ul > li > span {
        font-size: 1.25em;
        cursor: text;
    }

    .groups > ul ul {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .groups > ul ul li {
        display: flex;
        align-items: center;
        gap: .5em;
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

    .groups > ul ul li span {
        font-size: 1.25em;
        cursor: text;
    }
</style>