

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
            type: [String,Number,Boolean,Array,InputEvent,Object],
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
    methods: {
        getStep(){
            if (this.schema.type === 'number') {
                console.log(this.schema);
            }
        }
    },
    computed: {
        autocomplete: function(){
            if (this.schema.autocomplete === false) {
                return 'off';
            } else if(this.schema.autocomplete === true) {
                return 'on'
            } else {
                return undefined;
            }
        }
    }
}