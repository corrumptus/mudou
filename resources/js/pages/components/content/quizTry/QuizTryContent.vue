<template>
    <ContentHeader>
        <span class="cQuestion" v-if="question !== undefined">{{ index+1 }}</span>
    </ContentHeader>
    <div class="quizTry">
        <div class="description" v-if="question !== undefined">
            <Description
                v-for="(d, i) in question.description"
                :type="d.type"
                :value="d.value"
                :key="i"
            />
        </div>
        <div class="options">
            <ul v-if="question !== undefined">
                <OptionElement
                    v-for="[ id, o ] of Object.entries(question.options)"
                    :id="id"
                    :option="o as object"
                    :selected="
                        selectedOption !== undefined
                        &&
                        selectedOption === id
                    "
                    :select="() => selectOption(id)"
                    :status="status"
                    :key="id"
                />
            </ul>
        </div>
        <div class="button" v-if="nextIterator.hasNext">
            <button @click="nextIterator.next">Próxima</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
    import Description from '@content/quizTry/Description.vue';
    import OptionElement from '@content/quizTry/OptionElement.vue';

    defineProps({
        question: {
            type: Object
        },
        index: {
            type: Number,
            required: true
        },
        selectOption: {
            type: Function,
            required: true
        },
        selectedOption: {
            type: String
        },
        status: {
            type: String,
            required: true
        },
        nextIterator: {
            type: Object,
            required: true
        }
    });
</script>

<style scoped>
    .cQuestion {
        font-size: 1.6em;
        font-weight: bold;
    }

    .quizTry {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: .7em;
        overflow: auto;
    }

    .description {
        font-size: 1.25em;
    }

    .button {
        margin-top: auto;
        display: flex;
        justify-content: center;
    }

    button {
        padding-inline: 1em;
        border-radius: .8em;
        font-size: 1.25em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }
</style>