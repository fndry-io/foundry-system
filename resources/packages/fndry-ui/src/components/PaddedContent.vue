<template>
    <div class="text-center p-5">
        <slot v-if="raw"><p class="lead mb-0">No results found!</p></slot>
        <div v-else><p class="lead mb-0"><slot>No results found!</slot></p></div>
    </div>
</template>

<script>
    export default {
        name: "PaddedContent",
        props: {
            raw: Boolean
        }
    }
</script>
