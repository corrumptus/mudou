<template>
    <li
        :style="{ backgroundColor: selected ? '#555555' : 'transparent' }"
        @click="() => toElement(element.type, element.elementId)"
    >
        <img
            :src="imgType.url"
            :alt="imgType.alt"
        >
        <span>{{ element.name }}</span>
    </li>
</template>

<script setup>
    const props = defineProps({
        element: {
            type: Object,
            required: true
        },
        selected: {
            type: Boolean,
            required: true
        },
        toElement: {
            type: Function,
            required: true
        }
    });

    const imgType = {
        file: {
            url: "https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/File_alt_font_awesome.svg/512px-File_alt_font_awesome.svg.png",
            alt: "file icon(paper with a folded tip)"
        },
        link: {
            url: "https://cdn-icons-png.flaticon.com/512/341/341017.png",
            alt: "2 pieces of a chain"
        },
        text: {
            url: "https://cdn-icons-png.freepik.com/512/74/74522.png",
            alt: "a letter t"
        },
        poll: {
            url: "https://cdn-icons-png.flaticon.com/512/1246/1246239.png",
            alt: "a graph of the poll results"
        },
        homework: {
            url: "https://cdn-icons-png.flaticon.com/512/2196/2196468.png",
            alt: "a book with a pencil on top of it"
        },
        group: {
            url: "https://cdn-icons-png.flaticon.com/512/681/681443.png",
            alt: "3 people"
        },
        quiz: {
            url: "https://cdn-icons-png.flaticon.com/512/8940/8940602.png",
            alt: "each letter of quiz in a box in a grid of 2x2"
        }
    }[props.element.type];
</script>

<style scoped>
    li {
        padding: .3em;
        border-radius: 10px;
        display: flex;
        gap: .5em;
        cursor: pointer;
    }

    li:hover {
        background-color: #777777;
    }

    img {
        width: 25px;
        filter: invert(100%);
    }

    span {
        font-size: 1.25em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>