<template>
    <div>
        <component ref="child"
                   v-if="fieldVisible(schema)"
                   :is="fieldType(schema)"
                   :model="model"
                   :schema="schema"
                   v-on="$listeners"
        ></component>
    </div>
</template>
<script>

    import conditional from '../mixins/conditional';
    import {wrappers} from "../fields/index.js";

    console.log(wrappers);

    export default {
        name: "form-component",
        components: wrappers,
        mixins: [
            conditional
        ],
        props: {
            schema: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: false
            }
        },
        methods: {
            // Get type of field. It'll be the name of HTML element
            fieldType(fieldSchema) {
                let wrappers = ['section',
                    'form',
                    'row',
                    'collection',
                    'column',
                    'tag'];

                if (wrappers.includes(fieldSchema.type)) {
                    return `field-${fieldSchema.type}`;
                } else {
                    return 'form-group';
                }
            }
        }
    };
</script>
