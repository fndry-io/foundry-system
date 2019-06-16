<template>
    <b-form-checkbox-group
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :options="schema.options"
            :stacked="!schema.inline"
    ></b-form-checkbox-group>

</template>

<script>

    import { BFormCheckboxGroup } from 'bootstrap-vue'

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-checkboxes",
        components: {
            BFormCheckboxGroup
        },
        mixins: [
            abstractInput
        ]
    };
</script>
