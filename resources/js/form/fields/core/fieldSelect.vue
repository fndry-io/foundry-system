<template>
	<select class="form-control"
        ref="select"
	    data-live-search="true"
	    @change="onSelect"
	    :disabled="disabled"
	    :id="getFieldID(schema)"
	    :name="getFieldName(schema)"
	    :class="getSelectClasses(schema)"
	    :invalid="isInvalid()"
	    :multiple="schema.multiple"
	    v-on:keyup.enter="onInputEnter()"
	>
	<option :style="getPlaceholderStyle(schema)" :value="null">{{ getSelectPlaceholder(schema) }}</option>
        <template v-for="item in items">
            <optgroup v-if="item.group" :label="getGroupName(item)"></optgroup>
            <option v-if="item.ops" v-for="i in item.ops", :value="getItemValue(i)">{{ getItemName(i) }}</option>
            <option v-if="!item.group" :value="getItemValue(item)">{{ getItemName(item) }}</option>
        </template>
    </select>

</template>

<script>
import { isObject, isNil, clone , union, isArray} from "lodash";
import abstractField from "../abstractField";

export default {
	mixins: [abstractField],

    data(){
	    return {
            select: null
        }
    },

    mounted(){
	    let val = this.value;
        let $select = $(this.$refs.select);
        let that = this;

	    $(document).ready(function(){
	        if(val != null){
	            if(isArray(val))
                    $select.val(val);
	            else
                    $select.val(val.toString());
            }

            $select.selectpicker();

	        $($select.next('button')).on('keyup', function(e){
                if (e.keyCode === 13){
                    that.onInputEnter();
                }
            });
        });
    },
	computed: {
		selectOptions() {
			return this.schema.options || {};
		},

		items() {
			let values = this.schema.options || {};
            if(values){
                return this.groupValues(values);
            }else
                return [];

        }
	},

	methods: {
		formatValueToField(value) {
			if (isNil(value)) {
				return null;
			}
			return value;
		},

        getSelectClasses(schema){
		    return `selectpicker ${schema.class? schema.class: ''}`;
        },
		groupValues(values) {

		    let v = [];

		    for (let key in values){
                if(typeof key !== "object" && values.hasOwnProperty(key)){
                    v.push({value: key, label: values[key]});
                }else{
                    v.push(values[key]);
                }
            }

            values = v;

			let array = [];

			values.forEach(item => {
                array.push(item);
			});

			// With Groups.
			return array;
		},

        getSelectPlaceholder(schema){

		    let label = "< Nothing selected >";

		   if(schema.empty){
		       if(schema.empty != true)
		           label = schema.empty;
           }

		    return label;
        },
        onSelect(){

		    let val = $(this.$refs.select).val();

		     if(this.schema.multiple){

		         val = val.filter(value => value !== "");

                 if (isNil(this.value) || !Array.isArray(this.value)) {
                     this.value = [];
                 }

                 const arr = clone(this.value);

                 while (arr.length) {
                     arr.pop();
                 }

                 arr.push(...val);
                 this.value = arr;

                 $(this.$refs.select).selectpicker('val', val);

             }else{
                 this.value = val;
             }
        },
		getItemValue(item) {
			if (isObject(item)) {
				if (typeof this.schema["selectOptions"] !== "undefined" && typeof this.schema["selectOptions"]["value"] !== "undefined") {
					return item[this.schema.selectOptions.value];
				} else {
					if (typeof item["value"] !== "undefined") {
						return item.value;
					} else {
						throw "`value` is not defined. If you want to use another key name, add a `value` property under `selectOptions` in the field object.";
					}
				}
			} else {
				return item;
			}
		},
        getPlaceholderStyle(schema){
		    return `${schema.required? 'cursor: not-allowed': 'cursor:all'}`;
        },
		getItemName(item) {
			if (isObject(item)) {
				if (typeof this.schema["selectOptions"] !== "undefined" && typeof this.schema["selectOptions"]["label"] !== "undefined") {
					return item[this.schema.selectOptions.label];
				} else {
					if (typeof item["label"] !== "undefined") {
						return item.label;
					} else {
						throw "`label` is not defined. If you want to use another key name, add a `label` property under `selectOptions` in the field object.";
					}
				}
			} else {
				return item;
			}
		}
	}
};
</script>


<style lang="sass">

</style>
