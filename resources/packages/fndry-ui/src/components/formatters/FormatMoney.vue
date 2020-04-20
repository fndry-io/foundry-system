<template>
    <span>{{ formatted }}</span>
</template>

<script>

    import numeral from 'numeral';

    export default {
        name: 'FormatMoney',
        props: {
            value: {
                required: true
            },
            format: {
                type: String,
                default() {
                    return '$ 0,0[.]00'
                }
            }
        },
        computed: {
            formatted: function() {
                if (this.value !== null) {
                    return numeral(this.value).format(this.format);
                } else {
                    return null;
                }
            }
        }
    }

</script>
