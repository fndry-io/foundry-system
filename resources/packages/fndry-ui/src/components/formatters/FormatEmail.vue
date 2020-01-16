<template>
    <a :href="mailto">{{ value }}</a>
</template>

<script>

    export default {
        name: 'FormatEmail',
        props: {
            value: {
                type: [String, Number],
                required: true,
            }
        },
        computed: {
            mailto: function() {
                return 'mailto:' + this.value;
            }
        }
    }

</script>
