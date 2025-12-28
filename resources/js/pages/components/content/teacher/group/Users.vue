<template>
    <div class="users">
        <header>
            <div>
                <input type="checkbox" id="inGroup" :checked="hasInGroup" @click="() => change('in')">
                <label for="inGroup">Com grupo</label>
            </div>
            <div>
                <input type="checkbox" id="noGroup" :checked="hasNoGroup" @click="() => change('no')">
                <label for="noGroup">Sem grupo</label>
            </div>
        </header>
        <div class="content">
            <ul>
                <li v-for="u in users" :key="u.id" :title="u.groupName">
                    <div class="img">
                        <img :src="u.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                    </div>
                    <span>{{ u.name }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';

    const props = defineProps({
        group: {
            type: Object,
            required: true
        }
    });

    const hasInGroup = ref(true);
    const hasNoGroup = ref(true);

    type Member = {
        id: number,
        name: string,
        img: string,
        groupName: string,
        status: string
    }

    const users = ref((props.group.members as Member[])
        .reduce((acc, cur) => {
            acc.push(cur);

            return acc;
        }, [] as Member[]));

    function change(type: "in" | "no") {
        if (type === "in")
            hasInGroup.value = !hasInGroup.value;
        else
            hasNoGroup.value = !hasNoGroup.value;

        users.value = (props.group.members as Member[])
            .filter(m =>
                (hasInGroup.value && m.status !== "noGroup")
                ||
                (hasNoGroup.value && m.status === "noGroup")
            );
    }
</script>

<style scoped>
    .users {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: .5em;
    }

    header {
        display: flex;
        justify-content: unset;
        gap: 1em;
    }

    header div {
        display: flex;
        align-items: center;
        gap: .5em;
    }

    header div label {
        font-size: 1.25em;
        cursor: pointer;
    }

    .content {
        height: 100%;
    }

    ul {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    li {
        display: flex;
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

    span {
        font-size: 1.25em;
        cursor: text;
    }
</style>