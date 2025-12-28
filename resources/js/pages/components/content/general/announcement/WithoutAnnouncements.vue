<template>
    <ContentHeader />
    <div class="announcement">
        Avisos não encontrados
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
</script>

<style scoped>
    .announcement {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>