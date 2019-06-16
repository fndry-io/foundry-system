<template>
    <div>
        <div v-for="(child, index) in schema.children" :key="index">
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
