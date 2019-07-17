<template>
    <b-button :variant="variant" :size="size" @click="onClick">
        <slot>Confirm</slot>
    </b-button>
</template>

<script>

    export default {
        name: 'ConfirmButton',
        props: {
            variant: String,
            size: String,
            message: {
                type: String,
                required: true
            },
            options: {
                type: Object,
                default() {
                    return {
                        title: 'Confirm',
                        size: 'sm',
                        okTitle: 'Yes',
                        cancelTitle: 'No',
                        centered: true
                    }
                }
            }
        },
        methods: {
            onClick(){
                this.$bvModal.msgBoxConfirm(this.message, this.options)
                    .then(value => {
                        if (value === true) {
                            this.$emit('confirmed');
                        } else {
                            this.$emit('cancel');
                        }
                    });
            }
        }
    }
</script>