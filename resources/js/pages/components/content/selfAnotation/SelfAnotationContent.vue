<template>
    <ContentHeader>
        <button>
            <img src="https://cdn-icons-png.flaticon.com/512/764/764599.png" alt="pencil">
        </button>
    </ContentHeader>
    <div class="selfAnotation">
        <div class="anotation">
            {{ anotation.text }}
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';

    defineProps({
        anotation: {
            type: Object,
            required: true
        }
    });
</script>

<style scoped>
    button {
        width: 40px;
        height: 40px;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    img {
        width: 26px;
        height: 26px;
        margin: auto;
        transform: translate(1px);
    }

    .selfAnotation {
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