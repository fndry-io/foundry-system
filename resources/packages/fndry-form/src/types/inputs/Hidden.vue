<template>
    <div>
        <input type="hidden"
                      :id="id"
                      :name="name"
                      v-model="model"
                      :disabled="disabled"
                      :required="schema.required"
                      v-on="$listeners"
        >
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-hidden",
        mixins: [
            abstractInput
        ]

    };
</script>
