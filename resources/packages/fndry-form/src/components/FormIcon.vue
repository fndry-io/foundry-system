<template>
    <span :class="`fa fa-${this.name}`"></span>
</template>

<script>
    export default {
        name: 'form-icon',
        props: {
            name: {
                type: String,
                required: true
            }
        }
    }
</script>
