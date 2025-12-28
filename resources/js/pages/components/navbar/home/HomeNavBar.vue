<template>
    <nav>
        <button
            v-if="userTypes.isAdmin"
            :style="{ color: page === 'Administração' ? '#00ffff' : 'white' }"
        >
            Administração
        </button>
        <button
            v-if="userTypes.isTeacher"
            :style="{ color: page === 'Professor' ? '#00ffff' : 'white' }"
        >
            Professor
        </button>
        <button
            v-if="userTypes.isStudent"
            :style="{ color: page === 'Disciplinas' ? '#00ffff' : 'white' }"
        >
            Disciplinas
        </button>
        <button
            v-if="userTypes.isMonitor"
            :style="{ color: page === 'Monitoria' ? '#00ffff' : 'white' }"
        >
            Monitoria
        </button>
        <button
            v-if="userTypes.isStudent"
            :style="{ color: page === 'Anotações' ? '#00ffff' : 'white' }"
        >
            Anotações
        </button>
    </nav>
</template>

<script setup>
    import { usePage } from '@inertiajs/vue3';

    const props = defineProps({
        page: {
            type: String,
            required: true
        }
    });

    const currentPage = usePage();

    const { userTypes } = currentPage.props;
</script>

<style scoped>
    nav {
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: start;
    }

    button {
        font-size: 1.5em;
        font-weight: bold;
        cursor: pointer;
    }
</style>