<template>
    <div :class="schema.class" :id="schema.id">
        <div v-for="child in schema.children">
            <div>
                <form-component
                        :vfg="vfg"
                        :schema="child"
                        :errors="errors"
                        :options="options"
                        :model="model"
                        @validated="onFieldValidated"
                        @filter-enter="onFilterEnter"
                        @model-updated="onModelUpdated"></form-component>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name : "formColumn",
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
        mounted(){},
        methods: {
            onFieldValidated(res, errors, field) {
                this.$emit("validated", res, errors, field);
            },
            onModelUpdated(newVal, schema) {
                this.$emit("model-updated", newVal, schema);
            },
            handleButtonClick(btn){
                this.$emit("button-clicked", btn);
            },
            onFilterEnter(btn){
                this.$emit("filter-enter", btn);
            }
        }
    };
</script>
