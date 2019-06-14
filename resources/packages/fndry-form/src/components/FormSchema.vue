<template>
    <div class="form-fields">
        <div class="form-field" v-for="(child, index) in schema.children" :key="index">
            <form-component
                    :schema="child"
                    :model="model"
                    v-on="$listeners"
                    @change="onChange"
                    @input="onInput"
            ></form-component>
        </div>
    </div>
</template>

<script>
    import {set} from 'lodash';

    export default {
        name: 'form-schema',
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: false
            }
        },
        methods: {
            onInput(field, value) {
                set(this.model, field, value);
                this.$emit('model-update', this.model);
            },
            onChange(field, value) {
                set(this.model, field, value);
                this.$emit('model-update', this.model);
            },
        }
    }
</script>
