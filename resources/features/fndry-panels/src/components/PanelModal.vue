<template>
    <b-modal ref="modal" v-model="show" scrollable :title="modalTitle" :hide-footer="true" :size="panel.size || 'lg'" @hide="canHide" :modal-class="modalClass" @hidden="onHidden">
        <component :is="panel.component"
                   v-bind="panel.props"
                   @event="onEvent"
                   :dirty="dirty"
                   @dirty="handleDirty"
                   @panel="handlePanel"
                   :modal="modal"
        ></component>
    </b-modal>
</template>

<script>

    export default {
        name: "PanelModal",
        mixins: [],
        components: {
        },
        props: {
            index: {
                type: Number,
                default() {
                    return 0
                }
            },
            panel: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                show: true,
                allowClose: false,
                dirty: false
            }
        },
        computed: {
            modal: function(){
                return this.$refs['modal'];
            },
            modalTitle: function(){
                return this.panel.title;
            },
            modalClass: function(){
                return {
                    'panel-modal': true,
                    [`panel-${this.index}`]: true
                }
            }
        },
        methods: {
            showModal() {
                this.show = true;
            },
            hideModal() {
                this.show = false;
            },
            onSuccess: function(response){
                this.$emit('success', response, this.model);
                this.hideModal();
            },
            onCancel: function(){
                this.$emit('cancel');
                this.hideModal();
            },
            canHide: function(bvModalEvt){
                if (this.allowClose === false) {
                    if (this.dirty) {
                        bvModalEvt.preventDefault();
                        this.$bvModal.msgBoxConfirm('Looks like you made some changes, are you sure you want to cancel? Any changes would be lost.', {
                            okTitle: 'Discard',
                            cancelTitle: 'Keep',
                        }).then((value) => {
                            if (value === true) {
                                this.allowClose = true;
                                this.onCancel();
                            }
                        })
                    } else {
                        this.allowClose = true;
                        this.onCancel();
                    }
                }
            },
            onHidden: function(){
                this.$emit('hidden');
            },
            onEvent: function(name, data){
                this.$emit('event', name, data);
                if (name === 'success') {
                    this.dirty = false;
                }
                if (name === 'success' || name === 'cancel') {
                    this.show = false;
                }
            },
            handleDirty: function(_dirty){
                this.dirty = _dirty;
            },
            handlePanel: function(props){

            }
        }
    }
</script>

