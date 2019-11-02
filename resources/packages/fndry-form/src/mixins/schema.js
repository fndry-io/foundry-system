import { forEach, isNil, isArray, isString, isFunction, get as objGet, extend } from "lodash";

export const process = (schema, model) => {

    schema.visible = isVisible(schema, model);
    if (schema.children && schema.children.length > 0) {
        for (let i = 0; i < schema.children.length; i++) {
            process(schema.children[i], model);
        }
    }
};


export const isVisible = (schema, model) => {

    let show = true;

    if(isArray(schema.conditions) && schema.conditions.length > 0){
        let conditions = [];

        for (let i = 0; i < schema.conditions.length; i++){

            let condition = schema.conditions[i].split(":");


            if(condition && condition.length > 0){
                let ref = condition[0];
                let operator = condition[1];
                let value = condition[2];
                let values = [];

                if(value){
                    let v = value.split(',');
                    if(v && v.length > 0){
                        for (let j = 0; j < v.length; j++){
                            values.push(v[j]);
                        }
                    }
                }

                if(ref && operator && values.length > 0){

                    conditions.push({
                        ref,
                        operator,
                        values
                    });
                }
            }
            show = runConditional(conditions, model);
        }
    }

    return show;

};

export const runConditional = (conditions, model) => {

    for(let key in conditions){
        if (!determineVisibility(conditions[key], model)) {
            return false;
        }
    }
    return true;
};

export const determineVisibility = (condition, model) => {

    let expression = condition.operator;
    let value = objGet(model, `${condition.ref}`, null);

    if(expression === 'in'){
        let show = false;
        if(value && isArray(value)){
            for (let k = 0; k < value.length; k++){
                if(!show){
                    show = condition.values.includes(eval(value[k]));
                }
            }
        } else {
            show = value ? condition.values.includes(eval(value)): false;
        }
        return show;
    } else {
        let values = condition.values[0];

        if (typeof(value) === 'string') {
            value = `'${value}'`;
        }

        if (values === "'NULL'") {
            return eval(`${value}${expression}null`);
        } else {
            return eval(`${value}${expression}${values}`);
        }

    }

};

export default {
	methods: {
        fieldVisible() {
            //console.log('schema', this.schema.type, this.schema.visible);
            return this.schema.visible === undefined || this.schema.visible;
        }
    }
};
