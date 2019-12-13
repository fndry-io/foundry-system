import Vue from 'vue';

import {merge} from 'lodash';
import BootstrapVue from 'bootstrap-vue'
import Toasted from 'vue-toasted';

import Loader from './components/Loader';
import App from './components/App';
import Widget from './components/Widget';
import Icon from './components/Icon';
import store, {userHasAbility} from './store';

import FndryServices from '../../fndry-services';
import FndryForm from '../../fndry-form/src';
import FndryRequest from '../../fndry-requests/src';
import ApiService from "../../fndry-services/src/ApiService";

// Using plugin
Vue.use(BootstrapVue);
Vue.use(FndryServices);
Vue.use(FndryForm);
Vue.use(FndryRequest);

Vue.component('loader', Loader);
Vue.component('fndry-app', App);

export {
    Vue,
    Loader,
    Widget,
    Icon
}

Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 5000,
    keepOnHover: true,
    iconPack: 'fontawesome'
});

Vue.prototype.$userCan = userHasAbility;

Vue.directive('can', (el, binding, vnode) => {

    //todo, cache this so further checks of the same permission are faster
    let check = userHasAbility(binding.value);

    if (binding.modifiers.not) {
        check = !!check;
    }

    if (!check) {
        // replace HTMLElement with comment node
        const comment = document.createComment(' ');
        Object.defineProperty(comment, 'setAttribute', {
            value: () => undefined,
        });
        vnode.elm = comment;
        vnode.text = ' ';
        vnode.isComment = true;
        vnode.context = undefined;
        vnode.tag = undefined;
        vnode.data.directives = undefined;

        if (vnode.componentInstance) {
            vnode.componentInstance.$el = comment;
        }

        if (el.parentNode) {
            el.parentNode.replaceChild(comment, el);
        }
    }
});

const FndryVue = (config) => {
    let _config = merge({}, config, {
        store,
        methods: {
            toast(message, result = true){
                if (result) {
                    this.$toasted.show(message, {
                        icon: 'check'
                    });
                } else {
                    this.$toasted.show(message, {
                        icon: 'exclamation-circle'
                    });
                }
            }
        }

    });
    return new Vue(_config);
};

export default FndryVue;
