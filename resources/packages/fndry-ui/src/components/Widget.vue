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
                <div v-if="!noContext && hasPermission" class="card-context">
                    <slot name="context"></slot>
                </div>
            </div>
        </div>
        <div class="card-filter" v-if="$slots.filter && hasPermission">
            <slot name="filter"></slot>
        </div>
        <div v-if="!noBody" :class="{'card-body': !noCard}">
            <div v-if="hasPermission">
                <loader v-if="!noLoader" :loading="loading">
                    <slot></slot>
                </loader>
                <div v-else>
                    <slot></slot>
                </div>
            </div>
            <div v-else>
                <div class="text-center m-5">
                    <p class="lead mb-0">You do not have permission to perform this action</p>
                </div>
            </div>
        </div>
        <div class="card-footer" v-if="!noFooter && hasPermission">
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
            noCard: Boolean,
            noBody: Boolean,
            permission: String,
            noLoader: Boolean
        },
        computed: {
            hasPermission: function(){
                return (this.permission) ? this.$userCan(this.permission) : true;
            }
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

        .card-footer {
            .paginate .paginate-report {
                padding: 0.5rem 0;
            }
        }
    }
</style>
