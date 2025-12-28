<template>
    <ContentHeader />
    <div class="quiz">
        <div class="time">
            <p>
                Abre em: <span>{{ formatDate(quiz.beginDate) }}</span>
            </p>
            <p>
                fecha em: <span>{{ formatDate(quiz.endDate) }}</span>
            </p>
            <p :style="{ display: quiz.hasMaxTime ? 'block' : 'none' }">
                Tempo máximo: <span>{{ quiz.maxTime }}</span>
            </p>
        </div>
        <div class="homeworkTrys">
            <p :style="{ display: hasStudyTrys ? 'block' : 'none' }">
                Tentativas de tarefa
            </p>
            <table>
                <thead>
                    <th>Tentativa</th>
                    <th>Nota</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <QuizTry
                        v-for="[ i, t ] of
                            quiz.trys
                                .filter((t: Try) => t.type === 'homework')
                                .entries()
                        "
                        :quizTry="t"
                        :index="i"
                        :key="i"
                    />
                </tbody>
            </table>
        </div>
        <div
            class="studyTrys"
            :style="{ display: hasStudyTrys ? 'block' : 'none' }"
        >
            <p>
                Tentativas de estudo
            </p>
            <table>
                <thead>
                    <th>Tentativa</th>
                    <th>Nota</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <QuizTry
                        v-for="[ i, t ] of
                            quiz.trys
                                .filter((t: Try) => t.type === 'study')
                                .entries()
                        "
                        :quizTry="t"
                        :index="i"
                        :key="i"
                    />
                </tbody>
            </table>
        </div>
        <div class="try">
            <button>Tentar</button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import ContentHeader from '@header/ContentHeader.vue';
    import QuizTry from '@content/quiz/QuizTry.vue';
    import { formatDate } from '@utils/dateTime';

    type Try = {
        id: number,
        type: string
    }

    const props = defineProps({
        quiz: {
            type: Object,
            required: true
        }
    });

    const hasStudyTrys = props.quiz.trys.findLast((t: Try) => t.type === "study");
</script>

<style scoped>
    .quiz {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: .7em;
        overflow: auto;
    }

    .time {
        font-size: 1.25em;
    }

    .time span {
        font-weight: bold;
    }

    .homeworkTrys p, .studyTrys p {
        font-size: 1.2em;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: .5em;
    }

    .try {
        display: flex;
        justify-content: center;
    }

    button {
        padding: .2em 1.5em;
        border-radius: 1em;
        font-size: 1.2em;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }
</style>