<template>
    <div class="img-input" @click.stop="click">
        <input
            :id="id"
            :dusk="id"
            type="file"
            accept=".png, .jpg, .jpeg, .webp"
            ref="img"
            @change="selectFile"
        >
        <div v-if="url !== undefined" class="base-img">
            <img v-if="url !== undefined" :src="url">
            <div class="real-img">
                <img v-if="url !== undefined" :src="url">
            </div>
        </div>
        <div v-else class="no-img">
            Selecione uma imagem
        </div>
    </div>
</template>

<script setup lang="ts">
    import { onMounted, ref, watch } from 'vue';

    const props = defineProps({
        id: {
            type: String
        },
        value: {
            type: Blob
        },
        change: {
            type: Function,
            required: true
        }
    });

    const img = ref();
    const url = ref();

    function click(e: MouseEvent) {
        let element = e.target as HTMLElement;

        while (element.className !== "img-input")
            element = element.parentElement!;

        element.querySelector("input")!.click();
    }

    function selectFile(e: Event) {
        url.value = URL.createObjectURL((e.target as HTMLInputElement).files![0]);

        props.change(e);
    }

    function setBlob(blobFile?: Blob) {
        if (blobFile === undefined)
            return;

        const file = new File([blobFile], "profile-pic", {
            type: blobFile.type || 'application/octet-stream'
        });

        const dt = new DataTransfer();

        dt.items.add(file);

        img.value.files = dt.files;

        url.value = URL.createObjectURL(file);
    }

    onMounted(() => {
        setBlob(props.value);
    });

    watch(
        () => props.value,
        (newBlob) => {
            setBlob(newBlob);
        }
    )
</script>

<style scoped>
    .img-input {
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        cursor: pointer;
    }

    input {
        display: none;
    }

    .base-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .base-img > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .4;
    }

    .real-img {
        width: 100%;
        height: 100%;
        border: 3px black;
        border-style: ridge;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: absolute;
    }

    .real-img > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-img {
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>