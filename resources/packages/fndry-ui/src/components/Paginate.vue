<template>
    <div class="row paginate">
        <div class="col-md flex-grow-1">
            <div role="status" aria-live="polite" class="paginate-report">
              <span v-if="response.from">Showing {{response.from}} to {{response.to}} of {{response.total}} entries</span>
              <span v-else>No results found</span>
            </div>
        </div>
        <div class="col-md flex-grow-0">
            <ul v-if="response.current_page && response.total > response.per_page" class="pagination" style="margin-bottom: 0;">
                <li v-if="!simple" class="paginate_button page-item first" v-bind:class="{disabled: !(response.current_page > 1)}">
                    <a href="#" v-on:click.prevent="goToFirstPage" tabindex="0" class="page-link"><i class="fa fa-angle-double-left"></i></a></li>
                <li class="paginate_button page-item previous" v-bind:class="{disabled: !(response.current_page > 1)}">
                    <a href="#" v-on:click.prevent="goToPrevPage" tabindex="0" class="page-link"><i class="fa fa-angle-left"></i></a></li>
                <li v-if="!simple" v-for="n in pages()" class="paginate_button page-item" :class="{active: response.current_page === n}">
                    <a href="#" v-on:click.prevent="()=> goToPage(n)" tabindex="0" class="page-link">{{ n }}</a></li>
                <li class="paginate_button page-item next" v-bind:class="{disabled: !(response.current_page < response.last_page)}">
                    <a href="#" v-on:click.prevent="goToNextPage" tabindex="0" class="page-link"><i class="fa fa-angle-right"></i></a></li>
                <li v-if="!simple" class="paginate_button page-item last" v-bind:class="{disabled: !(response.current_page < response.last_page)}">
                    <a href="#" v-on:click.prevent="goToLastPage" tabindex="0" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
            </ul>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'Paginate',
        props: {
            response: Object,
            simple: Boolean,
            default: function(){
                return {
                    from: 0,
                    to: 0,
                    total: 0,
                    current_page: 0,
                    per_page: 0,
                    last_page: 0
                };
            }
        },
        methods : {
            goToFirstPage(){
                this.$emit('first-page');
            },
            goToLastPage(){
                this.$emit('last-page');
            },
            goToPage(n){
                this.$emit('page', n);
            },
            goToNextPage(){
                this.$emit('next-page');
            },
            goToPrevPage(){
                this.$emit('previous-page');
            },
            pages: function(){
                const curr = this.response.current_page;
                let left = curr - 4;
                let right = curr + 4;

                //we are towards the beginning
                if (left < 1) {
                    left = 1;
                    right = left + 8;
                    if (right > this.response.last_page) {
                        right = this.response.last_page;
                    }
                }
                //we are towards the end
                else if (right > this.response.last_page) {
                    right = this.response.last_page;
                    left = right - 8;
                    if (left < 1) {
                        left = 1;
                    }
                }

                let pages = [];
                for (let i=left; i<=right; i++) {
                    pages.push(i);
                }
                return pages;
            }
        }
    }

</script>
