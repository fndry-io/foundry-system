
import panelStore from './store'

import PanelsWidget from './widgets/PanelsWidget'
import Panel from "./components/Panel";

const Plugin = {
    install: function(Vue, {store}){

        Vue.component('fndry-panels', PanelsWidget);

        store.registerModule('panels', panelStore);

        Vue.prototype.$panel = function(panel){
            store.commit('panels/add', panel);
        }
    }
};

export {
    PanelsWidget,
    Panel
}

export default Plugin;
