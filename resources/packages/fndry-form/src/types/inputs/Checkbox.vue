<template>
    <b-form-checkbox
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :switch="schema.switch"
    >
        {{schema.label}}
    </b-form-checkbox>

</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-checkbox",
        mixins: [
            abstractInput
        ]
    };
</script>
