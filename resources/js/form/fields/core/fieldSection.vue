<template>
    <div :class="[schema.class, 'section']" :id="schema.id">
        <h4>{{schema.title}}</h4>
        <p v-if="schema.description">{{schema.description}}</p>
        <div v-for="child in schema.children">
            <div>
                <form-component
                        :vfg="vfg"
                        :schema="child"
                        :errors="errors"
                        :options="options"
                        :model="model"
                        @filter-enter="onFilterEnter"
                        @validated="onFieldValidated"
                        @model-updated="onModelUpdated"></form-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "formSection",
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


<style lang="scss">
    .section{
        margin: 0 0 30px 0;
    }
</style>
