<template>
    <div :class="{'select-dropdown': true, 'open': open}">
        <div ref="input-group" class="input-group-wrapper">
            <div class="input-group">

                <div :class="{'form-control': true, 'input-area': true, 'is-invalid': state === false, 'is-valid': state === true, 'disabled': schema.disabled}">
                    <div :id="id" class="selected-values" v-if="!open && selected.length > 0" @click.stop="handleClick" tabindex="0" @focus="handleClick">
                        <div>{{text}}&nbsp;</div>
                    </div>
                    <div :id="id" class="input" v-if="open || selected.length == 0">
                        <input ref="search" :placeholder="searchPlaceholderText" class="form-control" v-model="search" @input="handleSearch" @keyup.tab="handleSearchEnter" @focus="handleFocus">
                    </div>
                    <span class="dropdown-toggle" @click.stop="handleClick"></span>
                </div>

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

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="!schema.disabled">
                <!--<div class="dropdown-item-text dropdown-item-search" v-if="selected.length > 0">-->
                <!--<b-form-input ref="search" :placeholder="searchPlaceholderText" v-model="search" @input="handleSearch" @keyup.tab="handleSearchEnter"></b-form-input>-->
                <!--<button type="button" class="close" aria-label="Close" v-if="search !== '' && !searching" @click.stop="resetSearch">-->
                <!--<span aria-hidden="true">&times;</span>-->
                <!--</button>-->
                <!--</div>-->
                <div class="dropdown-items">
                    <div v-if="searching">
                        <div class="dropdown-item-text">Searching... <span class="append"><b-spinner small label="Searching..."></b-spinner></span></div>
                    </div>
                    <div v-if="search && !searching && this.search.length < 3">
                        <div class="dropdown-item-text">{{searchTextStatus}}</div>
                    </div>
                    <div v-else-if="!errorText && (ajaxOptions.length > 0 || optionKeys.length > 0)">
                        <h6 class="dropdown-header">{{selectTitle}}</h6>
                    </div>

                    <div v-if="ajaxOptions && ajaxOptions.length > 0">
                        <div class="dropdown-item" v-for="(option, index) in ajaxOptions" :key="`${key}-${option[valueKey]}`" @click.stop="() => selectSearchItem(option, index)" @keyup.enter="(evt) => {evt.preventDefault(); selectSearchItem(option, index);}" tabindex="0">{{option[textKey]}}</div>
                    </div>

                    <div v-if="ajaxOptions && ajaxOptions.length > 0 && optionKeys && optionKeys.length > 0" class="dropdown-divider"></div>

                    <div v-if="optionKeys && optionKeys.length > 0">
                        <div class="dropdown-item" v-for="k in optionKeys" :key="`${key}-${options[k][valueKey]}`" @click.stop="() => selectItem(options[k])" @keyup.enter="(evt) => {evt.preventDefault();selectItem(options[k]);}" tabindex="0"><span v-if="options[k].temp" class="append"><b-spinner variant="secondary" small label="Saving..."></b-spinner></span><span v-if="isSelected(options[k]) !== false" class="append icon fa fa-check"></span>{{options[k][textKey]}}</div>
                    </div>

                    <div v-if="!searching && (options && optionKeys.length === 0) && (ajaxOptions && ajaxOptions.length === 0)">
                        <div class="dropdown-item-text">{{noOptionsText}}</div>
                    </div>

                    <div v-if="errorText">
                        <h6 class="dropdown-header">{{errorTitleText}}</h6>
                        <div class="dropdown-item-text">{{errorText}}</div>
                    </div>

                    <!--<div v-if="groups" v-for="(group_options, label) in groups" :key="`group-${key}-${label}`">-->
                    <!--<h6 class="dropdown-header">{{label}}</h6>-->
                    <!--<a class="dropdown-item" v-if="options" v-for="option in group_options" :key="`${key}-${option.value}`" href="#" @click.stop="() => selectItem(option)">{{option.text}}</a>-->
                    <!--</div>-->
                    <div v-if="search && !exactMatchExists && schema.taggable && !errorText">
                        <h6 class="dropdown-header">{{addTagText}}</h6>
                        <a class="dropdown-item" v-if="canEnterToAdd && search" @click.stop="() => addTaggable(search)" @keyup.enter="(evt) => {evt.preventDefault();addTaggable(search);}" tabindex="0"><span class="append badge badge-secondary">Click item to add</span>{{search}}</a>
                    </div>
                </div>
                <div class="dropdown-item-text dropdown-item-footer" v-if="schema.multiple && !searching && options.length > 0">
                    <ul>
                        <li v-if="!schema.max"><a href="#" @click.prevent="selectAll">Select All</a></li>
                        <li><a href="#" @click.prevent="selectNone">Select None</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="badges" v-if="schema.taggable && selected.length > 0">
            <span class="badge badge-primary" v-for="option in selected">{{option[textKey]}}<button type="button" class="close" aria-label="Remove" @click.stop="() => selectItem(option)" tabindex="0"><span aria-hidden="true">&times;</span></button></span>
        </div>
    </div>

