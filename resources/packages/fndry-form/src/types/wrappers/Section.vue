<template>
    <div :class="[schema.class, 'section']" :id="schema.id">
        <div class="section-wrapper">
            <div class="section-title">
                <h4>{{schema.title}}</h4>
                <p v-if="schema.description">{{schema.description}}</p>
            </div>
            <div class="section-body">
                <div v-for="child in schema.children">
                    <fndry-form-type :schema="child" :model="model" :errors="errors" v-on="$listeners"></fndry-form-type>
                </div>
            </div>
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
