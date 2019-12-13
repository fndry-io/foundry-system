<template>
    <span :class="`fa fa-${this.name}`"></span>
</template>

<script>
    export default {
        name: 'Icon',
        props: {
            name: {
                type: String,
                required: true
            }
        }
    }
</script>