</template>

<script>

    import {forEach, isObject, isString, isEmpty, findIndex, merge, inArray, keys, map, uniqueId, isEqual, debounce, find} from 'lodash';
    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-select",
        mixins: [
            abstractInput
        ],
        data() {
            return {
                key: 0,
                text: "",
                placeholder: (this.schema.placeholder) ?  this.schema.placeholder : "Select an option...",
                search: '',
                searching: false,
                ajaxOptions: [],
                exactMatchExists: false,
                errorText: null,
                adding: false,
                open: false,
                options: [],
                optionKeys: [],
                selected: (this.schema.multiple) ? [] : null,
                model: (this.schema.multiple) ? [] : null
            }
        },
        mounted(){
            this.setOptions();
            document.addEventListener('click', this.handleClickOutside);
            document.addEventListener('focusin', this.handleClickOutside);
            // document.addEventListener('blur', this.handleClickOutside);
            this.setSelected(this.value);
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside);
            document.removeEventListener('focusin', this.handleClickOutside);
            // document.removeEventListener('blur', this.handleClickOutside);
        },
        created() {
            this.text = this.placeholder;
        },
        methods: {
            setOptions(){
                this.options = [...this.schema.options];
                this.optionKeys = keys(this.options);
                this.key++;
            },
            setSelected(value){
                if (Array.isArray(value)) {
                    forEach(value, (_value) => {
                        let option = find(this.options, (option) => {
                            return option[this.valueKey] === _value
                        });
                        if (option) {
                            this.selectItem(option, false);
                        }
                    })
                } else if (value !== null) {
                    let option = find(this.options, (option) => option[this.valueKey] === value);
                    if (option) {
                        this.selectItem(option, false);
                    }
                }
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
                    this.options[index] = response.data;
                } else {
                    this.options.push(response.data);
                }
                this.options = [...this.options];
                this.optionKeys = keys(this.options);
            },
            getButtonParams(button){
                return merge({}, this.schema.params, button.params, {
                    _entity: this.model
                })
            },

            onModelChange() {
                this.$emit('input', this.model);
            },
            onValueChange(value) {
                if (this.schema.multiple) {
                    if (value === null) {
                        this.selected = [];
                    } else {
                        forEach(value, (_value) => {
                            let option = find(this.options, (_option) => _option[this.valueKey] === _value);
                            if (option) {
                                this.selectItem(option, false);
                            }
                        })
                    }
                } else {
                    let option = find(this.options, (_option) => _option[this.valueKey] === value);
                    if (option) {
                        this.selectItem(option, false);
                    }
                }
            },

            handleClick(){
                this.open = !this.open;
                if (this.open && this.schema.searchable) {
                    this.handleFocus();
                }
                if (this.open) {
                    this.errorText = null;
                }
            },
            handleFocus(){
                this.open = true;
                this.$nextTick(() => {
                    this.$refs['search'].focus();
                    this.search = "";
                    this.handleSearch();
                });
            },
            handleBlur(){
                this.open = false;
                this.search = null;
                this.$emit('blur');
            },
            handleClickOutside(evt){
                if (this.$refs['input-group'].contains(evt.target)) {
                    this.open = true;
                } else if (!this.$refs['input-group'].contains(evt.target) && this.open) {
                    this.handleBlur();
                }
            },

            selectSearchItem(option, index) {
                //move it out of the search results
                this.ajaxOptions.splice(index, 1);
                this.addSelectedOption(option, 'top');
                this.selectItem(option);
                this.resetSearch();
            },
            selectItem(option, emit = true){
                let index = this.isSelected(option);
                if (this.schema.multiple) {
                    if (index !== false) {
                        this.selected.splice(index, 1);
                    } else {
                        if (!this.schema.max || this.selected.length < this.schema.max) {
                            this.selected.push(option);
                        }
                    }
                    this.selected = [...this.selected];
                } else {
                    if (index !== false) {
                        this.selected = null;
                    } else {
                        this.selected = option;
                    }
                }
                this.updateTextAndModel(emit);
            },
            addItem(text){
                let option = {
                    [this.textKey]: text,
                    [this.valueKey]: uniqueId('item_')
                };
                this.addSelectedOption(option);
                this.selectItem(option);
                this.resetSearch();
            },
            handleSearchEnter(evt){
                evt.preventDefault();
                if (this.search !== "") {
                    this.selectItem(this.search);
                }
            },
            addSelectedOption(option, place = 'bottom'){
                let index = findIndex(this.options, (_option) => _option[this.valueKey] === option[this.valueKey]);
                if (index === -1) {
                    if (place === 'bottom') {
                        this.options.push(option);
                    } else {
                        this.options.unshift(option);
                    }
                    this.options = [...this.options];
                    this.optionKeys = keys(this.options);
                }
            },
            selectAll(){
                this.selected = [...this.options];
                this.updateTextAndModel();
            },
            selectNone(){
                this.selected = [];
                this.updateTextAndModel();
            },
            isSelected(option){
                if (isEmpty(this.selected) || isEmpty(option)) {
                    return false;
                }
                if (this.schema.multiple) {
                    let index = findIndex(this.selected, (_option) => _option[this.valueKey] === option[this.valueKey]);
                    if (index === -1) {
                        return false;
                    } else {
                        return index;
                    }
                } else {
                    return this.selected[this.valueKey] === option[this.valueKey];
                }
            },
            resetSearch(){
                this.search = "";
                this.errorText = null;
                this.optionKeys = keys(this.options);
            },
            handleSearch(){
                this.open = true;
                this.localSearch();
                if (this.schema.url && this.search.length > 2) {
                    this.ajaxOptions = [];
                    this.searching = true;
                    this.ajaxSearch();
                }
                if (this.search.length < 3) {
                    this.updateSearchText(3 - this.search.length);
                }
            },
            updateSearchText(number){
                this.searchTextStatus = `Enter ${number} more character(s)`;
            },
            localSearch(){
                this.exactMatchExists = false;
                let _keys = [];
                if (this.search !== "") {
                    forEach(this.options, (option, key) => {
                        if (option[this.textKey].toLowerCase().indexOf(this.search.toLowerCase()) !== -1) {
                            _keys.push(key);
                            if (option[this.textKey].toLowerCase() === this.search.toLowerCase()) {
                                this.exactMatchExists = true;
                            }
                        }
                    });
                } else {
                    _keys = keys(this.options);
                }
                this.optionKeys = _keys;
            },
            updateTextAndModel(emit = true){
                if (this.selected === null || this.selected.length === 0) {
                    this.text = this.placeholder;
                    this.model = null;
                } else if (this.schema.multiple) {
                    this.model = map(this.selected, this.valueKey);
                    if (this.selected.length <= 2) {
                        this.text = map(this.selected, this.textKey).join(', ');
                    } else {
                        this.text = `${this.selected.length} selected`;
                    }
                } else {
                    this.model = this.selected[this.valueKey];
                    this.text = this.selected[this.textKey];
                }
                if (emit) {
                    this.onModelChange();
                }
            },


            ajaxSearch: debounce(function(){

                this.searching = true;
                this.errorText = null;

                let params = merge({}, this.schema.params, {
                    [this.queryParam]: this.search
                });

                return this.$fndryApiService.call(`${this.schema.url}`, 'GET', params)
                    .then((res) => {
                        let results = [];
                        if (res.data.length > 0) {
                            forEach(res.data, (item) => {
                                results.push(item)
                            });
                        }
                        this.ajaxOptions = results;
                    })
                    .catch(({error}) => {
                        this.ajaxOptions = [];
                        this.errorText = error;
                    })
                    .finally(() => {
                        this.searching = false;
                    })
                ;
            }, 500),

            addTaggable(value){

                let id = uniqueId('item_');

                this.errorText = null;

                let option = {
                    [this.textKey]: value,
                    [this.valueKey]: !!(this.schema.taggableUrl) ? id : value,
                    temp: !!(this.schema.taggableUrl)
                };
                this.addSelectedOption(option);
                this.selectItem(option);
                this.resetSearch();

                if (this.schema.taggableUrl) {
                    let params = merge({}, this.schema.params, {
                        [this.textKey]: value
                    });

                    return this.$fndryApiService.call(`${this.schema.taggableUrl}`, 'POST', params)
                        .then(({data}) => {
                            //replace the add item
                            let oIndex = findIndex(this.options, (_option) => _option[this.valueKey] === id);
                            let sIndex = findIndex(this.selected, (_option) => _option[this.valueKey] === id);
                            this.options.splice(oIndex, 1, data);
                            this.selected.splice(sIndex, 1, data);
                        }).catch(({error}) => {
                            //replace the add item
                            let oIndex = findIndex(this.options, (_option) => _option[this.valueKey] === id);
                            let sIndex = findIndex(this.selected, (_option) => _option[this.valueKey] === id);
                            this.options.splice(oIndex, 1);
                            this.selected.splice(sIndex, 1);

                            this.options = [...this.options];
                            this.optionKeys = keys(this.options);
                            this.updateTextAndModel();

                            this.errorText = error;
                        })
                        ;
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
            },
            searchPlaceholderText(){
                return (this.schema.searchText) ? this.schema.searchText : "Type to search...";
            },
            selectTitle: function(){
                if (this.schema.multiple) {
                    if (this.schema.required) {
                        if (this.schema.max && this.schema.max > 1) {
                            return 'Select between 1 and ' + this.schema.max + ' options';
                        } else {
                            return 'Select at least one option';
                        }
                    } else {
                        if (this.schema.max) {
                            return 'Select up to ' + this.schema.max + ' option(s)';
                        } else {
                            return 'Select one or more';
                        }
                    }
                } else {
                    return 'Select one option';
                }
            },
            noOptionsText: function(){
                return 'No options found';
            },
            canEnterToAdd: function(){
                return true;
            },
            addTagText: function(){
                return 'Create a new tag';
            },
            queryParam: function(){
                return (this.schema.query) ? this.schema.query : 'q';
            },
            errorTitleText: function(){
                return 'Oops, looks like there was an error';
            },
            showTags: function(){
                return this.schema.showTags;
            }
        },
        watch: {
            value: function(newVal, oldVal){
                if (newVal !== oldVal && (newVal === null || newVal === undefined)) {
                    this.selected = [];
                    this.model = [];
                }
            }
        }
    };
