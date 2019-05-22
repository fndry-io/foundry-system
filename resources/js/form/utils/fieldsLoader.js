import {forEach, camelCase} from "lodash";

let fieldComponents = {};

//Register form inputs components
const files = require.context('../fields/core/', true, /\.vue$/i);

files.keys().map(key => {
    let compName = _.last(key.split('/')).split('.')[0];
    compName = camelCase(compName);
    fieldComponents[compName] = files(key).default;

});

export default fieldComponents
