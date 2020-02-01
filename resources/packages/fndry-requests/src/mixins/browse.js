
import { merge, forEach, get, debounce, unset } from 'lodash';
import { ValidationProvider, ValidationObserver } from 'vee-validate';

export const HasBrowseData = {
    props: {
        data: {
            type: Array,
            default(){return [];}
        },
        fields: {
            type: Array,
            default(){return [];}
        },
        selected: {
            type: Object,
            default(){return {};}
        },
        allSelected: {
            type: Boolean,
            default(){return false;}
        }
    }
};

export const HasFilter = {
    props: {
        filters: {
            type: Object,
            required: false
        },
        defaultParams: {
            type: Object,
            default() {
                return {
                    search: null,
                    deleted: false
                };
            }
        },
        params: {
            type: Object,
            default() {
                return {
                    search: null,
                    deleted: false
                };
            }
        }
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {

        let form = {};

        if (this.filters) {
            forEach(this.filters, (filter, name) => {
                form[name] = (filter.value) ? filter.value : ((filter.default) ? filter.default : null);
            });
        }

        return {
            filterEndpoint: {
                request: null,
                method: 'GET',
                params: {}
            },
            form: merge({}, form, this.params),
            errors: {},
            loading: false,
            fields: null
        }
    },
    created() {
        if (!this.filters && this.filterEndpoint.request) this.getFilters();
    },
    methods: {
        getFilters() {
            this.loading = true;
            this.$fndryApiService.call(this.filterEndpoint.request, this.filterEndpoint.method, this.filterEndpoint.params)
                .then((response) => {
                    this.fields = response.data;
                }).finally(() => {
                this.loading = false;
            })
            ;
        },
        onFilterInput(name, value){
            this.form = merge({}, this.form, {[name]: value});
        },
        onFilterChange(name, value){
            this.form = merge({}, this.form, {[name]: value});
        },
        onSubmit() {
            const isValid = this.$refs.observer.validate();
            if (!isValid) {
                return;
            }
            this.$emit('filter', this.form);
        }
    },
    watch: {
        params: function(newValue, oldValue){
            let form = {};
            if (this.filters) {
                forEach(this.filters, (filter, name) => {
                    form[name] = null;
                });
            }
            this.form = merge({}, form, newValue);
        }
    }
};

export const HasBrowseRequest = {

    props: {
        autoload: {
            type: Boolean,
            default: true
        }
    },
    data: function(){
        return {
            loading: false,
            defaultParams: {
                limit: 25,
                page: 1,
                orderBy: null,
                orderByDirection: null
            },
            selected: {},
            allSelected: false,
            selectedCount: 0,
            params: {},
            request: null,
            response: {
                data: []
            },
            filterActive: false,
            showFilter: false,
            limits: [10,25,50,100]
        };
    },
    computed: {
        filters: function(){
            return get(this.response, 'meta.filters', undefined);
        },
        fields: function()
        {
            let columns = [];
            if (this.selectable) {
                columns.push({
                    key: 'selectable',
                    label: 'Select'
                });
            }
            forEach(this.columns, (column) => {
                if (column.display) {
                    columns.push(column);
                }
            });
            if (!this.noActions) {
                columns.push({
                    key: 'actions',
                    label: 'Actions',
                    thClass: 'text-right buttons',
                    tdClass: 'text-right buttons'
                });
            }
            return columns;
        }
    },
    mounted: function(){
        if (this.autoload === true) {
            this.fetch();
        }
    },
    created(){
        this.params = merge({}, this.defaultParams, this.params);
    },
    methods: {
        /**
         * Pushes an item onto the data
         *
         * @param item
         */
        push(item){
            let index = findIndex(this.response.data, (_item) => _item.id === item.id);
            if (index !== -1) {
                this.response.data[index] = item;
            } else {
                this.response.data.push(item);
            }
            this.response.data = [...this.response.data];
        },

        /**
         * Pulls an item from the data
         *
         * @param item
         */
        pull(item){
            let index = findIndex(this.response.data, (_item) => _item.id === item.id);
            if (index !== -1) {
                this.response.data.splice(index, 1);
            }
            this.response.data = [...this.response.data];
        },

        fetch(){
            if (this.request == null) {
                return;
            }
            this.loading = true;
            this.$fndryApiService.call(this.$fndryApiService.getHandleUrl(this.request), this.method, this.params).then((response) => {
                this.setResponse(response);
                this.loading = false;
            });
        },
        setResponse(response){
            this.allSelected = false;
            this.response = response;
        },
        refresh(){
            this.fetch();
        },
        reload(response, params){
            this.response = response.data;
            this.params = Object.assign({}, params, {page: 1});
        },
        reset: function(){
            this.params = Object.assign({}, this.defaultParams);
            this.fetch();
        },

        toggleOrderBy: function(orderBy){
            this.params = Object.assign({}, this.params, {
                page: 1,
                orderBy: orderBy,
                orderByDirection: (this.params.orderBy !== undefined && this.params.orderBy == orderBy) ? ((this.params.orderByDirection !== undefined && this.params.orderByDirection == 'asc') ? 'desc': 'asc') : 'asc'
            });
            this.fetch();
        },
        toggleSortBy: function(sortBy, sortDesc){
            this.params = Object.assign({}, this.params, {
                page: 1,
                sortBy: sortBy,
                sortDesc: sortDesc
            });
            this.fetch();
        },

        //todo this should go into another mixin for this
        goToFirstPage: function(){
            this.goToPage(1)
        },
        goToLastPage: function(){
            this.goToPage(this.response.last_page)
        },
        goToNextPage: function(){
            this.goToPage(this.response.current_page + 1)
        },
        goToPrevPage: function(){
            this.goToPage(this.response.current_page - 1)
        },
        goToPage: function(number){
            this.params = Object.assign({}, this.params, {page: number});
            this.fetch();
        },
        onFilter: function(params){
            this.filterActive = true;
            this.params = Object.assign({}, this.params, params, {page: 1});
            this.fetch();
            if (this.$refs['filter']) {
                this.$refs['filter'].hide();
            }
        },
        onResetFilter: function(){
            this.filterActive = false;
            this.showFilter = false;
            this.params = Object.assign({}, this.defaultParams);
            this.fetch();
            if (this.$refs['filter']) {
                this.$refs['filter'].hide();
            }
        },
        toggleFilter: function(){
            this.showFilter = !this.showFilter;
        },
        onChangeLimit: function(value){
            this.params.limit = this.defaultParams.limit = value;
            this.fetch();
        },
        handleToggleSelect(id, value, updateCount = true){
            if (value === true) {
                this.selected[id] = value;
            } else {
                unset(this.selected, id);
            }
            this.selected = {...this.selected};
            this.updateSelectedCount();
        },
        handleToggleSelectAll(select){
            this.allSelected = select;
            forEach(this.response.data, (item) => {
                this.handleToggleSelect(item.id, select, false);
            });
            this.selected = {...this.selected};
            this.updateSelectedCount();
        },
        updateSelectedCount: function(){
            this.selectedCount = Object.keys(this.selected).length;
        },
        clearSelected: function(){
            this.allSelected = false;
            this.selected = {};
            this.selectedCount = 0
        },
        getSelected: function(){
            return Object.keys(this.selected);
        }

    }
};


export const HasFormRequest = {

    data: function() {
        return {
            request: null,
            form: null,
            params: {}
        };
    },

    mounted() {
        this.getForm();
    },

    methods: {
        getForm(){
            this.$fndryApiService
                .call(this.$fndryApiService.getViewUrl(this.request), 'GET', this.params)
                .then((response) => {
                    this.form = response.data;
                })
            ;
        }
    }

};
