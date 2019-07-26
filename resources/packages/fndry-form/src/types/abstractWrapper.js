

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
    }
}