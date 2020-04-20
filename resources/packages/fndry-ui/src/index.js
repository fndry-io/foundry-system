import Vue from 'vue';

import {merge} from 'lodash';
import BootstrapVue from 'bootstrap-vue'
import Toasted from 'vue-toasted';

import Loader from './components/Loader';
import App from './components/App';
import Screen from './components/Screen';
import Widget from './components/Widget';
import WidgetFilter from './components/WidgetFilter';
import Icon from './components/Icon';
import Paginate from './components/Paginate';

import PickListBadge from "./components/PickListBadge";
import FndryPromiseButton from "./components/FndryPromiseButton";
import PermissionDeniedWidget from './components/PermissionDeniedWidget';
import WithPermission from './components/WithPermission'

//formatters
import FormatDate from "./components/formatters/FormatDate";
import FormatUser from "./components/formatters/FormatUser";
import FormatSwitch from "./components/formatters/FormatSwitch";
import FormatPhone from "./components/formatters/FormatPhone";
import FormatEmail from "./components/formatters/FormatEmail";
import FormatFileSize from "./components/formatters/FormatFileSize";
import FormatMoney from "./components/formatters/FormatMoney";

import store, {userHasAbility} from './store';

import FndryServices from '../../fndry-services';
import FndryForm from '../../fndry-form/src';
import FndryRequest from '../../fndry-requests/src';

/**
 * Plugin
 * (c) 2019
 * @license MIT
 */
const Plugin = {};

/**
 * Plugin API
 */
Plugin.install = function (Vue, options) {
    Vue.component('loader', Loader);
    Vue.component('fndry-app', App);
    Vue.component('fndry-screen', Screen);
    Vue.component('fndry-widget', Widget);
    Vue.component('fndry-icon', Icon);
    Vue.component('fndry-promise-button', FndryPromiseButton);
};

// Using plugin
Vue.use(Plugin);
Vue.use(BootstrapVue);
Vue.use(FndryServices);
Vue.use(FndryForm);
Vue.use(FndryRequest);

export {
    Vue,
    Loader,
    Widget,
    WidgetFilter,
    Icon,
    Screen,
    Paginate,

    PickListBadge,

    //formatters
    FormatDate,
    FormatUser,
    FormatSwitch,
    FormatPhone,
    FormatEmail,
    FormatFileSize,
    FormatMoney,
    store,

    FndryPromiseButton,
    PermissionDeniedWidget,
    WithPermission
}

Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 5000,
    keepOnHover: true,
    iconPack: 'fontawesome'
});

Vue.prototype.$userCan = userHasAbility;
Vue.prototype.$isAuthUser = function(user){
    return user && store.getters['auth/isLoggedIn'] && store.state.auth.user.id === user.id;
};

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
