<template>
	<div :id="schema.id"></div>
</template>

<script>

import abstractField from "../abstractField";

export default {
	mixins: [abstractField],
    mounted(){
	    let $class = this.schema.class? this.schema.class: "";
	    let html = `<${this.schema.tag} class="${$class}">${this.schema.content}</${this.schema.tag}>`;
	    $(`#${this.schema.id}`).html(html);
    },
};
</script>
