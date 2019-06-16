<template>
    <b-form-radio-group
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :options="schema.options"
            :stacked="!schema.inline"
    ></b-form-radio-group>

</template>

<script>

    import { BFormRadioGroup } from 'bootstrap-vue'

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-radio",
        components: {
            BFormRadioGroup
        },
        mixins: [
            abstractInput
        ]
    };
</script>
