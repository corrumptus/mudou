<template>
    <ContentHeader>
        <div class="buttons">
            <button class="files">
                <img src="https://cdn-icons-png.flaticon.com/512/659/659774.png" alt="clips">
            </button>
            <button class="share" :style="{ backgroundColor: anotation.isPublic ? '#00ff03' : '#fc0000' }">
                <img src="https://cdn-icons-png.flaticon.com/512/107/107784.png" alt="share">
            </button>
        </div>
    </ContentHeader>
    <div class="editSelfAnotation">
        <div class="classElementLink">
            <select>
                <option
                    v-for="e in elements"
                    :value="e.name"
                    :key="e.id"
                >
                    {{ e.name }}
                </option>
            </select>
            <button class="link">
                <img src="https://www.freeiconspng.com/uploads/arrow-icon-28.png" alt="arrow">
            </button>
        </div>
        <div class="text">
            <textarea
                :value="anotation.text"
                @input="e => anotation.text = e.target.value"
            ></textarea>
        </div>
        <div class="buttons">
            <button class="save">Salvar</button>
            <button class="cancel">Cancelar</button>
        </div>
    </div>
</template>

<script setup lang="js">
    import ContentHeader from '@header/ContentHeader.vue';

    const props = defineProps({
        classes: {
            type: Array,
            required: true
        },
        anotation: {
            type: Object,
            required: true
        }
    });

    const elements = props.classes
        .reduce((acc, cur) => {
            return acc.concat(cur.elements.map(e => ({ id: e.id, name: e.name})));
        }, []);
</script>

<style scoped>
    .buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5em;
    }

    .files, .share {
        width: 40px;
        height: 40px;
        border-radius: 100%;
        background-color: #b4b4b4;
        cursor: pointer;
    }

    .files img, .share img {
        width: 28px;
        height: 28px;
        margin: auto;
    }

    .share img {
        transform: translate(-2px);
    }

    .editSelfAnotation {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: hidden;
    }

    .classElementLink {
        display: flex;
        align-items: center;
        gap: .5em;
    }

    select {
        width: 100%;
        padding: .3em;
        border-radius: 1em;
        font-size: 1.25em;
        color: white;
        background-color: #2c2a2a;
        cursor: pointer;
    }

    .link {
        width: 40px;
        height: 40px;
        cursor: pointer;
    }

    .link img {
        transform: rotate(90deg);
    }

    .text {
        height: 100%;
    }

    textarea {
        width: 100%;
        height: 100%;
        padding: .3em .6em;
        border-radius: 1em;
        box-shadow: inset 0 0 3px black;
        resize: none;
    }

    .save, .cancel {
        padding-inline: 1em;
        border-radius: 0.8em;
        font-size: 1.25em;
        color: white;
        cursor: pointer;
    }

    .save {
        background-color: #00ff03;
    }

    .cancel {
        background-color: #fc0000;
    }
</style>