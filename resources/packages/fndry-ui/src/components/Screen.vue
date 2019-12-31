<template>
    <div class="screen">
        <div v-if="!noHeader" class="screen-header">
            <div class="d-flex flex-row">
                <div class="flex-grow-1">
                    <slot name="heading"><h2>{{this.heading}}</h2></slot>
                    <slot name="sub-heading"><p class="lead">{{this.subHeading}}</p></slot>
                    <slot name="breadcrumbs" v-if="breadcrumbs">
                        <b-breadcrumb :items="breadcrumbs" class="mb-4"></b-breadcrumb>
                    </slot>
                </div>
                <div class="screen-context">
                    <slot name="context"></slot>
                </div>
            </div>
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
    }
</style>
