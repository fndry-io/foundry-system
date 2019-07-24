<template>
    <component :is="tag" :variant="variant" :size="size" @click.prevent="onClick" :title="title">
        <slot>
            <span v-if="buttonIcon" :class="buttonIcon" aria-hidden="true"></span>
            <span v-if="buttonText"><span v-if="buttonIcon">&nbsp;</span>{{buttonText}}</span>
            <b-spinner v-if="submitting" small label="Loading" type="grow" style="margin-left: 10px"></b-spinner>
        </slot>
    </component>
</template>

<script>

    import {merge} from 'lodash';

    export default {
        name: 'fndry-request-button',
        props: {
            request: String,
            title: String,
            params: {
                type: Object,
                default() {
                    return {}
                }
            },
            data: {
                type: Object,
                default() {
                    return {}
                }
            },
            tag: {
                type: String,
                default(){
                    return 'b-button'
                }
            },
            method: {
                type: String,
                default(){
                    return 'POST'
                }
            },
            type: {
                type: String,
                default(){
                    return 'modal'
                },
            },
            confirm: {
                type: Boolean,
                default() {
                    return false
                }
            },

            size: {
                type: String,
                default() {
                    return ''
                }
            },
            variant: {
                type: String,
                default() {
                    return ''
                }
            },

            button: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            buttonIcon: {
                type: [String, Boolean],
                default() {
                    //fa fa-pencil
                    return false;
                }
            },
            buttonText: {
                type: [String, Boolean],
                default() {
                    return false;
                }
            },

            modalOptions: {
                type: Object,
                default() {
                    return {
                        size: 'lg'
                    };
                }
            },

            confirmOptions: {
                type: Object,
                default() {
                    return {
                        size: 'lg'
                    };
                }
            }
        },
        data() {
            return {
                submitting: false
            }
        },
        methods: {
            onClick() {
                this.submitting = true;

                let $request;

                switch(this.type) {
                    case 'modal':
                        $request = this.$fndryRequestForm(this.request, 'modal', merge({},
                            this.modalOptions,
                            {params: this.params, data: this.data}
                        ));
                        break;
                    case 'action':
                        $request = this.$fndryApiService.call(
                            this.$fndryApiService.getHandleUrl(this.request, this.params),
                            this.method,
                            this.data
                        );
                        break;
                    case 'confirm':
                        $request = this.$fndryRequestConfirm(this.request, merge({},
                            this.confirmOptions,
                            {params: this.params, data: this.data}
                        ));
                        break;
                }

                $request
                    .then((response) => {
                        this.onSuccess(response);
                        this.submitting = false;
                    }, (response) => {
                        this.onFail(response);
                        this.submitting = false;
                    })
                    .catch((response) => {
                        //alert accordingly
                        this.submitting = false;
                    })
                    .finally((response) => {
                        this.submitting = false;
                    })
                ;

            },
            onSuccess(response){
                this.$emit('success', response);
            },
            onFail(response){
                this.$emit('fail', response);
            }
        }
    };

</script>