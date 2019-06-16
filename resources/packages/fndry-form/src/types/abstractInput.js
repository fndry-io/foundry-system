

export default {
    props: {
        schema: {
            type: Object,
            required: true
        },
        id: {
            type: String
        },
        name: {
            type: String
        },
        disabled: {
            type: Boolean,
            required: false
        },
        value: {
            type: [String,Number,Boolean,Array,InputEvent],
            required: false
        },
        state: Boolean,
        validation: Object
    },
    data(){
        return {
            model: this.value
        }
    },
}