<template>
    <div class="form-fields">
        <div class="form-field" v-for="(child, index) in schema.children" :key="index">
            <fndry-form-type
                    :schema="child"
                    :model="model"
                    :errors="errors"
                    v-on="$listeners"
                    @change="onChange"
                    @input="onInput"
            ></fndry-form-type>
        </div>
    </div>
</template>

<script>
    import {set} from 'lodash';

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
            model: {
                type: Object,
                required: false
            },
            errors: {
                type: Object,
                required: false
            }
        },
        methods: {
            onInput(field, value) {
                set(this.model, field, value);
                this.$emit('input', this.model);
            },
            onChange(field, value) {
                set(this.model, field, value);
                this.$emit('change', this.model);
            },
        }
    }
</script>
