<template>
    <ContentHeader />
    <div class="manage">
        <div class="infos">
            <div class="name">
                <label for="name" class="title">Nome</label>
                <input id="name" :value="user.name" @input="change">
            </div>
            <div v-if="isEdit" class="password">
                <label for="password" class="title">Senha</label>
                <input id="password" :value="user.password" @input="change">
            </div>
            <div class="email">
                <label for="email" class="title">Email</label>
                <input id="email" :value="user.email" @input="change">
            </div>
            <div class="birth-date">
                <label for="birthDate" class="title">Data de nascimento</label>
                <input type="date" id="birthDate" :value="user.birthDate" @input="change">
            </div>
            <div class="cpf">
                <label for="cpf" class="title">CPF</label>
                <input id="cpf" :value="user.cpf" @input="change">
            </div>
            <div class="types">
                <span class="title">Tipos</span>
                <div>
                    <input
                        id="isAdmin"
                        type="checkbox"
                        :checked="user.isAdmin"
                        @change="change"    
                    >
                    <label for="isAdmin">Administrador</label>
                </div>
                <div>
                    <input
                        id="isTeacher"
                        type="checkbox"
                        :checked="user.isTeacher"
                        @change="change"    
                    >
                    <label for="isTeacher">Professor</label>
                </div>
                <div>
                    <input
                        id="isStudent"
                        type="checkbox"
                        :checked="user.isStudent"
                        @change="change"    
                    >
                    <label for="isStudent">Estudante</label>
                </div>
                <div>
                    <input
                        id="isMonitor"
                        type="checkbox"
                        :checked="user.isMonitor"
                        @change="change"    
                    >
                    <label for="isMonitor">Monitor</label>
                </div>
            </div>
            <div class="image">
                <label for="image" class="title">Foto de perfil</label>
                <ImageInput id="img" :value="user.img" :change="change" />
            </div>
            <div class="roles">
                <span class="title">Permissões</span>
                <input :value="filter" @input="input" placeholder="Filtro">
                <ul>
                    <li
                        v-for="role in filteredRoles"
                        :key="role.id"
                    >
                        <input type="checkbox" :id="`role-${role.id}`" :checked="role.id in user.roles">
                        <label :for="`role-${role.id}`">{{ role.name }}</label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="buttons">
            <button class="confirm" @click="sendUser">Salvar</button>
            <button class="cancel">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="js">
    import { nextTick, onMounted, ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';
    import ImageInput from '@content/admin/manageUser/ImageInput.vue';

    const props = defineProps({
        user: {
            type: Object
        },
        roles: {
            type: Array,
            required: true
        },
        send: {
            type: Function,
            required: true
        },
        isEdit: {
            type: Boolean,
            required: true
        }
    });

    const user = ref({
        ...props.user,
        roles: props.user.roles
            .reduce((acc, cur) => {
                acc[cur] = undefined;

                return acc;
            }, {})
    });

    onMounted(() => {
        if (props.isEdit) fetchImg();
    });

    async function fetchImg() {
        let newValue;
        
        try {
            const response = await fetch(user.value.img);

            if (!response.ok)
                throw new Error("something went wrong");

            newValue = await response.blob();
        } catch (e) {
            newValue = undefined;
        }

        await nextTick();

        user.value.img = newValue;
    }

    const filter = ref("");
    const filteredRoles = ref([ ...props.roles ]);
    const rolesLinkedToTraits = props.roles.reduce((acc, cur) => {
        if (cur.linked !== undefined)
            acc[cur.linked] = cur.id;

        return acc;
    }, {});

    function change(e) {
        const { id, value, checked, files } = e.target;

        if (id === "img") {
            user.value[id] = files[0];
            return;
        }

        if (id.startsWith('is')) {
            user.value[id] = Number(checked);

            if (checked)
                if (id in rolesLinkedToTraits)
                    user.value.roles[rolesLinkedToTraits[id]] = undefined;
            else
                if (id in rolesLinkedToTraits)
                    delete user.value.roles[rolesLinkedToTraits[id]];

            return;
        }

        if (id.startsWith("permission")) {
            if (e.target.checked)
                user.value.roles[id.slice(11)] = undefined;
            else
                delete user.value.roles[id.slice(11)];

            return;
        }

        user.value[id] = value;
    }

    let debouncer = undefined;

    function input(e) {
        if (debouncer !== undefined)
            clearTimeout(debouncer);

        filter.value = e.target.value;

        debouncer = setTimeout(() => {
            if (e.target.value === "")
                filteredRoles.value = [ ...props.roles ];
            else
                filteredRoles.value = props.roles.filter(p => p.name.includes(e.target.value));

            debouncer = undefined;
        }, 500);
    }

    function sendUser() {
        props.send({
            ...user.value,
            roles: Object.keys(user.value.roles)
        });
    }
</script>

<style scoped>
    .manage {
        height: 100%;
        padding-inline: .5em;
        display: flex;
        flex-direction: column;
        overflow: auto;
    }

    .infos {
        width: 100%;
        padding: 1em;
        border-radius: 1em;
        display: grid;
        grid-template-areas: '_ _';
        grid-template-columns: repeat(2, calc(50% - .5em));
        gap: 1em;
        background-color: #e1e1e1;
    }

    .name, .password, .email, .birth-date, .cpf, .image, .types, .roles {
        height: fit-content;
        padding: .5em;
        border-radius: .8em;
        display: flex;
        flex-direction: column;
        background-color: #b4b4b4;
        position: relative;
    }

    .roles {
        aspect-ratio: 1/1;
    }

    .roles input {
        border-bottom: 1px solid black;
        outline: none;
    }

    .roles ul {
        height: calc(100% - 25px);
        overflow: auto;
    }

    .title {
        position: absolute;
        font-weight: bold;
        transform: translate(5px, -22px);
        cursor: text;
    }

    .buttons {
        padding-block: .5em;
        display: flex;
        justify-content: center;
        gap: 1em;
    }

    .confirm, .cancel {
        padding-inline: .5em;
        border-radius: .5em;
        font-size: 1.5em;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .confirm {
        background-color: green;
    }

    .cancel {
        background-color: red;
    }
</style>