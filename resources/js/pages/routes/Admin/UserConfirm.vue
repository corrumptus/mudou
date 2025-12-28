<template>
    <BaseLayout>
        <template #header>
            <LoginHeader />
        </template>

        <template #content>
            <UserConfirmContent :token="token" />
        </template>
    </BaseLayout>
</template>

<script setup>
    import { onMounted } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import LoginHeader from '@header/LoginHeader.vue';
    import UserConfirmContent from '@content/admin/userConfirm/UserConfirmContent.vue';

    const page = usePage();

    const { token } = page.props;

    onMounted(() => {
        document.body.style.backgroundImage = 'url("https://i.pinimg.com/736x/00/77/cb/0077cbd5b342e791060befe976f36d71.jpg")';
    });
</script>

<style>
    body {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
</style>