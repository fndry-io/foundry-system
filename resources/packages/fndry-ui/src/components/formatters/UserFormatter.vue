<template>
    <span>{{ value.display_name }}</span>
</template>

<script>

    export default {
        name: 'UserFormatter',
        props: {
            value: {
                type: Object,
                required: true,
            }
        }
    }

</script>
