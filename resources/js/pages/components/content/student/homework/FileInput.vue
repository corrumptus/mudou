<template>
    <div class="input" @click="onClick">
        <input type="file" ref="input">
    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const input = ref();

    function onClick() {
        input.value.click();
    }
</script>

<style scoped>
    .input {
        width: 100%;
        height: 150px;
        border-radius: 10px;
        overflow: auto;
        box-shadow: inset 0px 0px 6px #868585;
        cursor: pointer;
    }

    input {
        display: none;
    }
</style>