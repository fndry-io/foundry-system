
<template>
    <div :class="getHolderClasses(schema)">
        <div class="autocomplete">
            <div :class="getInputWrapperClasses(schema)">
                <input
                        type="text"
                        @input="onChange"
                        v-model="search"
                        :readonly="schema.readonly || !schema.url"
                        :required="schema.required"
                        :name="getFieldName(schema)"
                        :id="getFieldID(schema)"
                        :invalid="isInvalid()"
                        class="form-control"
                        :placeholder="getPlaceholder(schema)">
                <span v-if="schema.url" class="k-input-icon__icon k-input-icon__icon--left">
                    <span><i class="la la-search"></i></span>
                </span>
            </div>
            <ul
                    id="autocomplete-results"
                    v-show="isOpen"
                    class="autocomplete-results"
            >
                <li class="hint"
                    v-if="search === undefined || (search && search.length < 3 && isEmpty(results))">
                    <p>Type 3 or more characters</p>
                </li>
                <li class="empty"
                    v-if="!isLoading && search && search.length > 3 && isEmpty(results)">
                    <p>No result matching your search criteria found!</p>
                </li>
                <li
                        class="loading"
                        v-if="isLoading"
                >
                    <span class="k-spinner k-spinner--lg k-spinner--focus"></span>
                </li>
                <li
                        v-else
                        v-for="(result, i) in results"
                        :key="i"
                        @click="setResult(result)"
                        class="autocomplete-result"
                >
                    {{ result }}
                </li>
            </ul>
        </div>
        <div v-if="buttonVisibility(schema)" class="input-group-append">
            <action-button
                    v-for="(btn, index) in schema.buttons"
                    v-if="getButtonVisibility(btn, value)"
                    :title="btn.title"
                    :key="index"
                    :callback="onRelatedFormSubmitted"
                    :type="getButtonType(btn)"
                    :class="getButtonClasses(btn)"
                    :schema = "schema"
                    :action="getButtonAction(btn, vfg)"
                    :label="btn.label"
            ></action-button>
            <action-button
                    v-if="value && !schema.required"
                    :callback="onRelatedFormDeleted"
                    type="confirm"
                    message="Are you sure you want to unset the value of this field?"
                    class="btn btn-danger"
                    :schema = "schema"
                    label="Unset"
            ></action-button>
        </div>
    </div>
</template>

<script>

    import abstractField from "../abstractField";
    import {debounce, isEmpty} from 'lodash';
    import ApiService from "../../../services/ApiService";

    export default {
        name: 'autocomplete',
        mixins: [abstractField],
        props: {
            items: {
                type: Array,
                required: false,
                default: () => {},
            }
        },

        data() {
            return {
                isOpen: false,
                results: [],
                search: '',
                isAsync: this.schema.url,
                options: this.schema.url? []: this.items,
                isLoading: false,
                arrowCounter: 0,
            };
        },

        methods: {
            isEmpty(object){
                return isEmpty(object)
            },
            getHolderClasses(schema){
                return `${schema.buttons && schema.buttons.length > 0? 'input-group': ''}`;
            },
            getPlaceholder(schema){
                return `${schema.url? 'Search...': schema.placeholder}`
            },
            getInputWrapperClasses(schema){
                return `${schema.url? 'k-input-icon k-input-icon--left': ''}`;
            },
            onChange() {
                this.isOpen = true;
                // Is the data given by an outside ajax request?
                if (this.isAsync) {
                    if(!this.isLoading && this.search && this.search.length >= 3){
                        //console.log('searching');
                        this.isLoading = true;
                        let that = this;
                        let bounce = debounce(function(){
                            that.getItems(that.search);
                        }, 300);
                        bounce();

                    }
                } else{
                    this.filterResults();
                }
            },
            getItems(param){

                let that = this;

                ApiService.call(`${this.schema.url}`, 'GET', {[this.schema.query_param]: param})
                    .then((res) => {
                        if(res.status){
                            that.results = res.data;
                        }else{
                            //todo show error
                        }

                    }, (res) => {
                        //todo show error
                       //console.log(res);
                    })
                    .finally(() => {
                        that.isLoading = false;
                       //console.log('finally');
                    })

            },
            filterResults() {
                let object = this.options;
                this.results = Object.keys(object).filter((key) => {
                    return object[key].toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                });
            },
            setResult(result) {
                this.search = result;
                let object = this.results;
                let v = Object.keys(object).find(key => object[key] === result);
                if(v){
                    this.updateModelValue(v.toString());
                    this.isOpen = false;
                }

            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            },
            handleKeyDown(evt){
                if (this.$el.contains(evt.target)) {
                    if(this.vfg.model[this.schema.model]){
                        this.updateModelValue('');
                    }
                }
            }
        },
        watch: {
        },
        mounted() {
            document.addEventListener('click', this.handleClickOutside);
            $(document).on('keydown', this.handleKeyDown);
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside)
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
            webkit-box-shadow: 0px 0px 50px 0px rgba(82, 63, 105, 0.15);
            box-shadow: 0 0 50px 0 rgba(82, 63, 105, 0.15);
            z-index: 95;
            float: left;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0.125rem 0 0;
            font-size: 0.9rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            border-radius: 0.25rem;
            background-clip: padding-box;

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
            .autocomplete-result {
                list-style: none;
                text-align: left;
                padding: 4px;
                cursor: pointer;
                border-bottom: 1px solid #fff;
            }

            .autocomplete-result.is-active,
            .autocomplete-result:hover {
                background: #f7f8fa;
                color: #212529;
            }

        }
    }


</style>
