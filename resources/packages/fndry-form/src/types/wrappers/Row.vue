<template>
    <div class="row">
        <div :class="['col-md-' + 12/schema.children.length]" v-for="(child, index) in schema.children" :key="index">
            <fndry-form-type :schema="child" :model="model" :errors="errors" v-on="$listeners"></fndry-form-type>
        </div>
    </div>
</template>

<script>
    import abstractWrapper from '../abstractWrapper';

    export default {
        name: "fndry-wrapper-row",
        mixins: [
            abstractWrapper
        ]
    };
</script>
