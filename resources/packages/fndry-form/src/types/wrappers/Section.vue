<template>
    <div :class="[schema.class, 'section']" :id="schema.id">
        <h4 class="section-title">{{schema.title}}</h4>
        <p v-if="schema.description">{{schema.description}}</p>
        <div v-for="child in schema.children">
            <fndry-form-type :schema="child" :model="model" :errors="errors" v-on="$listeners"></fndry-form-type>
        </div>
    </div>
</template>

<script>
    import abstractWrapper from '../abstractWrapper';

    export default {
        name: "fndry-wrapper-section",
        mixins: [
            abstractWrapper
        ]
    };
</script>
