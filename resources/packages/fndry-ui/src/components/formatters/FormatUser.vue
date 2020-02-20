<template>
    <span v-if="value">{{ value.display_name }}</span>
</template>

<script>

    export default {
        name: 'FormatUser',
        props: {
            value: Object
        }
    }

</script>
