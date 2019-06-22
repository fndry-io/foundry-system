
export const fields = {
    'fndry-field-input': require('./inputs/Input').default,
    'fndry-field-textarea': require('./inputs/TextArea').default,
    'fndry-field-checkbox': require('./inputs/Checkbox').default,
    'fndry-field-checkboxes': require('./inputs/Checkboxes').default,
    'fndry-field-radios': require('./inputs/Radios').default,
    'fndry-field-select': require('./inputs/Select').default,
    'fndry-field-date': require('./inputs/Date').default
    //collection
    //reference
    //upload
};

export const wrappers = {
    'fndry-wrapper-row': require('./wrappers/Row').default,
    'fndry-wrapper-column': require('./wrappers/Column').default,
    'fndry-wrapper-section': require('./wrappers/Section').default,
    'fndry-wrapper-tag': require('./wrappers/Tag'),
    'fndry-form-group': require('../components/FormGroup').default
};
