// import FieldRow from './core/FieldRow'
// import FieldInput from './core/FieldInput'
// import FormGroup from '../components/FormGroup';

//Register form inputs components
// const files = require.context('./core/', true, /\.vue$/i);
// let components = {};
// files.keys().map(key => {
//     let compName = _.last(key.split('/')).split('.')[0];
//     components[compName] = files(key);
// });

export const fields = {
    'field-input': require('./core/FieldInput').default
};

export const wrappers = {
    'field-row': require('./core/FieldRow').default,
    'form-group': require('../components/FormGroup').default
};
