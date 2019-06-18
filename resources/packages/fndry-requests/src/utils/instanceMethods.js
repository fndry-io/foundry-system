import Vue from 'vue';
import RequestFormModal from '../components/RequestFormModal';
import RequestFormInline from '../components/RequestFormInline';

const FormModal = Vue.extend(RequestFormModal);
const FormInline = Vue.extend(RequestFormInline);

/**
 * Request a form
 *
 * @param {string} request The request name
 * @param {string} type The type of form to display [inline|modal]
 * @param {object} options The options to pass to the sub components
 * @see modalForm
 * @see inlineForm
 *
 * @returns {Promise<any>}
 */
export const requestForm = (request, type = 'inline', options = {}) => {
    if (type === 'inline') {
        return inlineForm(request, options);
    } else {
        return modalForm(request, options);
    }
};

/**
 * Display a request inside a modal
 *
 * @param {string} request The request name
 * @param {null|object} data default data to load inside of the form
 * @param {null|object} params Default params for the request
 * @param {string} size The size of the modal
 * @param {string} position The modal position
 * @return {Promise<any>}
 */
export const modalForm = (request, {data, params, size, position}) => {

    let modal, vNode;

    modal = new FormModal({
        propsData: {
            request,
            data,
            params,
            size,
            position
        }
    });

    vNode = modal.$mount();

    const remove = () => {
        vNode.$el.remove();
        modal.$destroy();
    };

    return new Promise((accept, reject) => {

        modal.$on('success', (response, model) => {
            remove();
            accept({response, model});
        });
        modal.$on('fail', (response, model) => {
            reject('fail', {response, model});
        });
        modal.$on('cancel', () => {
            remove();
            reject('cancel');
        });

    });

};

/**
 * Display a request inside a modal
 *
 * @param {string} request The request name
 * @param {string} target The target element id to render the form
 * @param {null|object} data default data to load inside of the form
 * @param {null|object} params Default params for the request
 * @param {boolean} inline If to display the fields inline
 * @return {Promise<any>}
 */
export const inlineForm = (request, {target, data, params, inline}) => {

    let form, vNode, $target;

    if (target) {
        if ($target = document.getElementById(target)) {
            form = new FormInline({
                propsData: {
                    request,
                    params,
                    data,
                    inline
                }
            });

            vNode = form.$mount();

            $target.childNodes.forEach((node) => {
                $target.removeChild(node);
            });
            $target.appendChild(vNode.$el);
        }
    }

    return new Promise((accept, reject) => {

        if (target === undefined) {
            reject('error');
            console.error('Target not set for inline form');
        }
        if (form === undefined) {
            reject('error');
            console.warn('Target element #' + target + ' not found. Did you set the right target for the form?');
        }

        form.$on('success', (response, model) => {
            accept({response, model});
        });
        form.$on('fail', (response) => {
            reject({reason:'fail', response, model});
        });
        form.$on('cancel', () => {
            reject({reason:'cancel'});
        });

    });

};