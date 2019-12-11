<template>
    <div class="widget card">
        <div class="card-header" v-if="!noHeader">
            <div class="row">
                <div class="card-title col">
                    <slot name="header">
                        <h3 class="mb-0">{{title}}</h3>
                    </slot>
                </div>
                <div v-if="!noContext" class="card-context col">
                    <slot name="context"></slot>
                </div>
            </div>
        </div>
        <div class="card-body">
            <slot></slot>
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
            noContext: Boolean
        }
    }
</script>

<style lang="scss">
    .widget {
        .card-title {
            text-align: left;
            h3 {
                font-size: inherit;
            }
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
