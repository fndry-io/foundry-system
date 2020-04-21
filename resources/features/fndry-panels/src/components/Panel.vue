<template>
    <div class="panel">
        <loader :loading="loading" class="panel-body">
            <slot></slot>
        </loader>
        <footer v-if="!noFooter" class="panel-footer">
            <slot name="footer"></slot>
        </footer>
    </div>
</template>

<script>
    export default {
        name: "Panel",
        props: {
            noFooter: Boolean,
            loading: Boolean
        }
    }
</script>
