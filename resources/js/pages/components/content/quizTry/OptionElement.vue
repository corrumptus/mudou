<template>
    <li>
        <div class="option">
            <input
                type="radio"
                name="question-option"
                :id="`question-option-${id}`"
                @click="e => {
                    if (status !== 'incomplete')
                        e.preventDefault();

                    select();
                }"
                :checked="selected"
            />
            <label
                :for="`question-option-${id}`"
                :style="{ color: status !== 'complete' || !selected ?
                    'black'
                    :
                    option.explain === undefined ?
                        '#005b00'
                        :
                        '#fc0000'
                }"
            >
                {{ option.text }}
            </label>
        </div>
        <div v-if="option.explain !== undefined && selected" class="explain">
            {{ option.explain }}
        </div>
    </li>
</template>

<script setup lang="ts">
    defineProps({
        id: {
            type: String,
            required: true
        },
        option: {
            type: Object,
            required: true
        },
        selected: {
            type: Boolean,
            required: true
        },
        select: {
            type: Function,
            required: true
        },
        status: {
            type: String,
            required: true
        }
    });
</script>

<style scoped>
    .option {
        display: flex;
        align-items: baseline;
        gap: .5em;
    }

    label {
        font-size: 1.15em;
    }

    .explain {
        padding: .25em;
        border-radius: .5em;
        font-size: 1.25em;
        color: white;
        background-color: #fc0000;
    }
</style>