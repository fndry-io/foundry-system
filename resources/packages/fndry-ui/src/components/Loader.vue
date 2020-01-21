<template>
    <div class="loading-wrapper" :style="{'min-height': (loading) ? height : null}">
        <div v-if="loading">
            <div :class="{'loading d-flex align-items-center justify-content-center': true, 'no-background': transparent}">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
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
            },
            height: {
                type: String,
                default() {
                    return '80px'
                }
            },
            transparent: Boolean
        }
    }
</script>

<style lang="scss">

    .loading-wrapper {
        position: relative;
    }

    .loading {
        position: absolute;
        z-index: 10000;
        background-color: rgba(255,255,255,0.5);
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

        &.no-background {
            background: transparent;
        }
    }

</style>
