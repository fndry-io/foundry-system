<template>
    <div>
        <div v-if="isInline()">
            <fndry-request-form-inline :request="request" :data="data" :params="params" v-on="$listeners" :key="request" :inline="true"></fndry-request-form-inline>
        </div>
        <div v-else>
            <fndry-request-form-modal :request="request" :data="data" :params="params" v-on="$listeners" :key="request"></fndry-request-form-modal>
        </div>
    </div>
</template>

<script>

    import FndryRequestFormInline from './RequestFormInline';
    import FndryRequestFormModal from './RequestFormModal';

    export default {
        name: "fndry-request-form",
        components: {
            FndryRequestFormInline,
            FndryRequestFormModal
        },
        props: {
            type: {
                type: String,
                default: function(){
                    return 'modal';
                }
            },
            request: String,
            data: {
                type: Object,
                default: function () {
                    return {};
                }
            },
            params: {
                type: Object,
                default: function(){
                    return {};
                }
            }
        },
        methods: {
            isInline() {
                return this.type === 'inline';
            },
            isModal() {
                return this.type === 'modal';
            },
        }
    }
</script>

