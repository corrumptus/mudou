<template>
    <header>
        <span>{{ pageName }}</span>
        <button @click="click">
            <img src="https://assets.streamlinehq.com/image/private/w_300,h_300,ar_1/f_auto/v1/icons/c4/logout-31dva2zl3l9ao92pwnmgnt.png/logout-6bjs3s3fnciv7c0ye52y3l.png" alt="arrow pointing to the other side of a door">
        </button>
    </header>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { logout } from '@request/general/login';

    defineProps({
        pageName: {
            type: String,
            required: true
        }
    });

    function click() {
        logout()
            .then(() => router.visit('/'))
            .catch(alert);
    }
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
        font-size: 1.5em;
        font-weight: bold;
    }

    img {
        width: 40px;
        filter: invert(100%);
    }

    button {
        cursor: pointer;
    }
</style>