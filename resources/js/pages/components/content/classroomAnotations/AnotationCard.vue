<template>
    <div class="card">
        <div class="img">
            <img :src="img" :alt="alt" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
        </div>
        <span>
            {{ text }}
        </span>
    </div>
</template>

<script setup lang="ts">
    defineProps({
        text: {
            type: String,
            required: true
        },
        img: {
            type: String,
            required: true
        },
        alt: {
            type: String
        }
    });
</script>

<style scoped>
    .card {
        width: 130px;
        padding: 1em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #32a9ff;
        cursor: pointer;
    }

    .img {
        width: 70px;
        height: 70px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    span {
        margin: auto;
        display: -webkit-box;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 2;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
</style>