
import { merge } from 'lodash';
import { ValidationProvider, ValidationObserver } from 'vee-validate';

export const HasFilter = {
    props: {
        params: {
            type: Object,
            default() {
                return {
                    search: null,
                    deleted: 'deleted'
                };
            }
        }
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            form: merge({}, this.params)
        }
    },
    methods: {
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
            this.form = newValue;
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
                page: 1,
                orderBy: null,
                orderByDirection: null
            },
            params: {},
            request: null,
            response: {
                data: []
            },
            filterActive: false
        };
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
        setData(data){
            this.response = data;
        },

        /**
         * Pushes an item onto the data
         *
         * @param item
         */
        push(item){
            let index = findIndex(this.data, (_item) => _item.id === item.id);
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
            this.$fndryApiService.call(this.$fndryApiService.getHandleUrl(this.request), this.method, this.params).then(({data}) => {
                this.setData(data);
                this.loading = false;
            });
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
            this.params = Object.assign({}, this.params, params, {page: 1});
            this.fetch();
            this.filterActive = true;
            if (this.$refs['filter']) {
                this.$refs['filter'].hide();
            }
        },
        onResetFilter: function(){
            this.filterActive = false;
            this.params = Object.assign({}, this.defaultParams);
            this.fetch();
            if (this.$refs['filter']) {
                this.$refs['filter'].hide();
            }
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
