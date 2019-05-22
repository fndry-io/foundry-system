<template>
	<span :id="getFieldID(schema)" :class="schema.class">{{ value }}</span>
</template>

<script>
import abstractField from "../abstractField";

export default {
	mixins: [abstractField]
};
</script>

<style lang="scss">

</style>
