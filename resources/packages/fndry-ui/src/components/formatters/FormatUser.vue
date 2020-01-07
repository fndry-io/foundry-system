<template>
    <span>{{ value.display_name }}</span>
</template>

<script>

    export default {
        name: 'FormatUser',
        props: {
            value: {
                type: Object,
                required: true,
            }
        }
    }

</script>
