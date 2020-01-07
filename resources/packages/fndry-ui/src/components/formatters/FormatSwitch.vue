<template>
    <span v-if="value === trueValue">{{trueText}}</span>
    <span v-else-if="value === falseValue">{{falseText}}</span>
</template>

<script>

    export default {
        name: 'FormatSwitch',
        props: {
            value: {
                type: [String, Boolean, Number],
                required: true,
            },
            trueValue: {
                type: [String, Boolean, Number],
                required: false,
                default() {
                    return true
                }
            },
            trueText: {
                type: [String],
                required: false,
                default() {
                    return 'Yes'
                }
            },
            falseValue: {
                type: [String, Boolean, Number],
                required: false,
                default() {
                    return false
                }
            },
            falseText: {
                type: [String],
                required: false,
                default() {
                    return 'No'
                }
            }
        }
    }

</script>
