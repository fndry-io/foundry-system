import jQuery from 'jquery'
import __ from './TranslateService'

const viewRequestUri = '/system/request/view';
const handleRequestUri = '/system/request/handle';

export const getViewRequestUri = (request) => {
    return route(viewRequestUri, Object.assign({}, {
        _request: request
    }));
};

export const getHandleRequestUri = (request) => {
    return route(handleRequestUri, Object.assign({}, {
        _request: request
    }));
};

/**
 * Universally handle the response from the server
 *
 * @param response (object) The axios response object
 * @param {function} resolve Resolve the promise function
 * @param {function} reject Reject the promise function
 * @see https://github.com/axios/axios#response-schema
 */
function handleResponse(response, resolve, reject)
{
    //if (config.debug) console.log('Response:', response);
    //console.log('Server Response', response);

    //we hav an api response
    if (response.data !== null && response.data.hasOwnProperty('status')) {
        handleResponseData(response.data, resolve, reject);
    } else {
        let message = '';
        if(response.data.message) {
            //invalid response from the server
            message = response.data.message;
        } else {
            message = __('An invalid response was returned from the server.');
        }
        reject(makeResponse(false, null, message, 502));
    }
}

/**
 * Universally handle the response data from the api
 *
 * @param {Object} data The api response data
 * @param {function} resolve Resolve the promise function
 * @param {function} reject Reject the promise function
 */
function handleResponseData(data, resolve, reject)
{
    if (data.status === true) {
        if (data.code === 301 || data.code === 302) {
            window.location.href = data.data;
        }
        resolve(data);
    } else {
        reject(data);
    }
}

/**
 * A specific function to handle the response returned if it result was false from the API
 *
 * @param {Object} error The error object returned by Axios
 * @param {function} resolve Resolve the promise function
 * @param {function} reject Reject the promise function
 */
function handleError(error, resolve, reject)
{
    if (error.response && error.response.status !== 408) {
        handleResponse(error.response, resolve, reject);
        //We have a different response
    } else if ((error.response && error.response.status === 408) || error.code === 'ECONNABORTED' || error.request) {
        let msg = __('Request timed out whilst trying to call the server. Please try again later.');
        reject(makeResponse(false, null, msg, 408));
    } else {
        // Something happened in setting up the request that triggered an Error
        //console.log('Error', error.message);
        let msg = __('An invalid response was returned from the server.');
        //invalid response from the server
        reject(makeResponse(false, null, msg, 500));
    }
}

/**
 * Makes a new response for returning to the application
 *
 * @param {Boolean} status The status of the response
 * @param {null|array|object} data The response data from the api
 * @param {String} error The error message if applicable
 * @param {String} code The response code which follows HTTP status code standards
 * @returns {{status: *, code: *, data: *, error: *}}
 */
function makeResponse(status, data = null, error = null, code = null)
{
    return {
        status: status,
        code: code,
        data: data,
        error: error
    }
}

/**
 * Replace any {*} placeholder with their param equivalent
 *
 * @param url (string) The url
 * @param params (object) The parameter we are sending
 */
function updateUrlPlaceholders(url, params, remove)
{
    if (remove === undefined) {
        remove = false;
    }
    let regex = new RegExp('{.*}', 'g');
    if (regex.test(url)) {
        jQuery.each(params, function(placeholder, value){
            if (url.search('\{' + placeholder + '\}') > -1) {
                url = url.replace('\{' + placeholder + '\}', value);
                if (remove) {
                    delete params[placeholder];
                }
            }
        });
    }
    return {
        url: url,
        data: params
    };
}

/**
 * Generates a url based on the provided url and params
 *
 * This will do regular url replacements
 *
 * @param url
 * @param params
 * @returns {string}
 */
export const route = (url, params) => {

    let data = updateUrlPlaceholders(url, params, true);
    let queryString = '';
    if (Object.keys(data.data).length > 0) {
        queryString = decodeURIComponent( jQuery.param( data.data ) );
        if (data.url.indexOf('?') === -1) {
            queryString = "?" + queryString;
        } else {
            queryString = "&" + queryString;
        }
    }

    return data.url + queryString;
};

//todo work out a better way for this, such as a class constructor or similar
let axios = window.axios || require('axios');

export const makeService = ({}) => {
    return service;
};

const service = {

    /**
     * Call the API endpoint
     *
     * This will standardise the response from the server to the known response structure regardless of if the
     * call state.
     *
     * It will return a promise which wraps an Axios call, and will resolve or reject the promise accordingly.
     *
     * Resolved promises are mapped to any stats = true response from the server
     * Rejected promises are mapped to any status = false response from the server
     *
     * @param {string} url The exact doc item to return
     * @param {string} method The method to use for the call. GET, PUT, POST, DELETER
     * @param {object} params The parameters to send to the server
     * @return {Promise} A promise
     */
    call: function(url, method, params) {
        if (method === undefined) {
            method = 'GET';
        } else {
            method = method.toUpperCase();
        }

        let _params = Object.assign({}, params);

        //prepare the options for the http call
        let options = {
            method: method,
            url: url,
            maxRedirects: 0,
            withCredentials: true,
            timeout: 60000 * 3,
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json',
            }
        };

        //do any parameter replacements in the URL
        options = Object.assign(options, updateUrlPlaceholders(url, _params));

        if (method === 'GET') {
            options.params = options.data;
            delete(options.data);
        }

        console.log('calling...', options, _params);

        return new Promise(function(resolve, reject){
            axios(options)
            //call to the server successful (2xx)
                .then(function (response) {
                    console.log('response...', response);
                    handleResponse(response, resolve, reject);
                })
                //call to server failed (!=2xx)
                .catch(function (error) {
                    console.log('error...', error);
                    handleError(error, resolve, reject);
                })
            ;
        })
            ;
    },
    upload: function(url, file, onUploadProgress) {
        let method = 'POST';

        let formData = new FormData();
        formData.append('file', file);

        //prepare the options for the http call
        let options = {
            method: method,
            url: url,
            maxRedirects: 0,
            withCredentials: true,
            timeout: 60000 * 3,
            headers: {
                'accept': 'application/json',
                'content-type': 'multipart/form-data',
            },
            onUploadProgress: function( progressEvent ) {
                onUploadProgress(progressEvent);
            },
            data: formData
        };

        return new Promise(function(resolve, reject){
            axios(options)
            //call to the server successful (2xx)
                .then(function (response) {
                    handleResponse(response, resolve, reject);
                })
                //call to server failed (!=2xx)
                .catch(function (error) {
                    handleError(error, resolve, reject);
                })
            ;
        });
    },
    getViewUrl: function(request, params) {
        let req = getViewRequestUri(request);
        return route(req, params);
    },
    getHandleUrl: function(request, params) {
        let req = getHandleRequestUri(request);
        return route(req, params);
    },
};

export default service;
