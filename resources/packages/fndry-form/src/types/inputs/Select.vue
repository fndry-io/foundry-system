<template>
    <div :class="{'input-group': true, 'input-group-sm': schema.size || size}">
        <div v-if="prepend || schema.prepend" class="input-group-prepend">
            <span class="input-group-text">{{prepend || schema.prepend}}</span>
        </div>

        <b-form-select
                :id="id"
                :name="name"
                v-model="model"
                v-on="$listeners"
                :multiple="multiple || schema.multiple"
                :state="state"
                :disabled="disabled"
                :size="schema.size || size"
        >
            <option v-if="schema.empty && !schema.multiple" value="">{{(schema.empty === true) ? 'Please select an option' : schema.empty}}</option>
            <option v-if="options" v-for="option in options" :value="option.value" :key="`${key}-${option.value}`">{{option.text}}</option>
            <optgroup v-if="groups" v-for="(group) in groups" :label="group.label" :key="`group-${key}-${group.label}`">
                <option v-for="option in group.values" :value="option.value" :key="`${key}-${option.value}`">{{option.text}}</option>
            </optgroup>
        </b-form-select>

        <div class="input-group-append" v-if="schema.buttons">
            <fndry-request-button
                    v-for="(button, index) in schema.buttons"
                    :key="index"
                    v-if="canDisplayButton(button.type)"
                    :request="button.action"
                    :params="getButtonParams(button)"
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

    import {forEach, isObject, isString, isEmpty, findIndex, merge, debounce, find} from 'lodash';
    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-select",
        mixins: [
            abstractInput
        ],
        props: {
            multiple: Boolean
        },
        data() {
            return {
                key: 0,
                options: [],
                groups: {},
                selected: [],
                model: [],
                loading: false
            }
        },
        mounted(){
            this.setOptions();
            this.model = this.value !== undefined ? this.value : ((this.schema.multiple) ? [] : null);
            forEach(this.model, (value) => {
                let selected = find(this.options, (option) => option.value === value);
                if (selected) {
                    this.selected.push(selected);
                }
            });
        },
        methods: {
            setOptions(){
                this.options = [];
                let groups = {};
                forEach(this.schema.options, (option) => {
                    let _option = this.extractItem(option);
                    if (this.groupKey && option.hasOwnProperty(this.groupKey)) {
                        if (this.groups[option[this.groupKey]] === undefined) {
                            this.groups[option[this.groupKey]] = {
                                label: option[this.groupKey],
                                values: []
                            };
                        }
                        this.groups[option[this.groupKey]].values.push(_option);
                    } else {
                        this.options.push(_option);
                    }
                });
                if (this.groups) {
                    forEach(groups, (group) => {
                        this.options.push(group);
                    })
                }
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
                this.model = value;
                if (value !== this.value) {
                    this.$emit('change', this.model);
                }
            },
            removeValue(value) {
                this.model = null;
                this.$emit('change', this.model);
            },
            getResults(query) {
                this.loading = true;

                let params = merge({}, this.schema.params, {
                    [this.schema.query]: query
                });

                this.$fndryApiService.call(`${this.schema.url}`, 'GET', params)
                    .then((res) => {
                        let results = [];
                        if (res.data.length > 0) {
                            forEach(res.data, (item) => {
                                results.push({
                                    [this.textKey]: item,
                                    [this.valueKey]: item
                                })
                            });
                        }
                        this.options = results;
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                ;
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
            getButtonParams(button){
                return merge({}, this.schema.params, button.params, {
                    _entity: this.model
                })
            }
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
        },
        watch: {
            value: function(newVal, oldVal){
                if (newVal !== oldVal) {
                    this.model = (newVal !== null && newVal !== undefined) ? newVal : ((this.schema.multiple) ? [] : null);
                    forEach(this.model, (value) => {
                        let selected = find(this.options, (option) => option.value === value);
                        if (selected) {
                            this.selected.push(selected);
                        }
                    });
                }
            }
        }
    };
</script>
