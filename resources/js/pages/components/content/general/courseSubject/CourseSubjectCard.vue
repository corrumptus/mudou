<template>
    <div
        class="card"
        :style="{ backgroundColor: subject.color }"
        @click="goToClassroom"
        dusk="subject"
    >
        <div class="code">
            <span>{{ subject.code }}</span>
        </div>
        <div class="name" ref="root">
            {{ subject.name }}
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
    import { router } from '@inertiajs/vue3';
    // @ts-expect-error
    import clampJs from 'clamp-js';

    const props = defineProps({
        subject: {
            type: Object,
            required: true
        },
        type: {
            type: String,
            required: true
        }
    });

    function goToClassroom() {
        if (props.type === "student")
            router.visit(`/course/${props.subject.course}/subject/${props.subject.name}/classroom/${props.subject.classroomId}`);
        else
            router.visit(`/teacher/course/${props.subject.course}/subject/${props.subject.name}/classroom/${props.subject.classroomId}`);
    }

    const root = ref();
    let ro: ResizeObserver;
    let io: IntersectionObserver;

    async function applyClamp() {
        if (!root.value) return;

        root.value.innerText = props.subject.name;

        await nextTick();

        clampJs(root.value, { clamp: 3 });
    }

    onMounted(() => {
        // Resize local (melhor que window.resize para listas grandes)
        ro = new ResizeObserver(applyClamp);
        ro.observe(root.value);

        // Só clampa quando aparecer no viewport
        io = new IntersectionObserver((entries) => {
            const visible = entries.some(e => e.isIntersecting);

            if (visible)
                applyClamp();
        }, { rootMargin: '64px' });
        io.observe(root.value);

        applyClamp();
    });

    onBeforeUnmount(() => {
        ro?.disconnect();
        io?.disconnect();
    });
</script>

<style scoped>
    .card {
        width: 150px;
        height: 180px;
        padding: 10px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .code {
        width: 75px;
        height: 75px;
        padding: 5px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background-color: white;
        font-size: 1.25em;
        font-weight: bold;
    }

    .code span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .name {
        margin: auto;
        text-align: center;
    }
</style>