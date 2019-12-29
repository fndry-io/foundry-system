<template>
    <b-form-checkbox-group
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :options="schema.options"
            :stacked="!schema.stacked"
            :disabled="disabled"
    ></b-form-checkbox-group>

</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-checkboxes",
        mixins: [
            abstractInput
        ]
    };
</script>
