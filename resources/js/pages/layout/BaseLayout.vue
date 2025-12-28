<template>
    <slot name="header"></slot>
    <slot name="content"></slot>
</template>

<style>
    body {
        width: 100vw;
        height: 100vh;
        background-color: #191919;
        color: white;
    }

    div#app {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
</style>