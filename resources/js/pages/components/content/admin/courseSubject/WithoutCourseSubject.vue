<template>
    <ContentHeader />
    <div class="without-course-subject">
        Disciplina não encontrada.
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
</script>

<style scoped>
    .without-course-subject {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>