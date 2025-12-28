<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="user !== null" :pageName="user.name" to="/admin/people" />
            <HomeHeader v-else pageName="" to="/admin/people" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <HomeNavBar page="Administração" />
                </template>

                <template #content>
                    <UserContent
                        v-if="user !== null"
                        :user="user"
                        :route="route"
                    />
                    <WithoutUser v-else />
                </template>

                <template #sidebar>
                    <Navigator
                        :route="route"
                        :changeRoute="(r) => route = r"
                    />
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
    import UserContent from '@content/admin/user/UserContent.vue';
    import WithoutUser from '@content/admin/user/WithoutUser.vue';
    import Navigator from '@sidebar/user/Navigator.vue';

    const page = usePage();

    const { user } = page.props;

    const route = ref("info");
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>