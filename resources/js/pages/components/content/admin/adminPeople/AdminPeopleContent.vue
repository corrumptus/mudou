<template>
    <header>
        <div class="type-filter">
            <span
                :style="{
                    transform: selectedType == '' ?
                        'translate(6px, 7px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: selectedType == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: selectedType == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: selectedType == '' ?
                        ''
                        :
                        '0s, .5s, .5s'
                }"
                @click="e => e.target.parentElement.querySelector('select').showPicker()"
            >
                Tipo
            </span>
            <select :value="selectedType" @change="selectUserType" dusk="type-filter">
                <option value=""></option>
                <option value="isAdmin">Administrador</option>
                <option value="isTeacher">Professor</option>
                <option value="isStudent">Estudante</option>
                <option value="isMonitor">Monitor</option>
            </select>
        </div>
        <div class="name-filter">
            <span
                :style="{
                    transform: typedName == '' ?
                        'translate(6px, 10px)'
                        :
                        'translate(17px, -9px) scale(0.9)',
                    borderInline: typedName == '' ?
                        ''
                        :
                        '1px solid #b4b4b4',
                    backgroundColor: typedName == '' ?
                        ''
                        :
                        'white',
                    transitionDelay: typedName == '' ?
                        ''
                        :
                        '0s, .5s, .5s'
                }"
                @click="e => e.target.parentElement.querySelector('input').focus()"
            >
                Nome
            </span>
            <input :value="typedName" @input="typeName" dusk="name-filter">
        </div>
        <button @click="toNewPerson" class="add" dusk="add">
            <img src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png" alt="plus">
        </button>
    </header>
    <div class="people">
        <table>
            <thead>
                <th>Nº</th>
                <th>Foto</th>
                <th>Nome</th>
                <th
                    v-if="selectedType === '' || selectedType === 'admin'"
                >
                    Admin
                </th>
                <th
                    v-if="selectedType === '' || selectedType === 'teacher'"
                >
                    Prof.
                </th>
                <th
                    v-if="selectedType === '' || selectedType === 'student'"
                >
                    Aluno
                </th>
                <th
                    v-if="selectedType === '' || selectedType === 'monitor'"
                >
                    Monitor
                </th>
            </thead>
            <tbody>
                <tr v-for="(user, i) in renderedUsers" :key="user.id" dusk="person">
                    <td>{{ i+1 }}</td>
                    <td>
                        <div class="img">
                            <img :src="user.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
                        </div>
                    </td>
                    <td @click="() => toUser(user.email)">{{ user.name }}</td>
                    <td v-if="selectedType === '' || selectedType === 'admin'" >
                        <img v-if="user.isAdmin" class="true" src="https://cdn-icons-png.flaticon.com/512/271/271205.png" alt="check">
                        <img v-else class="false" src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </td>
                    <td v-if="selectedType === '' || selectedType === 'teacher'" >
                        <img v-if="user.isTeacher" class="true" src="https://cdn-icons-png.flaticon.com/512/271/271205.png" alt="check">
                        <img v-else class="false" src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </td>
                    <td v-if="selectedType === '' || selectedType === 'student'" >
                        <img v-if="user.isStudent" class="true" src="https://cdn-icons-png.flaticon.com/512/271/271205.png" alt="check">
                        <img v-else class="false" src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </td>
                    <td v-if="selectedType === '' || selectedType === 'monitor'" >
                        <img v-if="user.isMonitor" class="true" src="https://cdn-icons-png.flaticon.com/512/271/271205.png" alt="check">
                        <img v-else class="false" src="https://cdn-icons-png.flaticon.com/512/1828/1828774.png" alt="x">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="js">
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        users: {
            type: Array,
            required: true
        }
    });

    const selectedType = ref("");
    const typedName = ref("");

    const renderedUsers = ref([ ...props.users ]);

    function selectUserType(e) {
        selectedType.value = e.target.value;

        filter();
    }
    
    let debouncer = undefined;

    function typeName(e) {
        typedName.value = e.target.value;

        if (debouncer !== undefined)
            clearTimeout(debouncer);

        debouncer = setTimeout(() => {
            debouncer = undefined;

            filter();
        }, 500);
    }

    function filter() {
        renderedUsers.value = [ ...props.users ];

        if (selectedType.value !== "")
            renderedUsers.value = renderedUsers.value
                .filter(u => u[selectedType.value]);

        if (typedName.value !== "")
            renderedUsers.value = renderedUsers.value
                .filter(u => u.name.includes(typedName.value));
    }

    function toNewPerson() {
        router.visit("/user/new");
    }

    function toUser(email) {
        router.visit(`/user/${email}`);
    }
</script>

<style scoped>
    header {
        padding: .3em;
        padding-top: .7em;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5em;
        overflow: hidden;
    }

    .type-filter {
        position: relative;
    }

    .type-filter span {
        padding-inline: 6px;
        position: absolute;
        z-index: 1;
        color: #4f4f4f;
        line-height: 1.2em;
        transition: transform linear .5s, border linear 0s, background-color linear 0s;
    }

    select {
        padding: 0.2em;
        padding-left: 0.4em;
        border: 1px solid #b4b4b4;
        border-radius: 1em;
        font-size: 1.25em;
    }

    .name-filter {
        position: relative;
    }

    .name-filter span {
        padding-inline: 6px;
        position: absolute;
        z-index: 1;
        color: #4f4f4f;
        line-height: 1.2em;
        user-select: none;
        transition: transform linear .5s, border linear 0s, background-color linear 0s;
    }

    input {
        width: 100%;
        padding: 0.2em;
        padding-left: 0.4em;
        border: 1px solid #b4b4b4;
        border-radius: 1em;
        font-size: 1.25em;
        outline: none;
    }

    .add {
        padding: .2em;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
        flex-shrink: 0;
    }

    .add img {
        width: 35px;
    }

    .people {
        height: 100%;
        padding: .5em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: auto;
    }

    table {
        width: 100%;
    }

    th, td {
        text-align: center;
        padding: .2em;
    }

    tr:nth-child(even) td {
        background-color: #b4b4b4;
    }

    tr:hover td {
        background-color: lightblue;
        color: blue;
    }

    tr td:nth-child(3) {
        cursor: pointer;
    }

    .img, .true, .false {
        margin: auto;
    }

    .img {
        width: 40px;
        height: 40px;
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

    .true {
        width: 20px;
        filter: invert(52%) sepia(65%) saturate(2506%) hue-rotate(83deg) brightness(118%) contrast(125%);
    }

    .false {
        width: 14px;
        filter: invert(20%) sepia(96%) saturate(7245%) hue-rotate(358deg) brightness(94%) contrast(115%);
    }
</style>