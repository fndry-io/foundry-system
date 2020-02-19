<template>
    <div>
        <b-table :items="data" :fields="fields" hover striped responsive no-local-sorting @sort-changed="(ctx) => this.$emit('sort', ctx)">
            <template v-slot:cell(label)="data">
                <strong>{{data.item.label}}</strong><span v-if="data.item.description"><br>
                <small class="font-italic">{{data.item.description}}</small></span>
            </template>
            <template v-slot:cell(identifier)="data">{{data.item.identifier}}</template>
            <template v-slot:cell(sequence)="data">{{data.item.sequence}}</template>
            <template v-slot:cell(status)="data"><format-switch :value="data.value"></format-switch></template>
            <template v-slot:cell(is_default)="data"><format-switch :value="data.item.is_default"></format-switch></template>
            <template v-slot:cell(actions)="data">
                <slot name="buttons" v-bind:data="data">
                </slot>
            </template>
        </b-table>
    </div>
</template>

<script>

    import {FormatSwitch} from "../index";

    export default {
        name: 'PicklistItemsTable',
        components: {
            FormatSwitch
        },
        props: {
            data: {
                type: Array,
                default(){
                    return [];
                }
            },
            fields: {
                type: Array,
                default(){return [];}
            }
        }
    }
</script>
