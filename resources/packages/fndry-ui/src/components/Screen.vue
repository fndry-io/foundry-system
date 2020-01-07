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
            <slot name="breadcrumbs" v-if="breadcrumbs && breadcrumbs.length">
                <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>
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
            breadcrumbs: {
                type: Array,
                required: false
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
            padding: $screen-header-padding;
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
            padding: $screen-body-padding;
            text-align: left;
        }

        .filter-block {
            display: flex;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            list-style: none;
            background-color: #e9ecef;
            border-radius: 0.25rem;

            align-content: flex-end;
            align-items: flex-end;

            > * {
                margin-left: 3px;
                margin-right: 3px;
            }
        }
    }
</style>
