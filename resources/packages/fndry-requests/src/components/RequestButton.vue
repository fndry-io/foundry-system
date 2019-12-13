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

                let options = {};

                if (this.type === 'confirm') {
                    options = this.confirmOptions;
                } else if (this.type === 'modal') {
                    options = this.modalOptions;
                }

                this.$fndryRequest(this.request, this.params, this.method, this.data, this.type, options)
                    .then((response) => {
                        this.onSuccess(response);
                    }, (response) => {
                        this.onFail(response);
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
