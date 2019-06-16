<template>
    <div class="row">
        <div :class="['col-' + 12/schema.children.length]" v-for="(child, index) in schema.children" :key="index">
            <fndry-form-type :schema="child" :model="model" :errors="errors" v-on="$listeners"></fndry-form-type>
        </div>
    </div>
</template>

<script>
    export default {
        name: "fndry-wrapper-row",
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: false
            },
            errors: {
                type: Object,
                required: false
            }
        }
    };
</script>
