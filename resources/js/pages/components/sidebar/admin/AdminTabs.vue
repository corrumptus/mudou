<template>
    <div>
        <button
            :style="{ color: route === 'people' ? '#00ff03' : 'white' }"
            @click="() => select('/admin/people')"
        >
            Pessoas
        </button>
        <button
            :style="{ color: route === 'courses' ? '#00ff03' : 'white' }"
            @click="() => select('/admin/courses')"
        >
            Cursos
        </button>
        <button
            :style="{ color: route === 'subjects' ? '#00ff03' : 'white' }"
            @click="() => select('/admin/subjects')"
        >
            Diciplinas
        </button>
        <button
            :style="{ color: route === 'classrooms' ? '#00ff03' : 'white' }"
            @click="() => select('/admin/classrooms')"
        >
            Turmas
        </button>
        <button
            :style="{ color: route === 'permissions' ? '#00ff03' : 'white' }"
            @click="() => select('Permissões')"
        >
            Permissões
        </button>
    </div>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';

    defineProps({
        route: {
            type: String,
            required: true
        }
    });

    function select(route) {
        router.visit(route);
    }
</script>

<style scoped>
    div {
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: .3em;
    }

    button {
        font-size: 1.15em;
        font-weight: bold;
        cursor: pointer;
    }
</style>