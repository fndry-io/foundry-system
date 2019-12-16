<template>
    <div class="row">
        <div v-for="(child, index) in schema.children" :key="index" v-if="child.visible" :class="cols(child)">
            <fndry-form-type :schema="child" :model="model" :errors="errors" v-on="$listeners"></fndry-form-type>
        </div>
    </div>
</template>

<script>

    import {isObject, forEach} from 'lodash';

    import abstractWrapper from '../abstractWrapper';

    export default {
        name: "fndry-wrapper-row",
        mixins: [
            abstractWrapper
        ],
        methods: {
            cols(schema){
                let cols = ['col'];
                if (schema.cols) {
                    if (isObject(schema.cols)) {
                        forEach(schema.cols, (size, type) => {
                            cols.push(`col-${type}-${size}`);
                        });
                    } else {
                        cols.push(`col-${schema.cols}`);
                    }
                }
                return cols;
            }
        }
    };
</script>
