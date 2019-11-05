<template>
    <b-form-radio-group
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :options="schema.options"
            :stacked="!schema.inline"
            :value-field="schema.valueKey"
            :text-field="schema.textKey"
            :disabled="disabled"
    ></b-form-radio-group>

</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-radio",
        mixins: [
            abstractInput
        ]
    };
</script>
