<template>
    <div class="loading-area">
        <div v-if="loading" class="loading d-flex align-items-center justify-content-center">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'Loader',
        props: {
            loading: {
                type: Boolean,
                required: false
            }
        }
    }
</script>

<style lang="scss">

    .loading-area {
        position: relative;
        min-height: 100px;
    }

    .loading {
        position: absolute;
        z-index: 10000;
        background-color: rgba(255,255,255,0.5);
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

</style>
