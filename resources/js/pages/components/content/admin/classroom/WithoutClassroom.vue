<template>
    <ContentHeader />
    <div class="without-classroom">
        Turma não encontrada.
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
</script>

<style scoped>
    .without-classroom {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>