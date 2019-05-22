<template>
    <div :class="getCollectionClass(schema)" :id="schema.id">
        <div class="collection-inputs">
            <div class="single-collection" v-for="(child, index) in schema.children">
                <button
                        v-if="schema.children.length > 1"
                        title="Delete"
                        v-on:click="deleteCollectionItem(index)"
                        type="button"
                        class="btn btn-danger btn-sm">
                    <span class="fa fa-times"></span>
                </button>
                <form-component
                        :vfg="vfg"
                        :schema="child"
                        :errors="errors"
                        :options="options"
                        :model="model"
                        @validated="onFieldValidated"
                        @model-updated="onModelUpdated"></form-component>
            </div>
        </div>

        <div class="collection-buttons">
            <button
                    title="Add New"
                    v-if="schema.max && schema.max > 1 && schema.children.length < schema.max"
                    v-on:click="addToCollection()"
                    type="button"
                    class="btn btn-success btn-sm">
                Add Another
            </button>

        </div>
    </div>
</template>

<script>

    import {cloneDeep} from 'lodash';

    export default {
        name : "formCollection",
        props: {
            vfg: {
                type: Object,
                required: true
            },
            model: {
                type: Object,
                required: true
            },
            options: {
                type: Object,
                required: true
            },
            schema: {
                type: Object,
                required: true
            },
            errors: {
                type: Array,
                required: true
            }
        },
        created(){
            this.prototype = cloneDeep(this.schema.children[0]);
            this.schema = this.updateIndex(this.schema);
        },
        mounted(){
            //console.log('updated');
        },
        data(){
            return{
                prototype: null,
                index: 0,
            }
        },
        methods: {
            buildCollection(){

            },
            updateIndex(schema, index){

                let types = ['row','section','form', 'collection'];

                let update = (field, index) => {
                    for (let i in field.children){
                        if(field.children.hasOwnProperty(i)){
                            let child = field.children[i];
                            if(child){
                                if(types.includes(child.type)){
                                    child.index = index? parseInt(index) : parseInt(i);
                                    update(child, index ? index : i)
                                }else {
                                    this.index = index ? index : i;
                                    child.name = child.name.replace('*', [index ? index : i])
                                }
                            }
                        }
                    }
                };

                update(schema, index);

                return schema;
            },
            onFieldValidated(res, errors, field) {
                this.$emit("validated", res, errors, field);
            },
            onModelUpdated(newVal, schema) {
                this.$emit("model-updated", newVal, schema);
            },
            handleButtonClick(btn){
                this.$emit("button-clicked", btn);
            },
            getCollectionClass(schema){
                return `collection-holder ${schema.class}`;
            },
            addToCollection(){
                let index = parseInt(this.index) + 1;
                let field = cloneDeep(this.prototype);
                field = this.updateIndex(field, index);

                field.index = index;

                this.schema.children.push(field);

            },
            deleteCollectionItem(index){

                let field = this.schema.children.splice(index, 1)[0];
                let o = this.model[this.schema.name];
                let i = field.index? field.index : 0;

                if(o && o[i] !== undefined){
                    this.$root.$delete(o, i);
                }
            }
        }
    };
</script>

<style lang="scss">
    .collection-holder{
        margin-bottom: 15px;
        .collection-buttons {
            margin-top: -10px;
            text-align: right;
            z-index: 2;
            position: relative;
            padding: 10px 0 0;
        }
        .collection-inputs{
            .single-collection{
                position: relative;
                padding: 10px;
                margin: 0 0 5px 0;
                background: #fafafa;
                border-radius: 4px;
                border-bottom: 1px solid #fafafa;

                button.btn-danger {
                    position: absolute;
                    right: 10px;
                    top: 10px;
                    border-radius: 15px;
                    padding: 3px 5px;
                    line-height: 1;
                    z-index: 100;
                }
            }

        }
    }
</style>
