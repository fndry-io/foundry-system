import { defaults, isNil, isNumber, isInteger, isString, isArray, isFunction, isFinite } from "lodash";
import fecha from "fecha";

let resources = {
	fieldIsRequired: "This field is required!",
	invalidFormat: "Invalid format!",

	numberTooSmall: "The number is too small! Minimum: {0}",
	numberTooBig: "The number is too big! Maximum: {0}",
	invalidNumber: "Invalid number",
	invalidInteger: "The value is not an integer",

	textTooSmall: "The length is too small! Current: {0}, Minimum: {1}",
	textTooBig: "The length is too big! Current: {0}, Maximum: {1}",
	thisNotText: "This is not a text!",

	thisNotArray: "This is not an array!",

	selectMinItems: "Select minimum {0} items!",
	selectMaxItems: "Select maximum {0} items!",

	invalidDate: "Invalid date!",
	dateIsEarly: "The date is too early! Current: {0}, Minimum: {1}",
	dateIsLate: "The date is too late! Current: {0}, Maximum: {1}",

	invalidEmail: "Invalid e-mail address!",
	invalidURL: "Invalid URL!",

	invalidCard: "Invalid card format!",
	invalidCardNumber: "Invalid card number!",

	invalidTextContainNumber: "Invalid text! Cannot contains numbers or special characters",
	invalidTextContainSpec: "Invalid text! Cannot contains special characters",

    digitsOnly: "Only numeric values are allowed"
};

function checkEmpty(value, required, messages = resources) {
	if (isNil(value) || value === "") {
		if (required) {
			return [msg(messages.fieldIsRequired)];
		} else {
			return [];
		}
	}
	return null;
}

function msg(text) {
	if (text != null && arguments.length > 1) {
		for (let i = 1; i < arguments.length; i++) {
			text = text.replace("{" + (i - 1) + "}", arguments[i]);
		}
	}

	return text;
}

const validators = {
	resources,

	required(value, field, model, messages = resources) {
		return checkEmpty(value, field.required, messages);
	},

	number(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let err = [];
		if (isFinite(parseInt(value))) {
			if (!isNil(field.min) && value < field.min) {
				err.push(msg(messages.numberTooSmall, field.min));
			}

			if (!isNil(field.max) && value > field.max) {
				err.push(msg(messages.numberTooBig, field.max));
			}
		} else {
			if(value)
				err.push(msg(messages.invalidNumber));
		}

		return err;
	},

	integer(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;
		let errs = validators.number(value, field, model, messages);

		if (!isInteger(value)) {
			errs.push(msg(messages.invalidInteger));
		}

		return errs;
	},

	double(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		if (!isNumber(value) || isNaN(value)) {
			return [msg(messages.invalidNumber)];
		}
	},

	string(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let err = [];
		if (isString(value.toString())) {
			if (!isNil(field.min) && value.length < field.min) {
				err.push(msg(messages.textTooSmall, value.length, field.min));
			}

			if (!isNil(field.max) && value.length > field.max) {
				err.push(msg(messages.textTooBig, value.length, field.max));
			}
		} else {
			err.push(msg(messages.thisNotText));
		}

		return err;
	},

	array(value, field, model, messages = resources) {
		if (field.required) {
			if (!isArray(value)) {
				return [msg(messages.thisNotArray)];
			}

			if (value.length === 0) {
				return [msg(messages.fieldIsRequired)];
			}
		}

		if (!isNil(value)) {
			if (!isNil(field.min) && value.length < field.min) {
				return [msg(messages.selectMinItems, field.min)];
			}

			if (!isNil(field.max) && value.length > field.max) {
				return [msg(messages.selectMaxItems, field.max)];
			}
		}
	},

	date(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let m = new Date(value);
		if (!m) {
			return [msg(messages.invalidDate)];
		}

		let err = [];

		if (!isNil(field.min)) {
			let min = new Date(field.min);
			if (m.valueOf() < min.valueOf()) {
				err.push(msg(messages.dateIsEarly, fecha.format(m), fecha.format(min)));
			}
		}

		if (!isNil(field.max)) {
			let max = new Date(field.max);
			if (m.valueOf() > max.valueOf()) {
				err.push(msg(messages.dateIsLate, fecha.format(m), fecha.format(max)));
			}
		}

		return err;
	},

	regexp(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		if (!isNil(field.pattern)) {
			let re = new RegExp(field.pattern);
			if (!re.test(value)) {
				return [msg(messages.invalidFormat)];
			}
		}
	},

	email(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; // eslint-disable-line no-useless-escape
		if (!re.test(value)) {
			return [msg(messages.invalidEmail)];
		}
	},

	url(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let re = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g; // eslint-disable-line no-useless-escape
		if (!re.test(value)) {
			return [msg(messages.invalidURL)];
		}
	},

	alpha(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let re = /^[a-zA-Z]*$/;
		if (!re.test(value)) {
			return [msg(messages.invalidTextContainNumber)];
		}
	},

	alphaNumeric(value, field, model, messages = resources) {
		let res = checkEmpty(value, field.required, messages);
		if (res != null) return res;

		let re = /^[a-zA-Z0-9]*$/;
		if (!re.test(value)) {
			return [msg(messages.invalidTextContainSpec)];
		}
	},

	numeric(value, field, model, messages = resources){
        let res = checkEmpty(value, field.required, messages);
        if (res != null) return res;

        let re = /^\d+$/;

        if(!re.test(value)){
        	return [msg(messages.digitsOnly)]
		}

        let err = [];

		if (!isNil(field.min) && value.length < field.min) {
			err.push(msg(messages.textTooSmall, value.length, field.min));
		}

		if (!isNil(field.max) && value.length > field.max) {
			err.push(msg(messages.textTooBig, value.length, field.max));
		}

		return err;

    }
};

Object.keys(validators).forEach(name => {
	const fn = validators[name];
	if (isFunction(fn)) {
		fn.locale = customMessages => (value, field, model) => fn(value, field, model, defaults(customMessages, resources));
	}
});

export default validators;
