<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="discussionRef !== null" :pageName="discussionRef.title" />
            <HomeHeader v-else pageName="" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: '', elementId: '' }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <DiscussionContent
                        v-if="discussionRef !== null"
                        :key="switchBoolean"
                        :discussion="discussionRef"
                        :send="send"
                    />
                    <WithoutDiscussion v-else />
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
    import ClassroomNavBar from '@navbar/class/ClassroomNavBar.vue';
    import DiscussionContent from '@content/general/discussion/DiscussionContent.vue';
    import WithoutDiscussion from '@content/general/discussion/WithoutDiscussion.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create } from '@request/general/discussionComment';

    const page = usePage();

    const { classroom, classes, discussion } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const discussionRef = ref(discussion);

    const switchBoolean = ref(false);

    function send(comment) {
        create({
            ...classroom,
            discussionId: discussion.id,
            comment: comment
        })
            .then(c => {
                discussionRef.value.comments.unshift(c);

                alert("Comentário criado com sucesso");
            })
            .catch(alert);
    }
</script>

<style>
    section {
        display: flex;
        flex-direction: column;
    }
</style>