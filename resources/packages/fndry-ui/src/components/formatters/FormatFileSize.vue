<template>
    <span>{{ formatted }}</span>
</template>

<script>

    import numeral from 'numeral';

    export default {
        name: 'FormatFileSize',
        props: {
            value: {
                type: [String, Number],
                required: true,
            },
            /**
             * Base allows us to control the factor we multiple the incoming number by to get it into bytes
             */
            base: {
                type: Number,
                default(){
                    return 1000;
                },
            },
            format: {
                type: String,
                default() {
                    return '0.00b'
                }
            }
        },
        computed: {
            formatted: function() {
                let value = parseFloat(this.value);
                value = value * this.base;
                return numeral(value).format(this.format);
            }
        }
    }

</script>
