<template>
    <div :class="[schema.class, 'content']">
        <div class="content-wrapper">
            <div class="content-body" v-html="schema.content">
            </div>
        </div>
    </div>
</template>

<script>
    import abstractWrapper from '../abstractWrapper';

    export default {
        name: "fndry-wrapper-content",
        mixins: [
            abstractWrapper
        ]
    };
</script>
