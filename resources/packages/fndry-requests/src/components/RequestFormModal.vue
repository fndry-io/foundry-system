<template>
    <validation-observer ref="observer" v-slot="{ invalid }" :slim="true">
        <b-modal ref="modal" v-model="show" scrollable :title="getModalTitle()" :hide-footer="loading" :size="size" @hide="canHide" modal-class="request-modal">
            <div v-if="loading" class="text-center">
                <b-spinner label="Loading..."></b-spinner>
            </div>
            <div v-else>
                <form @submit.prevent="onSubmit">
                    <b-alert variant="warning" :show="response && response.error">{{response.error}}</b-alert>
                    <fndry-form-schema :schema="schema" :errors="errors" :data="data" @update="onModelUpdated"></fndry-form-schema>
                </form>
            </div>
            <template v-if="!loading" slot="modal-footer">
                <fndry-form-buttons :active="activeButton" :buttons="schema.buttons" @click="handleButtonClick" :submitting="submitting"></fndry-form-buttons>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>

    import { extend } from 'lodash';

    import form from '../mixins/form';
    import {FndryFormButtons} from '../../../fndry-form/src';

    export default {
        name: "fndry-request-form-modal",
        mixins: [
            form
        ],
        components: {
            FndryFormButtons
        },
        props: {
            size: {
                type: String,
                default() {
                    return 'lg'
                },
                required: false
            },
            position: {
                type: String,
                required: false
            }
        },
        data(){
            return {
                show: true,
                allowClose: false
            }
        },
        methods: {
            showModal() {
                this.show = true;
            },
            hideModal() {
                this.show = false;
            },
            onSuccess: function(response){
                this.$emit('success', response, this.model);
                this.hideModal();
            },
            onCancel: function(){
                this.$emit('cancel');
                this.hideModal();
            },
            canHide: function(bvModalEvt){
                if (this.allowClose === false) {
                    if (this.dirty) {
                        bvModalEvt.preventDefault();
                        this.$bvModal.msgBoxConfirm('Looks like you made some changes, are you sure you want to cancel? Any changes would be lost.', {
                            okTitle: 'Close',
                            cancelTitle: 'Continue',
                        }).then((value) => {
                            if (value === true) {
                                this.allowClose = true;
                                this.onCancel();
                            }
                        })
                    } else {
                        this.allowClose = true;
                        this.onCancel();
                    }
                }
            },
            getModalTitle() {
                if (this.schema) {
                    if (this.schema.title) {
                        return this.schema.title;
                    } else {
                        return 'Form';
                    }
                } else {
                    return 'Loading...';
                }
            }
        }
    }
</script>

