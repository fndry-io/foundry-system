<template>
    <panel>
        <template>
        <p>{{title}}</p>
        <p><b-button @click="$emit('dirty', true)">Dirty</b-button></p>
        <p><b-button @click="() => this.$panel({
                    title: 'Test',
                    size: 'lg',
                    component: 'fndry-panel-events'})">Add</b-button></p>
        <p v-for="n in 100">okj oekrjfokejr fokjerokgj eokrjg oekjrg </p>
        </template>
        <template v-slot:footer>
            <b-button @click="$emit('event', 'cancel', {bob: 1})">Cancel</b-button>
            <b-button @click="$emit('event', 'success', {bob: 1})">Success</b-button>
        </template>
    </panel>
</template>

<script>
    import Panel from "./Panel";

    export default {
        name: "Events",
        components: {
            Panel
        },
        props: {
            title: String
        }
    }
</script>
