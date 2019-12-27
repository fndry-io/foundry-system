<template>
    <div>
        <input type="hidden"
                      :id="id"
                      :name="name"
                      v-model="model"
                      :disabled="disabled"
                      v-on="$listeners"
        >
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-hidden",
        props: {
            schema: {
                type: Object,
                required: false
            },
        },
        mixins: [
            abstractInput
        ]

    };
</script>
