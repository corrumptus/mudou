<template>
    <main>
        <slot name="navbar"></slot>
        <section>
            <slot name="content"></slot>
        </section>
        <slot name="sidebar"></slot>
    </main>
</template>

<style scoped>
    main {
        width: 100%;
        height: 100%;
        padding: .5em;
        display: flex;
        gap: 10px;
        overflow: hidden;
    }
    
    section {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        background-color: #fdfdfd;
        color: black;
        overflow: hidden;
    }
</style>