<template>
    <ContentHeader />
    <div class="discussion">
        <Comment
            :person="discussion.owner"
            :text="discussion.description"
        />
        <div class="comments">
            <p>{{ discussion.comments.length }} Respostas</p>
            <ul>
                <li
                    v-for="c in discussion.comments"
                    :key="c.id"
                >
                    <Comment
                        :person="c.user"
                        :text="c.comment"
                    />
                </li>
            </ul>
        </div>
        <div class="send">
            <input
                placeholder="Comentário"
                :value="comment"
                @input="e => comment = (e.target as HTMLInputElement).value"
            />
            <button @click="() => send(comment)">
                <img src="https://static.thenounproject.com/png/105496-200.png" alt="airplane paper">
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import ContentHeader from '@header/ContentHeader.vue';
    import Comment from '@content/general/discussion/Comment.vue';

    defineProps({
        discussion: {
            type: Object,
            required: true
        },
        send: {
            type: Function,
            required: true
        }
    });

    const comment = ref("");
</script>

<style scoped>
    .discussion {
        height: 100%;
        padding: 1em;
        display: flex;
        flex-direction: column;
        gap: 1em;
        overflow: auto;
        position: relative;
    }

    .comments {
        display: flex;
        flex-direction: column;
        gap: .3em;
    }

    p {
        font-size: 1.25em;
    }

    ul {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .send {
        width: 100%;
        display: flex;
        gap: .5em;
        position: sticky;
        bottom: 0;
    }

    input {
        width: 100%;
        padding: .5em;
        border-radius: 1em;
        background-color: #2c2a2a;
        color: white;
    }

    input::placeholder {
        color: white;
        opacity: 1;
    }

    button {
        padding: .5em;
        border-radius: 100%;
        background-color: #2c2c2c;
        cursor: pointer;
        flex-shrink: 0;
    }

    img {
        width: 24px;
        filter: invert(1);
        transform: translate(2px);
    }
</style>