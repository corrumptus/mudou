<template>
	<div class="router" dusk="navigation">
		<button
			@click="() => changeRoute('info')"
			:style="{ color: route === 'info' ? '#00ff03' : 'white' }"
		>
			Informações
		</button>
		<button
			@click="() => changeRoute('disc')"
			:style="{ color: route === 'disc' ? '#00ff03' : 'white' }"
		>
			Disciplinas
		</button>
		<button
			@click="() => changeRoute('moni')"
			:style="{ color: route === 'moni' ? '#00ff03' : 'white' }"
		>
			Monitorias
		</button>
		<button
			@click="() => changeRoute('turm')"
			:style="{ color: route === 'turm' ? '#00ff03' : 'white' }"
		>
			Turmas
		</button>
		<button
			@click="() => changeRoute('perm')"
			:style="{ color: route === 'perm' ? '#00ff03' : 'white' }"
		>
			Permissões
		</button>
	</div>
</template>

<script setup>
	defineProps({
		route: {
			type: String,
			required: true
		},
		changeRoute: {
			type: Function,
			required: true
		}
	});
</script>

<style scoped>
    .router {
        display: flex;
		flex-direction: column;
		align-items: start;
		gap: .3em;
    }

    .router button {
        font-size: 1.15em;
        font-weight: bold;
        cursor: pointer;
    }
</style>
