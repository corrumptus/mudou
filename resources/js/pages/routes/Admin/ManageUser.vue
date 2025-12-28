<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="user !== undefined" :pageName="user?.name" to="/admin/people" />
            <HomeHeader v-else pageName="Criar novo usuário" to="/admin/people" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar
                        page="Administração"
                    />
                </template>

                <template #content>
                    <ManageUserContent
                        v-if="userRef !== null"
                        :user="userRef"
                        :roles="roles"
                        :send="send"
                        :isEdit="isEdit"
                    />
                    <WithoutUser v-else />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import HomeHeader from '@header/HomeHeader.vue';
    import HomeNavBar from '@navbar/home/HomeNavBar.vue';
    import ManageUserContent from '@content/admin/manageUser/ManageUserContent.vue';
    import WithoutUser from '@content/admin/manageUser/WithoutUser.vue';
    import { create, edit } from '@request/admin/user';

    const page = usePage();

    const { user, roles } = page.props;

    const isEdit = user !== undefined;

    const userRef = ref(isEdit ?
        user
        :
        {
            name: "",
            password: "",
            email: "",
            birthDate: "",
            cpf: "",
            img: undefined,
            isAdmin: 0,
            isTeacher: 0,
            isStudent: 1,
            isMonitor: 0,
            roles: []
        }
    );

    function send(u) {
        if (isEdit)
            edit(u)
                .then(() => {
                    alert("Usuário atualizado com sucesso");
                })
                .catch(alert);
        else
            create(u)
                .then(l => {
                    alert(`O link de primeiro acesso é: ${l}`);
                    userRef.value = {
                        name: "",
                        password: "",
                        email: "",
                        birthDate: "",
                        cpf: "",
                        img: undefined,
                        types: [],
                        roles: []
                    };
                })
                .catch(alert);
    }
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>