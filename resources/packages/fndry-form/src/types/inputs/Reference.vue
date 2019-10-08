
<template>
    <div>
        <div class="autocomplete">
            <div class="input-group">
                <div v-if="schema.url && !schema.readonly" class="input-group-prepend">
                    <span class="input-group-text">
                        <span v-if="loading"><b-spinner small label="Small Spinner"></b-spinner></span>
                        <span v-else><i class="fa fa-search"></i></span>
                    </span>
                </div>
                <b-form-input
                        type="text"
                        @input="onChange"
                        :readonly="schema.readonly || !schema.url"
                        class="form-control"
                        :id="id"
                        :name="name"
                        v-model="search"
                        :placeholder="placeholder"
                        :disabled="disabled"
                        :autocomplete="schema.autocomplete"
                        :min="schema.min"
                        :max="schema.max"
                        :required="schema.required"
                        :state="state"
                        @blur="onBlur"
                        @focus="onFocus"
                ></b-form-input>
                <div class="input-group-append">
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
                    <b-button v-if="canDisplayButton('unset')" @click="unset">Unset</b-button>
                </div>
            </div>
            <small class="form-text" v-if="search && search.length < 3">
                Type {{ 3 - search.length }} or more character(s)
            </small>
            <small class="form-text" v-if="!loading && search && search.length >= 3 && results && results.length === 0">
                No result matching your search criteria found!
            </small>
            <ul
                    id="autocomplete-results"
                    v-show="open && !isEmpty(results)"
                    class="autocomplete-results"
            >
                <li
                        v-for="(result, i) in results"
                        :key="i"
                        @click.prevent="setResult(result)"
                >
                    {{ extractItem(result).text }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';
    import {debounce, isEmpty, isNull, isString, isObject, forEach, extend, merge, find} from 'lodash';

    export default {
        name: 'reference-input',
        mixins: [
            abstractInput
        ],
        props: {
            items: {
                type: Array,
                required: false,
                default: () => {},
            }
        },
        data() {
            return {
                open: false,
                results: (this.schema.options) ? [...this.schema.options]: false,
                search: '',
                reference: {},
                model: null,
                isAsync: this.schema.url,
                options: this.schema.url? []: this.items,
                loading: false
            };
        },
        mounted() {
            if (isObject(this.schema.reference) && this.value) {
                this.results = null;
                this.reference = this.schema.reference;
                let item = this.extractItem(this.reference);
                this.search = item.text;
                this.label = item.text;
                this.model = item.value;
            }
            //If we have a value already set, we need to update the UI to reflect the correct item
            else if (this.schema.options && this.value) {
                let result = this.extractResult(this.value, this.schema.options);
                let item = this.extractItem(result);
                this.search = item.text;
                this.label = item.text;
                this.model = item.value;
            }
            document.addEventListener('click', this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside)
        },
        methods: {
            isEmpty(object){
                return isEmpty(object)
            },
            onChange() {
                this.results = null;
                if(!this.loading && this.search && this.search.length >= 3){
                    this.loading = true;
                    let bounce = debounce(this.getResults, 300);
                    bounce();
                }
            },

            /**
             * Gets results from the server
             */
            getResults(){

                let params = merge({}, this.schema.params, {
                    [this.schema.query]: this.search
                });

                this.$fndryApiService.call(`${this.schema.url}`, 'GET', params)
                    .then((res) => {
                        if(res.status){
                            let results = [];
                            if (res.data.length > 0) {
                                forEach(res.data, (item) => {
                                    results.push(item)
                                });
                            }
                            this.results = results;
                        } else {
                            //todo show error
                        }
                    }, (res) => {
                        //todo show error
                    })
                    .finally(() => {
                        this.loading = false;
                        this.open = true;
                    })

            },

            extractResult(value, results){
                return find(results, (result) => result[this.valueKey] === value);
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

            /**
             * Set the result to the given entity
             *
             * This will also set the current reference on the data
             *
             * @param entity
             */
            setResult(entity) {
                if (entity === null) {
                    this.reference = null;
                    this.search = '';
                    this.label = '';
                    this.model = null;
                } else {
                    this.reference = extend({},  entity);
                    let item = this.extractItem(this.reference);
                    this.search = item.text;
                    this.label = item.text;
                    this.model = item.value;
                }

                this.$emit('change', this.model);
                this.open = false;
            },
            onBlur() {
                this.$emit('blur');
            },
            handleClickOutside(evt){
                if (!this.$el.contains(evt.target)) {
                    this.open = false;
                    if (this.model && this.search !== this.label) {
                        this.search = this.label;
                    }
                }
            },
            onFocus() {
                this.$emit('focus');
            },
            onRequestButtonSuccess({response}) {
                this.setResult(response.data);
            },
            unset(){
                this.$bvModal.msgBoxConfirm('Are you sure you want to unset the value of this field?')
                    .then((answer) => {
                        if (answer === true) {
                            this.setResult(null);
                        }
                    })
                    .catch(err => {
                        // An error occurred
                    })
            },
            canDisplayButton(type) {
                switch (type) {
                    case 'add':
                        return isNull(this.model) || this.model === undefined;
                    case 'edit':
                    case 'unset':
                        return !isNull(this.model) && this.model !== undefined;
                    default:
                        return true;
                }
            }
        },
        computed: {
            placeholder: function(){
                return this.schema.url ? 'Search...' : this.schema.placeholder;
            },
            textKey: function(){
                return this.schema.textKey ? this.schema.textKey : 'text';
            },
            valueKey: function(){
                return this.schema.valueKey ? this.schema.valueKey : 'value';
            }
        },
        watch: {
            'value': function(newValue, oldValue){
                if (isObject(newValue)) {
                    this.setResult(newValue);
                }
            }
        }

    };
</script>

<style lang="scss">
    .autocomplete {
        position: relative;
        flex: 1;

        .autocomplete-results {
            border: 0 !important;
            max-height: 180px;
            overflow: auto;
            min-height: 10px;
            width: 100%;
            position: absolute;
            -webkit-box-shadow: 10px 10px 46px -8px rgba(0,0,0,0.64);
            -moz-box-shadow: 10px 10px 46px -8px rgba(0,0,0,0.64);
            box-shadow: 10px 10px 46px -8px rgba(0,0,0,0.64);
            z-index: 95;
            float: left;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0.125rem 0 0;
            font-size: 0.9rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fafafa;
            border-radius: 0.25rem;
            background-clip: padding-box;

            li {
                list-style: none;
                text-align: left;
                padding: 5px 10px;
            }

            li.loading {
                padding: 20px 0;
                text-align: center;
            }
            li.hint p{
                text-align: center;
                margin-bottom: 5px;
            }
            li.empty p{
                text-align: center;
                margin-bottom: 5px;
            }
            li {
                margin: 0;
                font-size: 1rem;
                cursor: pointer;
                border-bottom: 1px solid #fff;

                &:last-child {
                    border: none;
                }
                &.is-active,
                &:hover {
                    background: #e4e4e4;
                    color: darken(#006fe6, 0.5);
                }
            }

        }
    }


</style>
