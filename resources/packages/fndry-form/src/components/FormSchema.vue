<template>
    <div class="form-fields">
        <div class="form-field" v-for="(child, index) in schema.children" :key="index">
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

    import {getChildInputValues} from '../utils/schema';

    /**
     * Foundry Form Schema
     *
     * A component that starts the rendering of the provided schema
     */
    export default {
        name: 'fndry-form-schema',
        props: {
            schema: {
                type: Object,
                required: true
            },
            errors: {
                type: Object,
                required: false
            },
            data: {
                type: Object,
                required: false
            }
        },
        data() {
            return {
                model: {}
            };
        },
        created(){
            let model = this.data ? extend({}, this.data) : {};
            getChildInputValues(this.schema, model);
            this.model = model;
        },
        methods: {
            onUpdate(field, value) {
                set(this.model, field, value);
                this.$emit('update', this.model);
            },
        }
    }
</script>
