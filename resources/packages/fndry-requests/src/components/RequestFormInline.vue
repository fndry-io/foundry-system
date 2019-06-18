<template>
    <div>
        <div v-if="loading" class="text-center">
            <b-spinner label="Loading..."></b-spinner>
        </div>
        <div v-else>
            <validation-observer ref="observer" v-slot="{ invalid }" :slim="true">
                <form @submit.prevent="onSubmit" :class="{'form-inline':inline}">
                    <b-alert variant="warning" :show="response && response.error">{{response.error}}</b-alert>
                    <fndry-form-schema :schema="schema" :errors="errors" :data="data" @update="onModelUpdated" :inline="inline"></fndry-form-schema>
                    <fndry-form-buttons :buttons="schema.buttons" @click="handleButtonClick" :submitting="submitting"></fndry-form-buttons>
                </form>
            </validation-observer>
        </div>
    </div>
</template>

<script>

    import { extend } from 'lodash';

    import form from '../mixins/form';
    import FndryFormButtons from './FormButtons';

    export default {
        name: "fndry-request-form-inline",
        mixins: [
            form
        ],
        props: {
            inline: {
                type: Boolean,
                default() {
                    return false;
                }
            }
        },
        components: {
            FndryFormButtons
        }
    }
</script>

<style type="text/css">
    @media (max-width: 576px) {
        .form-inline {
            display: block;
        }
    }
    @media (min-width: 576px) {
        .form-inline {
            display: flex;
            align-items: flex-start;
            flex-wrap: nowrap;
            height: 100%;
            margin: 0 -5px;
        }
        .form-inline .form-fields {
            display: flex;
            align-items: flex-start;
            height: 100%;
        }
        .form-inline .form-field {
            padding: 0 5px;
        }
        .form-inline .form-buttons {
            display: flex;
            align-items: flex-end;
            height: 100%;
            width: 30%;
            margin: 0 5px;
        }
        .form-inline .form-buttons > div {
            margin-top: 24px;
        }
        .form-inline .form-control {
            width: 100%;
        }
        .form-inline .form-buttons button {
            white-space: nowrap;
        }
    }
</style>