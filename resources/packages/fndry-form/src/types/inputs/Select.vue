<template>
    <div class="input-group">
        <b-form-select
                :id="id"
                :name="name"
                v-model="model"
                v-on="$listeners"
                :multiple="schema.multiple"
                :state="state"
        >
            <option v-if="schema.empty && !schema.multiple" :value="null">{{(schema.empty === true) ? 'Please select an option' : schema.empty}}</option>
            <option v-if="options" v-for="option in options" :value="option.value" :key="`${key}-${option.value}`">{{option.text}}</option>
            <optgroup v-if="groups" v-for="(group_options, label) in groups" :label="label" :key="`group-${key}-${option.value}`">
                <option v-for="option in group_options" :value="option.value" :key="`${key}-${option.value}`">{{option.text}}</option>
            </optgroup>
        </b-form-select>
        <div class="input-group-append" v-if="schema.buttons">
            <fndry-request-button
                    v-for="(button, index) in schema.buttons"
                    :key="index"
                    v-if="canDisplayButton(button.type)"
                    :request="button.action"
                    :params="{_entity: model, ...schema.params}"
                    :variant="button.variant ? button.variant : ``"
                    :size="button.size ? button.size : `size`"
                    type="modal"
                    :button-icon="button.icon"
                    :button-text="button.label"
                    @success="onRequestButtonSuccess"
            ></fndry-request-button>
        </div>
    </div>

</template>

<script>

    import {forEach, isObject, isString, isEmpty, findIndex} from 'lodash';
    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-select",
        mixins: [
            abstractInput
        ],
        data() {
            return {
                key: 0,
                options: [],
                groups: {},
                model: (this.value ? this.value : ((this.schema.multiple) ? [] : null))
            }
        },
        mounted(){
            this.setOptions();
        },
        methods: {
            setOptions(){
                this.options = [];
                forEach(this.schema.options, (option) => {
                    let _option = this.extractItem(option);
                    if (this.groupKey) {
                        if (this.groups[option[this.groupKey]] === undefined) {
                            this.groups[option[this.groupKey]] = [];
                        }
                        this.groups[option[this.groupKey]].push(_option);
                    } else {
                        this.options.push(_option);
                    }
                });
                this.key++;
            },
            canDisplayButton(type) {
                switch (type) {
                    case 'edit':
                        return !this.schema.multiple && this.model;
                    case 'add':
                    default:
                        return true;
                }
            },
            onRequestButtonSuccess({response}) {
                let index = findIndex(this.schema.options, (option) => option[this.valueKey] === response.data[this.valueKey]);
                if (index !== -1) {
                    this.schema.options[index] = response.data;
                } else {
                    this.schema.options.push(response.data);
                }
                this.schema.options = [...this.schema.options];
                this.setOptions();
                this.setValue(response.data[this.valueKey]);
            },
            setValue(value) {
                if (this.schema.multiple) {
                    this.model.push(value);
                } else {
                    this.model = value;
                }
                this.$emit('change', this.model);
            },
            /**
             * Extracts the text and value from the given entity/item
             *
             * The idea here is the results/options can be an array of entities, allowing us to easily extract what we need
             *
             * @param item
             * @return {{text: *, value: *}}
             */
            extractItem(item){
                return {
                    text: this.extractItemText(item, this.textKey),
                    value: item[this.valueKey]
                };
            },
            extractItemText(item, key){
                if (isObject(key)) {
                    let {fields, join} = key;
                    let arr = [];
                    forEach(fields, (key) => {
                        if (!isEmpty(item[key])) {
                            arr.push(item[key]);
                        }
                    });
                    return arr.join(join);
                } else if(isString(key)) {
                    return item[key];
                }
            },
        },
        computed: {
            textKey: function(){
                return this.schema.textKey ? this.schema.textKey : 'text';
            },
            valueKey: function(){
                return this.schema.valueKey ? this.schema.valueKey : 'value';
            },
            groupKey: function(){
                return this.schema.valueKey ? this.schema.groupKey : null;
            }
        }
    };
</script>
