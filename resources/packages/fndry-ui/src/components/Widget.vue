<template>
    <div class="widget card">
        <div class="card-header" v-if="!noHeader">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <slot name="header">
                        <h5 class="card-title">{{title}}</h5>
                        <p v-if="description" class="card-description">{{description}}</p>
                    </slot>
                </div>
                <div v-if="!noContext" class="card-context">
                    <slot name="context"></slot>
                </div>
            </div>
        </div>
        <div class="card-filter" v-if="$slots.filter">
            <slot name="filter"></slot>
        </div>
        <div :class="{'card-body': !noCard}">
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
            description: String,
            noHeader: Boolean,
            noFooter: Boolean,
            noFilter: Boolean,
            noContext: Boolean,
            loading: Boolean,
            noCard: Boolean
        }
    }
</script>

<style lang="scss">
    .widget {
        .card-title {
            text-align: left;
            margin-bottom: 0;
        }
        .card-description {
            font-size: 1rem;
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
            .input-group .btn {
                margin: 0;
            }
        }

        .filter {
            .filter-body {
                padding: 1.25rem 1.25rem 0;
            }
            .filter-buttons {
                background: #efefef;
                padding: 0.75rem 1.25rem;
                text-align: right;
            }
        }
    }
</style>
