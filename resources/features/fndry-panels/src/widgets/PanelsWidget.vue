<template>
    <div class="panels">
        <panel-modal v-for="(panel, index) in panels"
               :key="panel.key"
               :panel="panel"
               @hidden="(action) => handleHidden(panel.key, action)"
               @event="(event, data) => handleEvent(panel.key, event, data)"
               :index="index"
        ></panel-modal>
    </div>
</template>

<script>

    import PanelModal from "../components/PanelModal";

    export default {
        name: "PanelsWidget",
        components: {
            PanelModal
        },
        computed: {
            panels: function(){
                return this.$store.state.panels.panels;
            }
        },
        methods: {
            handleHidden(key){
                this.$store.commit('panels/remove', key);
            },
            handleEvent(key, name, data){
                this.$store.dispatch('panels/get', key).then((panel) => {
                    if (panel.hasOwnProperty('onEvent')) {
                        panel.onEvent(name, data);
                    }
                });
            }
        }
    }
</script>
