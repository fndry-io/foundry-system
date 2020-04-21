import {findIndex, uniqueId, find} from 'lodash';

/**
 * Panels store object for managing panels
 *
 * @constructor
 */
const panel = {
    namespaced: true,

    /**
     * The panel state
     *
     * @type {{panels: []}}
     */
    state: {
        panels: []
    },

    mutations: {
        /**
         * Add a panel to the set
         *
         * @param panel
         * @param state
         */
        add(state, panel){
            panel.key = uniqueId();
            state.panels.unshift(panel);
            state.panels = [...state.panels];
        },

        /**
         * Remove a panel by key
         *
         * @param state
         * @param key
         */
        remove(state, key){
            let index = findIndex(state.panels, (panel) => panel.key === key);
            state.panels.splice(index, 1);
            state.panels = [...state.panels];
        },

        /**
         * Reset the panels
         */
        reset(state){
            state.panels = [];
        }

    },

    actions: {

        /**
         * Get a panel from the store
         *
         * @param state
         * @param key
         */
        get({state}, key){
            return new Promise((accept, reject) => {
                let panel = find(state.panels, (panel) => panel.key === key);
                if (panel) {
                    accept(panel);
                } else {
                    reject();
                }
            });
        }
    }
};

export default panel;
