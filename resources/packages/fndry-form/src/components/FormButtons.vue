<template>
    <div class="form-buttons">
        <slot>
            <span v-if="buttons">
                    <span v-for="(button, index) in buttons">
                        <b-button :variant="buttonVariant(button)" :disabled="submitting" @click="() => handleClick(button, index)" :key="index">
                            {{button.label}}
                            <b-spinner v-if="(active === index) && submitting" small label="Loading" type="grow" style="margin-left: 15px"></b-spinner>
                        </b-button>
                    </span>
            </span>
            <div v-else>
                <button v-if="submitButton !== false" type="submit" class="btn btn-primary" :disabled="submitting" @click="() => handleClick(submit, 'submit')">
                    {{submit.label}}
                    <b-spinner v-if="(active === 'submit') && submitting" small label="Loading" type="grow" style="margin-left: 15px"></b-spinner>
                </button>
            </div>
            <button v-if="cancelButton !== false" type="button" class="btn" :disabled="submitting" @click="() => handleClick(cancel, 'cancel')">
                {{cancel.label}}
                <b-spinner v-if="(active === 'cancel') && submitting" small label="Loading" type="grow" style="margin-left: 15px"></b-spinner>
            </button>
        </slot>
    </div>
</template>

<script>

    import {merge} from 'lodash';

    export default {
        name: 'fndry-form-buttons',
        props: {
            submitting: {
                type: Boolean,
                default: false
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
            buttons: {
                type: [Array, Boolean],
                default() {
                    return false;
                }
            },
            active: {
                type: [String,Number],
                default() {
                    return null;
                }
            }
        },
        data() {
            return {
                submit: (typeof(this.submitButton) === 'string') ? {label: this.submitButton, type: 'submit'}: merge({type: 'submit'}, this.submitButton),
                cancel: (typeof(this.cancelButton) === 'string') ? {label: this.cancelButton, type: 'cancel'}: merge({type: 'cancel'}, this.cancelButton),
            };
        },
        methods: {
            handleClick(button, index){
                this.$emit('click', button, index);
            },
            buttonVariant(button) {
                if (button.variant) {
                    return button.variant;
                }
                if (button.type === 'submit') {
                    return 'primary';
                } else {
                    return null;
                }
            }
        }
    }

</script>
