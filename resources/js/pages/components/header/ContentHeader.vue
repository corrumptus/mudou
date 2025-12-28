<template>
    <header>
        <button @click="goBack">
            <img
                src="https://www.freeiconspng.com/uploads/arrow-icon-28.png"
                alt="arrow"
            >
        </button>
        <slot></slot>
    </header>
</template>

<script setup>
    // import { router } from '@inertiajs/vue3';

    function goBack() {
        function reload() {
            // router.reload();
            window.location.reload();
            window.removeEventListener('popstate', reload);
        }

        window.addEventListener('popstate', reload);

        window.history.back();
    }
</script>

<style scoped>
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .5em;
    }

    button {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }

    img {
        width: 55px;
        transform: rotate(180deg);
        max-width: unset;
    }
</style>