<template>
    <b-form-select
            :id="id"
            :name="name"
            v-model="model"
            v-on="$listeners"
            :multiple="schema.multiple"
            :state="state"
    >
        <option v-if="!schema.empty" :value="null">Please select an option</option>
        <option v-if="options" v-for="option in options" :value="option.value">{{option.text}}</option>
        <optgroup v-if="groups" v-for="(group_options, label) in groups" :label="label">
            <option v-for="option in group_options" :value="option.value">{{option.text}}</option>
        </optgroup>
    </b-form-select>

</template>

<script>

    import {forEach} from 'lodash';
    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-select",
        mixins: [
            abstractInput
        ],
        data() {
            return {
                options: [],
                groups: {},
                model: this.value ? this.value : []
            }
        },
        created(){
            forEach(this.schema.options, (option) => {
                const textKey = (this.schema.textKey) ? this.schema.textKey: 'text';
                const valueKey = (this.schema.valueKey) ? this.schema.valueKey: 'value';
                let _option = {
                    text: option[textKey],
                    value: option[valueKey]
                };
                if (this.schema.groupKey && option.hasOwnProperty(this.schema.groupKey)) {
                    let groupKey = this.schema.groupKey;
                    if (this.groups[option[groupKey]] === undefined) {
                        this.groups[option[groupKey]] = [];
                    }
                    this.groups[option[groupKey]].push(_option);
                } else {
                    this.options.push(_option);
                }
            });
        }
    };
</script>
