<template>
    <a :href="uri">{{ formatted }}</a>
</template>

<script>

    import { parsePhoneNumberFromString, AsYouType } from 'libphonenumber-js'

    export default {
        name: 'FormatPhone',
        props: {
            value: {
                type: [String, Number],
                required: true,
            },
            format: {
                type: String,
                default() {
                    return 'international'
                }
            }
        },
        data() {
            return {
                number: parsePhoneNumberFromString(this.value, 'US')
            };
        },
        computed: {
            formatted: function() {
                if (this.format === 'national') {
                    return this.number.format('NATIONAL');
                } else {
                    return this.number.format('INTERNATIONAL');
                }
            },
            uri: function() {
                return 'tel:' + this.number.number;
            }
        }
    }

</script>
