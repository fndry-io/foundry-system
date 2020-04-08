import {Vue} from '../index';
import Vuex from 'vuex';

import AuthStore from '../../../fndry-services/src/store/auth';
import {has} from 'lodash';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {

    },
    mutations: {

    }
});

//register modules
store.registerModule('auth', AuthStore);

let qualifies = {};

export const userHasAbility = (ability) => {

    if (!store.getters['auth/isLoggedIn']) {
        return false;
    }

    if (store.state.auth.user.is_admin) {
        return true;
    }

    let abilities = [];
    if (ability.indexOf('|') !== -1) {
        abilities  = ability.split('|');
    } else {
        abilities = [ability];
    }

    let can = false;
    for(const _ability of abilities) {
        if (qualifies.hasOwnProperty(_ability)) {
            can = qualifies[_ability];
        } else if (store.state.auth.user.abilities.indexOf(_ability) !== -1) {
            qualifies[_ability] = true;
            can = true;
        } else {
            qualifies[_ability] = false;
        }
        if (can) {
            break;
        }
    }
    return can;
};

export const userHasAbilityWith = (ability, withObject, pathToUserId) => {

    if (userHasAbility(ability)) {
        if (!has(withObject, pathToUserId)) {
            return false;
        }
        return (get(withObject, pathToUserId) === store.state.auth.user.id);
    }
    return false;
};

export default store;
