<template>
    <div class="row">
        <div :class="['col-' + 12/schema.children.length]" v-for="(child, index) in schema.children" :key="index">
            <form-component :schema="child" :model="model" v-on="$listeners"></form-component>
        </div>
    </div>
</template>

<script>

    export default {
        name: "field-row",
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: false
            }
        }
    };
</script>