</script>


<style lang="scss">
    .select-dropdown {
        position: relative;

        .form-control {
            cursor: pointer;
            height: auto;

            &:invalid, &.is-invalid,
            &:valid, &.is-valid {
                background-position: right calc(1.5em + 0.4rem) center;
                .dropdown-toggle {
                    right: calc(0.75rem);
                }
                .badges {
                    padding-right: calc(1.5em + 0.75rem);
                }

            }
        }

        &.open {
            .dropdown-toggle::after {
                transform: rotate(180deg);
            }
            .dropdown-menu {
                display: block;
            }
        }

        .input-area {
            display: block;
            width: 100%;
            padding: 0 !important;
            position: relative;

            .dropdown-toggle {
                height: 100%;
                width: 1.9em;
                position: absolute;
                text-align: center;
                top: 0;
                right: 0 !important;
                padding-top: 0.5em;

                &::after {
                    -webkit-transition: all 250ms; /* Safari prior 6.1 */
                    transition: all 250ms;
                }
            }
        }

        .input-group-wrapper {
            position: relative;
        }

        .input {
            display: block;
            width: 100%;
            position: relative;
            padding-right: calc((1em + 0.75rem) * 3 / 4 + 1.75rem);

            .form-control {
                border: 0;
                background: none;
            }

        }


        .dropdown-menu {
            display: none;
            top: 100%;
            left: 0;
            right: 0;
            margin: 0;
            padding: 0;
            cursor: initial;
            z-index: 10;

            .dropdown-item-search {
                position: relative;
                padding: 0.5rem !important;
                border-bottom: 1px solid #efefef;

                input {
                    cursor: text;
                }

                button.close {
                    position: absolute;
                    top: .5rem;
                    right: .5rem;
                    padding: .1rem .75rem;
                    line-height: 1.45;
                }

                .searching {
                    position: absolute;
                    top: .5rem;
                    right: .5rem;
                    bottom: .5rem;
                    padding: .1rem .75rem;
                }
            }

            .dropdown-items {
                overflow-x: auto;
                max-height: 250px;
            }

            .dropdown-item-footer {
                font-size: 0.8rem;
                padding: 0.75rem 1.25rem;
                border-top: 1px solid #efefef;

                ul, li {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }

                li {
                    display: inline-block;
                    &:after {
                        content: " | ";
                        display: inline-block;
                        padding: 0 0.25rem;
                    }
                    &:last-child {
                        &:after {
                            content: "";
                            display: none;
                        }
                    }
                }
            }

            .dropdown-header,
            .dropdown-item,
            .dropdown-item-text {
                padding: 0.5rem 1.25rem;
                white-space: normal;
                .append {
                    float: right;
                    display: inline-block;
                    margin-left: 5px;
                }
                .icon {
                    color: darkgreen;
                    padding: 0.25rem 0;
                }
                .badge {
                    display: inline-block;
                    margin: 0.25rem 0;
                }
            }
            .dropdown-item {
                cursor: pointer;
            }
        }

        .selected-values {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
        }

        .badges {
            line-height: 1;
            display: block;
            margin: 0.5rem 0 0;
        }

        .badge {
            position: relative;
            display: inline-block;
            padding-right: 20px;
            /*margin: 3px 3px 0 0;*/
            overflow: hidden;

            .close {
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 18px;
                font-size: 0.8rem;

                &:hover {
                    background: rgba(0,0,0,0.25);
                }
            }
        }

    }


</style>
