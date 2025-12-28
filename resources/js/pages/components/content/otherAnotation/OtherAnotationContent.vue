<template>
    <ContentHeader />
    <div class="otherAnotation">
        <div class="anotation">
            {{ text }}
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        text: {
            type: String,
            required: true
        }
    });
</script>

<style scoped>
    .otherAnotation {
        height: 100%;
        padding: 1em;
        overflow: hidden;
    }

    .anotation {
        height: 100%;
        padding: .3em .6em;
        border-radius: 1em;
        box-shadow: inset 0px 0px 4px black;
        overflow: auto;
    }
</style>