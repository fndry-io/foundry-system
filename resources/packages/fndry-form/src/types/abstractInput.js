

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
        rootModel: {
            type: [Object],
            required: false
        },
        value: {
            type: [String,Number,Boolean,Array,Object],
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
    computed: {
        autocomplete: function(){
            return (this.schema) ? this.schema.autocomplete : undefined;
        }
    },
    watch: {
        value: function(newVal, oldVal){
            if (newVal !== oldVal) {
                this.model = newVal;
            }
        }
    }
}
