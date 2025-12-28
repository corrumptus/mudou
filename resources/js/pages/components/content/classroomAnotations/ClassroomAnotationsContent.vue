<template>
    <ContentHeader />
    <div class="anotations">
        <div class="minha">
            <h1>Minha</h1>
            <div class="cards">
                <AnotationCard
                    v-if="existentAnotations.hasSelf"
                    :text="'Minha'"
                    :img="existentAnotations.self.img"
                />
            </div>
        </div>
        <div class="monitores">
            <h1>Monitores</h1>
            <div class="cards" v-if="existentAnotations.hasMonitors">
                <AnotationCard
                    v-for="m in existentAnotations.monitors"
                    :text="m.name"
                    :img="m.img"
                    :key="m.id"
                />
            </div>
        </div>
        <div class="publico">
            <h1>Público</h1>
            <div class="cards">
                <AnotationCard
                    v-if="existentAnotations.hasPublic"
                    :text="'Público'"
                    :img="'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Globe_icon.svg/420px-Globe_icon.png'"
                    :alt="'world icon(circle divided by meridians and tropics)'"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
    import AnotationCard from '@content/classroomAnotations/AnotationCard.vue';

    defineProps({
        existentAnotations: {
            type: Object,
            required: true
        }
    });
</script>

<style scoped>
    .anotations {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 2em;
        overflow: auto;
    }

    .monitores .cards {
        display: flex;
        gap: 1em;
        flex-wrap: wrap;
    }

    h1 {
        font-size: 1.25em;
        font-weight: bold;
        text-align: center;
    }
</style>