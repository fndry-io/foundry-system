import { validators } from './utils/validators';

export const config = {
  validators,
  formComponent: 'vueForm',
  formTag: 'form',
  messagesComponent: 'fieldMessages',
  messagesTag: 'div',
  showMessages: '',
  validateComponent: 'validate',
  validateTag: 'div',
  fieldComponent: 'field',
  fieldTag: 'div',
  formClasses: {
    dirty: 'is-form-dirty',
    pristine: 'is-form-pristine',
    valid: 'is-form-valid',
    invalid: 'is-form-invalid',
    touched: 'is-form-touched',
    untouched: 'is-form-untouched',
    focused: 'is-form-focused',
    submitted: 'is-form-submitted',
    pending: 'is-form-pending'
  },
  validateClasses: {
    dirty: 'is-field-dirty',
    pristine: 'is-field-pristine',
    valid: 'is-field-valid',
    invalid: 'is-field-invalid',
    touched: 'is-field-touched',
    untouched: 'is-field-untouched',
    focused: 'is-field-focused',
    submitted: 'is-field-submitted',
    pending: 'is-field-pending'
  },
  inputClasses: {
    dirty: 'is-dirty',
    pristine: 'is-pristine',
    valid: 'is-valid',
    invalid: 'is-invalid',
    touched: 'is-touched',
    untouched: 'is-untouched',
    focused: 'is-focused',
    submitted: 'is-submitted',
    pending: 'is-pending'
  },
  Promise: typeof Promise === 'function' ? Promise : null
};
