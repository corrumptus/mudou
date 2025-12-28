<template>
    <ContentHeader />
    <div class="without-user">
        Usuário não encontrado.
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
</script>

<style scoped>
    .without-user {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>