<template>
    <b-modal ref="modal" v-model="show" scrollable :title="title" modal-class="request-panel" ok-only ok-title="Close">
        <div v-if="loading" class="text-center">
            <b-spinner label="Loading..."></b-spinner>
        </div>
        <div v-else-if="!request || response.data">
            <component :is="panel" :data="response.data" :panel="panelProps" @update="onUpdate"></component>
        </div>
    </b-modal>
</template>

<script>

    import {merge} from 'lodash';

    export default {
        name: 'Modal',
        props: {
            title: {
                type: String
            },
            panel: {
                type: String
            },
            request: String,
            method: {
                type: String,
                default: function(){
                    return 'GET';
                }
            },
            params: {
                type: Object,
                default: function(){
                    return {};
                }
            },
            panelProps: {
                type: Object,
                required: true,
                default: function(){
                    return {};
                }
            }
        },
        data() {
            return {
                loading: true,
                show: true,
                response: {}
            };
        },
        created(){
            if(this.request) {
                this.getData();
            } else {
                this.loading = false;
            }
        },
        methods: {
            onUpdate(data){
                let d = merge({}, this.response.data, data);
                this.response.data = d;
                this.$emit('update', d);
            },
            onClose(){
                this.$emit('close');
            },
            getData(){
                this.$fndryApiService.call(this.request, this.method, this.params)
                    .then((response) => {
                        this.response = response;
                    }, (response) => {
                        this.response = response;
                    })
                    .finally(() => {
                        this.loading = false;
                    })
                ;
            }
        }
    };

</script>