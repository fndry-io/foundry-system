<template>
        <div class="widget card">
            <div class="card-header" v-if="!noHeader">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <slot name="header">
                            <h5 class="card-title">{{title}}</h5>
                        </slot>
                    </div>
                    <div v-if="!noContext" class="card-context">
                        <slot name="context"></slot>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <loader :loading="loading">
                    <slot></slot>
                </loader>
            </div>
            <div class="card-footer" v-if="!noFooter">
                <slot name="footer"></slot>
            </div>
        </div>
</template>

<script>
    export default {
        name: "Widget",
        props: {
            title: String,
            noHeader: Boolean,
            noFooter: Boolean,
            noContext: Boolean,
            loading: Boolean
        }
    }
</script>

<style lang="scss">
    .widget {
        .card-title {
            text-align: left;
            margin-bottom: 0;
        }
        .card-body {
            overflow-x: auto;
        }
        .card-context {
            text-align: right;
            .btn {
                margin-left: 5px;
            }
        }
    }
</style>
