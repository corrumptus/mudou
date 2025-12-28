<template>
    <div>
        <p v-if="type === 'text'">{{ value }}</p>
        <img v-else :src="value">
    </div>
</template>

<script setup lang="ts">
    defineProps({
        type: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: true
        }
    });
</script>