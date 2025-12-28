<template>
    <ContentHeader />
    <div class="homework">
        <div class="description">
            <p>{{ homework.description }}</p>
        </div>
        <div class="timer">
            <p>Abre em: <span>{{ formatDate(homework.beginDate) }}</span></p>
            <p>fecha em: <span>{{ formatDate(homework.closeDate) }}</span></p>
            <p v-if="timerRef === 'beginsIn'">
                tempo até abrir: <span>{{ counterRef }}</span>
            </p>
            <p v-if="timerRef === 'endsIn'">
                tempo até fechar: <span>{{ counterRef }}</span>
            </p>
            <p v-if="timerRef === 'endedAt'">
                fechada desde de: <span>{{ counterRef }}</span>
            </p>
        </div>
        <div v-if="homework.group !== ''" class="group">
            <p>Grupo: <span>{{ homework.group }}</span></p>
        </div>
        <div v-if="homework.isText" class="text">
            <textarea
                placeholder="responda aqui"
                :value="response.text"
                @input="changeText"
            ></textarea>
        </div>
        <div v-if="homework.isFile" class="file">
            <!-- <FileInput  /> -->
            <input
                type="file"
                :accept="homework.fileTypes"
                :multiple="homework.maxAmountFiles > 1"
                @change="changeFile"
            >
        </div>
        <div v-if="timerRef === 'endsIn' || homework.canAcceptAfterClose" class="send">
            <button @click="() => answer(response)">Enviar</button>
        </div>
    </div>
</template>

<script setup>
    import { onBeforeUnmount, onMounted, ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';
    // import FileInput from '@content/student/homework/FileInput.vue';
    import { formatDate, timeDiff } from '@utils/dateTime';

    const props = defineProps({
        homework: {
            type: Object,
            required: true
        },
        response: {
            type: Object,
            required: true
        },
        answer: {
            type: Function,
            required: true
        }
    });

    const timerRef = ref(correctTimer());
    const counterRef = ref(getTimeDiff(timerRef.value));
    let timer;

    const response = ref({
        ...(props.homework.isText && (props.response.text !== null ? { text: props.response.text } : { text: "" })),
        ...(props.homework.isFile && (props.response.files !== null ? { files: props.response.files } : { files: [] }))
    });

    function correctTimer() {
        const now = new Date().getTime();

        if (now < props.homework.beginDate.getTime())
            return "beginsIn";

        if (now < props.homework.closeDate.getTime())
            return "endsIn";

        return "endedAt";
    }

    function getTimeDiff(timerType) {
        return {
            beginsIn: () => timeDiff(new Date(), props.homework.beginDate),
            endsIn: () => timeDiff(new Date(), props.homework.closeDate),
            endedAt: () => timeDiff(props.homework.closeDate, new Date())
        }[timerType]();
    }

    function changeText(e) {
        response.value.text = e.target.value;
    }

    function changeFile(e) {
        response.value.files = Array.from(e.target.files);
    }

    onMounted(() => {
        timer = setInterval(() => {
            const newTimer = correctTimer();
            timerRef.value = newTimer;
            counterRef.value = getTimeDiff(newTimer);
        }, 1000);
    });

    onBeforeUnmount(() => {
        clearInterval(timer);
    });
</script>

<style scoped>
    .homework {
        height: 100%;
        padding: .5em 1em;
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: .5em;
        overflow: auto;
    }

    p {
        font-size: 1.25em;
    }

    .timer span {
        font-weight: bold;
    }

    .group span {
        color: blue;
        cursor: pointer;
    }

    .text {
        width: 100%;
        display: flex;
    }

    .text textarea {
        width: 100%;
        height: 150px;
        padding: .3em .5em;
        box-shadow: inset 0px 0px 6px #868585;
        border-radius: 10px;
        resize: none;
    }

    .file {
        width: 100%;
        display: flex;
    }

    .send {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .send button {
        padding: .1em 1.5em;
        border-radius: 1em;
        font-weight: bold;
        color: white;
        background-color: #00ff03;
        cursor: pointer;
    }
</style>