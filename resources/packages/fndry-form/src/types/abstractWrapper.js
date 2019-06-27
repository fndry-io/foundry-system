

export default {
    props: {
        schema: {
            type: Object,
            required: true
        },
        model: {
            type: Object,
            required: false
        },
        errors: {
            type: Object,
            required: false
        }
    },
    watch: {
        schema: function(newVal, oldVal) { // watch it
            console.log('Prop changed: ', newVal, ' | was: ', oldVal)
        }
    }
}