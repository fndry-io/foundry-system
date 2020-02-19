<template>
    <div class="screen">
        <div v-if="!noHeader" class="screen-header">
            <div class="d-flex flex-row">
                <div class="flex-grow-1">
                    <slot name="heading"><h2>{{this.heading}}</h2></slot>
                    <slot name="sub-heading"><p class="lead">{{this.subHeading}}</p></slot>
                </div>
                <div class="screen-context">
                    <slot name="context"></slot>
                </div>
            </div>
            <slot name="breadcrumbs" v-if="!noBreadcrumbs">
                <b-breadcrumb v-if="list && list.length" :items="list"></b-breadcrumb>
            </slot>
        </div>
        <div class="screen-body">
            <slot></slot>
        </div>
        <div v-if="!noFooter">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Screen",
        props: {
            heading: String,
            subHeading: String,
            noHeader: Boolean,
            noFooter: Boolean,
            noBreadcrumbs: Boolean,
            breadcrumbs: {
                type: Array,
                required: false
            }
        },
        computed: {
            list: function() {
                if (this.breadcrumbs) {
                    return this.breadcrumbs;
                } else {
                    let matched = this.$route.matched.filter((route) => route.name || route.meta.label);
                    return matched.map((route) => {
                        return {
                            text: route.meta.title || route.name,
                            href: route.url
                        }
                    })
                }
            }
        }
    }
</script>

<style lang="scss">

    $screen-header-padding: 15px !default;

    $screen-body-padding: 15px !default;

    $screen-footer-padding: 15px !default;

    .screen {
        .screen-header {
            padding: 0 0 $screen-header-padding 0;
            text-align: left;

            .breadcrumb {
                margin-bottom: 0;
            }
        }
        .screen-context {
            text-align: right;

            > .btn {
                margin-left: 5px;
            }

        }
        .screen-body {
            padding: 0;
            text-align: left;
        }

        .filter-block {
            display: flex;
            padding: 0.75rem 1rem;
            list-style: none;
            background-color: #e9ecef;

            align-content: flex-end;
            align-items: flex-end;

            > * {
                margin-left: 3px;
                margin-right: 3px;
            }
        }
    }
</style>
