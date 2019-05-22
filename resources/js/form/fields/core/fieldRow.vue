<template>
    <div class="row">
        <div :class="['col-' + 12/schema.children.length]" v-for='field in schema.children'>
            <form-group
                    v-if='fieldVisible(field)'
                    :vfg="vfg"
                    :field="field"
                    :errors="errors"
                    :form="schema"
                    :model="model"
                    :options="options"
                    @validated="onFieldValidated"
                    @filter-enter="onFilterEnter"
                    @model-updated="onModelUpdated"></form-group>
        </div>
    </div>
</template>

<script>

    import formGroup from "../../formGroup.vue";
    import {isFunction, isNil} from "lodash";
    import conditional from '../../utils/conditional'

    export default {
        name: "formRow",
        components: { formGroup },
        mixins: [conditional],
        props: {
            vfg: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: true
            },
            options: {
                type: Object,
                required: true
            },
            schema: {
                type: Object,
                required: true
            },
            errors: {
                type: Array,
                required: true
            }
        },
        methods:{
            // Get visible prop of field
            fieldVisible(field) {

                if (isNil(field.visible)) return true;

                if (isFunction(field.visible)) return field.visible.call(this, this.model, field, this);

                return field.visible && !field.hidden && !field.guarded;
            },
            onFieldValidated(res, errors, field) {
                this.$emit("validated", res, errors, field);
            },
            onModelUpdated(newVal, schema) {
                this.$emit("model-updated", newVal, schema);
            },
            onFilterEnter(btn){
                this.$emit("filter-enter", btn);
            }
        }
    };
</script>
