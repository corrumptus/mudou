<template>
    <div>
        <button @click="switchCollapse">
            <span>{{ classElement.name }}</span>
            <img
                src="https://cdn-icons-png.freepik.com/512/32/32213.png"
                alt="minimalist arrow"
                :style="{ transform: `rotateZ(${isCollapsed ? '-90' : '90'}deg)` }"
            >
        </button>
        <ul ref="classElementsRef" :style="{ height: isCollapsed ? '0px' : `${classElementsHeightRef}px` }">
            <ClassElement
                v-for="e in classElement.elements"
                :element="e"
                :selected="
                    classElement.id == selectedClassElement.classId
                    &&
                    e.id == selectedClassElement.elementId
                "
                :toElement="(type, id) => toElement(classElement.id, type, id)"
            />
        </ul>
    </div>
</template>

<script setup>
    import { onMounted, ref } from 'vue';
    import ClassElement from '@navbar/class/ClassElement.vue';

    defineProps({
        classElement: {
            type: Object,
            required: true
        },
        selectedClassElement: {
            type: Object,
            required: true
        },
        toElement: {
            type: Function,
            required: true
        },
        isCollapsed: {
            type: Boolean,
            required: true
        },
        switchCollapse: {
            type: Function,
            required: true
        }
    });

    const classElementsHeightRef = ref(0);
    const classElementsRef = ref();

    onMounted(() => {
        classElementsHeightRef.value = classElementsRef.value.scrollHeight;
    });
</script>

<style scoped>
    button {
        width: 100%;
        padding: .3em;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    button:hover {
        background-color: #777777;
    }

    button span {
        font-size: 1.25em;
    }

    button img {
        width: 25px;
        height: 25px;
        aspect-ratio: 1/1;
        transition: transform .3s linear;
        filter: invert(100%);
    }

    ul {
        padding-left: .5em;
        display: flex;
        flex-direction: column;
        gap: .5em;
        transition: height .3s linear;
        overflow: hidden;
    }
</style>