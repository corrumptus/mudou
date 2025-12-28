<template>
    <aside>
        <div class="sideBar">
            <p v-if="quizTry.hasMaxTime">
                {{ printTime() }}
            </p>
            <ul>
                <li
                    v-for="[ i, q ] of Object.entries(quizTry.questions).entries()"
                    :style="{
                        cursor: currentQuestion === q[0] ? 'unset' : 'pointer',
                        backgroundColor: getColor(q[0])
                    }"
                    @click="setCurrentQuestion(q[0])"
                >
                    {{ i+1 }}
                </li>
            </ul>
        </div>
        <div class="send" v-if="quizTry.status === 'incomplete'">
            <button>
                Enviar
            </button>
        </div>
    </aside>
</template>

<script setup>
    import { onBeforeUnmount, onMounted, ref } from 'vue';

    const props = defineProps({
        quizTry: {
            type: Object,
            required: true
        },
        currentQuestion: {
            type: String
        },
        setCurrentQuestion: {
            type: Function,
            required: true
        },
        selectedOptions: {
            type: Object,
            required: true
        }
    });

    function getColor(id) {
        switch (props.quizTry.status) {
            case "incomplete":
                return props.selectedOptions[id] !== undefined ? 
                    "#32a9ff"
                    :
                    "#b4b4b4";
            case "blocked":
                return "#b4b4b4";
            case "complete":
                return props.quizTry.questions[id]
                    .options[props.selectedOptions[id]].explain === undefined ?
                    "#00ff03"
                    :
                    "#fc0000";
        }
    }

    function timeToNumber() {
        if (!props.quizTry.hasMaxTime) {
            return {
                seconds: 0,
                minutes: 0,
                hours: 0
            }
        }

        const stringNumbers = props.quizTry.maxTime.split(":").map(Number);

        if (stringNumbers.length == 2) {
            return {
                seconds: stringNumbers[1],
                minutes: stringNumbers[0],
                hours: 0
            };
        }

        return {
            seconds: stringNumbers[2],
            minutes: stringNumbers[1],
            hours: stringNumbers[0]
        };
    }

    function printTime() {
        const seconds = String(timerRef.value.seconds).padStart(2, '0');
        const minutes = String(timerRef.value.minutes).padStart(2, '0');

        if (timerRef.value.hours === 0)
            return `${minutes}:${seconds}`;

        return `${timerRef.value.hours}:${minutes}:${seconds}`;
    }

    const timerRef = ref(timeToNumber());
    const intervalRef = ref();

    onMounted(() => {
        intervalRef.value = setInterval(() => {
            if (
                timerRef.value.seconds === 0
                &&
                timerRef.value.minutes === 0
                &&
                timerRef.value.hours === 0
            ) {
                clearInterval(intervalRef.value);
                return;
            }

            if (
                timerRef.value.seconds === 0
                &&
                timerRef.value.minutes === 0
                &&
                timerRef.value.hours > 0
            ) {
                timerRef.value.minutes = 60;
                timerRef.value.hours--;
            }

            if (timerRef.value.seconds === 0)
                timerRef.value.seconds = 60;

            timerRef.value.seconds--;

            if (timerRef.value.seconds === 59 && timerRef.value.minutes > 0)
                timerRef.value.minutes--;
        }, 1000);
    });

    onBeforeUnmount(() => {
        clearInterval(intervalRef.value);
    });
</script>

<style scoped>
    aside {
        height: 100%;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        gap: .5em;
        overflow: auto;
    }

    .sideBar {
        min-width: 59px;
    }

    p {
        font-size: 1.25em;
        font-weight: bold;
        text-align: center;
    }

    ul {
        width: max-content;
        max-width: 136px;
        display: flex;
        flex-wrap: wrap;
        gap: .5em;
    }

    li {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 1.25em;
        font-weight: bold;
    }

    .send {
        margin-top: auto;
        display: flex;
        justify-content: center;
    }

    button {
        padding-inline: 1em;
        border-radius: 0.8em;
        font-size: 1.25em;
        background-color: #00ff03;
        cursor: pointer;
    }
</style>