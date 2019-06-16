
export const fields = {
    'fndry-field-input': require('./inputs/Input'),
    'fndry-field-textarea': require('./inputs/TextArea'),
    'fndry-field-checkbox': require('./inputs/Checkbox'),
    'fndry-field-checkboxes': require('./inputs/Checkboxes'),
    'fndry-field-radios': require('./inputs/Radios'),
    'fndry-field-select': require('./inputs/Select'),
    'fndry-field-date': require('./inputs/Date')
    //collection
    //reference
    //upload
};

export const wrappers = {
    'fndry-wrapper-row': require('./wrappers/Row'),
    'fndry-wrapper-column': require('./wrappers/Column'),
    'fndry-wrapper-section': require('./wrappers/Section'),
    'fndry-wrapper-tag': require('./wrappers/Tag').default,
    'fndry-form-group': require('../components/FormGroup')
};
