<template>
    <BaseLayout>
        <template #header>
            <HomeHeader :pageName="`Chat${chatRef !== undefined ? ` - ${chats[chatRef].entity.name}` : ''}`" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ChatNavBar
                        :chats="chats"
                        :selectedChat="chatRef"
                        :select="i => chatRef = i"
                    />
                </template>

                <template #content>
                    <ChatContent
                        :messages="chats[chatRef]?.messages"
                        :userName="userName"
                    />
                </template>
            </BaseMainLayout>
        </template>
    </BaseLayout>
</template>

<script setup>
    import { ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import BaseLayout from '@layout/BaseLayout.vue';
    import BaseMainLayout from '@layout/BaseMainLayout.vue';
    import HomeHeader from '@header/HomeHeader.vue';
    import ChatNavBar from '@navbar/chat/ChatNavBar.vue';
    import ChatContent from '@content/general/chat/ChatContent.vue';

    const page = usePage();

    const { chats, userName } = page.props;

    const chatRef = ref();
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>