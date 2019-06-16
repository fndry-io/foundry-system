<template>
    <span>
        <component :is="fieldType(schema)"
                   :schema="schema"
                   :model="model"
                   :errors="errors"
                   v-on="$listeners"
        ></component>
    </span>
</template>
<script>

    import conditional from '../mixins/conditional';
    import {wrappers} from "../types/index.js";

    /**
     * Foundry Form Type
     *
     * A class to control the rendering of sub components based on the schema
     *
     * The purposes of this class is to control whether we use a wrapper component or the form group component to render
     * inputs
     *
     */
    export default {
        name: "fndry-form-type",
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
            },
            errors: {
                type: Object,
                required: false
            }
        },
        methods: {
            // Get type of field. It'll be the name of HTML element
            fieldType(fieldSchema) {
                let wrappers = [
                    'section',
                    'form',
                    'row',
                    'collection',
                    'column',
                    'tag'
                ];

                if (wrappers.includes(fieldSchema.type)) {
                    return `fndry-wrapper-${fieldSchema.type}`;
                } else {
                    return 'fndry-form-group';
                }
            }
        }
    };
</script>
