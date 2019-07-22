<template>
    <b-modal ref="modal" v-model="show" scrollable :title="title" :size="size" @hide="onCancel">
        <div>
            {{message}}
        </div>
        <template slot="modal-footer">
            <b-button :disabled="submitting" @click="onCancel">No</b-button>
            <b-button variant="danger" :disabled="submitting" @click="onConfirm">Yes
                <b-spinner v-if="submitting" small label="Loading" type="grow" style="margin-left: 15px"></b-spinner>
            </b-button>
        </template>
    </b-modal>
</template>

<script>

    import { extend } from 'lodash';

    export default {
        name: "fndry-request-form-confirm-modal",
        props: {
            request: String,
            params: {
                type: Object,
                default: function(){
                    return {};
                }
            },
            data: {
                type: Object,
                default: function () {
                    return {};
                }
            },
            title: {
                type: String,
                default() {
                    return 'Confirm'
                }
            },
            message: {
                type: String,
                default() {
                    return 'Are you sure?'
                }
            },
            size: {
                type: String,
                default() {
                    return 'lg'
                },
                required: false
            }
        },
        data(){
            return {
                show: true,
                submitting: false
            }
        },
        methods: {
            showModal() {
                this.show = true;
            },
            hideModal() {
                this.show = false;
            },
            onConfirm: function(){
                this.submitting = true;
                this.$fndryApiService.call(this.$fndryApiService.getHandleUrl(this.request, this.params), 'POST', this.data)
                    .then((response) => {
                        this.response = response;
                        this.onSuccess(response);
                    }, (response) => {
                        this.response = response;
                        this.onCancel(response);
                    })
                    .finally(() => {
                        this.submitting = false;
                    })
                ;
            },
            onSuccess: function(response){
                this.$emit('success', response);
                this.hideModal();
            },
            onCancel: function(){
                this.$emit('cancel');
                this.hideModal();
            }
        }
    }
</script>

