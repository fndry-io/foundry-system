<template>
    <b-form-select
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :multiple="schema.multiple"
            :state="state"
    >
        <option v-if="!schema.empty" :value="null">Please select an option</option>
        <option v-for="option in schema.options" :value="option.value">{{option.text}}</option>
    </b-form-select>

</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-select",
        mixins: [
            abstractInput
        ]
    };
</script>
