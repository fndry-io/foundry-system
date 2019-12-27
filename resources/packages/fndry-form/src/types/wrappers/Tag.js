export default {
    render(h){
        return h(this.schema.tag, {
            'class': this.schema.class ? this.schema.class : "",
            domProps: {
                innerHTML: this.schema.content
            }
        });
    },
    props: {
        schema: {
            type: Object,
            required: true
        }
    },
    name: "fndry-wrapper-tag"
};