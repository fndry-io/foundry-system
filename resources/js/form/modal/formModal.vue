<template>
    <div ref="modal" :class="classes" tabindex="-1" role="dialog" :id="id" :aria-labelledby="labelledBy"
    :style="dialogZindex"
    >
        <div class="modal-dialog" role="document"  :style="dialogStyle">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="labelledBy">{{title}}</h5>
                    <button type="button" class="close" v-on:click="cancel" :aria-label="cancelText">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--<div class="k-scroll" data-scroll="true">-->
                    <!--</div>-->
                    <!--<form-modal-button-->
                            <!--url="/system/request/view"-->
                            <!--request="okinus.system.retailer.add"-->
                            <!--title="'Add Retailer'"-->
                            <!--class="btn btn-primary btn-sm">Add</form-modal-button>-->
                </div>
                <!--<div class="modal-footer">-->
                    <!--<slot name="footer"></slot>-->
                    <!--<button type="button" class="btn btn-primary" v-on:click="confirm">{{confirmText}}</button>-->
                    <!--<button type="button" class="btn btn-secondary" v-on:click="cancel">{{cancelText}}</button>-->

                    <!--<form-modal-button-->
                            <!--url="/system/request/view"-->
                            <!--request="okinus.system.retailer.add"-->
                            <!--title="'Add Retailer'"-->
                            <!--class="btn btn-primary btn-sm">Add</form-modal-button>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>

<script>

    import {uniqueId} from 'lodash';


    export default {
        name: 'formModal',
        props: {
            message: String,
            show: Boolean,
            index: {
                type: Number,
                required: false,
                default: function(){
                    return 0;
                }
            },
            title: {
                type: String,
                default: function(){
                    return 'Form'
                }
            },
            confirmText: {
                type: String,
                default: function(){
                    return 'Yes'
                }
            },
            cancelText: {
                type: String,
                default: function(){
                    return 'No'
                }
            },
            center:{
                type: Boolean,
                default: false
            }
        },
        data: function(){
            return {
                id: uniqueId('FormModal')
            };
        },
        mounted: function(){

            $(this.$refs.modal).modal({
                backdrop: true,
                show: true
            });
            $(this.$refs.modal).on('shown.bs.modal', () => {
                this.$emit('shown');
            });
            $(this.$refs.modal).on('hide.bs.modal', () => {
                this.$emit('hide');
            });
            $(this.$refs.modal).on('hidden.bs.modal', () => {
                this.$emit('hidden');
                $(this.$refs.modal)
                    .modal('dispose');
                this.$destroy();
            });
        },
        updated: function(){

            //console.log('updated', this.index);

            $(this.$refs.modal).modal({
                backdrop: this.index === 0
            });
        },
        methods: {
            remove: function(){
                $(this.$refs.modal)
                    .modal('hide')
                ;
            },
            cancel: function(){
                this.remove();
            },
            confirm: function(){
                this.remove();
            }
        },
        computed: {
            labelledBy: function(){
                return this.id + 'Label';
            },
            dialogZindex: function(){
                return {
                    zIndex: 1050 - this.index
                }
            },
            dialogStyle: function(){
                return {
                    right: (this.index * 15) + 'px'
                };
            },
            classes: function(){
                return this.center? 'modal fade': 'modal fade right';
            }
        }
    }
</script>
