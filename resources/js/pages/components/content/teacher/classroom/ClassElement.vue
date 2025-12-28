<template>
    <div class="class">
        <h3>Aula {{ index+1 }} - {{ classElement.name }}</h3>
        <ul>
            <li
                v-for="(element, i) in classElement.elements"
                @click="() => toElement(element.type, element.elementId)"
            >
                <img
                    :src="imgTypes[i].url"
                    :alt="imgTypes[i].alt"
                >
                <span>{{ element.name }}</span>
            </li>
        </ul>
        <button
            @click="click"
            class="to-new-class-element"
        >
            <img
                src="https://www.iconpacks.net/icons/2/free-plus-icon-3107-thumb.png"
                alt="plus"
            >
        </button>
        <div
            v-if="isNewClassElementVisible || isTextMenuVisible"
            class="window"
            @click.stop="windowClick"
        >
            <div
                v-if="isNewClassElementVisible"
                class="menu"
                :style="{
                    top: `${position.top}px`,
                    left: `${position.left}px`
                }"
            >
                <button>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/File_alt_font_awesome.svg/512px-File_alt_font_awesome.svg.png" alt="file icon(paper with a folded tip)">
                    <span>Arquivo</span>
                </button>
                <button>
                    <img src="https://cdn-icons-png.flaticon.com/512/341/341017.png" alt="2 pieces of a chain">
                    <span>Link</span>
                </button>
                <button @click="() => toNew(classElement.id, 'homework')">
                    <img src="https://cdn-icons-png.flaticon.com/512/2196/2196468.png" alt="a book with a pencil on top of it">
                    <span>Tarefa</span>
                </button>
                <button>
                    <img src="https://cdn-icons-png.flaticon.com/512/8940/8940602.png" alt="each letter of quiz in a box in a grid of 2x2">
                    <span>Quiz</span>
                </button>
                <button @click="() => toNew(classElement.id, 'group')">
                    <img src="https://cdn-icons-png.flaticon.com/512/681/681443.png" alt="3 people">
                    <span>Grupo</span>
                </button>
            </div>
            <div
                v-if="isMenuTextVisible"
                class="menu-text"
                :style="{
                    top: `${position.top}px`,
                    left: `${position.left}px`
                }"
            >
                <div>
                    <label for="name">Nome</label>
                    <input id="name">
                </div>
                <div v-if="type === 'arquivo'">
                    <label for="file">Arquivo</label>
                    <input type="file" id="file">
                </div>
                <div v-else>
                    <label for="link">Link</label>
                    <input id="link">
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';

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
        },
        toNew: {
            type: Function,
            required: true
        }
    });

    const isNewClassElementVisible = ref(false);
    const isTextMenuVisible = ref(false);
    const position = ref({ top: 0, left: 0 });

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

    function click(e) {
        isNewClassElementVisible.value = true;

        position.value.top = e.clientY;
        position.value.left = e.clientX;
    }

    function windowClick(e) {
        if (e.target.className === "window") {
            isNewClassElementVisible.value = false;
            isTextMenuVisible.value = false;
        }
    }
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

    .to-new-class-element {
        display: none;
        width: 100%;
        padding: .2em;
        border-radius: .5em;
        justify-content: center;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .class:hover .to-new-class-element {
        display: flex;
    }

    .window {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .menu {
        padding: .5em;
        border-radius: .5em;
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: .5em;
        position: absolute;
        background-color: gray;
    }

    .menu button {
        width: 100%;
        padding: .2em;
        border-radius: .4em;
        display: flex;
        align-items: center;
        gap: .5em;
        cursor: pointer;
    }

    .menu button:hover {
        background-color: #b4b4b4;
    }

    .menu img {
        width: 30px;
        filter: invert(1);
    }

    .menu span {
        font-size: 1.25em;
        color: white;
    }
</style>