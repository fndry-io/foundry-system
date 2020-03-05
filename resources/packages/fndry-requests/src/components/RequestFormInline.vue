<template>
    <div class="request-form-inline">
        <div v-if="loading" class="loading text-center">
            <b-spinner label="Loading..."></b-spinner>
        </div>
        <div v-else>
            <h3 v-if="title">{{title}}</h3>
            <validation-observer ref="observer" v-slot="{ invalid }" :slim="true">
                <form @submit.prevent="onSubmit" :class="{'form-inline':inline}">
                    <b-alert variant="warning" :show="response && response.error">{{response.error}}</b-alert>
                    <fndry-form-schema :schema="schema" :errors="errors" :data="data" @update="onModelUpdated" :inline="inline"></fndry-form-schema>
                    <fndry-form-buttons :buttons="schema.buttons" @click="handleButtonClick" :submitting="submitting" :cancelButton="cancelButton" :submitButton="submitButton"></fndry-form-buttons>
                </form>
            </validation-observer>
        </div>
    </div>
</template>

<script>

    import { extend } from 'lodash';

    import form from '../mixins/form';
    import {FndryFormButtons} from '../../../fndry-form/src';

    import styles from '../css/styles.css';

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
            },
            showTitle: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            labelForTitle: {
                type: String,
                default() {
                    return null;
                }
            },
            submitButton: {
                type: [Boolean, String, Object],
                default() {
                    return {
                        label: 'Submit'
                    }
                }
            },
            cancelButton: {
                type: [Boolean, String, Object],
                default() {
                    return {
                        label: 'Cancel'
                    }
                }
            },
        },
        components: {
            FndryFormButtons
        },
        computed: {
            title() {
                if (this.showTitle === true) {
                    if (this.schema.title) {
                        return this.schema.title;
                    } else if (this.labelForTitle) {
                        return this.labelForTitle;
                    }
                }
            },
        }
    }
</script>
