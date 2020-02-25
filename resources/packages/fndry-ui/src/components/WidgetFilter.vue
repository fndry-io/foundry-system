<template>
    <div class="filter">
        <loader :loading="loading">
            <ValidationObserver v-if="!loading" ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="onSubmit">
                <div class="filter-body">
                    <div class="row" v-for="row in layout">
                        <div class="col" v-for="name in row" v-if="name">
                            <fndry-form-group v-if="filters && filters[name]" :model="form" :errors="errors" :schema="filters[name]" @input="onFilterInput" @change="onFilterChange"></fndry-form-group>
                        </div>
                    </div>
                </div>
                <div class="filter-buttons">
                    <b-button variant="primary" type="submit" :disabled="invalid">Apply</b-button>&nbsp;
                    <b-button variant="info" @click="$emit('reset')">Reset</b-button>
                </div>
            </ValidationObserver>
        </loader>
    </div>
</template>

<script>

    import {HasFilter} from '../../../fndry-requests/src/mixins/browse';

    export default {
        name: 'WidgetFilter',
        mixins: [
            HasFilter
        ],
        props: {
            layout: {
                type: Array,
                required: true
            }
        }
    }
</script>
