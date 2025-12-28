<template>
    <header>
        <span>{{ pageName }}</span>
        <img src="https://cdn-icons-png.flaticon.com/512/846/846449.png" alt="home" @click="() => router.visit(to)">
    </header>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';

    defineProps({
        pageName: {
            type: String,
            required: true
        },
        to: {
            type: String,
            required: true
        }
    });
</script>

<style scoped>
    header {
        width: 100%;
        padding-block: .4em;
        padding-left: .5em;
        padding-right: .3em;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #2c2a2a;
    }

    span {
        max-width: 60%;
        height: 1.5em;
        font-size: 1.5em;
        font-weight: bold;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    img {
        width: 40px;
        filter: invert(100%);
        cursor: pointer;
    }
</style>