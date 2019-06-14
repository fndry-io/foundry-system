import {forEach, isNil, isArray, isString, isFunction } from "lodash";

export default {
	methods: {
        fieldVisible(field) {

            let show = true;

            if(isArray(field.conditions) && field.conditions.length > 0){
                let conditions = [];

                for (let i = 0; i < field.conditions.length; i++){

                    let c = field.conditions[i].split(":");

                    if(c && c.length > 0){
                        let control = c[0];
                        let d = c[1];
                        let model = null;
                        let expression = null;
                        let values = [];

                        if(d){
                            d = d.split(',');
                            if(d && d.length > 1){
                                model = d[0];
                                expression = d[1];

                                for (let j = 2; j < d.length; j++){
                                    values.push(d[j]);
                                }
                            }
                        }

                        if(model && expression){
                            let y = [];
                            y['model'] = model;
                            y['expression'] = expression;
                            y['value'] = values;
                                conditions[control] = y;
                        }
                    }

                    show = this.runConditional(conditions);
                }

            }

            if(show){
                if (isNil(field.visible)) return true;

                if (isFunction(field.visible)) return field.visible.call(this, this.model, field, this);

                return field.visible && !field.hidden && !field.guarded;
            }else{
                return show;
            }



        },
        runConditional(conditions){

            let condition = false;

            for(let key in conditions){
                if(conditions.hasOwnProperty(key)){

                    condition = this.determineVisibility(conditions[key]);

                    if(key === 'hide')
                        condition = !condition;
                }
            }

            return condition;
        },
        determineVisibility(condition){

            let model = this.vfg.model;

            let reference = condition['model'].split(".");
            let expression = condition['expression'];
            let value = null;

            if(reference.length > 1){
                for (let i = 0 ; i < reference.length; i++){
                    if(i < (reference.length - 1))
                        model = model[reference[i]];
                    else
                        value = model[reference[i]];
                }
            }else{
                value = (model[reference[0]]) ? model[reference[0]].toString() : null;
            }

            if(expression === 'in'){

                let show = false;

                if(value && isArray(value)){
                    for (let k = 0; k < value.length; k++){
                        if(!show){
                            show = condition['value'].includes(value[k].toString());
                        }
                    }
                }else{
                    show = value? condition['value'].includes(value.toString()): false;
                }

                return show;
            }else{
                return eval(`'${value}'${expression}${condition['value']}`);
            }

        },
    }
};
