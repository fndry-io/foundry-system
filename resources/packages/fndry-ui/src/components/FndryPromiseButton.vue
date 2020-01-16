<template>
    <component :is="tag" :variant="variant" :size="size" @click.prevent="onClick" :title="title">
        <slot>
            <span v-if="icon" :class="icon" aria-hidden="true"></span>
            <span v-if="text"><span v-if="icon">&nbsp;</span>{{text}}</span>
            <b-spinner v-if="submitting" small label="Loading" type="grow" style="margin-left: 10px"></b-spinner>
        </slot>
    </component>
</template>

<script>
    export default {
        name: "FndryPromiseButton",
        props: {
            title: String,
            tag: {
                type: String,
                default() {
                    return 'b-button'
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
            icon: {
                type: [String, Boolean],
                default() {
                    return false;
                }
            },
            text: {
                type: [String, Boolean],
                default() {
                    return false;
                }
            },
            promise: {
                type: [Promise, Function],
                required: true
            }
        },
        data(){
            return {
                submitting: false
            };
        },
        methods: {
            onClick(){
                this.submitting = true;
                this.promise().finally(() => {
                    this.submitting = false;
                })
            }
        }
    }
</script>

