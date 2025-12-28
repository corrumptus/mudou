<template>
    <BaseLayout>
        <template #header>
            <HomeHeader v-if="groupRef !== null" :pageName="groupRef.title" to="/course-subject" />
            <HomeHeader v-else pageName="" to="/course-subject" />
        </template>

        <template #content>
            <BaseMainLayout>
                <template #navbar>
                    <ClassroomNavBar
                        :classroom="classroom"
                        :classes="classes"
                        :selectedClassElement="{ classId: groupRef.classId, elementId: groupRef.id }"
                        :collapsedClasses="collapsedClasses"
                        :switchCollapse="switchCollapse"
                    />
                </template>

                <template #content>
                    <WithoutGroup
                        v-if="groupRef === null"
                    />
                    <NoGroupContent
                        v-if="groupRef.status === 'noGroup'"
                        :group="groupRef"
                        :create="createGroup"
                        :requestEnter="requestEnter"
                    />
                    <RequestingEnterGroupContent
                        v-if="groupRef.status === 'requesting'"
                        :group="groupRef"
                        :cancel="cancelRequest"
                        :colorFromStatus="colorFromStatus"
                    />
                    <InvitedGroupContent
                        v-if="groupRef.status === 'invited'"
                        :group="groupRef"
                        :accept="acceptGroup"
                        :decline="declineGroup"
                        :colorFromStatus="colorFromStatus"
                    />
                    <InGroupContent
                        v-if="groupRef.status === 'inGroup'"
                        :group="groupRef"
                        :cancel="cancelInviteRequest"
                        :accept="acceptUserRequest"
                        :decline="declineUserRequest"
                        :leave="leaveGroup"
                        :colorFromStatus="colorFromStatus"
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
    import ClassroomNavBar from '@navbar/class/ClassroomNavBar.vue';
    import InGroupContent from '@content/student/group/InGroupContent.vue';
    import InvitedGroupContent from '@content/student/group/InvitedGroupContent.vue';
    import NoGroupContent from '@content/student/group/NoGroupContent.vue';
    import RequestingEnterGroupContent from '@content/student/group/RequestingEnterGroupContent.vue';
    import WithoutGroup from '@content/student/group/WithoutGroup.vue';
    import useCollapsedClasses from '@hooks/collapsedClasses';
    import { create, cancelInvite, request, cancel, accept, decline, acceptRequest, declineRequest, leave } from '@request/student/group';

    const page = usePage();

    const { classroom, classes, group } = page.props;

    const [ collapsedClasses, switchCollapse ] = useCollapsedClasses(classes);

    const groupRef = ref(group);

    function colorFromStatus(status) {
        switch (status) {
            case 'inGroup': return "#32a9ff";
            case 'invited': return "#fffc00";
            case 'requesting': return "#ff5959";
        }
    }

    function createGroup(newGroup) {
        create({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            ...newGroup
        })
            .then(g => {
                alert("Grupo criado com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function cancelInviteRequest(id) {
        cancelInvite({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            userId: id
        })
            .then(g => {
                alert("Convite desfeito com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function requestEnter(groupName) {
        request({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            name: groupName
        })
            .then(g => {
                alert("Pedido enviado com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function cancelRequest() {
        cancel({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            name: groupRef.value.group.name
        })
            .then(g => {
                alert("Pedido cancelado com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function acceptGroup() {
        accept({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id
        })
            .then(g => {
                alert("Convite aceito com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function declineGroup() {
        decline({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id
        })
            .then(g => {
                alert("Convite recusado com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function acceptUserRequest(id) {
        acceptRequest({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            userId: id
        })
            .then(g => {
                alert("Pedido aceito com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function declineUserRequest(id) {
        declineRequest({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id,
            userId: id
        })
            .then(g => {
                alert("Pedido recusado com sucesso");
                groupRef.value = g;
            })
            .catch(alert);
    }

    function leaveGroup() {
        leave({
            ...classroom,
            classId: groupRef.value.classId,
            elementId: groupRef.value.id
        })
            .then(g => {
                alert("Grupo deixado com sucesso");
                groupRef.value = g;
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