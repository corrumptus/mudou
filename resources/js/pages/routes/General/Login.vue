<template>
    <BaseLayout>
        <template #header>
            <LoginHeader />
        </template>

        <template #content>
            <LoginContent />
        </template>
    </BaseLayout>
</template>

<script setup lang="ts">
    import { onBeforeUnmount, onMounted } from 'vue';
    import BaseLayout from '@layout/BaseLayout.vue';
    import LoginHeader from '@header/LoginHeader.vue';
    import LoginContent from '@content/general/login/LoginContent.vue';

    onMounted(() => {
        document.body.style.backgroundImage = 'url("https://i.pinimg.com/736x/00/77/cb/0077cbd5b342e791060befe976f36d71.jpg")';
        document.body.style.backgroundRepeat = "no-repeat";
        document.body.style.backgroundSize = "cover";
        document.body.style.backgroundPosition = "center";
    });

    onBeforeUnmount(() => {
        document.body.style.backgroundImage = "";
        document.body.style.backgroundRepeat = "";
        document.body.style.backgroundSize = "";
        document.body.style.backgroundPosition = "";
    });
</script>

<style>
    body {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
</style>