<template>
    <div>
        <h3>Aula {{ index+1 }} - {{ classElement.name }}</h3>
        <ul>
            <li
                v-for="(element, i) in classElement.elements"
                @click="() => toElement(classElement.id, element.type, element.elementId)"
                dusk="class-element"
            >
                <img
                    :src="imgTypes[i].url"
                    :alt="imgTypes[i].alt"
                >
                <span>{{ element.name }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup>
    const props = defineProps({
        index: {
            type: Number,
            required: true
        },
        classElement: {
            type: Object,
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
    };

    const imgTypes = props.classElement.elements.map(e => imgType[e.type]);
</script>

<style scoped>
    h3 {
        font-size: 1.25em;
        font-weight: bold;
    }

    ul {
        padding-left: 1.2em;
    }

    li {
        padding: .3em;
        border-radius: 10px;
        display: flex;
        gap: .5em;
        cursor: pointer;
    }

    img {
        width: 30px;
    }

    span {
        font-size: 1.25em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>