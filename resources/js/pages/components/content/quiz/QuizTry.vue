<template>
    <tr>
        <td
            :style="{
                color: quizTry.linkable ? '#23048b' : 'black',
                cursor: quizTry.linkable ? 'pointer' : 'unset'
            }"
        >
            Tentativa {{ index }}
        </td>
        <td>
            {{ quizTry.score === undefined ?
                "-"
                :
                `${quizTry.score.value}/${quizTry.score.base}`
            }}
        </td>
        <td>
            <div
                :style="{
                    backgroundColor: getStatusColor(quizTry.status)
                }"
            >
                {{ getStatus(quizTry.status) }}
            </div>
        </td>
    </tr>
</template>

<script setup lang="ts">
    defineProps({
        quizTry: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        }
    });

    function getStatus(s: "complete" | "blocked" | "incomplete") {
        return {
            complete: "Completa",
            blocked: "Bloqueado",
            incomplete: "Incomplete"
        }[s];
    }

    function getStatusColor(s: "complete" | "blocked" | "incomplete") {
        return {
            complete: "#00ff03",
            blocked: "#fc0000",
            incomplete: "#fffc00"
        }[s];
    }
</script>

<style scoped>
    td {
        text-align: center;
    }

    div {
        width: 130px;
        margin: auto;
        padding: .2em;
        border-radius: 1.2em;
    }
</style>