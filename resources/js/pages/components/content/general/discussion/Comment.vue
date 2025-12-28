<template>
    <div class="comment">
        <div class="person">
            <div class="img">
                <img :src="person.img" onerror="this.onerror=null; this.src='https://phoenixestimations.com/files/user_icon.png';">
            </div>
            <span>{{ person.name }}</span>
        </div>
        <div class="text">
            {{ text }}
        </div>
    </div>
</template>

<script setup lang="ts">
    defineProps({
        person: {
            type: Object,
            required: true
        },
        text: {
            type: String,
            required: true
        }
    });
</script>

<style scoped>
    .comment  {
        display: flex;
        gap: 1em;
    }

    .person {
        height: fit-content;
        padding: .5em;
        border-radius: 1em;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #a8a8a8;
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
        width: 100%;
        margin: auto;
        display: -webkit-box;
        text-align: center;
        text-overflow: ellipsis;
        font-size: 1.25em;
        overflow: hidden;
        line-clamp: 2;
        -moz-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .text {
        padding: .6em 1em;
        border-radius: 1em;
        background-color: #cecece;
    }
</style>