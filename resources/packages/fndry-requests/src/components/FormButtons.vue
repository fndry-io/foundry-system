<template>
    <div class="form-buttons">
        <slot>
            <span v-if="buttons">
                    <span v-for="(button, index) in buttons">
                        <b-button :variant="buttonVariant(button)" :disabled="submitting" @click="() => handleClick(button)" :key="index">
                            {{button.label}}
                        </b-button>
                    </span>
            </span>
            <div v-else>
                <button v-if="submitButton !== false" type="submit" class="btn btn-primary" :disabled="submitting" @click="() => handleClick(submit)">
                    {{submit.label}}
                </button>
            </div>
            <button v-if="cancelButton !== false" type="button" class="btn" :disabled="submitting" @click="() => handleClick(cancel)">
                {{cancel.label}}
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
                default: {
                    label: 'Submit'
                }
            },
            cancelButton: {
                type: [Boolean, String, Object],
                default: {
                    label: 'Cancel'
                }
            },
            buttons: {
                type: [Array, Boolean],
                default() {
                    return false;
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
            handleClick(button){
                this.$emit('click', button);
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