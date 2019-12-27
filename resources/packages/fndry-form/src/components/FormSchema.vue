<template>
    <div class="form-fields">
        <div class="form-field" v-for="(child, index) in type.children" :key="index">
            <fndry-form-type
                    :schema="child"
                    :model="model"
                    :errors="errors"
                    @change="onUpdate"
                    @input="onUpdate"
            ></fndry-form-type>
        </div>
    </div>
</template>

<script>
    import {set, extend} from 'lodash';

    //import {getChildInputValues} from '../utils/schema';
    import schema, {process} from "../mixins/schema";

    /**
     * Foundry Form Schema
     *
     * A component that starts the rendering of the provided schema
     */
    export default {
        name: 'fndry-form-schema',
        mixins: [
            schema
        ],
        props: {
            schema: {
                type: Object,
                required: true
            },
            errors: {
                type: [Object, Array],
                required: false
            },
            data: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                model: {},
                type: extend({}, this.schema)
            };
        },
        created(){
            let model = extend({}, this.schema.values, this.data);
            process(this.type, model);
            this.model = model;
        },
        methods: {
            onUpdate(field, value) {
                set(this.model, field, value);
                this.$emit('update', this.model);
                process(this.type, this.model);
            }
        }
    }
</script>
