"use strict";
/**
 * @class PMDynaform
 * Base class PMDynaform
 * @singleton
 */

 /**
  * @feature support for ie8
  * functions 
  */
  
function getScrollTop(){
	if(typeof pageYOffset!= 'undefined'){
		//most browsers except IE before #9
		return pageYOffset;
	}
	else{
		var B= document.body; //IE 'quirks'
		var D= document.documentElement; //IE with doctype
		D= (D.clientHeight)? D: B;
		return D.scrollTop;
	}
};
  //.trim to support ie8
if (!Array.prototype.filter) {
  Array.prototype.filter = function(fun/*, thisArg*/) {
	'use strict';

	if (this === void 0 || this === null) {
	  throw new TypeError();
	}

	var t = Object(this);
	var len = t.length >>> 0;
	if (typeof fun !== 'function') {
	  throw new TypeError();
	}

	var res = [];
	var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
	for (var i = 0; i < len; i++) {
	  if (i in t) {
		var val = t[i];

		// NOTA: Tecnicamente este Object.defineProperty deben en 
		//        el indice siguiente, como push puede ser 
		//        afectado por la propiedad en object.prototype y 
		//        Array.prototype.
		//       Pero estos metodos nuevos, y colisiones deben ser
		//       raro, así que la alternativas mas compatible.       
		if (fun.call(thisArg, val, i, t)) {
		  res.push(val);
		}
	  }
	}

	return res;
  };
}

if (!String.prototype.trim) {
  (function() {
	// Make sure we trim BOM and NBSP
	var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
	String.prototype.trim = function() {
	  return this.replace(rtrim, '');
	};
  })();
}

if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
	var len = this.length >>> 0;

	var from = Number(arguments[1]) || 0;
	from = (from < 0)
		 ? Math.ceil(from)
		 : Math.floor(from);
	if (from < 0)
	  from += len;

	for (; from < len; from++)
	{
	  if (from in this &&
		  this[from] === elt)
		return from;
	}
	return -1;
  };
}

if (!document.getElementsByClassName) {
    document.getElementsByClassName = function (classname) {
        var a = [];
        var re = new RegExp('(^| )' + classname + '( |$)');
        var els = this.getElementsByTagName("*");
        for (var i = 0, j = els.length; i < j; i++)
            if (re.test(els[i].className))
                a.push(els[i]);
        return a;
    };
}

if (!Array.prototype.find) {
    Array.prototype.find = function (callback, thisArg) {
        "use strict";
        var arr = this,
            arrLen = arr.length,
            i;
        for (i = 0; i < arrLen; i += 1) {
            if (callback.call(thisArg, arr[i], i, arr)) {
                return arr[i];
            }
        }
        return undefined;
    };
}

var PMDynaform = {
	VERSION: "0.1.0",
	view: {},
	model: {},
	collection: {},
	Extension: {},
	restData: {},
	activeProject: null,
	FLashMessage : null
};
/**
 * Extends the PMDynaform namespace with the given `path` and making a pointer
 * from `path` to the given `class` (note that the `path`'s last token will be the pointer visible from outside
 * the definition of the class).
 *
 *      // e.g.
 *      // let's define a class inside an anonymous function
 *      // so that the global scope is not polluted
 *      (function () {
 *          var Class = function () {...};
 *
 *          // let's extend the namespace
 *          PMDynaform.extendNamespace('PMDynaform.package.Class', Class);
 *
 *      }());
 *
 *      // now PMDynaform.package.Class is a pointer to the class defined above
 *
 * @param {string} path
 * @param {Object} newClass
 * @return {Object} The argument `newClass`
 */
PMDynaform.extendNamespace = function (path, newClass) {
	var current,
		pathArray,
		extension,
		i;
	
	if (arguments.length !== 2) {
		throw new Error("Dynaform.extendNamespace(): method needs 2 arguments");
	}

	pathArray = path.split('.');
	if (pathArray[0] === 'PMDynaform') {
		pathArray = pathArray.slice(1);
	}
	current = PMDynaform;

	// create the 'path' namespace
	for (i = 0; i < pathArray.length - 1; i += 1) {
		extension = pathArray[i];
		if (typeof current[extension] === 'undefined') {
			current[extension] = {};
		}
		current = current[extension];
	}

	extension = pathArray[pathArray.length - 1];
	if (current[extension]) {
		
	}
	current[extension] = newClass;
	return newClass;
};

/**
 * Creates an object whose [[Prototype]] link points to an object's prototype (the object is gathered using the
 * argument `path` and it's the last token in the string), since `subClass` is given it will also mimic the
 * creation of the property `constructor` and a pointer to its parent called `superclass`:
 *
 *      // constructor pointer
 *      subClass.prototype.constructor === subClass       // true
 *
 *      // let's assume that superClass is the last token in the string 'path'
 *      subClass.superclass === superClass         // true
 *
 * An example of use:
 *
 *      (function () {
 *          var Class = function () {...};
 *
 *          // extending the namespace
 *          PMDynaform.extendNamespace('PMDynaform.package.Class', Class);
 *
 *      }());
 *
 *      (function () {
 *          var NewClass = function () {...};
 *
 *          // this class inherits from PMDynaform.package.Class
 *          PMDynaform.inheritFrom('PMDynaform.package.Class', NewClass);
 *
 *          // extending the namespace
 *          PMDynaform.extendNamespace('PMDynaform.package.NewClass', NewClass);
 *
 *      }());
 *
 * @param {string} path
 * @param {Object} subClass
 * @return {Object}
 */
PMDynaform.inheritFrom = function (path, subClass) {
	var current,
		extension,
		pathArray,
		i,
		prototype;

	if (arguments.length !== 2) {
		throw new Error("PMDynaform.inheritFrom(): method needs 2 arguments");
	}

	// function used to create an object whose [[Prototype]] link
	// points to `object`
	function clone(object) {
		var F = function () {};
		F.prototype = object;
		return new F();
	}

	pathArray = path.split('.');
	if (pathArray[0] === 'PMDynaform') {
		pathArray = pathArray.slice(1);
	}
	current = PMDynaform;

	// find that class the 'path' namespace
	for (i = 0; i < pathArray.length; i += 1) {
		extension = pathArray[i];
		if (typeof current[extension] === 'undefined') {
			throw new Error("PMDynaform.inheritFrom(): object " + extension + " not found, full path was " + path);
		}
		current = current[extension];
	}

	prototype = clone(current.prototype);

	prototype.constructor = subClass;
	subClass.prototype = prototype;
	subClass.superclass = current;
};
/**
 * Get the keys from active project
 * @returns {*}
 */
PMDynaform.getProjectKeys = function () {
	var resp = null,
		options;
	if (this.activeProject) {
		options = this.activeProject.webServiceManager.options;
		resp = _.extend(options.keys, options.token);
	}
	return resp;
};

/**
 * Set the instance of an active project pmdynaform
 * @param project
 * @returns { null | PMDynaform.core.ProjectMobile | PMDynaform.core.Project }
 */
PMDynaform.setActiveProject = function (project) {
	if ((PMDynaform.core.ProjectMobile && project instanceof PMDynaform.core.ProjectMobile) || project instanceof PMDynaform.core.Project) {
		this.activeProject = project;
		return project;
	}
	return null;
};

/**
 * Get the active project instance of pmdynaform
 * @returns { PMDynaform.core.ProjectMobile | PMDynaform.core.Project }
 */
PMDynaform.getActiveProject = function () {
	return this.activeProject;
};

/**
 * Get the workspace from the project
 * @returns {*}
 */
PMDynaform.getWorkspaceName = function () {
	var resp = null;
	if (this.activeProject) {
		resp = this.activeProject.webServiceManager.getKey("workspace");
	}
	return resp;
};

/**
 * Get the Accestoken from the project
 * @returns {*}
 */
PMDynaform.getAccessToken = function () {
	var resp = null;
	if (this.activeProject) {
		resp = this.activeProject.webServiceManager.getToken()["accessToken"];
	}
	return resp;
};

/**
 * Get the hostName from the project
 * @returns {*}
 */
PMDynaform.getHostName = function () {
	var resp = null;
	if (this.activeProject) {
		resp = this.activeProject.webServiceManager.getKey("server");
	}
	return resp;
};

/**
 * Get the enviroment (desktop, webkit android or iOS)
 * @returns {string}
 */
PMDynaform.getEnvironment = function () {
	var nav = navigator.userAgent,
		resp;
	resp = nav;
	if (nav === 'formslider-ios') {
		resp = "iOS";
	}
	if (nav === 'formslider-android') {
		resp = "android";
	}
	return resp;
};

String.prototype.capitalize = function() {
	return this.toLowerCase().replace(/(^|\s)([a-z])/g, function(m, p1, p2) { return p1 + p2.toUpperCase(); });
};

jQuery.fn.extend({
	setLabel : function (newLabel, col) {
		var field = getFieldById(this.attr("id")) || null;
		if (typeof newLabel === "string" && field) {
			field.setLabel(newLabel, col);
		}
		return this;
	},
	getLabel : function (col) {
		var field = getFieldById(this.attr("id")) || null;
		if ( field ) {
			return field.getLabel(col);
		}
		return null;
  	},
	setValue : function (value, row, col) {
		var field = getFieldById(this.attr("id")) || null;
		if ( field ) {
			if (field.model.get("type") === "grid") {
				field.setValue(value, row, col);
			}else{
				field.setValue(value);
			}
		}
		return this;
	},
	setText : function (value, row, col) {
		var field = getFieldById(this.attr("id")) || null;
		if ( field ) {
			if (field.model.get("type") === "grid") {
				field.setText(value, row, col);
			}else{
				field.setText(value);
			}
		}
		return this;
	},
	getValue: function (row, col) {
		var field = getFieldById(this.attr("id")) || null, val = null, type;
		if (field) {
			type = field.model.get("type");
			if (val === null && field.model.get("type") === "grid" && row !== undefined && col !== undefined) {
				val = field.getValue(row, col);
			} else {
				if (val === null && field.getData) {
					val = field.getValue();
				}
				if (val === null || val === undefined) {
					if (type === "image") {
						return field.getSrc();
					}
					if (field && field.getLabel) {
						val = field.getLabel();
					}
				}
			}
		} else {
			null;
		}
		return val;
	},
	/**
	 * helper getAppDocUID function to get app document uid as reference to a document
	 * @returns {array} val
     */
	getAppDocUID: function () {
		var item,
			val = null;
		if (getFieldById(this.attr("id"))) {
			item = getFieldById(this.attr("id"));
			if (typeof item.model.getAppDocUID === 'function'
				&& item.model.getAppDocUID()) {

				val = item.model.getAppDocUID();
			}
		}
		return val;
	},

	setOnchange : function ( handler ) {
		var item;
		if (getFieldById(this.attr("id"))){
			item = getFieldById(this.attr("id"));
		} else if ( getFormById(this.attr("id")) ) {
			item = getFormById(this.attr("id"));
		}
		if ( typeof handler === "function" && item) {
			if (item.model.get("type")!== "label"){
				item.setOnChange(handler);
			}
		}
		return this;
	},
	getInfo : function () {
		var field = getFieldById(this.attr("id")) || null;
		if (field){
			return field.getInfo();
		}
		return null;
	},
	setHref : function (value) {
		var field = getFieldById(this.attr("id")) || null;
		if (field.model.get("type") === "link") {
			field.setHref(value);
		}
		return this;
	},
	getHref : function () {
		var field = getFieldById(this.attr("id")) || null;
		if (field.model.get("type") === "link") {
			return field.getHref();
		}
		return this;
	},
	setRequired : function (field) {
		//console.log("test method field");
	},
	required : function (field) {
		//console.log("test method field");
	},
	getText : function (row, col) {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field.model.get("type") !== "grid") {
			if ( field && field.getData ){
				val = field.getText();
			}
		} else {
			if (field.model.get("type") === "grid") {
				val = field.getText(row,col);
			}else{
				val = null;
			}
		}
		return val;
	},
	disableValidation : function (col) {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field && field.disableValidation) {
			field.disableValidation(col);
		}
		return this;
	},
	enableValidation : function (col) {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field && field.enableValidation) {
			field.enableValidation(col);
		}
		return this;
	},
	getControl : function (row, col) {
		var field = getFieldById(this.attr("id")) || null;
		if ( field ) {
			return field.getControl(row, col);
		}
		return [];
	},
	getLabelControl : function () {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field){
			field.getLabelControl();
		}
		return this;
	},
	getHintHtml : function () {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field){
			html = field.$el.find(".glyphicon-info-sign");
		}
		return $(html);
	},
	getSummary : function (col) {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid"){
			return field.getSummary(col);
		}
		return this;
	},
	getNumberRows: function () {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type") === "grid") {
			return field.getNumberRows();
		}
		return this;
	},
	addRow : function (data){
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid"){
			field.addRow(data);
		}
		return this;	
	},
	deleteRow: function (row) {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type") == "grid") {
			if (!row) {
				row = field.getNumberRows();
			}
			field.deleteRow(row);
		}
		return this;
	},
	onBeforeAdd : function () {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid"){
			if ( typeof handler === "function" ) {
				field.setOnBeforeAddCallback(handler);
			}
		}
	},
	onAddRow : function (handler) {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid"){
			if ( typeof handler === "function" ) {
				field.setOnAddRowCallback(handler);
			}
		}
	},
	onShowRowDialog : function (handler) {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid" && PMDynaform.core.ProjectMobile){
			if ( typeof handler === "function" ) {
				field.setOnShowRowDialog(handler);
			}
		}
	},
	onDeleteRow : function (handler) {
		var field = getFieldById(this.attr("id")) || null, html = [];
		if (field && field.model.get("type")=="grid"){
			if ( typeof handler === "function" ) {
				field.setOnDeleteRowCallback(handler);
			}
		}
	},
	hideColumn: function (col) {
		var field = getFieldById(this.attr("id")) || null;
		if (field && field.model.get("type") === "grid") {
			field.hideColumn(col);
		}
	},
	showColumn: function (col) {
		var field = getFieldById(this.attr("id")) || null;
		if (field && field.model.get("type") === "grid") {
			field.showColumn(col);
		}
	},
	getData : function () {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field && field.getData) {
			return field.getData();
		}
		return this;
	},
	getDataLabel : function () {
		var field = getFieldById(this.attr("id")) || null, val;
		if (field && field.getDataLabel) {
			return field.getDataLabel();
		}
		return this;
	},
    getForm: function () {
        var form;
        if (this.length) {
            form = getFormById(this.attr('id') || '') || null;
            return form;
        }
    },
    submitForm: function () {
        var project;
        var form = this.getForm();
        if (form){
            project = form.project;
            if (project && project.getFormAjax()){
                this.attr("action", project.getFormAjax().action);
                if (form.isValid()) {
                    form.el.submit();
                }
            } else {
                form.onSubmit();
            }
        }
    },
    saveForm: function () {
        var form,
            webServiceManager,
            formID = this.attr('id'),
            formData;
        form = this.getForm();
        if (form) {
            webServiceManager = form.project.webServiceManager;
            formData = form.project.view.getData2();
            return webServiceManager.submitFormCase(formID, formData);
        }
    },
    _getJSONFormValues : function (elements){
        var i;
        var data = {};
        if (elements.length > 0) {
            for (i = 0; i < elements.length; i += 1) {
                data[elements[i].name] = elements[i].value;
            }
        }
        return data;
    },
    /**
     * This is a help function to close the form, supported for mobile version
     * @returns {jQuery}
     */
    closeForm: function () {
        var form = getFormById(this.attr("id"));
        if (form && form instanceof PMDynaform.view.FormPanel) {
            form.close();
        }
        return this;
    },
    /**
     * Show Modal Loading
     * @returns {jQuery}
     */
    showFormModal : function () {
        var form = this.getForm(), modal;
        if (form) {
            modal = form.project.modalProgress;
            modal.render();
        }
        return this;
    },
    /**
     * Hide Modal Loading
     * @returns {jQuery}
     */
    hideFormModal : function () {
        var form = this.getForm(), modal;
        if (form) {
            modal = form.project.modalProgress;
            modal.hide();
        }
        return this;
    }
});


(function(){
	/*
	 * @param {String}
	 * The following key selectors are availables for the
	 * getField and getGridField methods
	 * - Using '#', is possible select a field with the identifier of the field
	 * - Using ''. is possible select a field with the className of the field
	 * - Putting 'attr[name="my-name"]' is possible select fields with the same name attribute
	 *
	 **/
	var Selector = function (options) {
		this.onSupportSelectorFields = null;
		this.fieldType = null;
		this.fields = [];
		this.queries = [];
		this.forms = [];

		Selector.prototype.init.call(this, options);
	};
	Selector.prototype.init = function (options) {
		var defaults = {
			fields: [],
			queries: [],
			forms : [],
			onSupportSelectorFields: {
				text: "onTextField",
				textarea: "onTextAreaField"
			}
		};

		$.extend(true, defaults, options);
		
		this.setOnSupportSelectorFields(defaults.onSupportSelectorFields)
			.setFields(defaults.fields)
			.setForms(defaults.forms)
			.applyGlobalSelectors();
	};
	Selector.prototype.addQuery = function (query) {
		if (typeof query === "string") {
			this.queries.push(query);
		} else {
			throw new Error ("The query selector must be a string");
		}

		return this;
	};
	Selector.prototype.setOnSupportSelectorFields = function (support) {
		if (typeof support === "object") {
			this.onSupportSelectorFields = support;
		} else {
			throw new Error ("The parameter for the support fields is wrong");
		}

		return this;
	};
	Selector.prototype.setFields = function (fields) {
		if (typeof fields === "object") {
			this.fields = fields;
		}

		return this;
	};

	Selector.prototype.setForms = function (forms) {
		if (jQuery.isArray(forms)){
			this.forms = forms;
		}
		return this;
	};

	Selector.prototype.onTextField = function (selector) {
		//console.log("selector text field", selector);

		return this;
	};
	Selector.prototype.onTextAreaField = function (selector) {
		//console.log("selector textarea field", selector);
		
		return this;
	};

	Selector.prototype.findFieldById = function (selectorAttr) {
		var i,
		fieldFinded = null;

		searching:
		for (i=0; i<this.fields.length; i+=1) {
			if (this.fields[i].model.id === selectorAttr) {
				fieldFinded = this.fields[i];
				break searching;
			}
		}
		return fieldFinded;
	};
	Selector.prototype.findFormById = function (selectorId) {
		var i;
		for ( i = 0 ; i < this.forms.length ; i+=1 ) {
			if ( this.forms[i].model.id === selectorId ) {
				return this.forms[i];
			}
		}
		return null;
	},
	Selector.prototype.findFieldByName = function (selectorAttr) {
		var i,
		fieldFinded = [];

		for (i=0; i<this.fields.length; i+=1) {
			if (this.fields[i].model.get("name") === selectorAttr) {
				fieldFinded.push(this.fields[i]);
			}
		}
		return fieldFinded;
	};
	Selector.prototype.findFieldByAttribute = function (parameter, value) {
		var i,
		fieldFinded = [];

		for (i=0; i<this.fields.length; i+=1) {
			if (this.fields[i].model.attributes[parameter]) {
				if (this.fields[i].model.get(parameter) === value) {
					fieldFinded.push(this.fields[i]);
				}
			}
		}
		return fieldFinded;
	};
	Selector.prototype.applyGlobalSelectors = function () {
		var sel,
		i,
		that = this;
		
		window.getFieldByAttribute = function (attr, value) {
			that.addQuery(attr + ": " + value);
			return that.findFieldByAttribute(attr, value);
		};
		window.getFieldById = function (query) {
			that.addQuery("id: " + query);
			return that.findFieldById(query);
		};
		window.getFieldByName = function (query) {
			that.addQuery("name: " + query);
			return that.findFieldByName(query);
		};
		window.getFormById = function (query) {
			that.addQuery("id: " + query);
			return that.findFormById(query);
		}

		return this;
	};

	PMDynaform.extendNamespace("PMDynaform.core.Selector", Selector);
}());
(function(){
	
	var Utils = {
		generateID: function () {
			var rand = function (min, max) {
	            // Returns a random number
	            //
	            // version: 1109.2015
	            // discuss at: http://phpjs.org/functions/rand
	            // +   original by: Leslie Hoare
	            // +   bugfixed by: Onno Marsman
	            // %          note 1: See the commented out code below for a
	            // version which will work with our experimental
	            // (though probably unnecessary) srand() function)
	            // *     example 1: rand(1, 1);
	            // *     returns 1: 1

	            // fix for jsLint
	            // from: var argc = arguments.length;
	            if (typeof min === "undefined") {
	                min = 0;
	            }
	            if (typeof max === "undefined") {
	                max = 999999999;
	            }
	            return Math.floor(Math.random() * (max - min + 1)) + min;
	        },
	        uniqid = function (prefix, more_entropy) {
	        	var php_js = {},
	        	retId,
				formatSeed;
	            // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	            // +    revised by: Kankrelune (http://www.webfaktory.info/)
	            // %        note 1: Uses an internal counter (in php_js global) to avoid collision
	            // *     example 1: uniqid();
	            // *     returns 1: 'a30285b160c14'
	            // *     example 2: uniqid('foo');
	            // *     returns 2: 'fooa30285b1cd361'
	            // *     example 3: uniqid('bar', true);
	            // *     returns 3: 'bara20285b23dfd1.31879087'
	            if (typeof prefix === 'undefined') {
	                prefix = "";
	            }
	            
                formatSeed = function (seed, reqWidth) {
                    var tempString = "",
                    i;

                    seed = parseInt(seed, 10).toString(16); // to hex str
                    if (reqWidth < seed.length) { // so long we split
                        return seed.slice(seed.length - reqWidth);
                    }
                    if (reqWidth > seed.length) { // so short we pad
                        // jsLint fix
                        tempString = "";
                        for (i = 0; i < 1 + (reqWidth - seed.length); i += 1) {
                            tempString += "0";
                        }
                        return tempString + seed;
                    }
                    return seed;
                };

	            // BEGIN REDUNDANT
	            if (!php_js) {
	                php_js = {};
	            }
	            // END REDUNDANT
	            if (!php_js.uniqidSeed) { // init seed with big random int
	                php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
	            }
	            php_js.uniqidSeed += 1;

	            retId = prefix; // start with prefix, add current milliseconds hex string
	            retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
	            retId += formatSeed(php_js.uniqidSeed, 5); // add seed hex string
	            if (more_entropy) {
	                // for more entropy we add a float lower to 10
	                retId += (Math.random() * 10).toFixed(8).toString();
	            }

	            return retId;
	        },
	        sUID;

		    do {
		        sUID = uniqid(rand(0, 999999999), true);
		        sUID = sUID.replace('.', '0');
		    } while (sUID.length !== 32);
		    
	    	return "pmd" + sUID;
		},
		generateName: function (type) {
			return type + "[" + PMDynaform.core.Utils.generateID() + "]";
		},
		/**
		 * validate JSON parse
		 * @param str
		 * @returns {boolean}
		 */
		isJsonAndParse: function (str) {
			var result;
			try {
				result = JSON.parse(str);
			} catch (e) {
				result = str.split(',');
			}
			return result;
		}
	};
	PMDynaform.extendNamespace("PMDynaform.core.Utils", Utils);
	
}());
(function() {
    var Validators = {
        /*
         * 
         * @type type
         */
        requiredText: {
            message: "This field is required",
            fn: function(value) {
                value = value.trim();
                if (value === null || value.length === 0 || /^\s+$/.test(value)) {
                    return false;
                }
                return true;
            }
        },
        /*
         * 
         * @type type
         */
        requiredDropDown: {
            message: "This field is required",
            fn: function(value) {
                value = value.trim();
                if (value === null || value.length === 0 || /^\s+$/.test(value)) {
                    return false;
                }
                return true;
            }
        },
        requiredCheckBox: {
            message: "This field is required",
            fn: function(value) {
                if (typeof value === "number") {
                    var bool = (value > 0)? true: false;    
                } else {
                    bool = false;
                }
                return bool;
            }
        },
		requiredCheckGroup: {
			message: "This field is required",
			fn: function(value) {
				if (typeof value === "number") {
					var bool = (value > 0)? true: false;    
                } else {
                    bool = false;
                }
                return bool;
            }
        },
        requiredFile: {
            message: "This field File is required",
            fn: function(value) {
                value = value.trim();
                if (value === null || value.length === 0 || /^\s+$/.test(value)) {
                    return false;
                }
                return true;
            }
        },
        requiredRadioGroup: {
            message: "This field is required",
            fn: function(value) {
                if (typeof value === "number") {
                    var bool = (value > 0)? true: false;    
                } else {
                    bool = false;
                }
                return bool;
            }
        },
        integer: {
            message: "Invalid value for the integer field",
            mask: /[\d\.]/i,
            fn: function(n) {
                return (n != "" && !isNaN(n) && Math.round(n) == n);
            }
        },
        float: {
            message: "Invalid value for the float field",
            fn: function(n) {
                return  /^-?\d+\.?\d*$/.test(n);
            }
        },
        string: {
            fn: function(string){
                return true;
            }
        },
        boolean: {
            fn: function(string) {
                return true;
            }
        },
        maxLength: {
            message: "The maximum length are ",
            fn: function(value, maxLength) {
                var maxLen;
                if (typeof maxLength !== "number") {
                    throw new Error ("The parameter maxlength is not a number");
                }
                maxLen = (value.toString().length <= maxLength)? true : false;                
                return maxLen;                                
            }
        },
        /**
         * validate that there is at least one row on the grid
         * return [boolean]
         */
        requiredGrid: {
            message: "At least one row is required",
            fn: function(value) {
                if (value === null || value === 0) {
                    return false;
                }
                return true;
            }
        }
    };
    
    PMDynaform.extendNamespace("PMDynaform.core.Validators", Validators);
}());

(function(){
    var ModalProgressBar = Backbone.View.extend({
        timeHide: 1000,
        template: _.template($("#tpl-modal-global").html()),
        initialize: function (){
            //TODO: no need params.
        },
        render: function(){
            if ($('#modalProgressBar').length) {
                $('#modalProgressBar').remove();
            }
            $('body').append(this.template());
            this.show();
            return this;
        },
        show: function(){
            $('#modalProgressBar').modal({backdrop: 'static', keyboard: false}, 'show');
            return this;
        },
        hide: function(){
            if ($('#modalProgressBar').length) {
                setTimeout(function(){
                    $('#modalProgressBar').modal('hide');
                }, this.timeHide);
            }
            return this;
        },
        setTimeHide: function(timeHide){
            this.timeHide = timeHide;
            return this;
        },
        getTimeHide: function(){
            return this.timeHide;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.ModalProgressBar", ModalProgressBar);
}());
(function () {
    var Project = function (options) {
        this.model = null;
        this.modalProgress = null;
        this.view = null;
        this.data = null;
        this.fields = null;
        this.keys = null;
        this.token = null;
        this.renderTo = null;
        this.urlFormat = null;
        this.endPointsPath = null;
        this.forms = null;
        this.externalLibs = null;
        this.dependentLibraries = null;
        this.submitRest = null;
        this.formAjax = null;
        this.globalMode = null;
        this.onSubmitForm = new Function();
        this.language = options.language || null;
        this.onBeforePrintHandler = null;
        this.onAfterPrintHanlder = null;
        this.flashView = null;
        Project.prototype.init.call(this, options);
    };

    Project.prototype.init = function (options) {
        var defaults = {
            formAction : null,
            formAjax: null,
            submitRest: false,
            globalMode: null,
            data: {
                "description": "",
                "items": [],
                "name": ""
            },
            urlFormatMobile : "{server}/api/1.0/{workspace}/{endPointPath}",
            urlFormat: "{server}/{apiName}/{apiVersion}/{workspace}/{keyProject}/{projectId}/{endPointPath}",
            keys: {
                server: "",
                projectId: "",
                workspace: "",
                keyProject: "project",
                apiName: "api",
                apiVersion: "1.0"
            },
            token: {
                accessToken: "",
                clientId: "x-pm-local-client",
                clientSecret: "",
                expiresIn: "",
                refreshToken: "",
                scope: "",
                tokenType: "bearer"
            },
            endPointsPath: {
                project: "",
                createVariable: "process-variable",
                variableList: "process-variable",
                /**
                 * @key {var_uid} Defines the identifier of the variable
                 * The Endpoint is for get all information about Variable
                 **/
                variableInfo: "process-variable/{var_uid}",
                /**
                 * @key {var_name} Defines the variable name
                 * The Endpoint executes the query associated to variable
                 **/
                executeQuery: "process-variable/{var_name}/execute-query",
                /**
                 *
                 * @key {field_name} Defines the field name
                 * The Endpoint uploads a file
                 **/
                uploadFile: "uploadfile/{field_name}",
                executeQuerySuggest: "process-variable/{var_name}/execute-query-suggest",
                fileStreaming : "en/neoclassic/cases/casesStreamingFile?actionAjax=streaming&a={caseID}&d={fileId}",
                getAllDataCase :"case/{caseID}/variables",
                imageDownload: 'light/case/{caseID}/download64',
                fileDownload: "case/{caseID}/file/{fileID}",
                imageInfo: "light/case/{caseID}/download64",
                getImageGeo: "light/case/{caseID}/download64"
            },
            externalLibs: "",
            renderTo: document.body,
            onLoad: new Function()
        };
        this.urlFormatStreaming  = "{server}/sys{workspace}/{endPointPath}";
        if (!_.isEmpty(options.data) && options.data.items[0] && options.data.items[0]["externalLibs"]) {
            this.externalLibs = options.data.items[0]["externalLibs"].split(",");
        }
        var that = this;
        //start loading
        $("body").append("<div class='pmDynaformLoading' style='position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url(/lib/img/loading.gif) 50% 50% no-repeat #f9f9f9;'></div>");
        this.setExternalLibreries(this.externalLibs, 0, function () {
        jQuery.extend(true, defaults, options);
        that.setFormAjax(defaults.formAjax);
        that.setBeforePrintHandler(defaults.onBeforePrintHandler);
        that.setAfterPrintHandler(defaults.onAfterPrintHandler);
        that.setData(defaults.data);
        that.setLanguage();
        that.initModalProgress();
        that.setUrlFormat(defaults.urlFormat);
        that.setUrlFormatMobile(defaults.urlFormatMobile);
        that.setKeys(defaults.keys);
        that.setToken(defaults.token);
        that.setRenderTo(defaults.renderTo);
        that.setEndPointsPath(defaults.endPointsPath);
        that.createWebServiceManager();
        PMDynaform.setActiveProject(that);
        that.checkMobileData();
        that.submitRest = defaults.submitRest;
        if (!PMDynaform.core.ProjectMobile){
            that.checkGeoMapsLibraries(defaults.onLoad);
        }else{
            that.checkGeoMapsLibraries();
        }
        //stop loading
        $("body").find(".pmDynaformLoading").remove();
    });
};
    Project.prototype.setFormAjax = function(params){
        if (params){
            this.formAjax = params;
        }
        return this;
    };
    Project.prototype.getFormAjax = function(){
        return this.formAjax;
    };

    Project.prototype.initModalProgress = function(){
        this.modalProgress =  new PMDynaform.view.ModalProgressBar();
        return this;
    };

    /**
     * @param globalMode
     * @returns {Project}
     */
    Project.prototype.setGlobalMode = function (globalMode) {
        if (globalMode) {
            this.globalMode = globalMode;
        }
        return this;
    };
    Project.prototype.checkMobileData = function () {
        if (!PMDynaform.core.ProjectMobile){
            this.mobileDataControls = this.loadAllDataCase();
        }
        return this;
    };
    Project.prototype.setLanguage = function () {       
        if(window.sysLang){
            this.language = window.sysLang;            
        }
        return this;
    };
    Project.prototype.setExternalLibreries = function (libs, i, fn) {
        var item, type, that = this;
        if (jQuery.isArray(libs) && i < libs.length) {
            item = libs[i].trim();
            type = item.substring(item.lastIndexOf(".") + 1);
            switch (type) {
                case "js" :
                    var script = document.createElement('script');
                    script.onload = function () {
                        that.setExternalLibreries(libs, i + 1, fn);
                    };
                    script.type = 'text/javascript';
                    script.src = item;
                    document.head.appendChild(script);

                    break;
                case "css" :
                    var link = document.createElement("link");
                    link.onload = function () {
                        that.setExternalLibreries(libs, i + 1, fn);
                    };
                    link.rel = "stylesheet";
                    link.href = item;
                    document.head.appendChild(link);
                    break;
            }
        } else {
            fn();
        }
    };
    Project.prototype.setData = function (data) {
        if (typeof data === "object") {
            this.data = data;
        }
        if (this.view) {
            this.destroy();
            this.loadProject();
        }

        return this;
    };
    Project.prototype.setData2 = function (data) {
        this.view.setData2(data);
        return this;
    };
    Project.prototype.setUrlFormat = function (url) {
        if (typeof url === "string") {
            this.urlFormat = url;
        }
        return this;
    };
    Project.prototype.setUrlFormatMobile = function (url) {
        if (typeof url === "string") {
            this.urlFormatMobile = url;
        }
        return this;
    };
    Project.prototype.setKeys = function (keys) {
        var keysFixed = {},
                key,
                leftBracket;
        if(!PMDynaform.core.ProjectMobile){
            if(keys.server.indexOf("http://") == -1 || keys.server.indexOf("https://") == -1){
                keys.server = keys.server;
            }           
        }
        if (typeof keys === "object") {
            for (key in keys) {
                leftBracket = (keys[key][0] === "/") ? keys[key].substring(1) : keys[key];
                keysFixed[key] = (leftBracket[leftBracket.length - 1] === "/") ? leftBracket.substring(0, leftBracket.length - 1) : leftBracket;
            }
            //keysFixed.server = keysFixed.server.replace(/\https:\/\//, "").replace(/\http:\/\//, "");
            this.keys = keysFixed;
        }

        return this;
    };
    Project.prototype.setToken = function (objToken) {
        if (typeof objToken === "object") {
            this.token = objToken;
        }

        return this;
    };
    Project.prototype.setRenderTo = function (to) {
        this.renderTo = to;

        return this;
    };
    Project.prototype.setEndPointsPath = function (endpoints) {
        var leftBracket,
                point,
                endpointsVerified = {};

        for (point in endpoints) {
            if (typeof endpoints[point] === "string") {
                leftBracket = (endpoints[point][0] === "/") ? endpoints[point].substring(1) : endpoints[point];
                endpointsVerified[point] = (endpoints[point][endpoints[point].length - 1] === "/") ?
                        endpoints[point].substring(0, endpoints[point].length - 1) :
                        endpoints[point];
            } else {
                throw new Error("The endpoint path is not correct, " + endpoints[point]);
            }
        }
        this.endPointsPath = endpointsVerified;

        return this;
    };
    Project.prototype.checkGeoMapsLibraries = function (onload) {
        var i,
                libs = [],
                enableGeoMap = false,
                searchingMap,
                forms = this.data.items;

        searchingMap = function (fields) {
            var j,
                    k,
                    l,
                    dependent = [
                        "geomap",
                        "other",
                        "location"
                    ];

            outer_loop:
            for (j = 0; j < fields.length; j += 1) {
                for (k = 0; k < fields[j].length; k += 1) {
                    if ($.inArray(fields[j][k].type, dependent) >= 0) {
                        enableGeoMap = true;
                        break outer_loop;
                    } else if (fields[j][k].type === "form") {
                        searchingMap(fields[j][k].items);
                    }
                }
            }
        };

        for (i = 0; i < forms.length; i += 1) {
            searchingMap(forms[i].items);
        }

        if (enableGeoMap) {
            this.loadGeoMapDependencies(onload);
        } else {
            this.loadProject(onload);
        }

        return this;
    };
    Project.prototype.checkScript = function () {
        var i, j, k, code, model, scriptCode = "", rows, item, type,row;
        for (i = 0; i < this.forms.length; i += 1) {
            rows = this.forms[i].model.get("items");
            if (!_.isEmpty(this.forms[i].model.get("script"))) {
                scriptCode = scriptCode.concat(this.forms[i].model.get("script").code);
            }
            for (j = 0 ; j < rows.length ; j +=1) {
                row = rows[j];
                for (var k = 0 ; k < row.length; k +=1) {
                    item = row[k];
                    if ( item.type === "form" ) {
                        if (!_.isEmpty(item.script)) {
                            scriptCode = scriptCode.concat(item.script.code);
                        }
                    }
                }
            }
        }
        if (scriptCode.trim().length){
            code = new PMDynaform.core.Script({
                script: scriptCode
            });
            code.render();
        }
    };
    Project.prototype.setAllFields = function (fields) {
        if (typeof fields === "object") {
            this.fields = fields;
            this.selector.setFields(fields);
            //console.log("set fields");
        }
        return this;
    };
    Project.prototype.getModelForm = function (index){
        if(this.data.items[index] !== undefined){
            return this.data.items[index];
        }else{
            return false;
        }
    };

    Project.prototype.loadProject = function (onload) {
        var that = this, firstForm;
        firstForm = this.getModelForm(0);
        if (firstForm){
            if (typeof this.onBeforePrintHandler === "function"){
                firstForm.onBeforePrintHandler = this.onBeforePrintHandler;
            }
            if (typeof this.onAfterPrintHandler === "function"){
                firstForm.onAfterPrintHandler = this.onAfterPrintHandler;
            }            
        }
        this.model = new PMDynaform.model.Panel(this.data);
        this.view = new PMDynaform.view.Panel({
            tagName: "div",
            renderTo: this.renderTo,
            model: this.model,
            project: this
        });
        this.flashMessage();
        if (onload && typeof onload == "function"){
            onload();
        }
        this.forms = this.view.getPanels();
        this.createGlobalPmdynaformClass(this.view);
        this.createSelectors();
        this.checkScript();
        this.createMessageLoading();
        that.view.afterRender();
        that.view.$el.find(".pmdynaform-form-message-loading").remove();
        $("#shadow-form").remove();
        this.onScrollUpdate();
        return this;
    };
    Project.prototype.createMessageLoading = function () {
        var msgTpl = _.template($('#tpl-loading').html());
        this.view.$el.prepend(msgTpl({
            title: "Loading",
            msg: "Please wait while the data is loading..."
        }));
        this.view.$el.find("#shadow-form").css("height", this.view.$el.height() + "px");
    };
    Project.prototype.createSelectors = function () {
        var i,
                eachForm,
                fields = [];

        eachForm = function (items) {
            var jFields;

            for (jFields = 0; jFields < items.length; jFields += 1) {
                if (items[jFields].model.get("type") === "form") {
                    eachForm(items[jFields].formView.getFields());
                } else {
                    fields.push(items[jFields]);
                }
            }
        };

        //Each Form
        for (i = 0; i < this.forms.length; i += 1) {
            eachForm(this.forms[i].getFields());
        }

        this.fields = fields;
        this.selector = new PMDynaform.core.Selector({
            fields: fields,
            forms: this.forms
        });

        return this;
    };
    Project.prototype.createGlobalPmdynaformClass = function (form) {

    };
    Project.prototype.loadGeoMapDependencies = function (onload) {
        var i,
                auxClass,
                instanceClass,
                that = this,
                loadScript = true,
                libs = "";

        libs = document.body.getElementsByTagName("script");
        outer_script:
                for (i = 0; i < libs.length; i += 1) {
            if ($(libs[i]).data) {
                if ($(libs[i]).data("script") === "google") {
                    loadScript = false;
                    break outer_script;
                }
            }
        }
        if (loadScript) {
            auxClass = function (params) {
                this.project = params.project;
            };
            auxClass.prototype.load = function () {
                this.project.loadProject(onload);
            };
            window.pmd = new auxClass({project: this});
            var script = document.createElement('script');
            script.type = 'text/javascript';
            $(script).data("script", "google");
            script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&callback=pmd.load';
            document.body.appendChild(script);
        } else {
            this.loadProject(onload);
        }
        return this;
    };
    Project.prototype.registerKey = function (key, value) {
        if ((typeof key === "string") && (typeof value === "string")) {
            if (!this.keys[key]) {
                this.keys[key] = value;
            } else {
                throw new Error("The key already exists.");
            }
        } else {
            throw new Error("The parameters must be strings.");
        }

        return this;
    };
    Project.prototype.getEndPoint = function (type) {
        return this.endPointsPath[type];
    };
    Project.prototype.setModel = function (model) {
        if (model instanceof Backbone.Model) {
            this.model = model;
        }
        return this;
    };
    Project.prototype.setView = function (view) {
        if (view instanceof Backbone.View) {
            this.view = view;
        }
        return this;
    };
    Project.prototype.getForms = function () {
        var forms,
                panels = [];

        if (this.view instanceof PMDynaform.view.Panel) {
            forms = this.view.getPanels();
        }

        return forms;
    };
    Project.prototype.getData = function () {
        var formData = this.view.getData();

        return formData;
    };
    Project.prototype.destroy = function () {
        this.view.$el.remove();

        return this;
    };
    /*
    Mobile project methods
     */
    Project.prototype.loadAllDataCase = function() {
        var restClient, endpoint, url, that=this, resp = {};
        if(window.app_uid){
            return this.webServiceManager.loadAllDataCase();
        }
        return resp;       
    };

    Project.prototype.getFullEndPoint= function (urlEndpoint) {
        var k, 
        keys  = this.keys,
        urlFormat = urlEndpoint;
        for (k in keys) {
            if (keys.hasOwnProperty(k)) {
                urlFormat = urlFormat.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);               
            }
        }
        return urlFormat;
    };
    Project.prototype.getFullURLMobile = function (endpoint) {
        var k, 
        keys  = this.keys,
        urlFormat = this.urlFormatMobile;
        urlFormat=urlFormat.replace(/{endPointPath}/, endpoint);
        for (k in keys) {
            if (keys.hasOwnProperty(k)) {                           
                    urlFormat = urlFormat.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);               
            }
        }
        urlFormat = window.location.protocol + "//" + urlFormat.replace(/{endPointPath}/, endpoint);
        return urlFormat;
    };
    Project.prototype.getFullURL = function (endpoint) {
        var k,
                keys = this.keys,
                urlFormat = this.urlFormat;

        for (k in keys) {
            if (keys.hasOwnProperty(k)) {
                urlFormat = urlFormat.replace(new RegExp("{" + k + "}", "g"), keys[k]);
                //endPointFixed =endpoint.replace(new RegExp(variable, "g"), keys[variable]);   
            }
        }
        urlFormat = window.location.protocol + "//" + urlFormat.replace(/{endPointPath}/, endpoint);        
        if (urlFormat.indexOf("file") > -1){
            urlFormat = urlFormat.replace(/file/g, "http");
        }
        return urlFormat;
    };

    Project.prototype.getFullURLStreaming = function (endpoint) {
        var k, 
        keys  = this.keys,
        urlFormat = this.urlFormatStreaming;
        urlFormat=urlFormat.replace(/{endPointPath}/, endpoint);
        for (k in keys) {
            if (keys.hasOwnProperty(k)) {                           
                    urlFormat = urlFormat.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);               
            }
        }
        urlFormat = window.location.protocol + "//" + urlFormat.replace(/{endPointPath}/, endpoint);
        return urlFormat;
    };
    Project.prototype.createWebServiceManager = function () {
        var keys1= {
            server: this.keys.server,
            processID: this.keys.projectId,
            taskID:window.app_uid? window.app_uid:null,
            caseID:window.app_uid? window.app_uid:null,
            workspace: this.keys.workspace,
            formID: null,
            keyProject : "project",            
            stepID : null,
            delIndex: null
        };

        this.webServiceManager = new PMDynaform.implements.WebServiceManager({
            keys : keys1,
            token : this.token,
            language : this.language        
        });    
    };
    Project.prototype.setBeforePrintHandler = function(handler){
        if (typeof handler === "function"){
            this.onBeforePrintHandler = handler;
        }else{
            handler = null;
        }
        return this;
    };
    Project.prototype.setAfterPrintHandler = function(handler){
        if (typeof handler === "function"){
            this.onAfterPrintHandler = handler;
        }else{
            handler = null;
        }
        return this;
    };
    Project.prototype.flashMessage = function(config){
        if (typeof config === "object" ) {
            if (!Project.flashMessage) {
                this.flashModel = new PMDynaform.ui.FlashMessageModel({
                    message: config.message || "undefined message",
                    emphasisMessage: config.emphasisMessage || "undefined emphasisMessage",   
                    startAnimation: config.startAnimation || 1000,
                    type: config.type || "info",
                    appendTo: config.appendTo || document.body,
                    duration: config.duration || 1500,
                    absoluteTop: config.absoluteTop || false
                });
                this.flashView = new PMDynaform.ui.FlashMessageView({
                    model : this.flashModel
                });
            } else {
                this.configFlashMessage(config);
            }
            this.flashView.render();
            if (this.flashModel.get("absoluteTop")){
                this.onScrollUpdate(this.flashView.el);
            }
        }
        return this;
    },
    Project.prototype.configFlashMessage = function (config){
        if (this.flashModel && this.flashModel instanceof PMDynaform.ui.FlashMessageModel){
            this.flashModel.set("message",config.message || "undefined message");
            this.flashModel.set("emphasisMessage",config.emphasisMessage || "undefined emphasisMessage"); 
            this.flashModel.set("startAnimation",config.startAnimation || 500);
            this.flashModel.set("type",config.type || "info");
            this.flashModel.set("appendTo",config.appendTo || document.body);
            this.flashModel.set("duration",config.duration || 1500);
            this.flashModel.set("absoluteTop",config.absoluteTop || false);
        }
        return this;
    },
    Project.prototype.onScrollUpdate = function(element){
        var that = this,
            iScrollPos = 0,
            dateTimePicker,
            i,
            picker;
        $(window).scroll(function () {
            if(element){
                element.style.top = $(document).scrollTop()+"px";
            }
            dateTimePicker = $(document).find(".datetime-container").children();
            for ( i = 0 ; i < dateTimePicker.length; i+=1){
                if (dateTimePicker.eq(i).data){
                    picker = dateTimePicker.eq(i).data().DateTimePicker;
                    picker.hide();
                }
            }
        });
    }
    PMDynaform.extendNamespace("PMDynaform.core.Project", Project);

}());
(function () {
	/**
	 * @class PMDynaform.core.TokenStream
	 * Class to handle tokens or attributes for build de Formula 
	 * @param {Object} tokens
	 */
	var TokenStream = function (tokens) {
	    /**
         * @property {Number} [cursor=0] The property represents the current index 
         * of the tokens array.
         * @private
         */
	    this.cursor = 0;
	    /**
         * @property {Object} Encapsulate all tokens passed as parameter 
         * @private
         */
	    this.tokens = tokens;
	};
	 /**
     * Gets the next token element of the array
     * @return {String} element selected.
     * @private
     */
	TokenStream.prototype.next = function () {
		return this.tokens[this.cursor++];
	};
	/**
	 * The method helps in the cases when exist brackets inside of Formula
	 * @private
	 * @param {String} direction
	 * @return {String} token Element selected from token array
	 */
	TokenStream.prototype.peek = function (direction) {
		if (direction === undefined) {
            direction = 0;
        }
        return this.tokens[this.cursor + direction];
	};
	PMDynaform.extendNamespace("PMDynaform.core.TokenStream", TokenStream);


	/**
	 * @class PMDynaform.core.Tokenizer
	 * Class to manage all fields and their values, those values may be CONSTANTS, 
	 * MATH functions and FIELDS.
	 * @param {Object} tokens
	 */
	var Tokenizer = function () {
	    /**
         * @property {Number} [tokens={}] The property represents all the tokens stored
         * @private
         */
	    this.tokens = {};
	    /**
         * @property {String} [regex=null] Represents the property that encapsulate the execution
         * of the Regular Expression when the token is been finded or executed
         * @private
         */
	    this.regex = null;
	    /**
         * @property {Array} [fields=[]] Encapsulate all the fields associated or that are inside
         * of the tokens
         * @private
         */
	    this.fields = [];
	    /**
         * @property {Array} [tokenNames=[]] All the names of the tokens are stored in the array
         * @private
         */
	    this.tokenNames = [];
	    /**
         * @property {Object} [tokenFields={}] All the tokens fields are stored in this property
         * @private
         */
	    this.tokenFields = {};
	};
	/**
     * Adds new token to tokens array. If the element already exist this is replaced.
     * @param {String} name The name corresponde to name of the property inside of the tokens
     * @param {String} expression This is the value if the element is a field and is an expression
     * if the element is a bracket or some function.
     * @private
     */
	Tokenizer.prototype.addToken = function (name, expression) {
		this.tokens[name] = expression;
	};
	/**
     * Adds new expression to field
     * @param {String} name Parameter that describes to new element (Must of times is 'field')
     * @param {String} expression Value of the new element (Must of times is the name of the field)
     * @private
     */
	Tokenizer.prototype.addField = function (name, expression) {
		var expr;

		if ($.inArray(expression, this.fields) === -1) {
			this.fields.push(expression);
		}

		expr = this.fields.toString().replace(/,/g,"|");
		expr = expr.replace(new RegExp("\\[","g"),"\\[");
		expr = expr.replace(new RegExp("\\]","g"),"\\]");
		this.tokens[name] = expr;
	};
	/**
     * Sets the value for the field selected
     * @param {String} name Corresponds to name of the field
     * @param {String||Number} value Value for the field
     * @private
     */
    Tokenizer.prototype.addTokenValue = function (name, value) {
		this.tokenFields[name] = parseFloat(value);
    };
	/**
     * Executes and find tokens based on the formula expression
     * @param {Object} data
     * @private
     */
	Tokenizer.prototype.tokenize = function (data) {
		var tokens;

		this.buildExpression(data);
        tokens = this.findTokens(data);
        
        return new TokenStream(tokens);
	};
	/**
     * Builds the formula expression separating by tokens 
     * @param {object} data Represent the data of the formula
     * @private
     */
	Tokenizer.prototype.buildExpression = function (data) {
		var tokenRegex = [],
		tokenName;

	    for (tokenName in this.tokens) {
	        this.tokenNames.push(tokenName);
	        tokenRegex.push('('+this.tokens[tokenName]+')');
	    }

	    this.regex = new RegExp(tokenRegex.join('|'), 'g');
	};
	/**
     * Find the tokens based of the data parameter and build an tokens array
     * @param {String} data
     * @private
     */
	Tokenizer.prototype.findTokens = function(data) {
        var tokens = [],
        match,
        group;

        while ((match = this.regex.exec(data)) !== null) {
            if (match === undefined) {
                continue;
            }

            for (group = 1; group < match.length; group+=1) {
                if (!match[group]) continue;
                
                tokens.push({
                    name: this.tokenNames[group - 1],
                    data: match[group],
                    value: null
                });
            }
        }

        return tokens;
    };
    PMDynaform.extendNamespace("PMDynaform.core.Tokenizer", Tokenizer);
	
	/**
	 * @class PMDynaform.core.Formula
	 * Class to handle all the formula property. The class support brackets that encapsulates
	  to numbers, mathematical operations and functions.
	 * @param {Object} tokens
	 */
	var Formula = function (data) {
	    this.data = data.toString();
	    this.tokenizer = new Tokenizer();
	    
	};
	/**
	 * Initializes tokens by default, like division, multiplication, constant and function.
	 * Using the {@link PMDynaform.core.Tokenizer Tokenizer} class for sets the new tokens
	 */
	Formula.prototype.initializeTokens = function () {
		this.tokenizer.addToken('whitespace', '\\s+');
	    this.tokenizer.addToken('l_paren', '\\(');
	    this.tokenizer.addToken('r_paren', '\\)');
	    this.tokenizer.addToken('float', '[0-9]+\\.[0-9]+');
	    this.tokenizer.addToken('int', '[0-9]+');
	    this.tokenizer.addToken('div', '\\/');
	    this.tokenizer.addToken('mul', '\\*');
	    this.tokenizer.addToken('add', '\\+');
	    this.tokenizer.addToken('sub', '\\-');
	    this.tokenizer.addToken('constant', 'pi|PI');
	    this.tokenizer.addToken('function', '[a-zA-Z_][a-zA-Z0-9_]*');

		return this;
	};
	/**
	 * Adds new token using the {@link PMDynaform.core.Tokenizer Tokenizer} class for
	 * set the data
	 * @param {String} name Name of the token
	 * @param {String} value Value for the new token
	 */
	Formula.prototype.addToken = function (name, value) {
		this.tokenizer.addToken(name, value);
		return this;
	};
	/**
	 * Adds new token using the {@link PMDynaform.core.Tokenizer Tokenizer} class for
	 * set the data
	 * @param {String} name Name of the token
	 * @param {String} value Value for the new token
	 */
	Formula.prototype.addField = function (name, value) {
		this.tokenizer.addField(name, value);
		return this;
	};
	/**
	 * Adds value for the field using {@link PMDynaform.core.Tokenizer Tokenizer} class for set
	 * the data
	 * @param {String} name Name of the token
	 * @param {String} value Value for the new token
	 */
	Formula.prototype.addTokenValue = function (name, value) {
		this.tokenizer.addTokenValue(name, value);
		
		return this;
	};
	/**
	 * The current method add the prefix 'Math' to data from token
	 * @param {String} token Represents the token
	 * @return {String} Return the Mathematical valid data
	 */
	Formula.prototype.consumeConstant = function(token) {
		return 'Math.' + token.data.toUpperCase();
	};
	/**
	 * Gets the valid value for the field passed as parameter
	 * @param {String} token Token that represent the data of the field
	 * @return {String} 
	 */
	Formula.prototype.consumeField = function(token) {
		return (this.tokenizer.tokenFields[token.data] === undefined) ? 0: this.tokenizer.tokenFields[token.data];
	};
	/**
	 * Adds new token using the {@link PMDynaform.core.Tokenizer Tokenizer} class
	 * @param {String} name Name of the token
	 * @param {String} value Value for the new token
	 */
	Formula.prototype.consumeFunction = function (ts, token) {
        var a = [token.data],
        t;

        while (t = ts.next()) {
            a.push(t.data);
            if (t.name === 'r_paren') {
                break;
            }
        }

        return 'Math.' + a.join('');
    };
    /**
	 * Adds new token using the {@link PMDynaform.core.Tokenizer Tokenizer} class
	 * @param {String} name Name of the token
	 * @param {String} value Value for the new token
	 */
    Formula.prototype.evaluate = function () {
        var ts,
        valueFixed,
        expr = [],
        e,
        t;

        this.initializeTokens();
        ts = this.tokenizer.tokenize(this.data);
        
        while (t = ts.next()) {
            switch (t.name) {
                case 'int':
                case 'float':
                case 'mul':
                case 'div':
                case 'sub':
                case 'add':

                    expr.push(t.data);
                    break;
                case 'field':
            		expr.push(this.consumeField(t));
                	break;	
                case 'constant':
                    expr.push(this.consumeConstant(t));
                    break;
                case 'l_paren':
                	expr.push("(");
                	break;
                case 'r_paren':
                	expr.push(")");
                	break;
                case 'function':
                    var n = ts.peek();
                    if (n && n.name === 'l_paren') {
                        expr.push(this.consumeFunction(ts, t));
                        continue;
                    }
                default:
                    break;
            }
        }
		var auxExpr = [];
		for (var i=0; i < expr.length ; i+=1){
			if (typeof expr[i] === "number") {
				auxExpr.push("("+expr[i]+")");	
			} else {
				auxExpr.push(expr[i]);
			}
		}
		expr = auxExpr;
        e = expr.join('');
        try {
        	valueFixed = (new Function('return ' + e))();
        } catch(e) {
        	valueFixed = 0;
        	var message = new PMDynaform.implements.Logger();
        	message.showMessage("formula");
        	throw new Error("Error in the formula property");
        }
        valueFixed = _.isNaN(valueFixed)? "" : valueFixed;
        return valueFixed;
    };


    PMDynaform.extendNamespace("PMDynaform.core.Formula", Formula);
}());


 (function(){
	var WebServiceManager = function(options) {
		/*
			options.keys
			options.endPoints
			options.urlBase
			options.token
		 */
		this.options = options || {};
		this.options.endPoints ={			
			dynaformDefinition : "light/project/{processID}/dynaform/{formID}",
			jsonDynaforms : "light/project/{processID}/dynaforms",			
			startCase :"light/process/{processID}/task/{taskID}/start-case",
			newTokens :"oauth/token",
			caseTypeList :"case/{caseID}/dynaform/{typeList}",
			loadDynaform :"case/{caseID}/dynaform/{formID}/data",
			getFormData :"case/{caseID}/dynaform/{formID}/data",
			getAllDataCase :"light/{caseID}/variables",
			submitFormCase :"light/{caseID}/variable?dyn_uid={formID}&del_index={delIndex}",
			routeCase :"light/cases/{caseID}/route-case",
			createVariable: "process-variable",
			imageInfo: "light/case/{caseID}/download64",
			fileDownload: "case/{caseID}/file/{fileID}",			
			variableList: "process-variable",			
			imageDownload: 'light/case/{caseID}/download64',			
			generateImageGeo:"/light/case/{caseID}/input-document/location",
			variableInfo: "process-variable/{var_uid}",
			uploadFile: "",
			refreshToken: "oauth/token",
			fileStreaming : "en/neoclassic/cases/casesStreamingFile?actionAjax=streaming&a={caseID}&d={fileId}",
			executeTrigger: "light/process/{processID}/task/{taskID}/case/{caseID}/step/{stepID}/execute-trigger/{triggerOption}",
            conditionalSteps: "light/process/{processID}/case/{caseID}/{delIndex}/step/{stepPosition}",
            executeQuery: "project/{processID}/process-variable/{var_name}/execute-query",
            executeQuerySuggest: "project/{processID}/process-variable/{var_name}/execute-query-suggest"
        };
		this.options.urlBase = "{server}/api/1.0/{workspace}/{endPointPath}";
		this.options.urlBaseStreaming = "{server}/sys{workspace}/{endPointPath}";

	};

	WebServiceManager.prototype.getFullEndPoint= function (keys, urlBase, endPoint) {
        var k;
		urlBase=urlBase.replace(/{endPointPath}/, endPoint);		
		for (k in keys) {
			if (keys.hasOwnProperty(k)) {
				urlBase = urlBase.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);				
			}
		}
		return urlBase;
    };

    WebServiceManager.prototype.setKey = function(name, value) {
		if(this.options.keys)
			this.options.keys[name] = value;		
		return this;
	};

    WebServiceManager.prototype.getKey = function(name) {
        var resp = false;
        if(this.options.keys)
            resp = this.options.keys[name];        
        return resp;
    };

	WebServiceManager.prototype.deleteKey = function(name, value) {
		if(this.options.keys)
			delete this.options.keys[name];
		return this;
	};

    WebServiceManager.prototype.getToken = function() {        
        return this.options.token;
    };
    
	WebServiceManager.prototype.startCase = function() {		
    	var that =this,
            resp,
    		url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.startCase),
    		method = "POST";

		$.ajax({
            url: url,
            type: method,
            async : false,            
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = {
            		"state":"success",
            		"caseID":data.caseId,
            		"caseTitle":data.caseNumber,
            		"caseNumber":data.caseNumber	            		
            	};
            },
            error: function (xhr, textStatus, errorThrown) {
                resp= {	            		
            		"state":"internetFail"
            	};
            } 
        });
        return resp;       			
	};

	WebServiceManager.prototype.loadAllDataCase = function() {
    	var that = this,        
            method, url, resp;
    		
		url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.getAllDataCase);	
		method = "GET";
		
		$.ajax({
            url: url,
            type: method,
            async : false,                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = {
            		"state":"success",
            		"data": data
            	};
            },
            error: function (xhr, textStatus, errorThrown) {
                resp = {
            		"state":"internetFail"
            	};
            } 
        });
        return resp;        
	};
     /**
      * This function that execute a endpoint VARIABLES of ProcessMaker
      * @param formID
      * @param data
      * @returns {*}
      */
     WebServiceManager.prototype.submitFormCase = function (formID, data) {
         var that = this,
             url, method, resp;
         this.setKey('formID', formID);
         url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.submitFormCase);
         method = "PUT";
         this.deleteKey('formID');

         data = (data && _.isObject(data)) ? data : {};
         $.ajax({
             url: url,
             type: method,
             async: false,
             data: JSON.stringify(data),
             contentType: "application/json",
             beforeSend: function (xhr) {
                 xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                 if (that.options.language !== null) {
                     xhr.setRequestHeader("Accept-Language", that.options.language);
                 }
             },
             success: function (data, textStatus) {
                 resp = {
                     "state": "success"
                 };
             },
             error: function (xhr, textStatus, errorThrown) {
                 if (xhr.status === 200) {
                     resp = {
                         "state": "success"
                     };
                 } else {
                     resp = {
                         "state": "internetFail"
                     };
                 }
             }
         });
         return resp;
     };

     WebServiceManager.prototype.execAjax = function (ajaxParams) {
         var resp;
         var that = this;
         function beforeSendCallback (xhr) {
             if (ajaxParams.isJSON){
                 xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                 if(that.options.language!=null){
                     xhr.setRequestHeader("Accept-Language", that.options.language);
                 }
             }
         }
         var params = {
             url: ajaxParams.url,
             type: ajaxParams.method,
             async: false,
             data: ajaxParams.data || {},
             beforeSend: function (xhr) {
                 beforeSendCallback(xhr);
             },
             success: function (data, textStatus) {
                 resp = {
                     "state":"success"
                 };
             },
             error: function (xhr, textStatus, errorThrown) {
                 if(xhr.status == 200){
                     resp = {
                         "state":"success"
                     };
                 }else{
                     resp = {
                         "state":"internetFail"
                     };
                 }
             }
         };

         $.ajax(params);
     };
	WebServiceManager.prototype.executeTrigger = function(stepID , triggerOption) {
    	var that = this,        
    	    method = "POST", url, resp;
		
		this.setKey('stepID', stepID);
		this.setKey('triggerOption', triggerOption);
		
		url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.executeTrigger);

		this.deleteKey('stepID');
		this.deleteKey('triggerOption');

		$.ajax({
            url: url,
            type: method,
            async : false,                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = {
            		"state":"success"
            	};
            },
            error: function (xhr, textStatus, errorThrown) {
                resp = {
            		"state":"internetFail"
            	};
            } 
        });
        return resp;    
	};

    WebServiceManager.prototype.executeQuery = function(data,varName) {
        var that = this,        
            method = "POST", url, resp=[];
        
        this.setKey('var_name', varName);
        
        url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.executeQuery); 
        
        this.deleteKey('var_name');
        
        $.ajax({
            url: url,
            type: method,
            data: JSON.stringify(data),
            async : false,                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = data;
            }
        });
        return resp;    
    };

    WebServiceManager.prototype.conditionalStep = function(stepID , stepPosition) {
        var that = this,        
            method = "GET", url, resp;
        
        this.setKey('stepID', stepID);
        this.setKey('stepPosition', stepPosition);
        
        url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.conditionalSteps); 
        
        this.deleteKey('stepID');
        this.deleteKey('stepPosition');

        $.ajax({
            url: url,
            type: method,
            async : false,                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = {
                        "state":"success",
                        "data":data
                };
            },
            error: function (xhr, textStatus, errorThrown) {
                resp = JSON.parse(xhr.responseText);
            } 
        });
        return resp;  
    };

	WebServiceManager.prototype.getJsonForm = function(formID) {
    	var that = this,
            method, url, sendData=[], resp;
    		
		sendData.push(formID);        
        url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.jsonDynaforms);	

		method = "POST";
		$.ajax({
            url: url,
            type: method,
            async : false,            
            data: JSON.stringify({
	            formId:sendData
	        }),
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                var respData = null;
            	if(data.length != 0){
            		respData = data[0].formContent;
            	}	            	
            	resp = {
            		"data":respData,
            		"state":"success"
            	};  
            },
            error: function (xhr, textStatus, errorThrown) {
                resp = {
            		"state":"internetFail"
            	};
            } 
        });
        return resp;        
	};

	WebServiceManager.prototype.getFormDefinition = function() {
    	var that = this,        
            method, url, resp;
		url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.dynaformDefinition);	
		method =  "GET",
		$.ajax({
            url: url,
            type: method,
            async : false,                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = {
            		"data":data.data.formContent,
            		"state":"success"
            	}; 
            },
            error: function (xhr, textStatus, errorThrown) {               
                resp = {
            		"state":"internetFail"
            	};
            } 
        });
        return resp;        
	};

    WebServiceManager.prototype.imageInfo = function(id,width) {
        var that = this,
            method, url, resp;
        url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.imageInfo); 
        method = "POST",
        $.ajax({
            url: url,
            type: method,
            async : false,
            data: JSON.stringify([{
                fileId: id,
                width : width,
                version :1
            }]),                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = data; 
            } 
        });
        if (resp){
            return {
                id: resp[0].fileId,
                base64: resp[0].fileContent
            };
        }else{
            return {
                id: "",
                base64: ""
            };
        }        
    };

    WebServiceManager.prototype.imagesInfo = function(data) {
        var that = this,
            method, url, resp;
        url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.imageInfo); 
        method = "POST",
        $.ajax({
            url: url,
            type: method,
            async : false,
            data: JSON.stringify(data),                        
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (data, textStatus) {
                resp = data; 
            } 
        });
        return resp;                
    };  

    WebServiceManager.prototype.restClient = function() {
        defaults = {
            url: "/rest/v10",
            method: "GET",
            contentType: "application/json",
            data: '',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + keys.access_token);
                if(that.options.language!=null){
                    xhr.setRequestHeader("Accept-Language", that.options.language);                        
                }
            },
            success: function (){},
            error: function () {}
        };
        _.extend(defaults,parems);

        defaults.type = _methodsMap[defaults.method];
        defaults.data = JSON.stringify(defaults.data);
        $.ajax(defaults);
    };

    WebServiceManager.prototype.getFullURLStreaming = function (id) {
        var k, 
        keys  = this.options.keys,
        urlFormat = this.options.urlBaseStreaming;
        this.setKey('fileId', id);
        urlFormat=urlFormat.replace(/{endPointPath}/, this.options.endPoints.fileStreaming);
        for (k in keys) {
            if (keys.hasOwnProperty(k)) {                           
                    urlFormat = urlFormat.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);               
            }
        }
        this.deleteKey("fileId");
        return urlFormat;
     };
     /**
      * consumes suggest rest service
      * @param data
      * @param varName
      * @returns {Array}
      */
     WebServiceManager.prototype.executeQuerySuggest = function (data, varName, callback) {
         var that = this,
             method = "POST", url, resp = [],
             appID = this.getKey("caseID");

         this.setKey('var_name', varName);
         if (appID) {
             data["app_uid"] = appID;
         }
         url = that.getFullEndPoint(that.options.keys, that.options.urlBase, that.options.endPoints.executeQuerySuggest);

         this.deleteKey('var_name');

         $.ajax({
             url: url,
             type: method,
             data: JSON.stringify(data),
             async: true,
             contentType: "application/json",
             beforeSend: function (xhr) {
                 xhr.setRequestHeader("Authorization", "Bearer " + that.options.token.accessToken);
                 if (that.options.language != null) {
                     xhr.setRequestHeader("Accept-Language", that.options.language);
                 }
             },
             success: function (data, textStatus) {
                 resp = data;
                 callback(data);
             }
         });
         return resp;
     };

	PMDynaform.extendNamespace("PMDynaform.implements.WebServiceManager", WebServiceManager);
}());

(function(){
	var Logger = function(option) {		
		this.template = _.template($("#tpl-messageWarning").html()),
		this.messages = {
			"formula" : "There are references to missing objects in the form. The form configuration is not completed and it is not going to be displayed (rendered). Please contact the administrator.",
			"default" : "Please contact the administrator."
		},
		this.$el;			
		Logger.prototype.init.call(this, option);
	};
	
	Logger.prototype.init = function(options) {
		
	};

	Logger.prototype.showMessage = function(optionMessage) {				
		var html,
			json = {
				message : this.messages[optionMessage] || this.messages["default"]
			};
		html = this.template(json);
		$(".pmDynaformLoading").css("background","no-repeat #f9f9f9");	
		$(".pmDynaformLoading").append(html);			
	};
	
	PMDynaform.extendNamespace("PMDynaform.implements.Logger", Logger);
}());
(function(){
    var Proxy = function (options) {
        //this.endpoint = null;
        this.method = null;
        this.data = null;
        this.successCallback = null;
        this.failureCallback = null;
        this.completeCallback = null;
        this.restProxy = null;
        this.keys = null;
        this.url = null;
        //this.server = null;
        this.multipart = null;

        this.dataType = 'json';
        this.authorizationType = "none";
        this.authorizationOAuth = false;
        Proxy.prototype.init.call(this, options);
    };

    Proxy.prototype.type = "proxy";

    Proxy.prototype.init = function (options) {
        var defaults = {
            url: "",
            //endpoint: '',
            method: 'GET',
            //server: '',
            data: {},
            keys: {
                accessToken: "",
                clientId: "x-pm-local-client",
                clientSecret: "",
                expiresIn: "",
                refreshToken: "",
                scope: "",
                tokenType: "bearer"
            },
            multipart: false,
            successCallback: function (resp, data) {},
            failureCallback: function (resp, data) {},
            completeCallback: function (resp, data) {}
        }

        jQuery.extend(true, defaults, options);

        this.setUrl(defaults.url)
            .setMethod(defaults.method)
            //.setServer(defaults.server)
            .setData(defaults.data)
            .setKeys(defaults.keys)
            .setMultipart(defaults.multipart)
            .setSuccessCallback(defaults.successCallback)
            .setFailureCallback(defaults.failureCallback)
            .setCompleteCallback(defaults.completeCallback)
            .setRestProxy();

        this.executeRestProxy();
    }
    Proxy.prototype.setUrl = function (url) {
        this.url = url;
        return this;
    };
    Proxy.prototype.setEndpoint = function (endpoint) {
        var leftBracket;
        if (typeof endpoint === "string") {
            leftBracket = (endpoint[0]==="/")? endpoint.substring(1) : endpoint;
            this.endpoint = (endpoint[endpoint.length-1]==="/")? endpoint.substring(0, endpoint.length-1) : endpoint;
        }
        
        return this;
    };
    Proxy.prototype.setMethod = function (method) {
        this.method = method;
        return this;
    };
    Proxy.prototype.setServer = function (server) {
        var leftBracket;
        if (typeof server === "string") {
            leftBracket = (server[0]==="/")? server.substring(1) : server;
            this.server = (server[server.length-1]==="/")? server.substring(0, server.length-1) : server;
        }
        return this;
    };
    Proxy.prototype.setData = function (data) {
        this.data = data;
        return this;
    };
    Proxy.prototype.setKeys = function (keys) {
        this.keys = keys;
        this.keys.token = {access_token: keys.token};
        return this;
    };
    Proxy.prototype.setMultipart = function (multipart ) {
        this.multipart = multipart ;
        return this;
    };
    Proxy.prototype.setSuccessCallback = function (fn) {
        if (typeof fn === 'function') {
            this.successCallback = fn;
        }
        return this;
    };
    Proxy.prototype.setFailureCallback = function (fn) {
        if (typeof fn === 'function') {
            this.failureCallback = fn;
        }
        return this;
    };
    Proxy.prototype.setCompleteCallback = function (fn) {
        if (typeof fn === 'function') {
            this.completeCallback = fn;
        }
        return this;
    };
    Proxy.prototype.getFullProxyPath = function () {
        return  this.server + "/" + 
                this.keys.apiName + "/" +
                this.keys.apiVersion + "/" + 
                this.keys.workspace + "/" + 
                "project" + "/" +
                this.keys.processId + "/" + 
                this.endpoint;
    };

    Proxy.prototype.setRestProxy = function () {
        var that = this;
        this.restProxy = new PMDynaform.proxy.RestProxy({
            url: this.url,
            method: that.method,
            data: that.data,
            authorizationOAuth: true,
            dataType: this.dataType,
            success: that.successCallback,
            failure: that.failureCallback,
            complete: that.completeCallback
        });
        this.restProxy.setAuthorizationType('oauth2', {
            access_token: this.keys.accessToken
        });
        return this;
    };

    Proxy.prototype.executeRestProxy = function () {
        var method = {
            "POST": "post",
            "UPDATE": "update",
            "GET": "get",
            "DELETE": "remove"
        };

        this.restProxy[method[this.method]]();  
        return this;
    };
    PMDynaform.extendNamespace("PMDynaform.core.Proxy", Proxy);

}());




(function(){
	var TransformJSON = function (settings) {
		this.parentMode = null;
		this.field = null;
		this.json = null;
		this.jsonBuilt = null;
		TransformJSON.prototype.init.call(this, settings);
	};

	TransformJSON.prototype.init = function (settings) {
		var defaults = {
			parentMode: "edit",
			field: {},
			json: {
				text: TransformJSON.prototype.text,
				textarea: TransformJSON.prototype.textArea,
				checkgroup: TransformJSON.prototype.checkgroup,
				checkbox: TransformJSON.prototype.checkbox,
				radio: TransformJSON.prototype.radio,
				dropdown: TransformJSON.prototype.dropdown, 
				button: TransformJSON.prototype.button,
				submit: TransformJSON.prototype.submit,
				datetime: TransformJSON.prototype.datetime,
				suggest: TransformJSON.prototype.suggest,
				link: TransformJSON.prototype.link, 
				file: TransformJSON.prototype.file,
				grid: TransformJSON.prototype.grid
			}		
		};

		jQuery.extend(true, defaults, settings);

		this.jsonBuilt = defaults.field;
		this.setParentMode(defaults.parentMode)
			.setField(defaults.field)
			.setJSONFactory(defaults.json)
			.buildJSON();

		return this;
	};
	TransformJSON.prototype.setParentMode = function (mode) {
		this.parentMode = mode;

		return this;
	};
	TransformJSON.prototype.text = function (field){
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: [
            	field.defaultValue || field.value
            ],
            data : field.data
		};
	};
	TransformJSON.prototype.textArea = function (field) {
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: [
            	field.defaultValue || field.value
            ],
            data : field.data
		};
	};
	TransformJSON.prototype.checkgroup = function (field) {
		var validOpt = [],
		i;

		for (i=0; i<field.options.length; i+=1) {
			if (field.options[i].selected) {
				if (field.options[i].selected === true) {
					validOpt.push(field.options[i].label);
				}
			}
			
		}
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: validOpt,
            data : field.data
		};
	};
	TransformJSON.prototype.checkbox = function (field) {
		var validOpt = [],
		i;
		for (i=0; i<field.options.length; i+=1) {
			if (field.options[i].selected) {
				if (field.options[i].selected === true) {
					validOpt.push(field.options[i].label);
				}
			}
			
		}
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: validOpt,
            data : field.data
		};
	};
	TransformJSON.prototype.radio = function (field) {
		var validOpt = [],
		i;

		for (i=0; i<field.options.length; i+=1) {
			if (field.defaultValue) {
				if (field.options[i].value.toString() === field.defaultValue.toString()) {
					validOpt.push(field.options[i].label);
				}
			}
			
		}
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: validOpt,
            data : field.data
		};
	};
	TransformJSON.prototype.dropdown = function (field) {
		var validOpt = [],
		i;

		for (i=0; i<field.options.length; i+=1) {
			if (field.defaultValue) {
				if (field.options[i].value.toString() === field.defaultValue.toString()) {
					validOpt.push(field.options[i].label);
				}
			}
		}
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: validOpt,
            data : field.data
		};
	};
	TransformJSON.prototype.button = function (field) {
		var fieldExtended = field;

		return fieldExtended;
	};
	TransformJSON.prototype.submit = function (field) {
		var fieldExtended = field;

		return fieldExtended;
	};
	TransformJSON.prototype.datetime = function (field) {
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: [
            	field.defaultValue || field.value
            ],
            data : field.data
		};
	};
	TransformJSON.prototype.suggest = function (field) {
		var validOpt = [],
		i, aux;

		for (i=0; i<field.options.length; i+=1) {
			if (field.defaultValue) {
				if (field.options[i].value.toString() === field.defaultValue.toString()) {
					validOpt.push(field.options[i].label);
				}
			}
		}
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            fullOptions: validOpt,
            data : field.data
		};
	};
	TransformJSON.prototype.link = function (field) {
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            options: [
            	field.value
            ]
		};
	};
	TransformJSON.prototype.file = function (field) {
		var fieldExtended = field;

		return fieldExtended;
	};
	TransformJSON.prototype.grid = function (field) {
		var fieldExtended = field;

		return fieldExtended;
	};
	TransformJSON.prototype.setField = function (field) {
		this.field = field;

		return this;
	};
	TransformJSON.prototype.setJSONFactory = function (factory) {
		this.json = factory;
		return this;
	};
	TransformJSON.prototype.discardViewField = function (type) {
        var disabled = [
            "button",
            "submit",
            "image",
            "label",
            "title",
            "subtitle"
        ];

        return ($.inArray(type, disabled) < 0) ? true : false;
    };
	TransformJSON.prototype.reviewField = function (field) {
		var jsonBuilt = field, total,
		sigleControl = ["text","suggest","textarea","datetime"], data, i;

		if (this.json[field.type] && this.discardViewField(field.type) ) {
			switch (field.mode) {
				case "disabled":
					jsonBuilt = field;
					jsonBuilt.disabled = true;
				break;
				case "parent":
					field.mode = this.parentMode;
					jsonBuilt = this.reviewField(field);
					//jsonBuilt = this.json[field.type](field);
				break;
				case "view":
					jsonBuilt = this.json[field.type](field);
				break;
				default :
					jsonBuilt = field;
			}
		}
		//if (jsonBuilt.fullOptions)
		jsonBuilt.dataType = field.dataType || "";
		jsonBuilt.originalType = field.originalType || field.type;
		jsonBuilt.var_name = field.var_name || "";
		jsonBuilt.var_uid = field.var_uid || "";
		jsonBuilt.options || field.var_accepted_values;
		if (field.data){
           	jsonBuilt.fullOptions = [];
           	if (sigleControl.indexOf(jsonBuilt.originalType) !== -1){
           		if (jsonBuilt.originalType === "suggest"){
					jsonBuilt.fullOptions = [ field.data["label"] || field.defaultValue];           			
           		}else{
					jsonBuilt.fullOptions = [ field.data["value"] || field.defaultValue];
           		}
			} else {
				if (jsonBuilt.originalType === "checkgroup" ){
	                data = [];
					if ($.isArray(field["optionsSql"])){
						total = field["options"].concat(field["optionsSql"]);					
					}else{
						total = field["options"];
					}
	                for ( i = 0 ; i < total.length ; i+=1 ) {
	                    if (field.data["value"].indexOf(total[i]["value"]) !==- 1){
	                    	data.push(total[i]["label"]);
	                    }
	                }
					jsonBuilt.fullOptions = data || [field.defaultValue];
				}else{
					jsonBuilt.fullOptions = [ field.data["label"] || field.defaultValue];
				}
			}
		}
		return jsonBuilt;
	};
	TransformJSON.prototype.buildJSON = function () {
		this.jsonBuilt = this.reviewField(this.field);
		
		return this;
	};
	
	TransformJSON.prototype.getJSON = function () {
		
		return this.jsonBuilt;
	};
	PMDynaform.extendNamespace("PMDynaform.core.TransformJSON", TransformJSON);

}());
(function(){
	
    var FileStream,
    FileReader = window.FileReader,
    FileReaderSyncSupport = false,
    workerScript = "self.addEventListener('message', function(e) { var data=e.data; try { var reader = new FileReaderSync; postMessage({ result: reader[data.readAs](data.file), extra: data.extra, file: data.file})} catch(e){ postMessage({ result:'error', extra:data.extra, file:data.file}); } }, false);",
    syncDetectionScript = "self.addEventListener('message', function(e) { postMessage(!!FileReaderSync); }, false);",
    fileReaderEvents = ['loadstart', 
                        'progress', 
                        'load', 
                        'abort', 
                        'error', 
                        'loadend'],

    FileStream = {
        enabled: false,
        setupInput: setupInput,
        setupDrop: setupDrop,
        setupClipboard: setupClipboard,
        sync: false,
        output: [],
        opts: {
            dragClass: "drag",
            accept: false,
            readAsDefault: 'BinaryString',
            readAsMap: {
                'image/*': 'DataURL',
                'text/*' : 'Text'
            },
            on: {
                loadstart: function() {},
                progress: function() {},
                load: function() {},
                abort: function() {},
                error: function() {},
                loadend: function() {},
                skip: function() {},
                groupstart: function() {},
                groupend: function() {},
                beforestart: function() {}
            }
        }
    };

    // Not all browsers support the FileReader interface.  Return with the enabled bit = false.
    if (!FileReader) {
        return;
    }

    // WorkerHelper is a little wrapper for generating web workers from strings
    var WorkerHelper = (function() {

        var URL = window.URL || window.webkitURL;
        var BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder;

        // May need to get just the URL in case it is needed for things beyond just creating a worker.
        function getURL (script) {
            if (window.Worker && BlobBuilder && URL) {
                var bb = new BlobBuilder();
                bb.append(script);
                return URL.createObjectURL(bb.getBlob());
            }

            return null;
        }

        // If there is no need to revoke a URL later, or do anything fancy then just return the worker.
        function getWorker (script, onmessage) {
            var worker,
            url = getURL(script);

            if (url) {
                worker = new Worker(url);
                worker.onmessage = onmessage;
                return worker;
            }

            return null;
        }

        return {
            getURL: getURL,
            getWorker: getWorker
        };

    })();

    // setupClipboard: bind to clipboard events (intended for document.body)
    function setupClipboard(element, opts) {
        var instanceOptions = {};
        if (!FileStream.enabled) {
            return;
        }

        $.extend(true, instanceOptions, FileStream.opts);
        $.extend(true, instanceOptions, opts);
        //instanceOptions = extend(extend({}, FileStream.opts), opts);

        element.addEventListener("paste", onpaste, false);

        function onpaste(e) {
            var files = [];
            var clipboardData = e.clipboardData || {};
            var items = clipboardData.items || [];

            for (var i = 0; i < items.length; i++) {
                var file = items[i].getAsFile();

                if (file) {

                    // Create a fake file name for images from clipboard, since this data doesn't get sent
                    var matches = new RegExp("/\(.*\)").exec(file.type);
                    if (!file.name && matches) {
                        var extension = matches[1];
                        file.name = "clipboard" + i + "." + extension;
                    }

                    files.push(file);
                }
            }

            if (files.length) {
                processFileList(e, files, instanceOptions);
                e.preventDefault();
                e.stopPropagation();
            }
        }
    }

    // setupInput: bind the 'change' event to an input[type=file]
    function setupInput(input, opts) {
        var instanceOptions = {};

        if (!FileStream.enabled) {
            return;
        }
        //var instanceOptions = extend(extend({}, FileStream.opts), opts);
        $.extend(true, instanceOptions, FileStream.opts);
        $.extend(true, instanceOptions, opts);

        input.addEventListener("change", inputChange, false);
        input.addEventListener("drop", inputDrop, false);

        function inputChange(e) {
            processFileList(e, input.files, instanceOptions);
        }

        function inputDrop(e) {
            e.stopPropagation();
            e.preventDefault();
            processFileList(e, e.dataTransfer.files, instanceOptions);
        }
    }

    // setupDrop: bind the 'drop' event for a DOM element
    function setupDrop(dropbox, opts) {
        var dragClass, 
        initializedOnBody,
        instanceOptions = {};

        if (!FileStream.enabled) {
            return;
        }
        //var instanceOptions = extend(extend({}, FileStream.opts), opts);
        $.extend(true, instanceOptions, FileStream.opts);
        $.extend(true, instanceOptions, opts);

        if (!instanceOptions.dnd) {
            return;
        }
        dragClass = instanceOptions.dragClass;
        initializedOnBody = false;

        // Bind drag events to the dropbox to add the class while dragging, and accept the drop data transfer.
        dropbox.addEventListener("dragenter", onlyWithFiles(dragenter), false);
        dropbox.addEventListener("dragleave", onlyWithFiles(dragleave), false);
        dropbox.addEventListener("dragover", onlyWithFiles(dragover), false);
        dropbox.addEventListener("drop", onlyWithFiles(drop), false);

        // Bind to body to prevent the dropbox events from firing when it was initialized on the page.
        document.body.addEventListener("dragstart", bodydragstart, true);
        document.body.addEventListener("dragend", bodydragend, true);
        document.body.addEventListener("drop", bodydrop, false);

        function bodydragend(e) {
            initializedOnBody = false;
        }

        function bodydragstart(e) {
            initializedOnBody = true;
        }

        function bodydrop(e) {
            if (e.dataTransfer.files && e.dataTransfer.files.length ){
                e.stopPropagation();
                e.preventDefault();
            }
        }

        function onlyWithFiles(fn) {
            return function() {
                if (!initializedOnBody) {
                    fn.apply(this, arguments);
                }
            };
        }

        function drop(e) {
            e.stopPropagation();
            e.preventDefault();
            if (dragClass) {
                removeClass(dropbox, dragClass);
            }
            processFileList(e, e.dataTransfer.files, instanceOptions);
        }

        function dragenter(e) {
            e.stopPropagation();
            e.preventDefault();
            if (dragClass) {
                addClass(dropbox, dragClass);
            }
        }

        function dragleave(e) {
            if (dragClass) {
                removeClass(dropbox, dragClass);
            }
        }

        function dragover(e) {
            e.stopPropagation();
            e.preventDefault();
            if (dragClass) {
                addClass(dropbox, dragClass);
            }
        }
    }

    // setupCustomFileProperties: modify the file object with extra properties
    function setupCustomFileProperties(files, groupID) {
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            file.extra = {
                nameNoExtension: file.name.substring(0, file.name.lastIndexOf('.')),
                extension: file.name.substring(file.name.lastIndexOf('.') + 1),
                fileID: i,
                uniqueID: getUniqueID(),
                groupID: groupID,
                prettySize: prettySize(file.size)
            };
        }
    }

    // getReadAsMethod: return method name for 'readAs*' - http://www.w3.org/TR/FileAPI/#reading-a-file
    function getReadAsMethod(type, readAsMap, readAsDefault) {
        for (var r in readAsMap) {
            if (type.match(new RegExp(r))) {
                return 'readAs' + readAsMap[r];
            }
        }
        return 'readAs' + readAsDefault;
    }

    // processFileList: read the files with FileReader, send off custom events.
    function processFileList(e, files, opts) {

        var filesLeft = files.length;
        var group = {
            groupID: getGroupID(),
            files: files,
            started: new Date()
        };

        function groupEnd() {
            group.ended = new Date();
            opts.on.groupend(group);
        }

        function groupFileDone() {
            if (--filesLeft === 0) {
                groupEnd();
            }
        }

        FileStream.output.push(group);
        setupCustomFileProperties(files, group.groupID);

        opts.on.groupstart(group);

        // No files in group - end immediately
        if (!files.length) {
            groupEnd();
            return;
        }

        var sync = FileStream.sync && FileReaderSyncSupport;
        var syncWorker;

        // Only initialize the synchronous worker if the option is enabled - to prevent the overhead
        if (sync) {
            syncWorker = WorkerHelper.getWorker(workerScript, function(e) {
                var file = e.data.file;
                var result = e.data.result;

                // Workers seem to lose the custom property on the file object.
                if (!file.extra) {
                    file.extra = e.data.extra;
                }

                file.extra.ended = new Date();

                // Call error or load event depending on success of the read from the worker.
                opts.on[result === "error" ? "error" : "load"]({ target: { result: result } }, file);
                groupFileDone();

            });
        }

        Array.prototype.forEach.call(files, function(file) {

            file.extra.started = new Date();

            if (opts.accept && !file.type.match(new RegExp(opts.accept))) {
                opts.on.skip(file);
                groupFileDone();
                return;
            }

            if (opts.on.beforestart(file) === false) {
                opts.on.skip(file);
                groupFileDone();
                return;
            }

            var readAs = getReadAsMethod(file.type, opts.readAsMap, opts.readAsDefault);

            if (sync && syncWorker) {
                syncWorker.postMessage({
                    file: file,
                    extra: file.extra,
                    readAs: readAs
                });
            }
            else {

                var reader = new FileReader();
                reader.originalEvent = e;

                fileReaderEvents.forEach(function(eventName) {
                    reader['on' + eventName] = function(e) {
                        if (eventName == 'load' || eventName == 'error') {
                            file.extra.ended = new Date();
                        }
                        opts.on[eventName](e, file);
                        if (eventName == 'loadend') {
                            groupFileDone();
                        }
                    };
                });

                reader[readAs](file);
            }
        });
    }

    // checkFileReaderSyncSupport: Create a temporary worker and see if FileReaderSync exists
    function checkFileReaderSyncSupport() {
        var worker = WorkerHelper.getWorker(syncDetectionScript, function(e) {
            FileReaderSyncSupport = e.data;
        });

        if (worker) {
            worker.postMessage({});
        }
    }


    // hasClass: does an element have the css class?
    function hasClass(el, name) {
        return new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)").test(el.className);
    }

    // addClass: add the css class for the element.
    function addClass(el, name) {
        if (!hasClass(el, name)) {
          el.className = el.className ? [el.className, name].join(' ') : name;
        }
    }

    // removeClass: remove the css class from the element.
    function removeClass(el, name) {
        if (hasClass(el, name)) {
          var c = el.className;
          el.className = c.replace(new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)", "g"), " ").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        }
    }

    // prettySize: convert bytes to a more readable string.
    function prettySize(bytes) {
        var s = ['bytes', 'kb', 'MB', 'GB', 'TB', 'PB'];
        var e = Math.floor(Math.log(bytes)/Math.log(1024));
        return (bytes/Math.pow(1024, Math.floor(e))).toFixed(2)+" "+s[e];
    }

    // getGroupID: generate a unique int ID for groups.
    var getGroupID = (function(id) {
        return function() {
            return id++;
        };
    })(0);
    
    // getUniqueID: generate a unique int ID for files
    var getUniqueID = (function(id) {
        return function() {
            return id++;
        };
    })(0);

    // The interface is supported, bind the FileStream callbacks
    FileStream.enabled = true;


	PMDynaform.extendNamespace("PMDynaform.core.FileStream", FileStream);

}());
(function(){
    /**
     * FullScreen class
     */
    var FullScreen = function(options) {
        this.element = null;
        this.onReadyScreen = null;
        this.onCancelScreen = null;
        this.isInFullScreen = null;
        this.supported = null;
        FullScreen.prototype.init.call(this, options);
    };
    /**
     * [init description]
     * @param  {Object} options Config options
     */
    FullScreen.prototype.init = function(options) {
        var defaults = {
            element: document.documentElement,
            onReadyScreen: function(){},
            onCancelScreen: function(){}
        };
        jQuery.extend(true, defaults, options);
        this.element = defaults.element;
        this.onReadyScreen = defaults.onReadyScreen;
        this.onCancelScreen = defaults.onCancelScreen;
        this.checkFullScreen();
    };
    FullScreen.prototype.checkFullScreen = function () {
        var el = this.element,
        request = el.requestFullScreen || 
                        el.webkitRequestFullScreen || 
                        el.mozRequestFullScreen || 
                        el.msRequestFullScreen;

        this.supported = request ? true: null;

        return this;
    };
    FullScreen.prototype.cancel = function () {
        var requestMethod,fnCancelScreen, wscript, el;
        if (parent.document.documentElement === document.documentElement) {
            el = document;
        } else {
            el = parent.document;
        }
        requestMethod = el.cancelFullScreen || 
                        el.webkitCancelFullScreen || 
                        el.mozCancelFullScreen || 
                        el.exitFullscreen;
        if (requestMethod) {
            requestMethod.call(el);
            try {
                fnCancelScreen = this.onCancelScreen;
                fnCancelScreen(el);
            } catch (e) {
                throw new Error(e);
            }
        } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
            wscript = new ActiveXObject("WScript.Shell");
            if (wscript !== null) {
                wscript.SendKeys("{F11}");
            }
        }
    };

    FullScreen.prototype.applyZoom = function () {
        var requestMethod, wscript, fnReadyScreen, el = this.element;
        requestMethod = el.requestFullScreen || 
                        el.webkitRequestFullScreen || 
                        el.mozRequestFullScreen || 
                        el.msRequestFullScreen;

        if (requestMethod) {
            requestMethod.call(el); 
            try {
                fnReadyScreen = this.onReadyScreen;
                fnReadyScreen(el);
            } catch (e) {
                throw new Error(e);
            }
        } else if (typeof window.ActiveXObject !== "undefined") {
            wscript = new ActiveXObject("WScript.Shell");
            if (wscript !== null) {
                wscript.SendKeys("{F11}");
            }
        }
        return false
    };
    FullScreen.prototype.toggle = function () {
        var el;
        if (parent.document.documentElement === document.documentElement) {
            el = document;
        } else {
            el = parent.document;
        }
        this.isInFullScreen = (el.fullScreenElement && el.fullScreenElement !== null) ||  (el.mozFullScreen || el.webkitIsFullScreen);
        if (this.isInFullScreen) {
            this.cancel();
        } else {
            this.applyZoom();
        }
        return false;
    };

    PMDynaform.extendNamespace("PMDynaform.core.FullScreen", FullScreen);
}());
(function() {
	var Script = function (options) {
		this.name = null;
		this.type = null;
		this.html = null;
		this.script = "";
		this.renderTo = document.body;

		Script.prototype.init.call(this, options);
	};
	Script.prototype.init = function (options) {
		var defaults = {
			type: "text/javascript",
			script: ""
		};

		$.extend(true, defaults, options);
		this.setType(defaults.type)
			.setScript(defaults.script);
	};
	Script.prototype.setType = function (type) {
		this.type = type
		return this;
	};
	Script.prototype.setScript = function (script) {
		this.script = script;
		return this;
	};
	Script.prototype.createHTML = function () {
		var html = document.createElement("script");		

		html.type = this.type;
		html.text = this.script
		this.html = html;

		return html;
	};
	Script.prototype.getHTML = function () {
		if (!this.html) {
			this.createHTML();
		}

		return this.html;
	};
	Script.prototype.render = function () {
		var html = this.getHTML();
		//(new Function(this.script))();
		$(this.renderTo).append(html);

		return this;
	};
    PMDynaform.extendNamespace("PMDynaform.core.Script", Script);
}());

(function () {
    /**
     * @class PMUI.util.ArrayList
     * Construct a List similar to Java's ArrayList that encapsulates methods for
     * making a list that supports operations like get, insert and others.
     *
     *      some examples:
     *      var item,
     *          arrayList = new ArrayList();
     *      arrayList.getSize()                 // 0
     *      arrayList.insert({                  // insert an object
     *          id: 100,
     *          width: 100,
     *          height: 100
     *      });
     *      arrayList.getSize();                // 1
     *      arrayList.asArray();                // [{id : 100, ...}]
     *      item = arrayList.find('id', 100);   // finds the first element with an id that equals 100
     *      arrayList.remove(item);             // remove item from the arrayList
     *      arrayList.getSize();                // 0
     *      arrayList.isEmpty();                // true because the arrayList has no elements
     *
     * @constructor Returns an instance of the class ArrayList
     */
    var ArrayList = function () {
        /**
         * The elements of the arrayList
         * @property {Array}
         * @private
         */
        var elements = [],
            /**
             * The size of the array
             * @property {number} [size=0]
             * @private
             */
            size = 0,
            index,
            i;
        return {

            /**
             * The ID of this ArrayList is generated using the function Math.random
             * @property {number} id
             */
            id: Math.random(),
            /**
             * Gets an element in the specified index or undefined if the index
             * is not present in the array
             * @param {number} index
             * @returns {Object / undefined}
             */
            get : function (index) {
                return elements[index];
            },
            /**
             * Inserts an element at the end of the list
             * @param {Object} item
             * @chainable
             */
            insert : function (item) {
                elements[size] = item;
                size += 1;
                return this;
            },
            /**
             * Inserts an element in a specific position
             * @param {Object} item
             * @chainable
             */
            insertAt: function(item, index) {
                elements.splice(index, 0, item);
                size = elements.length;
                return this;
            },
            /**
             * Removes an item from the list
             * @param {Object} item
             * @return {boolean}
             */
            remove : function (item) {
                index = this.indexOf(item);
                if (index === -1) {
                    return false;
                }
                //swap(elements[index], elements[size-1]);
                size -= 1;
                elements.splice(index, 1);
                return true;
            },
            /**
             * Gets the length of the list
             * @return {number}
             */
            getSize : function () {
                return size;
            },
            /**
             * Returns true if the list is empty
             * @returns {boolean}
             */
            isEmpty : function () {
                return size === 0;
            },
            /**
             * Returns the first occurrence of an element, if the element is not
             * contained in the list then returns -1
             * @param {Object} item
             * @return {number}
             */
            indexOf : function (item) {
                for (i = 0; i < size; i += 1) {
                    if (item === elements[i]) {
                        return i;
                    }
                }
                return -1;
            },
            /**
             * Returns the the first object of the list that has the
             * specified attribute with the specified value
             * if the object is not found it returns undefined
             * @param {string} attribute
             * @param {string} value
             * @return {Object / undefined}
             */
            find : function (target) {
                var sortedValues = elements.sort();
                // summary:
				//    Performs a binary search on an array of sorted 
				//    values for a specified target. 
				// sortedValues: Array<String|Number>
				//    Array of values to search within.
				// target: String|Number
				//    Item to search for, within the sortedValues array.
				// returns:
				//    Number or null. The location of the target within
				//    the sortedValues array, if found. Otherwise returns 
				//    null.

				// define the recursive function.
				function search( low, high ) {
					// If the low index is greater than the high index, 
					//  return null for not-found. 
					if ( low > high ) {
					  return undefined;
					}

					// If the value at the low index is our target, return 
					//  the low index.
					if ( sortedValues[low] === target ){
					  return low;
					}

					// If the value at the high index is our target, return
					//  the high index.
					if ( sortedValues[high] === target ){
					  return high;
					}

					// Find the middle index and median element.
					var middle = Math.floor( ( low + high ) / 2 );
					var middleElement = sortedValues[middle];

					// Recursive calls, depending on whether or not the 
					//  middleElement is less-than or greater-than the 
					//  target.
					// Note: We can use high-1 and low+1 because we've 
					//  already checked for equality at the high and low 
					//  indexes above.
					if ( middleElement < target ) {
					  return search(middle, high-1);
					} else if ( middleElement > target ) {
					  return search(low+1, middle);
					}

					// If middleElement === target, we can return that value.
					return middle;
				}

				// Start our search between the first and last elements of 
				//  the array.
				return search(0, sortedValues.length-1);
            },
            /**
             * Returns true if the list contains the item and false otherwise
             * @param {Object} item
             * @return {boolean}
             */
            contains : function (item) {
                if (this.indexOf(item) !== -1) {
                    return true;
                }
                return false;
            },
            /**
             * Sorts the list using compFunction if possible, if no compFunction
             * is passed as an parameter then a default sorting method will be used. This default method will sort in 
             * ascending order.
             * @param {Function} [compFunction] The criteria function used to find out the position for the elements in
             * the array list. This function will receive two parameters, each one will be an element from the array 
             * list, the function will compare those elements and it must return:
             *
             * - 1, if the first element must be before the second element.
             * - -1, if the second element must be before the first element.
             * - 0, if the current situation doesn't met any of the two situations above. In this case both elements 
             * can be evaluated as they had the same value. For example, in an array list of numbers, when you are 
             * trying to apply a lineal sorting (ascending/descending) in a array list of numbers, if the array sorting
             * function finds two elements with the value 3 they should be evaluated returning 0, since both values are
             * the same.
             *
             * IMPORTANT NOTE: for a correct performance the sent parameter must return at least two of the values 
             * listed above, if it doesn't the function can produce an infinite loop and thus an error.
             * @return {boolean}
             */
            sort : function (compFunction) {
                var compFunction = compFunction || function(a, b) {
                        if(a < b) {
                            return 1;
                        } else if(a > b) {
                            return -1;
                        } else {
                            return 0;
                        }
                    }, swap = function (items, firstIndex, secondIndex){
                        var temp = items[firstIndex];
                        items[firstIndex] = items[secondIndex];
                        items[secondIndex] = temp;
                    }, partition = function(items, left, right) {
                        var pivot = items[Math.floor((right + left) / 2)],
                            i = left,
                            j = right;
                        while (i <= j) {
                            while (compFunction(items[i], pivot) > 0) {
                                i++;
                            }
                            while (compFunction(items[j], pivot) < 0) {
                                j--;
                            }
                            if (i <= j) {
                                swap(items, i, j);
                                i++;
                                j--;
                            }
                        }
                        return i;
                    }, quickSort = function (items, left, right) {
                        var index;
                        if (items.length > 1) {
                            index = partition(items, left, right);
                            if (left < index - 1) {
                                quickSort(items, left, index - 1);
                            }
                            if (index < right) {
                                quickSort(items, index, right);
                            }
                        }
                        return items;
                    };

                return quickSort(elements, 0, size - 1);
            },
            /**
             * Returns the list as an array
             * @return {Array}
             */
            asArray : function () {
                return elements.slice(0);
            },
            /**
             * Swaps the position of two elements
             * @chainable
             */
            swap: function(index1, index2) {
                var aux;
                if(index1 < size && index1 >=0 && index2 < size && index2 >= 0) {
                    aux = elements[index1];
                    elements[index1] = elements[index2];
                    elements[index2] = aux;
                }
                return this;
            },
            /**
             * Returns the first element of the list
             * @return {Object}
             */
            getFirst : function () {
                return elements[0];
            },
            /**
             * Returns the last element of the list
             * @return {Object}
             */
            getLast : function () {
                return elements[size - 1];
            },

            /**
             * Returns the last element of the list and deletes it from the list
             * @return {Object}
             */
            popLast : function () {
                var lastElement;
                size -= 1;
                lastElement = elements[size];
                elements.splice(size, 1);
                return lastElement;
            },
            /**
             * Returns an array with the objects that determine the minimum size
             * the container should have
             * The array values are in this order TOP, RIGHT, BOTTOM AND LEFT
             * @return {Array}
             */
            getDimensionLimit : function () {
                var result = [100000, -1, -1, 100000],
                    objects = [undefined, undefined, undefined, undefined];
                //number of pixels we want the inner shapes to be
                //apart from the border

                for (i = 0; i < size; i += 1) {
                    if (result[0] > elements[i].y) {
                        result[0] = elements[i].y;
                        objects[0] = elements[i];

                    }
                    if (result[1] < elements[i].x + elements[i].width) {
                        result[1] = elements[i].x + elements[i].width;
                        objects[1] = elements[i];
                    }
                    if (result[2] < elements[i].y + elements[i].height) {
                        result[2] = elements[i].y + elements[i].height;
                        objects[2] = elements[i];
                    }
                    if (result[3] > elements[i].x) {
                        result[3] = elements[i].x;
                        objects[3] = elements[i];
                    }
                }
                return result;
            },
            /**
             * Clears the content of the arrayList
             * @chainable
             */
            clear : function () {
                if (size !== 0) {
                    elements = [];
                    size = 0;
                }
                return this;
            },
            /**
             * Sets the elements for the object.
             * @param {Array|null} items Array with the items to set.
             * @chainable
             */
            set: function(items) {
                if(!(items === null || jQuery.isArray(items))) {
                    throw new Error("set(): The parameter must be an array or null.");
                }
                elements = (items && items.slice(0)) || [];
                size = elements.length;
                return this;
            }
        };
    };

    PMDynaform.extendNamespace("PMDynaform.util.ArrayList", ArrayList);

}());

(function (){

    var RestProxy = function (options) {
        this.url = null;
        this.method = null;
        this.rc = null;
        this.data = null;
        RestProxy.prototype.init.call(this, options);
    };
    
    RestProxy.prototype.type = "RestProxy";
    RestProxy.prototype.init = function (options) {
        var defaults = {
            url: null,
            method: 'GET',
            data: {},
            dataType: 'json',
            authorizationType: 'none',
            authorizationOAuth: false,
            success: function(){},
            failure: function(){},
            complete: function(){}
        };
        jQuery.extend(true, defaults, options);
        this.setRestClient()
            .setUrl(defaults.url)
            .setAuthorizationOAuth(defaults.authorizationOAuth)
            .setMethod(defaults.method)
            .setData(defaults.data)
            .setDataType(defaults.dataType)
            .setSuccessAction(defaults.success)
            .setFailureAction(defaults.failure)
            .setCompleteAction(defaults.complete);
    };

    RestProxy.prototype.setRestClient = function () {
        if (this.rc instanceof RestClient === false) {
            this.rc = new RestClient();
        }
        return this;
    };

    RestProxy.prototype.setUrl = function (url) {
        this.url = url;
        return this;
    };

    RestProxy.prototype.setAuthorizationOAuth = function (option) {
        if (typeof option === 'boolean') {
            this.rc.setSendBearerAuthorization(option);
        }
        return this;
    };

    RestProxy.prototype.setMethod = function (method) {
        this.method = method;
        return this;
    };
    RestProxy.prototype.setSuccessAction = function (action) {
        RestProxy.prototype.success = action;
        return this;
    };
    RestProxy.prototype.setFailureAction = function (action) {
        RestProxy.prototype.failure = action;
        return this;
    };
    RestProxy.prototype.setCompleteAction = function (action) {
        RestProxy.prototype.complete = action;
        return this;
    };

    RestProxy.prototype.setData = function (data) {
        this.data = data;
        return this;
    };
    RestProxy.prototype.getData = function() {
        return this.data;
    };
    RestProxy.prototype.setDataType = function (dataType) {
        this.rc.setDataType(dataType);
        return this;
    };
    RestProxy.prototype.setCredentials = function (usr, pass) {
        this.rc.setBasicCredentials(usr, pass);
        return this;
    };
    RestProxy.prototype.setContentType = function () {
        this.rc.setContentType();
        return this;
    };
    RestProxy.prototype.send = function () {
    };

    RestProxy.prototype.receive = function () {
    };
    RestProxy.prototype.setAuthorizationType = function (type, credentials) {
        this.rc.setAuthorizationType(type); 
        switch(type) {
            case 'none':
                break;
            case 'basic':
                    this.rc.setBasicCredentials(credentials.client, credentials.secret);
                break;
            case 'oauth2':
                    this.rc.setAccessToken(credentials);
                break;
        }
        
        return this;
    };

    RestProxy.prototype.post = function (settings) {
        var that = this;
        if (settings !== undefined) {
            that.init(settings);
        }
        if (this.rc) {
            that.rc.postCall({
                url: that.url,
                id: that.uid,
                data: that.data,
                success : function (xhr, response) {
                    that.success.call(that, xhr, response);
                },
                failure: function (xhr, response) {
                    that.failure.call(that, xhr, response);
                },
                complete: function (xhr, response) {
                    that.complete.call(that, xhr, response);
                }
            });
            that.rc.setSendBearerAuthorization(false);

        } else {
            throw new Error("the RestClient was not defined, please verify the property 'rc' for continue.");
        }
    };

    RestProxy.prototype.update = function (settings) {
        var that = this;
        if (settings !== undefined) {
            that.init(settings);
        }
        if (this.rc) {
            this.rc.putCall({
                url: this.url,
                id: this.uid,
                data: this.data,
                success : function (xhr, response) {
                    that.success.call(this, xhr, response);
                },
                failure: function (xhr, response) {
                    that.failure.call(this, xhr, response);
                },
                complete: function (xhr, response) {
                    that.complete.call(that, xhr, response);
                }
            });
        } else {
            throw new Error("the RestClient was not defined, please verify the property 'rc' for continue.");
        }
    };
    
    RestProxy.prototype.get = function (settings) {
        var that = this;
        if (settings !== undefined) {
            that.init(settings);
        }
        if (this.rc) {
            that.rc.getCall({
                url: that.url,
                id: that.uid,
                data: that.data,
                success : function (xhr, response) {
                    that.success.call(that, xhr, response);
                },
                failure: function (xhr, response) {
                    that.failure.call(that, xhr, response);
                },
                complete: function (xhr, response) {
                    that.complete.call(that, xhr, response);
                }
            });
            that.rc.setSendBearerAuthorization(false);

        } else {
            throw new Error("the RestClient was not defined, please verify the property 'rc' for continue.");
        }
    };
   
    RestProxy.prototype.remove = function (settings) {
        var that = this;
        if (settings !== undefined) {
            that.init(settings);
        }
        if (this.rc) {
            this.rc.deleteCall({
                url: this.url,
                id: this.uid,
                data: this.data,
                success : function (xhr, response) {
                    that.success.call(this, xhr, response);
                },
                failure: function (xhr, response) {
                    that.failure.call(this, xhr, response);
                },
                complete: function (xhr, response) {
                    that.complete.call(that, xhr, response);
                }
            });
        } else {
            throw new Error("the RestClient was not defined, please verify the property for continue.");
        }
    };
   
    RestProxy.prototype.success = function (xhr, response){        
    };
   
    RestProxy.prototype.failure = function (xhr, response){
    };
    
    RestProxy.prototype.complete = function (xhr, response){
    };
    
    PMDynaform.extendNamespace('PMDynaform.proxy.RestProxy', RestProxy);

}());
(function(){

	var Validator =  Backbone.View.extend({
        template: _.template($("#tpl-validator").html()),
        events:{
            "mouseover": "onMouseOver"
        },
        initialize: function() {
            this.render();
        },
        onMouseOver: function() {
            
        },
        render: function() {
            this.$el.addClass("pmdynaform-message-error");
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Validator", Validator);

}());
(function () {
    var PanelView = Backbone.View.extend({
        content: null,
        colsIndex: null,
        template: null,
        collection: null,
        items: null,
        views: [],
        renderTo: document.body,
        project: null,
        events: {
            "click a#print-button": "printForm"
        },
        initialize: function (options) {
            var i, defaults = {
                factory: {
                    products: {
                        "form": {
                            model: PMDynaform.model.FormPanel,
                            view: PMDynaform.view.FormPanel
                        },
                        "fieldset": {
                            model: PMDynaform.model.Fieldset,
                            view: PMDynaform.view.Fieldset
                        },
                        "panel": {
                            model: PMDynaform.model.FormPanel,
                            view: PMDynaform.view.FormPanel
                        }
                    },
                    defaultProduct: "form"
                }
            };
            if (options.renderTo) {
                this.renderTo = options.renderTo;
            }
            if (options.project) {
                this.project = options.project;
            }
            this.views = [];

            this.makePanels();
            this.render();
            for (i = 0; i < this.views.length; i += 1) {
                this.views[i].runningFormulator();
            }
        },
        printForm: function () {
            var dataForm = this.project.view.getData2(),
                result,
                flashView,
                flashModel;
            if(typeof  this.views[0].model.get("onBeforePrintHandler") === "function") {
                this.views[0].model.get("onBeforePrintHandler")();
            }

            result = this.project.webServiceManager.submitFormCase(dataForm);
            if (result && result.state === 'success') {
                window.print();
                if(typeof this.views[0].model.get("onAfterPrintHandler") === "function") {
                    this.views[0].model.get("onAfterPrintHandler")();
                }
            } else {
                flashModel = new PMDynaform.ui.FlashMessageView({
                    message: result.state,
                    emphasisMessage: "Error: ",
                    startAnimation: 1000,
                    type: "danger",
                    appendTo: this.el,
                    duration: 3000
                });
                flashView = new PMDynaform.view.FlashMessageView({model: flashModel})
                flashView.render();
            }
        },
        getData: function () {
            var i,
                k,
                field,
                subform,
                fields,
                panels,
                formData;

            panels = this.model.get("items");
            formData = this.model.getData();
            for (i = 0; i < panels.length; i += 1) {
                fields = this.views[i].items.asArray();
                for (k = 0; k < fields.length; k += 1) {

                    if ((typeof fields[k].model.getData === "function") && (fields[k].model.attributes.type === "form")) {
                        subform = fields[k].getData();
                        $.extend(true, formData.variables, subform.variables);
                    } else if (typeof fields[k].model.getData === "function") {
                        field = fields[k].model.getData();
                        formData.variables[field.name] = field.value;
                    }
                }
            }
            return formData;
        },
        /**
         * Function to get Data to end point
         * @returns {{}}
         */
        getData2: function () {
            var i,
                k,
                field,
                fields,
                panels,
                grid,
                data = {},
                dataRecursive;

            panels = this.model.get("items");
            for (i = 0; i < panels.length; i += 1) {
                fields = this.views[i].items.asArray();
                for (k = 0; k < fields.length; k += 1) {
                    if (this.isValidFieldToSendData(fields[k])) {
                        if (typeof fields[k].getItems === "function") {
                            dataRecursive = this.getDataRecursive(fields[k]);
                            $.extend(true, data, dataRecursive);
                        } else if (typeof fields[k].model.getData === "function") {
                            if (fields[k].model.get("type") === "grid") {
                                grid = fields[k].model;
                                data[grid.get("name")] = fields[k].getData2();
                            } else {
                                field = fields[k].model.getData();
                                data[field.name] = field.value;
                                if (typeof fields[k].model.getKeyLabel === "function") {
                                    field = fields[k].model.getKeyLabel();
                                    data[field.name] = field.value;
                                }
                            }
                        }
                    }
                }
            }
            return data;
        },
        /**
         * This function verify that field is valid to send data
         * @param field
         * @returns {boolean}
         */
        isValidFieldToSendData: function (field) {
            var flag = false,
                invalidTypes = ["empty", "title", "subtitle", "button", "submit", "panel", "link", "image"];

            if (field.model.get("mode") === "view") {
                if (invalidTypes.indexOf(field.model.get("originalType")) === -1) {
                    flag = true;
                }
            } else {
                if (invalidTypes.indexOf(field.model.get("type")) === -1) {
                    flag = true;
                }
            }
            return flag;
        },
        getDataRecursive: function (view) {
            var items = view.getItems(),
                viewField,
                field,
                dataRecursive = {},
                grid,
                data = {},
                index;

            for (index = 0; index < items.length; index += 1) {
                viewField = items[index];
                if (typeof viewField.getItems === "function") {
                    dataRecursive = this.getDataRecursive(viewField);
                    $.extend(true, data, dataRecursive);
                } else if (typeof viewField.model.getData === "function") {
                    if (viewField.model.get("type") === "grid") {
                        grid = viewField.model;
                        data[grid.get("name")] = viewField.getData2();
                    } else {
                        field = viewField.model.getData();
                        data[field.name] = field.value;
                        if (typeof viewField.model.getKeyLabel === "function") {
                            field = viewField.model.getKeyLabel();
                            data[field.name] = field.value;
                        }
                    }
                }
            }
            return data;
        },
        setData2: function (data) {
            this.getPanels()[0].setData2(data);
            return this;
        },
        makePanels: function () {
            var i = 0,
                items,
                panelmodel,
                view;

            this.views = [];
            items = this.model.get("items");

            for (i = 0; i < items.length; i += 1) {
                if ($.inArray(items[i].type, ["panel", "form"]) >= 0) {

                    panelmodel = new PMDynaform.model.FormPanel(items[i]);

                    view = new PMDynaform.view.FormPanel({
                        model: panelmodel,
                        project: this.project
                    });

                    if (this.project){
                        panelmodel.set("project",this.project);
                    }
                    
                    this.views.push(view);
                }
            }

            return this;
        },
        getPanels: function () {
            var items = (this.views.length > 0) ? this.views : [];

            return items;
        },
        render: function () {
            var i,
                j,
                printed = true;

            this.$el = $(this.el);
            for (i = 0; i < this.views.length; i += 1) {
                printed = this.views[i].model.get("printable");
                this.$el.append(this.views[i].render().el);
                if (i === 0 && printed && typeof PMDynaform.core.ProjectMobile === "undefined") {
                    this.addPrinForm(this.views[i].el);
                    if (typeof this.views[i].model.get("onBeforePrintHandler") === "function"){
                        this.model.set("onBeforePrintHandlder", this.views[i].model.get("onBeforePrintHandlder"))
                    }
                    if (typeof this.views[i].model.get("onAfterPrintHandler") === "function"){
                        this.model.set("onAfterPrintHandlder", this.views[i].model.get("onAfterPrintHandlder"))
                    }
                }
            }
            this.$el.addClass("pmdynaform-container");
            if (PMDynaform.core.ProjectMobile) {
                this.$el.css({
                    height: "auto"
                });
            }
            $(this.renderTo).append(this.el);

            return this;
        },
        afterRender: function () {
            var i;

            for (i = 0; i < this.views.length; i += 1) {
                this.views[i].afterRender();
            }

            return this;
        },
        addPrinForm: function (container) {
            var item, printContainer, buttonPrint;
            printContainer = document.createElement("div");
            buttonPrint = document.createElement("a");
            buttonPrint.className = "print-button";
            buttonPrint.id = "print-button";
            printContainer.appendChild(buttonPrint);
            printContainer.className = "printContainer";
            if (container instanceof jQuery) {
                container.prepend(printContainer);
            } else {
                $(container).prepend(printContainer);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Panel", PanelView);

}());

(function () {
    var FormPanel = Backbone.View.extend({
        tagName: "form",
        content: null,
        template: null,
        items: new PMDynaform.util.ArrayList(),
        views: [],
        templateRow: _.template($('#tpl-row').html()),
        colSpanLabel: 3,
        colSpanControl: 9,
        project: null,
        preTargetControl: null,
        sqlFields: [],
        submit: [],
        validDependentFields: [],
        events: {
            'submit': 'onSubmit'
        },
        onChange: function () {
        },
        /*
         requireVariableByField: [
         "text",
         "textarea",
         "checkbox",
         "radio",
         "dropdown",
         "datetime",
         "suggest",
         "hidden",
         "label"
         ],*/
        requireVariableByField: [],
        checkBinding: function () {
            this.onChangeCallback(this.model.get("name"), this.previusValue, this.model.get("value"));
            //If the key is not pressed, executes the render method
            if (!this.keyPressed) {
                this.render();
            }
        },
        setOnChange: function (handler) {
            if (typeof handler === "function") {
                this.onChangeCallback = handler;
            }
            return this;
        },
        onChangeHandler: function () {
            var that = this;
            return function (field, newValue, previousValue) {
                if (typeof that.onChange === 'function') {
                    that.onChange(field, newValue, previousValue);
                }
            };
        },
        initialize: function (options) {
            var defaults,
                fileConf,
                gridConf;

            if (PMDynaform.core.ProjectMobile) {
                gridConf = {
                    model: PMDynaform.model.GridMobile,
                    view: PMDynaform.view.GridMobile
                };
                fileConf = {
                    model: PMDynaform.model.FileUpload,
                    view: PMDynaform.view.FileUpload
                };
            } else {
                gridConf = {
                    model: PMDynaform.model.GridPanel,
                    view: PMDynaform.view.GridPanel
                };
                fileConf = {
                    model: PMDynaform.model.File,
                    view: PMDynaform.view.File
                };
            }
            var defaults = {
                factory: {
                    products: {
                        "text": {
                            model: PMDynaform.model.Text,
                            view: PMDynaform.view.Text
                        },
                        "textarea": {
                            model: PMDynaform.model.TextArea,
                            view: PMDynaform.view.TextArea
                        },
                        "checkgroup": {
                            model: PMDynaform.model.CheckGroup,
                            view: PMDynaform.view.CheckGroup
                        },
                        "checkbox": {
                            model: PMDynaform.model.CheckBox,
                            view: PMDynaform.view.CheckBox
                        },
                        "radio": {
                            model: PMDynaform.model.Radio,
                            view: PMDynaform.view.Radio
                        },
                        "dropdown": {
                            model: PMDynaform.model.Dropdown,
                            view: PMDynaform.view.Dropdown
                        },
                        "button": {
                            model: PMDynaform.model.Button,
                            view: PMDynaform.view.Button
                        },
                        "submit": {
                            model: PMDynaform.model.Submit,
                            view: PMDynaform.view.Submit
                        },
                        "datetime": {
                            model: PMDynaform.model.Datetime,
                            view: PMDynaform.view.Datetime
                        },
                        "fieldset": {
                            model: PMDynaform.model.Fieldset,
                            view: PMDynaform.view.Fieldset
                        },
                        "suggest": {
                            model: PMDynaform.model.Suggest,
                            view: PMDynaform.view.Suggest
                        },
                        "link": {
                            model: PMDynaform.model.Link,
                            view: PMDynaform.view.Link
                        },
                        "hidden": {
                            model: PMDynaform.model.Hidden,
                            view: PMDynaform.view.Hidden
                        },
                        "title": {
                            model: PMDynaform.model.Title,
                            view: PMDynaform.view.Title
                        },
                        "subtitle": {
                            model: PMDynaform.model.Title,
                            view: PMDynaform.view.Title
                        },
                        "label": {
                            model: PMDynaform.model.Label,
                            view: PMDynaform.view.Label
                        },
                        "empty": {
                            model: PMDynaform.model.Empty,
                            view: PMDynaform.view.Empty
                        },
                        "file": fileConf,
                        "image": {
                            model: PMDynaform.model.Image,
                            view: PMDynaform.view.Image
                        },
                        "geomap": {
                            model: PMDynaform.model.GeoMap,
                            view: PMDynaform.view.GeoMap
                        },
                        "grid": gridConf,
                        "form": {
                            model: PMDynaform.model.SubForm,
                            view: PMDynaform.view.SubForm
                        },
                        "annotation": {
                            model: PMDynaform.model.Annotation,
                            view: PMDynaform.view.Annotation
                        },
                        "location": {
                            model: PMDynaform.model.GeoMobile,
                            view: PMDynaform.view.GeoMobile
                        },
                        "scannercode": {
                            model: PMDynaform.model.Qrcode_mobile,
                            view: PMDynaform.view.Qrcode_mobile
                        },
                        "signature": {
                            model: PMDynaform.model.Signature_mobile,
                            view: PMDynaform.view.Signature_mobile
                        },
                        "imagemobile": {
                            model: PMDynaform.model.FileMobile,
                            view: PMDynaform.view.FileMobile
                        },
                        "audiomobile": {
                            model: PMDynaform.model.FileMobile,
                            view: PMDynaform.view.FileMobile
                        },
                        "videomobile": {
                            model: PMDynaform.model.FileMobile,
                            view: PMDynaform.view.FileMobile
                        },
                        "panel": {
                            model: PMDynaform.model.PanelField,
                            view: PMDynaform.view.PanelField
                        }
                    },
                    defaultProduct: "empty"
                }
            };
            this.validDependentFields = ["dropdown", "suggest", "text", "textarea", "label"];
            this.items = new PMDynaform.util.ArrayList();
            if (options.project) {
                this.project = options.project;
            }
            this.setFactory(defaults.factory);
            this.applyGlobalMode();
            this.makeItems();
        },
        setAction: function () {
            this.$el.attr("action", this.model.get("action"));

            return this;
        },
        setMethod: function () {
            this.$el.attr("method", this.model.get("method"));

            return this;
        },
        setFactory: function (factory) {
            this.factory = factory;
            return this;
        },
        getData: function () {
            var i,
                k,
                field,
                fields,
                panels,
                data = [],

                panels = this.viewsBuilt;

            for (i = 0; i < panels.length; i += 1) {
                fields = panels[i];
                for (k = 0; k < fields.length; k += 1) {
                    field = fields[k].model.get("data");
                    data.push(field);
                }
            }
            return data;
        },
        countElementsInJSON: function (obj) {
            var i = 0, item;
            for (item  in obj) {
                i += 1;
            }
            return i;
        },
        setData2: function (data) {
            var key, value, label, field, nameReplace, name;
            if (typeof data === "object") {
                for (key in data) {
                    name = key;
                    if (key.indexOf("_label") !== -1) {
                        nameReplace = key.replace(/_label/g, "");
                        if (!data[nameReplace]) {
                            name = nameReplace;
                        }
                    }
                    field = getFieldByName(name);
                    if (_.isArray(field) && field.length > 0) {
                        value = data[key];
                        if (data.hasOwnProperty(key + "_label")) {
                            label = data[key + "_label"];
                        } else {
                            label = data[key];
                        }
                        jQuery.each(field, function (index, element) {
                            element.setData({
                                value: value,
                                label: label
                            });
                        });
                    }
                }
            }
            return this;
        },
        validateVariableField: function (field) {
            var isOk = false;

            if ($.inArray(field.type, this.requireVariableByField) >= 0) {
                if (field.var_uid) {
                    isOk = true;
                }
            } else {
                isOk = "NOT";
            }

            return isOk;
        },
        makeItems: function () {
            var i,
                j,
                factory = this.factory,
                that = this,
                product,
                variableEnabled,
                productBuilt,
                rowView,
                productModel,
                jsonFixed,
                fieldModel,
                fields,
                items;
            this.sqlFields = [];
            fields = this.model.get("items");
            this.viewsBuilt = [];
            this.items.clear();

            for (i = 0; i < fields.length; i += 1) {
                rowView = [];
                for (j = 0; j < fields[i].length; j += 1) {
                    variableEnabled = this.validateVariableField(fields[i][j]);
                    if (fields[i][j] !== null && (variableEnabled === true || variableEnabled === "NOT")) {
                        fields[i][j] = this.applyGlobalModeField(fields[i][j]);
                        if (fields[i][j].type) {
                            if (!PMDynaform.core.ProjectMobile && fields[i][j].type === "location") {
                                fields[i][j].type = "geomap";
                            }
                            if (fields[i][j].type === "label") {
                                fields[i][j].type = "annotation";
                            }
                            if (fields[i][j].type === "checkbox" && fields[i][j].dataType !== "boolean" && fields[i][j].dataType !== "") {
                                fields[i][j].type = "checkgroup";
                            }
                            if (fields[i][j].type === "file" && fields[i][j].hasOwnProperty("inputDocuments")) {
                                if ($.isArray(fields[i][j]["inputDocuments"])) {
                                    $(fields[i][j]["inputDocuments"]).each(function () {
                                        that.model.attributes.inputDocuments[fields[i][j]["variable"]] = this;
                                    });
                                }
                            }
                            jsonFixed = new PMDynaform.core.TransformJSON({
                                parentMode: this.model.get("mode"),
                                field: fields[i][j]
                            });
                            product = factory.products[jsonFixed.getJSON().type.toLowerCase()] ?
                                factory.products[jsonFixed.getJSON().type.toLowerCase()] : factory.products[factory.defaultProduct];
                        } else {
                            jsonFixed = new PMDynaform.core.TransformJSON({
                                parentMode: this.model.get("mode"),
                                field: fields[i][j]
                            });
                            product = factory.products[factory.defaultProduct];
                        }

                        //The number 12 is related to 12 columns from Bootstrap framework
                        fieldModel = {
                            colSpanLabel: this.createColspan(fields[i][j].colSpan, "label"),
                            colSpanControl: this.createColspan(fields[i][j].colSpan, "control"),
                            project: this.project,
                            parentMode: this.model.get("mode"),
                            namespace: this.model.get("namespace"),
                            variable: fields[i][j].variable ? fields[i][j].variable : null,
                            fieldsRelated: [],
                            name : fields[i][j].name,
                            id : fields[i][j].id || PMDynaform.core.Utils.generateID(),
                            options : fields[i][j].options,
                            form : this,
                            optionsSql : fields[i][j].optionsSql,
                            required : fields[i][j].required || false,
                            hint : fields[i][j].hint || "",
                            format: fields[i][j].format || "",
                            sql : fields[i][j].sql || "",
                            defaultValue : fields[i][j].defaultValue || "",
                            defaultDate: fields[i][j].defaultDate || null
                        };
                        if (fields[i][j].type === "form" || fields[i][j].type === "grid") {
                            fieldModel.variables = this.model.get("variables") || [];
                            fieldModel.data = this.model.get("data") || [];
                        }

                        $.extend(true, fieldModel, jsonFixed.getJSON());

                        if (fieldModel.type === "form" && fieldModel.mode === "parent") {
                            fieldModel.mode = this.model.get("mode");
                        }

                        //format data: geotag to text and textarea
                        if (fieldModel.data && (fieldModel.type === "text" || fieldModel.type === "textarea" )) {
                            var geo = fieldModel.data.value;
                            if (geo && geo.latitude && geo.longitude && geo.altitude) {
                                fieldModel.data.value = geo.latitude + " " + geo.longitude + " " + geo.altitude;
                                fieldModel.data.label = geo.latitude + " " + geo.longitude + " " + geo.altitude;
                            }
                        }
                        productModel = new product.model(fieldModel);

                        if (fieldModel.sql !== undefined && this.validDependentFields.indexOf(fieldModel.type) > -1) {
                            productModel.set("parentDependents", []);
                            productModel.set("dependents", []);
                            this.sqlFields.push(productModel);
                        }

                        productBuilt = new product.view({
                            model: productModel,
                            project: this.project,
                            parent: this
                        });
                        productBuilt.parent = this;
                        productBuilt.project = this.project;
                        //add view in mobile project
                        if (this.project.addViewFields && productModel.get("type") !== "empty") {
                            this.project.addViewFields(productBuilt);
                        }
                        rowView.push(productBuilt);
                        this.items.insert(productBuilt);
                        productBuilt.model.attributes.view = productBuilt;
                    } else {
                        console.error("The field must have the variable property and must to be an object: ", fields[i][j]);
                    }
                }
                if (rowView.length) {
                    this.viewsBuilt.push(rowView);
                }
            }
            this.createDependencies();
            this.runningFormulator();
            return this;
        },
        createDependencies: function () {
            var i, j, nameField, sql, fields;
            fields = this.sqlFields;
            for (i = 0; i < fields.length; i += 1) {
                fields[i].set("dependents", []);
                if (fields[i].get("variable") && fields[i].get("variable") !== "") {
                    nameField = fields[i].get("variable");
                } else {
                    nameField = fields[i].get("id");
                }
                for (j = 0; j < fields.length; j += 1) {
                    if (i !== j) {
                        sql = fields[j].get("sql");
                        sql = sql.replace(/\n/g, " ");
                        if (this._existVariableInSql(sql, nameField)) {
                            fields[j].attributes.parentDependents.push(fields[i]);
                            fields[i].attributes.dependents.push(fields[j]);
                        }
                    }
                }
            }
            return this;
        },
        // find the @, #, %, !, $ in property sql,  to verify the existence of dependence between fields
        // return true when exist the relation and false when not exist the relation
        _existVariableInSql: function (sql, nameField) {
            var parse, result, variable;
            parse = /\@(?:([\@\%\#\=\!Qq])([a-zA-Z\_]\w*)|([a-zA-Z\_][\w\-\>\:]*)\(((?:[^\\\\\)]*?)*)\))/g;
            while ((result = parse.exec(sql)) !== null) {
                if ($.isArray(result) && result.length) {
                    variable = result[0];
                    if (variable.substring(2, variable.length) === nameField) {
                        return true;
                    }
                }
            }
            return false;
        },
        createColspan: function (colSpan, target) {
            var colspan;
            switch (parseInt(colSpan)) {
                case 12:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 11:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 10:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 9:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 8:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 7:
                    if (target === "label") {
                        colspan = 2;
                    } else {
                        colspan = 10;
                    }
                    break;
                case 6:
                    if (target === "label") {
                        colspan = 4;
                    } else {
                        colspan = 8;
                    }
                    break;
                case 5:
                    if (target === "label") {
                        colspan = 5;
                    } else {
                        colspan = 7;
                    }
                    break;
                case 4:
                    if (target === "label") {
                        colspan = 4;
                    } else {
                        colspan = 8;
                    }
                    break;
                case 3:
                    if (target === "label") {
                        colspan = 5;
                    } else {
                        colspan = 7;
                    }
                    break;
                case 2:
                    if (target === "label") {
                        colspan = 5;
                    } else {
                        colspan = 7;
                    }
                    break;
                case 1:
                    if (target === "label") {
                        colspan = 4;
                    } else {
                        colspan = 8;
                    }
                    break;
            }
            return colspan;
        },
        runningFormulator: function () {
            var items, field, item, i, j, k, fieldsAsocied;
            items = this.viewsBuilt;
            for (i = 0; i < items.length; i += 1) {
                for (j = 0; j < items[i].length; j += 1) {
                    field = items[i][j];
                    if (field.model.get("type") === "form") {
                        if (field.runningFormulator) {
                            field.runningFormulator();
                        }
                    }
                    if (field.model.get("type") === "grid") {
                        var rowsAll = field.gridtable;
                        for (var row = 0, rowCurrent; row < rowsAll.length; row += 1) {
                            rowCurrent = rowsAll[row];
                            for (var col = 0; col < rowCurrent.length; col += 1) {
                                if (rowCurrent[col].model.get("formula") && rowCurrent[col].model.get("formula").trim().length) {
                                    fieldsAsocied = rowCurrent.filter(function (element) {
                                        if (rowCurrent[col].fieldValid.indexOf(element.model.get("id")) > -1) {
                                            element.onFieldAssociatedHandler();
                                        }
                                    });
                                }
                            }
                        }
                    } else {
                        if (field.model.get("formula") && field.model.get("formula").trim().length) {
                            fieldsAsocied = items.filter(function (element) {
                                var k;
                                for (k = 0; k < element.length; k += 1) {
                                    if (field.fieldValid.indexOf(element[k].model.get("id")) > -1) {
                                        element[k].onFieldAssociatedHandler();
                                    }
                                }
                            });
                        }
                    }
                }
            }
            return this;
        },
        setFieldRelated: function () {
            var i,
                j,
                k,
                l,
                fieldA,
                fieldB,
                related,
                relatedA,
                relatedB,
                fieldsSubForm,
                relatingField,
                fields = this.items.asArray();

            for (i = 0; i < fields.length; i += 1) {
                fieldA = fields[i].model.get("variable");
                if (fieldA) {
                    for (j = 0; j < fields.length; j += 1) {
                        if (i !== j) {
                            fieldB = fields[j].model.get("variable");
                            if (fieldB) {
                                if (fieldA.var_uid === fieldB.var_uid) {
                                    related = fields[i].model.get("fieldsRelated");
                                    related.push(fields[j]);
                                    fields[i].model.set("fieldsRelated", related);
                                }
                            }
                        }
                    }
                }

                if (fields[i].model.get("type") === "form") {
                    fieldsSubForm = fields[i].getItems();
                    for (k = 0; k < fields.length; k += 1) {
                        fieldA = fields[k].model.get("variable");
                        if (fieldA) {
                            for (l = 0; l < fieldsSubForm.length; l += 1) {
                                fieldB = fieldsSubForm[l].model.get("variable");
                                if (fieldB) {
                                    if (fieldA.var_uid === fieldB.var_uid) {
                                        relatedA = fields[k].model.get("fieldsRelated");
                                        relatedA.push(fieldsSubForm[l]);
                                        fields[k].model.set("fieldsRelated", relatedA);

                                        relatedB = fieldsSubForm[l].model.get("fieldsRelated");
                                        relatedB.push(fields[k]);
                                        fieldsSubForm[l].model.set("fieldsRelated", relatedB);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return this;
        },
        getVariable: function (var_uid) {
            var i,
                varSelected,
                variables = this.model.attributes.variables;

            loop_variables:
                for (i = 0; i < variables.length; i += 1) {
                    if (variables[i] && variables[i].var_uid === var_uid) {
                        varSelected = variables[i];
                        break loop_variables;
                    }
                }
            return varSelected;
        },
        getFields: function () {
            return (this.items.getSize() > 0) ? this.items.asArray() : [];
        },
        beforeRender: function () {
            return this;
        },
        disableContextMenu: function () {
            this.$el.on("contextmenu", function (event) {
                event.preventDefault();
                event.stopPropagation();
            });

            return this;
        },
        applySuccess: function () {
            var items = this.items.asArray(),
                i;

            for (i = 0; i < items.length; i += 1) {
                if (items[i].applyStyleSuccess) {
                    items[i].applyStyleSuccess();
                }
            }
        },
        onSubmit: function (event) {
            var booResponse;
            if (this.executeSubmitArray()) {
                if (!this.isValid(event)) {
                    booResponse = false;
                } else {
                    this.applySuccess();
                    booResponse = true;
                }
                //validate if has has submitRest enabled
                if (this.project.submitRest) {
                    if (event !== undefined) {
                        event.preventDefault();
                    }
                    if (booResponse) {
                        this.project.onSubmitForm();
                    }
                } else {
                    if(booResponse && event && event.type === 'submit') {
                        this.prepareFormToPost();
                    }
                }
            }
            else {
                if (event !== undefined) {
                    event.preventDefault();
                }
            }
            return booResponse;
        },
        /**
         * prepare and enable fields to post the data
         * @param event
         * @returns {FormPanel}
         */
        prepareFormToPost: function() {
            //force to enable to post data
            if (this.project){
                this.project.modalProgress.render();
            }
            this.$el.find(".form-control").prop('disabled', false);
            this.$el.find("input[type='hidden']").prop('disabled', false);
            this.$el.find(".pmdynaform-control-checkbox").prop('disabled', false);
            this.$el.find(".pmdynaform-control-checkgroup").prop('disabled', false);
            this.$el.find(".pmdynaform-control-radio").prop('disabled', false);
            return this;
        },
        executeSubmitArray: function () {
            var indexSubmit = 0,
                executeSubmit = true,
                responseCallback = true;
            for (indexSubmit = 0; indexSubmit < this.submit.length; indexSubmit++) {
                if (typeof this.submit[indexSubmit] === "function") {
                    responseCallback = this.submit[indexSubmit]();
                    if (responseCallback !== undefined && typeof responseCallback === "boolean" && responseCallback === false) {
                        executeSubmit = false;
                        break;
                    }
                }
            }
            return executeSubmit;
        },
        isValid: function (event) {
            var i,
                formValid = true,
                itemField,
                firstTime = true,
                itemsField = this.items.asArray();

            if (itemsField.length > 0) {
                for (i = 0; i < itemsField.length; i += 1) {
                    if (itemsField[i].validate) {
                        itemsField[i].validate(event);
                        if (!itemsField[i].model.get("valid")) {
                            if (itemField === undefined) {
                                itemField = itemsField[i];
                            }
                            formValid = itemsField[i].model.get("valid");
                        }
                    }
                }
            }
            if (formValid) {
                for (i = 0; i < itemsField.length; i += 1) {
                    if (( itemsField[i].model.get("var_name") !== undefined) && (itemsField[i].model.get("var_name").trim().length === 0 )) {
                        if (itemsField[i].model.get("type") === "radio") {
                            itemsField[i].$el.find("input").attr("name", "");
                        }
                    }
                }
            } else {
                if (itemField && itemField.model.get("type") !== "grid" && itemField.model.get("type") !== "form") {
                    itemField.setFocus();
                }
            }
            return formValid;
        },
        render: function (subForm) {
            var i, j, $rowView;
            if (subForm) {
                this.el = document.createElement("div");
                this.$el = $(this.el);
            }
            for (i = 0; i < this.viewsBuilt.length; i += 1) {
                $rowView = $(this.templateRow());
                for (j = 0; j < this.viewsBuilt[i].length; j += 1) {
                    $rowView.append(this.viewsBuilt[i][j].render().el);
                }
                this.$el.append($rowView);
            }
            this.$el.attr("role", "form");
            this.$el.addClass("form-horizontal pmdynaform-form");
            this.el.style.height = "auto";
            this.setAction();
            this.setMethod();
            this.$el.attr("id", this.model.get("id"));
            if (this.model.get("target")) {
                this.$el.attr("target", this.model.get("target"));
            }

            var ids = this.model.get("inputDocuments");
            for (var id in ids) {
                var hidenInputs = document.createElement("input");
                hidenInputs.name = "INPUTS[" + id + "]";
                hidenInputs.type = "hidden";
                hidenInputs.value = ids[id];
                this.el.appendChild(hidenInputs);
            }

            this.disableContextMenu();
            return this;
        },
        afterRender: function () {
            var i,
                j,
                items = this.items.asArray();
            for (i = 0; i < items.length; i += 1) {
                if (items[i].afterRender) {
                    items[i].afterRender();
                }
            }
            return this;
        },
        setOnSubmit: function (callback) {
            if (callback && typeof callback === "function") {
                this.submit.push(callback);
            } else {
                return null;
            }
        },
        applyGlobalModeField: function (json) {
            if (this.project.globalMode) {
                json.mode = this.project.globalMode;
            }
            return json;
        },
        applyGlobalMode: function () {
            if (this.project.globalMode) {
                if (this.model.get("type") && this.model.get("type") === "form") {
                    this.model.set("mode", this.project.globalMode);
                }
            }
            return this;
        },
        /**
         * this method close this form, stand alone version for mobile
         */
        close : function (){
            this.model.close();
        },
        /**
         * This method looks fields from a valid criterion
         * @param  {String} criteria : es un criterio de filtro
         * @return {Array} result filter
         */
        searchFieldType : function (criteria) {
            var result = [],
                fields = this.getFields();
            if (criteria && criteria !== undefined){
                result = _.filter(fields, function (item) {
                    if(item.model.get("type") === criteria){
                        return item;
                    }
                });
            }
            return result;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.FormPanel", FormPanel);

}());

(function () {
    var FieldView = Backbone.View.extend({
        tagName: "div",
        tagControl: "",
        tagHiddenToLabel: "",
        keyLabelControl: "",
        enableValidate: true,
        events: {
            "click .form-control": "onclickField",
        },
        initialize: function (options) {
            if (options.project) {
                this.project = options.project;
            }

            this.setClassName()
                .render();
        },
        setClassName: function () {
            return this;
        },
        enableTooltip: function () {
            this.$el.find("[data-toggle=tooltip]").tooltip().click(function (e) {
                $(this).tooltip('toggle');
            });
            return this;
        },
        applyStyleError: function () {
            this.$el.addClass("has-error has-feedback");
            return this;
        },
        applyStyleWarning: function () {
            this.$el.addClass('has-warning');
            return this;
        },
        applyStyleSuccess: function () {
            this.$el.removeClass("has-error");
            if (!this.model.get("disabled")) {
                this.$el.addClass("has-success");
            }

            return this;
        },
        changeValuesFieldsRelated: function () {
            this.model.changeValuesFieldsRelated();
            return this;
        },
        /**
         * The method is only supported if the field have options.
         * Checks if the value to sets is inside of the options property.
         */
        setValueToDomain: function () {
            var htmlElement = this.getHTMLControl();

            if (htmlElement.length && !this.model.attributes.disabled) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error');
                }

                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });

                    htmlElement.parent().append(this.validator.el);
                    this.applyStyleError();
                }
            }

            return this;
        },
        /**
         * Apply Javascript events associated to control
         *
         */
        on: function (e, fn) {
            var that = this,
                control = this.$el.find("input");

            if (control) {
                control.on(e, function (event) {
                    fn(event, that);

                    event.stopPropagation();
                });
            } else {
                throw new Error("Is not possible find the HTMLElement associated to field");
            }

            return this;
        },
        /**
         * The method is just for return the Jquery HTML of the control
         * @return {JQuery HTMLElement} Encapsulate the HTMLElement
         */
        getHTMLControl: function () {
            return this;
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint")) {
                this.enableTooltip();
            }
            this.setValueToDomain();
            return this;
        },
        onclickField: function () {
            return this;
        },
        setLabel: function (label, col) {
            var tagLabel;
            if (this.model.get("type") === "grid") {
                if (col !== undefined) {
                    if (col > 0 && col <= this.columnsModel.length) {
                        this.domTitleHeader[col - 1].find("span[class='title-column']").text(label);
                    } else {
                        return null;
                    }
                }
            } else {
                if (this.model.attributes.label !== undefined) {
                    this.model.attributes.label = label;
                    if (this.el || this.$el.length) {
                        this.$el.find("label").find("span[class='textlabel']").text(label);
                        this.$el.find("h4").find("span[class='textlabel']").text(label);
                        this.$el.find("h5").find("span[class='textlabel']").text(label);
                        this.$el.find(".pmdynaform-grid-title span").text(label);
                        this.$el.find(".pmdynaform-control-annotation span").text(label);
                        this.$el.find("button[type='button'].btn-primary span").text(label);
                        this.$el.find("button[type='submit'] span").text(label);
                    }
                }
            }
            return this;
        },
        getLabel: function (col) {
            if (this.model.get("label") !== undefined) {
                if (col && this.model.get("type") === "grid") {
                    if (col <= this.columnsModel.length) {
                        return this.columnsModel[col - 1].label || "";
                    } else {
                        return null;
                    }
                } else {
                    return this.model.get("label");
                }
            }
            return null;
        },
        setText: function (value, row, col) {
            var fieldWithOptions, label, options, keyValue, data, existData, type, dataType, option, row;
            existData = false;
            type = this.model.get("type");
            dataType = this.model.get("dataType");
            data = {};
            if (type === "grid") {
                if (row !== undefined && col !== undefined) {
                    if ((row > 0 && col > 0) && row <= this.gridtable.length && col <= this.columnsModel.length) {
                        return this.gridtable[row - 1][col - 1].setText(value);
                    } else {
                        return null;
                    }
                }
            }
            if (value && value.toString().length || jQuery.isArray(value)) {
                options = this.model.get("options");
                if (options && jQuery.isArray(options)) {
                    if (dataType === "boolean" && options.length) {
                        var valuesfortrue = [1, true, "1", "true"];
                        var valuesforFalse = [0, false, "0", "false"];
                        if (options[0].label === value) {
                            value = options[0].value;
                        }
                        if (options[1].label === value) {
                            value = options[1].value;
                        }
                        if (valuesfortrue.indexOf(options[0].value) > -1 &&
                            valuesfortrue.indexOf(value) > -1) {
                            data = {
                                value: "1",
                                label: options[0].label
                            }
                            this.setValue(data["value"]);
                            return;
                        }
                        if (valuesforFalse.indexOf(options[1].value) > -1 &&
                            valuesforFalse.indexOf(value) > -1) {
                            data = {
                                value: "0",
                                label: options[1].label
                            }
                            this.setValue(data["value"]);
                            return;
                        }
                    } else {
                        if (type === "checkgroup") {
                            var arrayDataValue = [], arrayDataLabel = [];
                            for (var i = 0; i < options.length; i += 1) {
                                options[i].selected = false;
                                if (value.indexOf(options[i].label) > -1) {
                                    options[i].selected = true;
                                    arrayDataLabel.push(options[i].label);
                                    arrayDataValue.push(options[i].value);
                                }
                            }
                            data = {
                                value: arrayDataValue,
                                label: arrayDataLabel
                            };
                            this.setValue(data["value"]);
                            return;
                        } else {
                            for (var i = 0; i < options.length; i += 1) {
                                if (options[i].label === value) {
                                    data = {
                                        label: options[i].label,
                                        value: options[i].value
                                    }
                                    existData = true;
                                    this.setValue(data["value"]);
                                    return;
                                    break;
                                }
                            }
                        }
                    }
                }
                if (!existData) {
                    if (type === "text" || type === "textarea" || type === "hidden" || type === "link" || type === "image" ||
                        type == "title" || type == "annotation" || type == "subtitle" || type === "button" || type === "submit" || "suggest") {
                        data = {
                            value: value,
                            label: value
                        };
                        this.setValue(data["value"]);
                        return;
                        existData = true;
                    }
                    if (type === "datetime") {
                        value = value.replace(/-/g, "/");
                        if (new Date(value).toString() !== "Invalid Date") {
                            data = {
                                value: value,
                                label: value
                            }
                            this.setValue(data["value"]);
                            return;
                        }
                        existData = true;
                    }
                }
                if (existData) {
                    this.updateValueControlAndData(data, type, dataType);
                }
            } else {
                if (this.model.get("type") === "annotation") {
                    this.$el.find(".pmdynaform-control-annotation span").text(value);
                    this.model.attributes.label = value;
                }
                if (this.model.get("type") === "button") {
                    this.$el.find("button[type='button'].btn-primary span").text(value);
                    this.model.attributes.label = value;
                }
                if (this.model.get("type") === "submit") {
                    this.$el.find("button[type='submit'] span").text(value);
                    this.model.attributes.label = value;
                }
                if (this.model.get("type") === "image") {
                    this.model.attributes.src = value;
                    this.$el.find("img").attr("src", value);
                }
            }
            return this;
        },
        updateValueControlAndData: function (data, type, dataType) {
            var i;
            if (this.tagControl instanceof jQuery && this.tagHiddenToLabel instanceof jQuery) {
                if (type !== "datetime") {
                    this.model.attributes.data = data;
                    this.model.attributes.value = data["value"];
                    if (this.model.attributes.hasOwnProperty("keyLabel")) {
                        this.model.attributes.keyLabel = data["label"];
                    }
                    if (this.model.attributes.hasOwnProperty("keyValue")) {
                        this.model.attributes.keyValue = data["value"];
                    }
                    if (type === "radio") {
                        this.tagControl.find("input[id='form\[" + this.model.get("id") + "\]'][value='" + data["value"] + "']").prop("checked", true);
                    } else if (type === "checkgroup" || type === "checkbox") {
                        this.tagControl.find("input[type='checkbox']").attr("checked", false);
                        for (i = 0; i < data["value"].length; i += 1) {
                            this.tagControl.find("input[id='form\[" + this.model.get("id") + "\][" + data["value"][i] + "]']").prop("checked", true);
                        }
                        this.model.attributes.labelsSelected = data["label"];
                    } else {
                        this.tagControl.val(data["value"]);
                    }
                    if (type === "checkgroup") {
                        this.tagHiddenToLabel.val(JSON.stringify(data["label"]));
                    } else {
                        if (type === "link" || type === "annotation" || type === "title" || type === "subtitle" ||
                            type === "button" || type === "submit") {
                            this.tagHiddenToLabel.html(data["label"]);
                        } else if (type === "image") {
                            this.setSrc(data["value"]);
                        } else {
                            this.tagHiddenToLabel.val(data["label"]);
                        }
                    }
                    if (this.validate) {
                        this.validate();
                    }
                } else {
                    this.$el.find("#datetime-container-control").data()["DateTimePicker"].date(new Date(data["value"]));
                    var label = this.$el.find("#datetime-container-control").data()["date"];
                    var value = this.formatData();
                    this.model.attributes.value = value;
                    this.model.attributes.data["value"] = value;
                    this.model.attributes.data["label"] = label;
                    this.model.attributes.keyLabel = label;
                    this.tagHiddenToLabel.val(this.model.get("data")["value"]);
                }
            }
            return this;
        },
        setValue : function () {
        },
        getInfo: function () {
            return this.model.toJSON();
        },
        getValue: function (row, col) {
            var data, val = null;
            if (row !== undefined && col !== undefined) {
                if ((row > 0 && col > 0) && row <= this.gridtable.length && col <= this.columnsModel.length) {
                    return this.gridtable[row - 1][col - 1].getValue();
                } else {
                    return null;
                }
            }
            if (this.model.getData !== undefined) {
                return this.model.getData()["value"];
            }
            return null;
        },
        setHref : function (value) {
        },
        getDataType : function () {
            return this.model.get("dataType") || null;
        },
        getControlType: function () {

        },
        verifyData: function () {
        },
        getData: function () {
            return this.model.getData();
        },
        getDataLabel: function () {
            return this.model.getKeyLabel();
        },
        getText: function (row, col) {
            if (row !== undefined && col !== undefined) {
                if (row <= this.gridtable.length && col <= this.columnsModel.length) {
                    if (this.gridtable[row - 1][col - 1].model.getKeyLabel) {
                        return this.gridtable[row - 1][col - 1].model.get("data")["label"];
                    }
                    return null;
                } else {
                    return null;
                }
            } else {
                if (this.model.getKeyLabel) {
                    if (this.model.get("type") === "link") {
                        return this.model.get("value");
                    }
                    if (this.model.get("type") === "image") {
                        return this.getSrc();
                    }
                    return this.model.get("data")["label"];
                } else {
                    return null;
                }
            }
        },
        disableValidation: function (col) {
            var type, i, j;
            var col = col;
            type = this.model.get("type");
            if (type === "grid") {
                if (col !== undefined) {
                    if (typeof col == "string") {
                        this.columnsModel.find(function (column, index) {
                            if (column.columnId === col) {
                                col = index + 1;
                                return;
                            }
                        })
                    }
                    if ((col > 0 && col <= this.columnsModel.length)) {
                        this.domTitleHeader[col - 1].find(".pmdynaform-field-required").hide();
                        for (i = 0; i < this.gridtable.length; i += 1) {
                            this.columnsModel[col - 1].enableValidate = false;
                            this.gridtable[i][col - 1].disableValidation();
                        }
                    } else {
                        return null;
                    }
                } else {
                    var i, j, row, cell;
                    for (i = 0; i < this.gridtable.length; i += 1) {
                        row = this.gridtable[i];
                        for (j = 0; j < row.length; j += 1) {
                            cell = row[j];
                            if (cell.model.get("enableValidate") !== undefined) {
                                cell.disableValidation();
                                this.columnsModel[j].enableValidate = false;
                                this.domTitleHeader[j].find(".pmdynaform-field-required").hide();
                            }
                        }
                    }
                }
            }
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (this.model.get("required")) {
                    this.$el.find(".pmdynaform-field-required").hide();
                }
                this.model.attributes.enableValidate = false;
            }
            return this;
        },
        enableValidation: function (col) {
            var type, i, j, col = col;
            type = this.model.get("type");
            if (type === "grid") {
                if (col !== undefined) {
                    if (typeof col == "string") {
                        this.columnsModel.find(function (column, index) {
                            if (column.columnId === col) {
                                col = index + 1;
                                return;
                            }
                        })
                    }
                    if (col > 0 && col <= this.columnsModel.length) {
                        this.domTitleHeader[col - 1].find(".pmdynaform-field-required").show();
                        for (i = 0; i < this.gridtable.length; i += 1) {
                            this.columnsModel[col - 1].enableValidate = true;
                            this.gridtable[i][col - 1].enableValidation();
                        }
                    } else {
                        return null;
                    }
                } else {
                    var i, j, row, cell;
                    for (i = 0; i < this.gridtable.length; i += 1) {
                        row = this.gridtable[i];
                        for (j = 0; j < row.length; j += 1) {
                            cell = row[j];
                            if (cell.model.get("enableValidate") !== undefined) {
                                cell.enableValidation();
                                this.columnsModel[j].enableValidate = true;
                                this.domTitleHeader[j].find(".pmdynaform-field-required").show();
                            }
                        }
                    }
                }
            }
            if (this.model.get("enableValidate") !== undefined) {
                this.model.attributes.enableValidate = true;
                if (this.model.get("group") === "form") {
                    this.$el.find(".pmdynaform-field-required").show();
                }
            }
            return this;
        },
        getControl: function (row, col) {
            var htmlControl = jQuery("");
            if (this.model.get("type") === "grid") {
                if (row !== undefined && col !== undefined) {
                    if ((row > 0 && col > 0) && row <= this.gridtable.length && col <= this.columnsModel.length) {
                        htmlControl = this.gridtable[row - 1][col - 1].getControl();
                    }
                }
            }
            if (this.model.get("type") === "text") {
                htmlControl = this.$el.find("input[type='text']");
            }
            if (this.model.get("type") === "textarea") {
                htmlControl = this.$el.find("textarea");
            }
            if (this.model.get("type") === "radio") {
                htmlControl = this.$el.find("input[type='radio']");
            }
            if (this.model.get("type") === "dropdown") {
                htmlControl = this.$el.find("select");
            }
            if (this.model.get("type") === "checkbox") {
                htmlControl = this.$el.find("input[type='checkbox']");
            }
            if (this.model.get("type") === "checkgroup") {
                htmlControl = this.$el.find("input[type='checkbox']");
            }
            if (this.model.get("type") === "datetime") {
                htmlControl = this.$el.find("input[type='text']");
            }
            if (this.model.get("type") === "suggest") {
                htmlControl = this.$el.find("input[type='suggest']");
            }
            if (this.model.get("type") === "hidden") {
                htmlControl = this.$el.find("input[type='hidden']").eq(0);
            }
            if (this.model.get("type") === "file") {
                htmlControl = this.$el.find("button").eq(0);
            }
            return htmlControl;
        },
        getLabelControl: function () {

        },
        getSummary: function (col) {
            var tag;
            var col = col;
            if (col !== undefined) {
                if (typeof col == "string") {
                    this.columnsModel.find(function (column, index) {
                        if (column.columnId === col) {
                            col = index + 1;
                            return;
                        }
                    });
                }
                if (col > 0 && col <= this.columnsModel.length) {
                    if (this.columnsModel[col - 1].operation) {
                        tag = this.$el.find("#" + this.columnsModel[col - 1].operation + "-" + this.model.get("name") + "-" + this.columnsModel[col - 1].name);
                    }
                    if (tag) {
                        return tag.val();
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }
        },
        getHref: function () {
            return this.model.get("href");
        },
        setFocus: function () {
            this.getControl().first().focus();
        },
        generateDataDependenField: function () {
            var i, parentDependents, data = {}, name;
            parentDependents = this.model.get("parentDependents");
            for (i = 0; i < parentDependents.length; i += 1) {
                if (parentDependents[i].get("group") === "grid") {
                    name = parentDependents[i].get("columnName");
                } else {
                    if (parentDependents[i].get("variable") && parentDependents[i].get("variable") !== ""){
                        name = parentDependents[i].get("variable");
                    }else{
                        name = parentDependents[i].get("id");
                    }
                }
                if (parentDependents[i].get("mode") === "view"){
                    data[name] = parentDependents[i].get("data")["value"];
                }else{
                    data[name] = parentDependents[i].get("value");
                }
            }
            return data;
        },
        onDependentHandler: function (target, datavalue) {
            var i, localOpt, remoteOptions;
            this.jsonData = this.generateDataDependenField();
            if (!_.isEmpty(this.jsonData)) {
                remoteOptions = this.executeQuery();
                this.mergeOptions(remoteOptions);
            }
            this.firstLoad = false;
            return this;
        },
        executeQuery: function (clicked) {
            var restClient, key, resp, prj, endpoint, url, data;
            data = this.preparePostData();
            prj = this.model.get("project");
            resp = prj.webServiceManager.executeQuery(data, this.model.get("variable") || "");
            return resp;
        },
        preparePostData: function () {
            var data;
            data = this.jsonData || {};
            if (this.model.get("group") === "grid") {
                data["field_id"] = this.model.get("columnName");
            } else {
                data["field_id"] = this.model.get("id");
            }
            if (this.model.get("form")) {
                if (this.model.get("form").model.get("form")) {
                    data["dyn_uid"] = this.model.get("form").model.get("form").model.get("id");
                } else {
                    data["dyn_uid"] = this.model.get("form").model.get("id");
                }
            }
            return data;
        },
        /*
         This function change the values in the field formula associated, use with formulas
         Render a new values in the field with formula
         */
        onFieldAssociatedHandler: function () {
            var i,
                fieldsAssoc = this.formulaFieldsAssociated;
            if (fieldsAssoc.length > 0) {
                for (i = 0; i < fieldsAssoc.length; i += 1) {
                    if (fieldsAssoc[i].model.get("formulator") instanceof PMDynaform.core.Formula) {
                        this.model.addFormulaTokenAssociated(fieldsAssoc[i].model.get("formulator"));
                        this.model.updateFormulaValueAssociated(fieldsAssoc[i]);
                    }
                }
            }
            return this;
        },
        runOnDependentHandler : function () {
            var i, dependents, dependent;
            dependents = this.model.get("dependents");
            if (_.isArray(dependents)){
                for (i = 0; i < this.model.get("dependents").length; i += 1) {
                    dependent = this.model.get("dependents")[i];
                    if (dependent.get("mode") === "view") {
                        if (dependent.get("originalType") !== "suggest") {
                            dependent.get("view").onDependentHandler();
                        }
                    } else {
                        if (dependent.get("type") !== "suggest") {
                            dependent.get("view").firstLoad = false;
                            dependent.get("view").onDependentHandler();
                        } else {
                            dependent.get("view").setValue("");
                        }
                    }
                }
            }
            return this;
        },
        setData: function (data) {
            var value, label;
            if (this.model.get("type") !== "submit" && this.model.get("type") !== "button" && this.model.get("type") !== "panel") {
                if (this.model.get("type") === "label") {
                    this.setValue(data);
                } else {
                    value = data["value"];
                    this.setValue(value);
                }
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Field", FieldView);
}());
(function () {
    var GridView = PMDynaform.view.Field.extend({
        block: true,
        template: _.template($("#tpl-grid").html()),
        templatePager: _.template($("#tpl-grid-pagination").html()),
        templateTotal: _.template($("#tpl-grid-totalcolumn").html()),
        templateEmptyGrid : _.template($("#tpl-grid-empty").html()),
        colSpanLabel: 3,
        colSpanControl: 9,
        gridtable: [],
        flagRow: 0,
        dom: [],
        row: [],
        cols: [],
        showPage: 1,
        items: [],
        numberRest: 0,
        rest: 0,
        priority: {
            file: 1,
            image: 2,
            radio: 3,
            checkbox: 4,
            textarea: 5,
            datetime: 6,
            dropdown: 7,
            text: 8,
            button: 9,
            link: 10,
            defect: 0
        },
        section: 1,
        titleHeader: [],
        indexResponsive: "3%",
        removeResponsive: "3%",
        thereArePriority: 0,
        columnsModel: [],
        domCarousel: null,
        tableBody: null,
        pageSize: null,
        paged: null,
        rowDataAdd: null,
        domTitleHeader: null,
        totalWidtRow: 0,
        colResponsiveTotalWidth: null,
        hiddenColumns: [],
        totalWidthStatic: 0,
        minCellWidth : 200,
        indexWidthStatic : 33,
        onDeleteRowCallback: function () {
        },
        onAddRowCallback: function () {
        },
        onBeforeAddRowCallback: function () {
        },
        onClickPageCallback: function () {
        },
        events: {
            "click .pmdynaform-grid-newitem": "onClickNew",
            "click .pagination li": "onClickPage"
        },
        requireVariableByField: [
            "text",
            "textarea",
            "checkbox",
            "radio",
            "dropdown",
            "datetime",
            "suggest",
            "link",
            "hidden",
            "label"
        ],
        factory: {},
        validDependentColumns: [],
        sqlColumns: [],
        initialize: function (options) {
            var factory = {
                    products: {
                        "text": {
                            model: PMDynaform.model.Text,
                            view: PMDynaform.view.Text
                        },
                        "textarea": {
                            model: PMDynaform.model.TextArea,
                            view: PMDynaform.view.TextArea
                        },
                        "checkbox": {
                            model: PMDynaform.model.CheckBox,
                            view: PMDynaform.view.CheckBox
                        },
                        "radio": {
                            model: PMDynaform.model.Radio,
                            view: PMDynaform.view.Radio
                        },
                        "dropdown": {
                            model: PMDynaform.model.Dropdown,
                            view: PMDynaform.view.Dropdown
                        },
                        "button": {
                            model: PMDynaform.model.Button,
                            view: PMDynaform.view.Button
                        },
                        "datetime": {
                            model: PMDynaform.model.Datetime,
                            view: PMDynaform.view.Datetime
                        },
                        "suggest": {
                            model: PMDynaform.model.Suggest,
                            view: PMDynaform.view.Suggest
                        },
                        "link": {
                            model: PMDynaform.model.Link,
                            view: PMDynaform.view.Link
                        },
                        "file": {
                            model: PMDynaform.model.File,
                            view: PMDynaform.view.File
                        },
                        "label": {
                            model: PMDynaform.model.Label,
                            view: PMDynaform.view.Label
                        },
                        "hidden": {
                            model: PMDynaform.model.Hidden,
                            view: PMDynaform.view.Hidden
                        }
                    },
                    defaultProduct: "text"
                },
                k,
                rows = parseInt(this.model.get("rows"), 10);
            this.pageSize = this.model.get("pageSize");
            this.validDependentColumns = ["dropdown", "suggest", "text", "textarea", "label"];
            this.paged = this.model.get("pager");
            this.colResponsiveTotalWidth = 0;
            this.items = [];
            this.row = [];
            this.dom = [];
            this.cols = [];
            this.showPage = 1;
            this.gridtable = [];
            this.titleHeader = [];
            this.checkColSpanResponsive();
            this.setFactory(factory);
            this.rowDataAdd = [];
            this.dom = [];
            this.makeColumnModels();
            this.model.attributes.titleHeader = this.titleHeader;

        },
        onClickNew: function (e) {
            var newItem;
            newItem = this.addRow(e);
            return this;
        },
        addRow: function (data) {
            var i, j, row, product, rowData, currentRows, flagRow;
            rowData = this.model.get("data");
            currentRows = this.model.get("rows");
            this.rowDataAdd = [];
            if (this.model.get("layout") === "static") {
                flagRow = this.tableBody.find(".pmdynaform-static").last().children().length;
                this.domCarousel = this.tableBody.find(".pmdynaform-static").last();
            } else {
                flagRow = this.tableBody.children().last().children().length;
                this.domCarousel = this.tableBody.children().last();
            }
            if (flagRow === this.pageSize || flagRow === 0) {
                this.block = true;
                this.section = Math.ceil(this.dom.length / this.pageSize) + 1;
                flagRow = 0;
            } else {
                this.block = false;
                this.section = Math.ceil(this.dom.length / this.pageSize);
            }
            this.onBeforeAddRowCallback(this, this.model.attributes.rows, this.rowDataAdd);
            if (data && jQuery.isArray(data) && data.length) {
                this.rowDataAdd = data;
            }
            row = this.createHTMLRow(currentRows, this.rowDataAdd, flagRow);
            this.model.attributes.rows = parseInt(currentRows + 1, 10);
            
            //new carousel container fix a in active mode
            if(this.model.get("rows") === 1){
                this.domCarousel.addClass('active');
            }
            this.model.setPaginationItems();
            this.createHTMLPager("add");

            this.model.attributes.gridFunctions.push(row.data);

            this.runningRowFormulator(row.view);

            for (j = 0; j < row.model.length; j += 1) {
                if (row.model[j].get("type") !== "label" && row.model[j].get("operation") && row.model[j].get("operation").trim().length) {
                    row.view[j].onChangeCallbackOperation();
                }
                if (row.model[j].get("type") == "label" && row.model[j].get("operation")) {
                    this.createHTMLTotal();
                }
            }
            if (typeof this.onAddRowCallback === "function") {
                this.onAddRowCallback(this.gridtable[currentRows], this, this.gridtable.length);
            }
            this.validateGrid();
            return this.gridtable[currentRows];
        },
        runningRowFormulator: function (row) {
            var fieldsAsocied;
            for (var i = 0; i < row.length; i += 1) {
                if (row[i].model.get("formula") && row[i].model.get("formula").trim().length) {
                    fieldsAsocied = row.filter(function (element) {
                        if (row[i].fieldValid.indexOf(element.model.get("id")) > -1) {
                            element.onFieldAssociatedHandler();
                        }
                    });
                }
            }
        },
        removeRow: function (row) {
            var currentRows = this.model.get("rows"),
                itemRemoved;

            itemRemoved = this.gridtable.splice(row, 1);
            this.dom.splice(row, 1);
            this.model.attributes.rows = parseInt(currentRows - 1, 10);
            return itemRemoved;
        },
        makeColumnModels: function () {
            var that = this,
                columns = this.model.get("columns"),
                data = this.model.get("data"),
                columnModel,
                suc,
                colSpanControl,
                factory = this.factory,
                size = this.gridtable.length,
                product,
                newNameField,
                newIdField,
                rowView = [],
                rowModel = [],
                productModel,
                variableEnabled,
                productBuilt,
                jsonFixed,
                mergeModel,
                newDependentFields,
                i;
            this.columnsModel = [];
            for (i = 0; i < columns.length; i += 1) {
                newNameField = "";
                mergeModel = columns[i];
                mergeModel.form = this.model.get("form") || null;
                if (mergeModel.mode && mergeModel.mode === "parent") {
                    mergeModel.mode = this.model.get("mode");
                }
                if ((mergeModel.originalType === "checkbox" || mergeModel.type === "checkbox" ) && mergeModel.mode === "view") {
                    mergeModel.mode = "disabled";
                    mergeModel.disabled = true;
                }
                if ((mergeModel.originalType === "checkbox" || mergeModel.type === "checkbox" ) && mergeModel.mode === "disabled") {
                    mergeModel.mode = "disabled";
                    mergeModel.disabled = true;
                }
                jsonFixed = new PMDynaform.core.TransformJSON({
                    parentMode: this.model.get("parentMode"),
                    field: mergeModel
                });
                if (jsonFixed.getJSON().type) {
                    product =   factory.products[jsonFixed.getJSON().type.toLowerCase()] ?
                        factory.products[jsonFixed.getJSON().type.toLowerCase()] : factory.products[factory.defaultProduct];
                } else {
                    product = factory.products[factory.defaultProduct];
                }
                colSpanControl = this.colSpanControlField(jsonFixed.getJSON().type, i);
                //variableEnabled = this.validateVariableField(mergeModel);
                columnModel = {
                    colSpanLabel: 4,
                    colSpanControl: (this.model.get("layout") === "form") ? 8:colSpanControl,
                    colSpan: colSpanControl,
                    label: mergeModel.title,
                    title: mergeModel.title,
                    layout: this.model.get("layout"),
                    width: "200px",
                    project: this.model.get("project"),
                    namespace: this.model.get("namespace"),
                    mode: this.model.get("mode"),
                    variable: (variableEnabled !== "NOT")? this.getVariable(mergeModel.var_uid) : null,
                    _extended: {
                        name: mergeModel.name || PMDynaform.core.Utils.generateName("radio"),
                        id: mergeModel.id || PMDynaform.core.Utils.generateID(),
                        dependentFields: mergeModel.dependentFields,
                        formula: mergeModel.formula || null
                    },
                    group: "grid",
                    columnName : mergeModel.name || PMDynaform.core.Utils.generateName("radio"),
                    columnId : mergeModel.id,
                    originalType : mergeModel.type,
                    product : product,
                    formula : mergeModel.formula || "",
                    operation : mergeModel.operation || "",
                    columnWidth : mergeModel.columnWidth || "",
                    defaultValue : mergeModel.defaultValue || "",
                    required : mergeModel.required || false,
                    hint : mergeModel.hint || "",
                    format: mergeModel.format || null,
                    sql : mergeModel.sql || "",
                    form : mergeModel.form || null,
                    options : mergeModel.options || [],
                    optionsSql : mergeModel.optionsSql || [],
                    defaultDate: mergeModel.defaultDate || null
                };
                jQuery.extend(true, columnModel, jsonFixed.getJSON());
                columnModel.row = this.gridtable.length;
                columnModel.col = i;
                if (this.model.get("layout") == "static"){
                    if (columnModel.columnWidth && jQuery.isNumeric(columnModel.columnWidth)){
                        var width = parseInt(columnModel.columnWidth);
                        this.totalWidtRow = this.totalWidtRow + width;
                    }else{
                        this.totalWidtRow = this.totalWidtRow + 200;
                    }
                }
                this.columnsModel.push(columnModel);
            }
            if (this.model.get("layout") == "responsive"){
                this.updateWidthResponsiveColumns();
            }

            return this;
        },
        updateWidthResponsiveColumns: function () {
            var i, totalWith = 0, width, undefinedWidth = [];
            if (this.columnsModel.length) {
                for (i = 0; i < this.columnsModel.length; i += 1) {
                    width = parseInt(this.columnsModel[i].columnWidth).toString();
                    if (width !== "NaN") {
                        if (totalWith + Number(width) < 94) {
                            totalWith = totalWith + Number(width);
                            this.columnsModel[i].columnWidth = Number(width) + "%";
                        } else {
                            if (94 - totalWith > 0) {
                                this.columnsModel[i].columnWidth = 94 - totalWith + "%";
                                totalWith = totalWith + 94 - totalWith;
                            } else {
                                this.columnsModel[i].columnWidth = 0 + "%";
                                undefinedWidth.push(this.columnsModel[i]);
                            }
                        }
                    } else {
                        this.columnsModel[i].columnWidth = 0 + "%";
                        undefinedWidth.push(this.columnsModel[i]);
                    }
                }
            }
            return this;
        },
        setValuesGridFunctions: function (field) {

            if (this.model.attributes.functions) {
                if (this.model.attributes.gridFunctions.length > 0) {
                    this.model.attributes.gridFunctions[field.row][field.col] = isNaN(parseFloat(field.data)) ? 0 : parseFloat(field.data);
                    this.model.applyFunction();
                }
            }
            return this;
        },
        getVariable: function (var_uid) {
            var i,
                varSelected,
                variables = this.model.attributes.variables;

            loop_variables:
                for (i = 0; i < variables.length; i += 1) {
                    if (variables[i] && variables[i].var_uid === var_uid) {
                        varSelected = variables[i];
                        break loop_variables;
                    }
                }

            return varSelected;
        },
        checkColSpanResponsive: function () {
            var i,
                columns = this.model.get("columns"),
                thereArePriority = 0,
                layout = this.model.get("layout");

            if (layout === "responsive" || layout === "form") {
                this.numberRest = 10 % columns.length;

                if (this.numberRest > 0) {
                    for (i = 0; i < columns.length; i += 1) {
                        if (this.priority[columns[i].type] <= 6) {
                            thereArePriority += 1;
                        }
                    }
                }
                this.thereArePriority = thereArePriority;
            }
            return this;
        },
        colSpanControlField: function (type, indexColumn) {
            var rest,
                itemsLength = this.model.get("columns").length,
                layout = this.model.get("layout"),
                defaultColSpan = 8;
            if (this.numberRest > 0) {
                if (this.priority[type] <= 6 && this.thereArePriority > 0) {
                    defaultColSpan = parseInt(10 / itemsLength) + 1;
                    this.numberRest -= 1;
                    this.thereArePriority -= 1;
                } else {
                    if (this.numberRest >= parseInt(itemsLength - indexColumn)) {
                        defaultColSpan = parseInt(10 / itemsLength) + 1;
                        this.numberRest -= 1;
                    } else {
                        defaultColSpan = parseInt(10 / itemsLength);
                    }
                }
            } else {
                defaultColSpan = parseInt(10 / itemsLength);
            }

            return defaultColSpan;
        },
        colSpanControlFieldResponsive: function () {
            var columnWidth = 100, res;
            res = parseInt(this.indexResponsive) + parseInt(this.removeResponsive);
            columnWidth = parseInt((columnWidth - res) / (this.columnsModel.length - this.model.get("countHiddenControl")));
            return columnWidth - 1;
        },
        /*
         form[grid1][1][nombre]
         form[grid1][2][nombre]
         */
        changeIdField: function (nameform, row, column) {
            return "[" + nameform + "][" + row + "][" + column + "]";
        },
        changeNameField: function (nameform, row, column) {
            return "[" + nameform + "][" + row + "][" + column + "]";
        },
        updateNameFields: function (rowView) {
            var i,
                j,
                k,
                l,
                label,
                formulaFields = "",
                dependentFields,
                newDependentFields = [];
            for (i = 0; i < rowView.length; i += 1) {
                formulaFields = rowView[i].model.get("_extended").formula;
                if (typeof formulaFields === "string") {
                    for (l = 0; l < rowView.length; l += 1) {
                        if (i !== l) {
                            formulaFields = formulaFields.replace(new RegExp(rowView[l].model.get("_extended").id, 'g'), rowView[l].model.get("id"));
                            rowView[i].model.attributes.formula = formulaFields;
                            rowView[i].model.attributes.formulator.data = formulaFields;
                        }
                    }
                }
            }
            return newDependentFields;
        },
        setFactory: function (factory) {
            this.factory = factory;
            return this;
        },
        validate: function (event) {
            var i,
                k,
                gridpanel,
                fields,
                row = [],
                validGrid = true,
                gridpanel = this.gridtable,
                itemCell;
            if(!this.validateGrid()){
                return this;
            }
            for (i = 0; i < gridpanel.length; i += 1) {
                row = [];
                for (k = 0; k < gridpanel[i].length; k += 1) {
                    if (gridpanel[i][k].validate) {
                        if (event) {
                            gridpanel[i][k].validate(event);
                            if (!gridpanel[i][k].model.get("valid")) {
                                if (itemCell === undefined) {
                                    itemCell = gridpanel[i][k];
                                }
                                validGrid = gridpanel[i][k].model.get("valid");
                                this.model.set("valid", validGrid);
                                validGrid = false;
                            }
                        } else {
                            gridpanel[i][k].validate();
                            validGrid = gridpanel[i][k].model.get("valid");
                            if (!validGrid) {
                                gridpanel[i][k].setFocus();
                                this.model.attributes.valid = false;
                                return false;
                            }
                        }
                    }
                }
            }
            if (itemCell) {
                itemCell.setFocus();
            }
            this.model.set("valid", validGrid);
            return validGrid;
        },
        onRemoveRow: function (event) {
            var rowNumber, itemRemoved;
            if (event) {
                rowNumber = $(event.target).data("row");
                this.deleteRow(rowNumber, event);
            }

            return this;
        },
        updateGridFunctions: function (rows, index) {
            var removed;
            removed = this.model.attributes.gridFunctions.splice(index - 1, 1);
            this.model.applyFunction()
            this.createHTMLTotal();
            return this;
        },
        deleteRow: function (index, event) {
            var itemRemoved, table, partyfloat, showSection = 1, showPage, removedSection, initPage;
            showPage = Math.ceil(index / this.pageSize);
            jQuery(this.dom[index - 1]).remove();
            if (index > 0) {
                itemRemoved = this.removeRow(index - 1);
            } else {
                return this;
            }
            this.updateGridFunctions(itemRemoved, index);
            this.updatePropertiesCell(index - 1);
            if (this.model.attributes.pager) {
                this.block = true;
                this.flagRow = 0;
                this.section = 0;
                if (this.model.get("layout") === "static") {
                    for (var i = showPage; i < this.tableBody.find(".pmdynaform-static").length; i += 1) {
                        if (this.tableBody.find(".pmdynaform-static").eq(i).children().length) {
                            this.tableBody.find(".pmdynaform-static").eq(i - 1).append(this.tableBody.find(".pmdynaform-static").eq(i).children()[0])
                        }
                    }
                    if (this.tableBody.find(".pmdynaform-static").eq(i - 1).children().length === 0) {
                        removedSection = this.tableBody.find(".pmdynaform-static").eq(i - 1).remove();
                        if (i == 1) {
                            initPage = true;
                        }
                    }
                    if (!this.tableBody.find(".pmdynaform-static").eq(showPage - 1).children().length) {
                        if (this.tableBody.find(".pmdynaform-static").eq(showPage - 2).length) {
                            this.tableBody.find(".pmdynaform-static").eq(showPage - 2).addClass("active");
                        }
                    }
                } else {
                    for (var i = showPage; i < this.tableBody.children().length; i += 1) {
                        if (this.tableBody.children().eq(i).children().length) {
                            this.tableBody.children().eq(i - 1).append(this.tableBody.children().eq(i).children()[0])
                        }
                    }
                    if (this.tableBody.children().eq(i - 1).children().length === 0) {
                        removedSection = this.tableBody.children().eq(i - 1).remove();
                    }
                    if (!this.tableBody.children().eq(showPage - 1).children().length) {
                        if (this.tableBody.children().eq(showPage - 2).length) {
                            this.tableBody.children().eq(showPage - 2).addClass("active");
                        }
                    }
                }
                this.model.setPaginationItems();
                this.createHTMLPager("remove");
                if (removedSection && removedSection.length) {
                    this.showPage = showPage - 1;
                    if (initPage) {
                        this.showPage = 1;
                    }
                } else {
                    this.showPage = showPage;
                }
            }
            if (typeof this.onDeleteRowCallback === "function") {
                this.onDeleteRowCallback(this, itemRemoved, index);
            }
            this.validateGrid();
            return this;
        },
        updatePropertiesCell: function (index) {
            var i, j, cell, cells, row, rows, element, name, control, container, idContainer,
                hiddenControls, type, nameHiddeControl = "", nameControl = "", idcontrol;
            rows = this.gridtable;
            for (i = index; i < rows.length; i += 1) {
                row = $(this.dom[i]);
                row.find(".index-row span").text(i + 1);
                row.find(".remove-row button").data("row", i + 1);
                cells = rows[i];
                if (cells) {
                    for (j = 0; j < cells.length; j += 1) {
                        cell = cells[j];
                        cell.model.attributes.row = i;
                        idContainer = this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
                        element = cell.$el;
                        container = element.find(".pmdynaform-" + cell.model.get("mode") + "-" + cell.model.get("type"));
                        container.attr({
                            "id": idContainer
                        });
                        type = cell.model.get("type");
                        switch (type) {
                            case "checkbox":
                                control = $(cell.$el.find("input[type='checkbox']"));
                                hiddenControls = element.find("input[type='hidden']");
                                if ( this.model.get("variable") !== "") {
                                    nameControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                                    nameHiddeControl = nameControl.substring(0, nameControl.length - 1).concat("_label]");
                                }
                                idcontrol = "form" + this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
                                control.attr({
                                    name: nameControl,
                                    id: idcontrol
                                });
                                hiddenControls.attr({
                                    name: nameHiddeControl,
                                    id: idcontrol
                                });
                                break;
                            case "suggest":
                                control = $(cell.$el.find(".form-control"));
                                hiddenControls = element.find("input[type='hidden']");
                                if (this.model.get("variable")) {
                                    nameControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                                    nameControl = nameControl.substring(0, nameControl.length - 1).concat("_label]");
                                    nameHiddeControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                                } else {
                                    nameControl = "";
                                    nameHiddeControl = ""
                                }
                                idcontrol = "form" + this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
                                control.attr({
                                    name: nameControl,
                                    id: idcontrol
                                });
                                hiddenControls.attr({
                                    name: nameHiddeControl,
                                    id: idcontrol
                                });
                                break;
                            case "label":
                                hiddenControls = element.find("input[type='hidden']");
                                if (this.model.get("variable") !== "") {
                                    nameControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                                    nameHiddeControl = nameControl.substring(0, nameControl.length - 1).concat("_label]");
                                } else {
                                    nameControl = "";
                                    nameHiddeControl = "";
                                }
                                idcontrol = "form" + this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
                                hiddenControls.eq(0).attr({
                                    name: nameControl,
                                    id: idcontrol
                                });
                                hiddenControls.eq(1).attr({
                                    name: nameHiddeControl,
                                    id: idcontrol
                                });
                                break;
                            case "file":
                                this.updateFileCell(cell, i, j);
                                // TODO need refactor
                                break;
                            default :
                                control = $(cell.$el.find(".form-control"));
                                hiddenControls = element.find("input[type='hidden']");
                                if (this.model.get("variable") !== "") {
                                    nameControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                                    nameHiddeControl = nameControl.substring(0, nameControl.length - 1).concat("_label]");
                                } else {
                                    nameControl = "";
                                    nameHiddeControl = "";
                                }
                                idcontrol = "form" + this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
                                control.attr({
                                    name: nameControl,
                                    id: idcontrol
                                });
                                hiddenControls.attr({
                                    name: nameHiddeControl,
                                    id: idcontrol
                                });
                                break;
                        }
                    }
                }
            }
            return this;
        },
        /**
         * Updates File cell when a row has been removed
         *
         * @param cell
         */
        updateFileCell: function (cell, i, j) {
            var element = cell.$el,
                control = $(cell.$el.find(".form-control")),
                hiddenControls = element.find("input[type='hidden']"),
                fileControls = element.find("input[type='file']"),
                nameControl,
                nameHiddeControl,
                idcontrol;

            if (this.model.get("variable") !== "") {
                nameControl = "form" + this.changeIdField(this.model.get("name"), i + 1, cell.model.get("columnName"));
                nameHiddeControl = nameControl.substring(0, nameControl.length - 1).concat("_label]");

            } else {
                nameControl = "";
                nameHiddeControl = "";
            }
            idcontrol = "form" + this.changeIdField(this.model.get("id"), i + 1, this.columnsModel[j].id);
            control.attr({
                name: nameControl,
                id: idcontrol
            });
            hiddenControls.attr({
                name: nameHiddeControl,
                id: idcontrol
            });

            fileControls.attr({
                name: nameControl,
                id: idcontrol
            });
        },

        onClickPage: function (event) {
            var objData = $(event.currentTarget.children).data(),
                parentNode = $(event.currentTarget).parent();

            /************************** pagination rotate ************************************/
            var nextItem = $('<li class="toNext"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">...</a></li>');
            var prevItem = $('<li class="toPrev"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">...</a></li>');

            if (!$.isNumeric($(event.currentTarget).find("a").text())) {
                var $currentItem = parentNode.find('li.active');
                if ($(event.currentTarget).hasClass("toNextItem")) {
                    if ($currentItem.hasClass("toNext")) {
                        this.onClickNextSection($currentItem, parentNode, nextItem, prevItem);
                    } else {
                        if ($currentItem.next().attr("class") !== "toNextItem") {
                            parentNode.find('li').removeClass('active');
                            $currentItem.next().addClass("active");
                            $currentItem.next().find("a:eq(0)").trigger("click");
                        }
                    }
                }
                if ($(event.currentTarget).hasClass("toPrevItem")) {
                    if ($currentItem.hasClass("toPrev")) {
                        this.onClickPrevSection($currentItem, parentNode, nextItem, prevItem);
                    } else {
                        if ($currentItem.prev().attr("class") !== "toPrevItem") {
                            parentNode.find('li').removeClass('active');
                            $currentItem.prev().addClass("active");
                            $currentItem.prev().find("a:eq(0)").trigger("click");
                        }
                    }
                }
                if ($(event.currentTarget).hasClass("toLast")) {
                    var lastPosition = parseInt(parentNode.children().length) - 3;
                    if (!this.model.get("paginationRotate")) {
                        this.tableBody.find(".active").removeClass("active");
                        var e = parentNode.find('li:eq(' + lastPosition + ')');
                        if (parentNode.find("li.active a").text() != Math.ceil(this.gridtable.length / this.pageSize)) {
                            this.onClickNextSection(e, parentNode, nextItem, prevItem);
                        }
                        this.tableBody.children().last().addClass("active")
                    }
                    parentNode.find('.active').removeClass('active');
                    lastPosition = parseInt(parentNode.children().length) - 3;
                    parentNode.find('li:eq(' + lastPosition + ')').addClass("active");
                    return false;
                }
                if ($(event.currentTarget).hasClass("toFirst")) {
                    if (!this.model.get("paginationRotate")) {
                        var e = parentNode.find('li:eq(3)');
                        if (parentNode.find("li.active a").text() !== "1") {
                            this.onClickPrevSection(e, parentNode, nextItem, prevItem);
                        }
                    }
                    parentNode.find('li').removeClass('active');
                    parentNode.find('li:eq(2)').addClass("active");
                }
                if ($(event.currentTarget).hasClass("toNext")) {
                    var i, index = Number($(event.currentTarget).prev().text().trim());
                    var nextItemElement;
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').removeClass("showItem");
                    $(parentNode).find('.toPrev').remove();
                    $(parentNode).find('.toNext').remove();
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').css({
                        display: "none"
                    });
                    if (((this.gridtable.length / this.pageSize) - index) > 5) {
                        for (i = index; i < index + 5; i += 1) {
                            if (!nextItemElement) {
                                nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                            }
                            $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                                display: ""
                            }).addClass("showItem");
                        }
                        $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).before(nextItem);
                    } else {
                        for (i = index; i < (this.gridtable.length / this.pageSize); i += 1) {
                            if (!nextItemElement) {
                                nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                            }
                            $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                                display: ""
                            }).addClass("showItem");
                        }
                    }
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(index - 1).after(prevItem);
                    if (nextItemElement) {
                        nextItemElement.find("a").trigger("click");
                    }
                }
                if ($(event.currentTarget).hasClass("toPrev")) {
                    var i, index = Number($(event.currentTarget).prev().text().trim());
                    var nextItemElement;
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').removeClass("showItem");
                    $(parentNode).find('.toPrev').remove();
                    $(parentNode).find('.toNext').remove();
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').css({
                        display: "none"
                    });

                    if (index - 5 != 0) {
                        if (index - 5 > -1) {
                            for (i = index - 1; i >= index - 5; i -= 1) {
                                if (!nextItemElement) {
                                    nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                                }
                                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                                    display: ""
                                }).addClass("showItem");
                            }
                            nextItemElement.after(nextItem);
                            $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).after(prevItem);
                            if (nextItemElement) {
                                nextItemElement.find("a").trigger("click");
                            }
                        } else {

                            for (i = 5 - 1; i >= 0; i -= 1) {
                                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                                    display: ""
                                }).addClass("showItem");
                            }
                            if ($(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(index - 1).length) {
                                nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(index - 1);
                            }
                            $(parentNode).find(".showItem").last().after(prevItem);
                            if (nextItemElement) {
                                nextItemElement.find("a").trigger("click");
                            }
                        }
                    } else {
                        for (i = index - 1; i > -1; i -= 1) {
                            if (!nextItemElement) {
                                nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                            }
                            $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                                display: ""
                            }).addClass("showItem");
                        }
                        nextItemElement.after(nextItem);
                        if (nextItemElement) {
                            nextItemElement.find("a").trigger("click");
                        }
                    }
                }
            } else {
                parentNode.children().removeClass('active');
                $(event.currentTarget).addClass("active");
            }
            return this;
        },
        onClickNextSection: function (currentTarget, parentNode, nextItem, prevItem) {
            if ($(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').length - 1 > 5) {
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').removeClass("showItem");
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').css("display", "none");
                $(parentNode).find('.toPrev').remove();
                $(parentNode).find('.toNext').remove();
                var i, nextItemElement, length = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').length - 1;
                for (i = length; i > length - 5; i -= 1) {
                    if (!nextItemElement) {
                        nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                    }
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                        display: ""
                    }).addClass("showItem");
                }
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).after(prevItem);
                if (nextItemElement) {
                    nextItemElement.find("a").trigger("click");
                }
            }
        },
        onClickPrevSection: function (currentTarget, parentNode, nextItem, prevItem) {
            if ($(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').length - 1 > 5) {
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').removeClass("showItem");
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').css("display", "none");
                $(parentNode).find('.toPrev').remove();
                $(parentNode).find('.toNext').remove();
                var i, nextItemElement;
                for (i = 0; i < 5; i += 1) {
                    if (!nextItemElement) {
                        nextItemElement = $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i);
                    }
                    $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).css({
                        display: ""
                    }).addClass("showItem");
                }
                $(parentNode).children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(i).before(nextItem);
                if (nextItemElement) {
                    nextItemElement.find("a").trigger("click");
                }
            }
        },

        refreshButtonsGrid: function () {
            var i,
                tdNumber,
                buttonRemove,
                trs = this.dom,
                element;

            for (i = 0; i < trs.length; i += 1) {
                element = $(trs[i]).html();
                tdNumber = this.createRowNumber(i + 1);
                buttonRemove = this.createRemoveButton(i);
                $(trs[i].firstChild).replaceWith(tdNumber);
                $(trs[i].lastChild).replaceWith(buttonRemove);
            }

            return this;
        },
        createRowNumber: function (index) {
            var tdNumber = document.createElement("div"),
                formgroup = document.createElement("div"),
                divNumber = document.createElement("div"),
                spanNumber = document.createElement("span"),
                label = document.createElement("label"),
                labelSpan = document.createElement("span"),
                containerField = document.createElement("div"),
                layout = this.model.get("layout"),
                tdRemove;
            if (layout === "form") {
                tdNumber.className = "col-xs-12 col-sm-1 col-md-1 col-lg-1"
            }
            if (layout === "static") {
                tdNumber.className = "pmdynaform-grid-field-static index";
                tdNumber.style.width = "33px";
            }
            if (layout === "responsive") {
                tdNumber.width = this.indexResponsive;
                tdNumber.style.display = "inline-block";
            }
            label.className = "hidden-lg hidden-md hidden-sm visible-xs control-label col-xs-4";
            labelSpan.innerHTML = "Nro";
            label.appendChild(labelSpan);

            divNumber.className = "col-xs-4 col-sm-12 col-md-12 col-lg-12 pmdynaform-grid-label rowIndex";
            spanNumber.innerHTML = index;
            divNumber.appendChild(spanNumber);
            if (layout === "form") {
                containerField.appendChild(label);

                tdRemove = this.createRemoveButton(index - 1);
                tdRemove.className = "col-xs-1 visible-xs hidden-sm hidden-md hidden-lg remove-row-form";
                tdRemove.style.cssText = "float: right; margin-right: 15%";
                containerField.appendChild(tdRemove);
            }
            containerField.appendChild(divNumber);
            formgroup.className = "row form-group";
            formgroup.appendChild(containerField);
            tdNumber.appendChild(formgroup);
            $(tdNumber).addClass("index-row");
            return tdNumber;
        },
        createRemoveButton: function (index) {
            var that = this,
                tdRemove,
                buttonRemove,
                layout = this.model.get("layout");
            tdRemove = document.createElement("div");
            if (layout === "form") {
                tdRemove.className = "pmdynaform-grid-removerow hidden-xs col-xs-1 col-sm-1 col-md-1 col-lg-1";
            }
            if (layout === "static") {
                tdRemove.className = "pmdynaform-grid-removerow-static";
            }
            if (layout === "responsive") {
                tdRemove.className = "pmdynaform-grid-removerow-responsive";
                tdRemove.style.display = "inline-block";
            }
            buttonRemove = document.createElement("button");

            buttonRemove.className = "glyphicon glyphicon-trash btn btn-danger btn-sm";
            buttonRemove.setAttribute("data-row", index);

            $(buttonRemove).data("row", index);
            $(buttonRemove).on("click", function (event) {
                that.onRemoveRow(event);
            });

            tdRemove.appendChild(buttonRemove);
            return tdRemove;
        },
        createHTMLTitle: function () {

            var k,
                dom,
                title,
                td,
                colSpan,
                label,
                layout = this.model.get("layout"),
                content,
                hint;
            this.domTitleHeader = [];

            dom = this.$el.find(".pmdynaform-grid-thead");
            td = document.createElement("div");
            content = document.createElement("div");
            label = document.createElement("span");

            if (layout === "static") {
                dom.addClass("pmdynaform-grid-thead-static");
                td.className = "pmdynaform-grid-field-static wildcard";
                td.style.minWidth = "33px";
            }
            if (layout === "form") {
                td.className = "col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center wildcard";
            }

            if (layout === "responsive") {
                //For the case: responsive and form
                td.className = "text-center wildcard";
                td.style.display = "inline-block";
                td.style.width = this.indexResponsive;
            }
            td.appendChild(label);
            dom.append(td);
            for (k = 0; k < this.columnsModel.length; k += 1) {
                if (this.columnsModel[k].type !== "hidden") {
                    colSpan = this.columnsModel[k].colSpan;
                    title = this.columnsModel[k].title;
                    td = document.createElement("div");
                    label = document.createElement("span");
                    label.className = "title-column";
                    this.checkColSpanResponsive();

                    if (layout !== "responsive") {
                        colSpan = this.colSpanControlField(this.columnsModel, this.columnsModel[k].type, k);
                        td = this._createHtmlCell(this.columnsModel[k].type, colSpan, k);
                    }
                    label.innerHTML = title;
                    label.style.fontWeight = "bold";
                    label.style.maginLeft = "2px";
                    $(label).css({
                        "text-overflow": "ellipsis",
                        "white-space": "nowrap",
                        "overflow": "hidden",
                        "display": "inline-block",
                        "width": "80%",
                        "text-align": "center"
                    });
                    if (layout === "responsive") {
                        $(label).css({
                            width: "70%",
                            display: "inline-block"
                        });
                        $(td).css({
                            width: this.colSpanControlFieldResponsive(this.columnsModel) + "%",
                            display: "inline-block"
                        });
                    }
                    if (layout === "static") {
                        if (this.columnsModel[k]["columnWidth"] && Number(this.columnsModel[k]["columnWidth"]).toString() !== "NaN") {
                            $(td).css({
                                "min-width": parseInt(this.columnsModel[k]["columnWidth"])
                            });
                            $(label).css({
                                "width": parseInt(this.columnsModel[k]["columnWidth"]) - 40
                            });
                        } else {
                            $(td).css({
                                "min-width": "200px"
                            });
                            $(label).css({
                                "width": "160px"
                            });
                        }
                    }
                    if (layout === "responsive") {
                        $(td).css({
                            "width": this.columnsModel[k].columnWidth
                        });
                    }
                    label.title = title;
                    if (this.columnsModel[k].required) {
                        if (parseInt(this.columnsModel[k].columnWidth) === 0) {
                            label.appendChild($("<span class='pmdynaform-field-required'>*</span>")[0]);
                            label.style.display = "none";
                        } else {
                            label.appendChild($("<span class='pmdynaform-field-required'>*</span>")[0]);
                        }
                    }

                    hint = document.createElement("span");

                    td.appendChild(label);

                    if (this.columnsModel[k].hint && this.columnsModel[k].hint.trim().length) {
                        hint = document.createElement("span");
                        hint.className = "glyphicon glyphicon-info-sign";
                        hint.setAttribute("data-toggle", "tooltip");
                        hint.setAttribute("data-container", "body");
                        hint.setAttribute("data-placement", "bottom");
                        hint.setAttribute("data-original-title", this.columnsModel[k]["hint"]);
                        hint.style.float = "inherit";
                        $(hint).tooltip().click(function (e) {
                            $(this).tooltip('toggle');
                        });
                        if (this.model.get("columns").length < 6 && (layout == "responsive" || layout == "form")) {
                            td.appendChild(hint);
                        } else {
                            if (layout === "static") {
                                td.appendChild(hint);
                            } else {
                                label.setAttribute("data-toggle", "tooltip");
                                label.setAttribute("data-container", "body");
                                label.setAttribute("data-placement", "bottom");
                                label.setAttribute("data-original-title", this.columnsModel[k]["hint"]);
                            }
                        }
                    }
                    dom.append(td);
                    this.domTitleHeader.push($(td));
                } else {
                    this.domTitleHeader.push($("<span></span>"));
                }
            }
            if (layout === "static") {
                var spaceDelete = document.createElement("div");
                spaceDelete.className = "pmdynaform-grid-removerow-static";
                $(spaceDelete).css({
                    "min-width": 38
                });
                dom.append(spaceDelete);
            }
            return this;
        },
        createHTMLPager: function (behavior) {
            var i,
                that = this,
                htmlPager,
                pagerContainer,
                activeIndex,
                pager,
                pagerItems,
                lastPager,
                elementList,
                ellipsis,
                newItem;
            pagerContainer = this.$el.find(".pmdynaform-grid-pagination");
            activeIndex = this.$el.find(".pagination").find("li.active");
            if (activeIndex.length) {
                htmlPager = pagerContainer.children();
                pagerItems = htmlPager.children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').not('.toPrev').not('.toNext');
                if (behavior == "add") {
                    if (Math.ceil(this.gridtable.length / this.pageSize) > pagerItems.length) {
                        elementList = jQuery("<li class = 'sec_" +
                            Math.ceil(this.gridtable.length / 5) + "'><a data-target='#" + this.model.get("id") + "-body' data-slide-to='" +
                            (Math.ceil(this.gridtable.length / this.pageSize) - 1) + "' href=''>" + Math.ceil(this.gridtable.length / this.pageSize) + "</a></li>");
                        elementList.css({display: "none"});
                        if (htmlPager.find(".toNext").length == 0 && (Number(elementList.text().trim()) > 5)) {
                            ellipsis = jQuery('<li class="toNext"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">...</a></li>');
                            htmlPager.find(".showItem").last().after(ellipsis);
                            ellipsis.after(elementList);
                        } else {
                            if (htmlPager.find(".toNext").length) {
                                htmlPager.find(".toNextItem").before(elementList);
                            } else {
                                elementList.css({
                                    display: ""
                                }).addClass("showItem");
                                htmlPager.find(".showItem").last().after(elementList);
                            }
                        }
                    }
                }
                if (behavior == "remove") {
                    var itemRemoved;
                    if (Math.ceil(this.gridtable.length / this.pageSize) > 0 && Math.ceil(this.gridtable.length / this.pageSize) < pagerItems.length) {
                        if (pagerItems.eq(pagerItems.length - 1).hasClass("active")) {
                            pagerItems.eq(pagerItems.length - 1).prev().addClass("active");
                        }
                        itemRemoved = pagerItems.eq(pagerItems.length - 1).remove();
                        if (htmlPager.find(".active").hasClass("toPrev")) {
                            htmlPager.find(".active").trigger("click");
                            htmlPager.find(".toNext").remove();
                        }
                        if (htmlPager.find(".active").text().trim() == 5) {
                            htmlPager.find(".toPrev").remove();
                            htmlPager.children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').css({
                                display: ""
                            });
                        }
                        if (Number(htmlPager.find(".showItem").last().text().trim()) <= (this.gridtable.length / 5)) {
                            htmlPager.find(".toNext").remove();
                        }
                    }
                }
            } else {
                pager = this.templatePager({
                    id: this.model.get("id") + "-body",
                    paginationItems: this.model.get("paginationItems"),
                    paginationRotate: this.model.get("paginationRotate"),
                    itemsSections: Math.ceil(this.dom.length / this.pageSize)
                });
                htmlPager = $(pager);
                pagerItems = htmlPager.children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem');
                htmlPager.children().not(":first").not(':last').not('.toPrevItem').not('.toNextItem').eq(0).addClass("active");
                if (Math.ceil(this.gridtable.length / 5) > 5) {
                    pagerItems.eq(5 - 1).nextAll().not(':last').not('.toNextItem').css({display: "none"});
                    pagerItems.eq(5).prevAll().not(".toFirst").addClass("showItem");
                    pagerItems.eq(5).before('<li class="toNext"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">...</a></li>');
                } else {
                    pagerItems.addClass("showItem");
                }
                htmlPager.children('li:first').after('<li class="toPrevItem"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">&lsaquo;</a></li>');
                htmlPager.children('li:last').before('<li class="toNextItem"><a data-target="#' + this.model.get("id") + '" data-rotate="' + this.model.get("paginationRotate") + '" href="javascript:void(0)">&rsaquo;</a></li>');
                pagerContainer.append(htmlPager);
            }

            return this;
        },
        createHTMLTotal: function () {
            var k,
                dom,
                title,
                td,
                operation,
                colSpan,
                label,
                result,
                icon,
                hint,
                containerStaticGrid,
                totalrow = this.model.get("totalrow"),
                layout = this.model.get("layout"),
                iconTotal = {
                    sum: "&#8721;",
                    avg: "&#935;",
                    other: "&#989;"
                },
                that = this;
            if (totalrow.length) {
                dom = this.$el.find(".pmdynaform-grid-functions");
                dom.children().remove();
                td = document.createElement("div");
                label = document.createElement("span");

                if (layout === "static") {
                    dom.addClass("pmdynaform-grid-thead-static");
                    if (this.$el.find(".pmdynaform-grid-static").length) {
                        dom.css({
                            width: this.totalWidtRow + 77
                        });
                    }
                } else {
                    //For the case: responsive and form
                    td.className = "col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center";
                }
                //label.innerHTML = "Nro";
                td.appendChild(label);
                dom.append(td);
                if (layout === "responsive") {
                    td.style.width = this.indexResponsive;
                }else{
                    $(td).css({
                        width : this.indexWidthStatic,
                        display : "inline-block"
                    });
                }
                if (this.gridtable[0]) {
                    for (k = 0; k < this.gridtable[0].length; k += 1) {
                        colSpan = this.gridtable[0][k].model.get("colSpan");
                        title = (totalrow[k] === null || totalrow[k] === undefined) ?  '' : totalrow[k];
                        td = document.createElement("div");
                        if (this.hiddenColumns.indexOf(k + 1) > -1) {
                            td.style.display = "none";
                        }
                        label = document.createElement("span");
                        result = document.createElement("input");
                        result.style.width = "50%";
                        result.disabled = true;
                        if (layout === "form") {
                            this.checkColSpanResponsive();
                            colSpan = this.colSpanControlField(this.gridtable[0], this.gridtable[0][k].model.get("type"), k);
                            td.className = "col-xs-12 col-sm-" + colSpan + " col-md-" + colSpan + " col-lg-" + colSpan;
                        } else {
                            if (layout === "static") {
                                $(td).css({
                                    display : "inline-block"
                                });
                                if (this.gridtable[0][k].model.get("columnWidth") && Number(this.gridtable[0][k].model.get("columnWidth")).toString() !== "NaN") {
                                    $(td).css({
                                        "width": parseInt(this.gridtable[0][k].model.get("columnWidth"))
                                    });
                                    this.gridtable[0][k].$el.css({
                                        "width": parseInt(this.gridtable[0][k].model.get("columnWidth"))
                                    });
                                } else {
                                    if(this.gridtable[0][k].model.get("type") !== "hidden"){
                                        $(td).css({
                                            "min-width": this.minCellWidth
                                        });
                                        this.gridtable[0][k].$el.css({
                                            "width": this.minCellWidth
                                        });
                                    }
                                }
                                td.className = "pmdynaform-grid-field-static field-operation-result";
                            } else {
                                if (parseInt(this.gridtable[0][k].model.get("columnWidth")) !== 0) {
                                    $(td).css({
                                        "width": this.gridtable[0][k].model.get("columnWidth"),
                                        display: "inline-block"
                                    });
                                } else {
                                    $(td).css({
                                        "width": this.gridtable[0][k].model.get("columnWidth"),
                                        display: "none"
                                    });
                                }
                            }
                        }
                        operation = this.gridtable[0][k].model.attributes.operation;
                        if (operation) {
                            $(td).addClass("total");
                            icon = iconTotal[operation] ? iconTotal[operation] : iconTotal["other"];
                            label.innerHTML = icon + ": ";
                            result.value = title;
                            result.id = (operation + "-" + this.model.get("name") + "-" +
                            this.gridtable[0][k].model.get("columnName"));
                            $(td).addClass("function-result-" + this.gridtable[0][k].model.get("columnName"));
                            td.appendChild(label);
                            td.appendChild(result);
                        } else {
                            label.innerHTML = "";
                            result.value = "";
                        }
                        dom.append(td);
                    }
                    if (this.model.get("layout") == "static") {
                        this.tableBody.on("scroll", function (e) {
                            that.$el.find(".containerStaticGrid")[0].scrollLeft = e.target.scrollLeft;
                        });
                    }
                }
            }
            return this;
        },
        createHTMLRow: function (numberRow, dataRow, sectionAfected) {
            var tr, td, k, tdRemove, tdNumber, element, colSpan, product, cellModel, nameCell, cloneModel,
                idCell, cellView, row, that, rowModel, rowView, rowData;
            rowModel = [];
            rowView = [];
            rowData = [];
            tr = this._createHtmlRow(), that = this;
            this.sqlColumns = [];
            row = [];
            if (sectionAfected) {
                this.flagRow = sectionAfected;
            }
            tdNumber = this.createRowNumber(numberRow + 1);
            tr.appendChild(tdNumber);
            for (k = 0; k < this.columnsModel.length; k += 1) {
                cloneModel = jQuery.extend(true, {}, this.columnsModel[k]);
                cellModel = null;
                product = cloneModel.product;
                var nameToPostControl = this.createNameCell(numberRow + 1 ,cloneModel.name);
                var nameToPostLabelControl = this.createNameToLabelCell(numberRow + 1 ,cloneModel.name);
                cloneModel["nameToPostControl"] = nameToPostControl;
                cloneModel["nameToPostLabelControl"] = nameToPostLabelControl;
                cellModel = new product.model(cloneModel);
                cellModel.set("row", numberRow);
                cellModel.set("col", k);
                if (cellModel.get("sql") !== undefined && this.validDependentColumns.indexOf(cellModel.attributes.type) > -1) {
                    cellModel.attributes.parentDependents = [];
                    cellModel.attributes.dependents = [];
                    this.sqlColumns.push(cellModel);
                }
                if (this.model.get("variable").trim().length === 0) {
                    nameCell = "";
                    idCell = this.changeIdField(this.model.get("id"), numberRow + 1, cellModel.get("_extended").name);
                } else {
                    nameCell = this.changeNameField(this.model.get("name"), numberRow + 1, cellModel.get("_extended").name);
                    idCell = this.changeIdField(this.model.get("id"), numberRow + 1, cellModel.get("_extended").name);
                }
                cellModel.attributes.name = nameCell;
                cellModel.attributes.id = idCell;
                rowModel.push(cellModel);
            }
            var i, idColumn, where, sql, indexWhere, j;
            for (var i = 0; i < this.sqlColumns.length; i += 1) {
                this.sqlColumns[i].set("dependents", []);
                idColumn = this.sqlColumns[i].get("columnName");
                for (j = 0; j < this.sqlColumns.length; j += 1) {
                    if (i !== j) {
                        sql = this.sqlColumns[j].get("sql");
                        if (this._existVariableInSql(sql, idColumn)) {
                            this.sqlColumns[j].attributes.parentDependents.push(this.sqlColumns[i]);
                            this.sqlColumns[i].attributes.dependents.push(this.sqlColumns[j]);
                        }
                    }
                }
            }
            for (var i = 0; i < rowModel.length; i += 1) {
                product = this.columnsModel[i].product;
                cellView = null;
                cellView = new product.view({
                    model: rowModel[i]
                });
                rowModel[i].set("view", cellView);
                cellView.project = this.project;
                cellView.parent = this;
                colSpan = rowModel[i].attributes.colSpan;
                element = cellView.render().el;
                if (this.model.get("layout") === "responsive") {
                    var elementParent = $(element).find(".form-control")[0].parentNode;
                    elementParent.style.padding = "0px";
                    td = document.createElement("div");
                    td.style.display = "inline-block";
                    $(td).css("float", "left");
                } else {
                    td = this._createHtmlCell(rowModel[i].attributes.type, colSpan, i);
                }
                if (this.hiddenColumns.indexOf(i + 1) > -1) {
                    $(td).hide();
                }
                if (cellView.model.get("type") !== "hidden" && this.model.get("layout") === "static") {
                    if (cellView.model.get("columnWidth") && Number(cellView.model.get("columnWidth")).toString() !== "NaN") {
                        $(td).css({
                            "min-width": parseInt(cellView.model.get("columnWidth")),
                            "max-width": parseInt(cellView.model.get("columnWidth"))
                        });
                        cellView.$el.css({
                            "width": parseInt(cellView.model.get("columnWidth"))
                        });
                    } else {
                        $(td).css({
                            "min-width": "200px",
                            "max-width": "200px"
                        });
                    }
                }

                if (this.model.get("layout") === "responsive") {
                    if (parseInt(cellView.model.get("columnWidth")) !== 0) {
                        $(td).css({
                            "width": cellView.model.get("columnWidth")
                        });
                    } else {
                        $(td).css({
                            "width": cellView.model.get("columnWidth"),
                            display: "none"
                        });
                    }
                }

                $(element).addClass("row form-group");
                td.appendChild(element);
                tr.appendChild(td);
                row.push(cellView);
                rowView.push(cellView);
            }
            rowData = this.prepareNewRow(row, dataRow);
            this.updateNameFields(row);
            for (var k = 0; k < row.length; k += 1) {
                if (row[k].model.get("formula")) {
                    row[k].model.attributes.formulaAssociatedObject = [];
                    row[k].onFormula(row);
                    //row[k].onFieldAssociatedHandler();
                }
            }
            if (this.model.get("mode") === "edit") {
                if (this.model.get("deleteRow")) {
                    tdRemove = this.createRemoveButton(numberRow + 1);
                    $(tdRemove).addClass("remove-row");
                    tr.appendChild(tdRemove);
                }
            }
            if (this.model.get("layout") === "responsive") {
                jQuery(tdNumber).css({width: this.indexResponsive});
                jQuery(tdRemove).css({width: this.removeResponsive});
            }
            this.flagRow += 1;
            if (this.paged) {
                this._createHTLMCarucel();
                this.domCarousel.append(tr);
                this.tableBody.append(this.domCarousel);
            } else {
                this.tableBody.append(tr);
            }
            this.gridtable.push(row);
            this.dom.push(tr);
            return {
                model: rowModel,
                view: rowView,
                data: rowData
            };
        },
        prepareNewRow : function (row, dataRow) {
            var cellView,
                cellModel,
                that = this,
                rowData = [];
            this.setRowData(row,dataRow);
            if(_.isArray(row)){
                for (var i = 0 ; i < row.length ; i+=1){
                    cellView = row[i];
                    if (cellView.model.get("operation") !== "") {
                        cellView.on("changeValues", function () {
                            that.setValuesGridFunctions({
                                row: this.model.attributes.row,
                                col: this.model.attributes.col,
                                data: this.model.attributes.value
                            });
                            that.createHTMLTotal();
                        });
                    }
                    if (row[i].model.get("operation")) {
                        if (!isNaN(parseFloat(row[i].model.get("value")))) {
                            rowData.push(parseFloat(row[i].model.get("value")));
                        } else {
                            rowData.push(0);
                        }
                    }
                }                
            }
            return rowData;
        },
        /**
         * this method, set data in the cells
         * @param {array|number} row: is a index or un array 
         * @param {[type]} rowData : is a set of data
         */
        setRowData : function (row, rowData) {
            var i,
                cell,
                auxValue,
                type,
                dataType,
                viewMode;
            if (typeof row === "number"){
                row = this.gridtable[row];
            }
            if (_.isArray(row) && _.isArray(rowData)){
                for (i = 0; i <  row.length; i+=1 ){
                    cell = row[i];
                    viewMode = cell.model.get("mode");
                    if (rowData[i] !== null && rowData[i] !== undefined){
                        if (viewMode === "edit" || viewMode === "disabled"){
                            this._setDataToEditMode(cell, rowData[i]);
                        }else{
                            this._setDataToViewMode(cell, rowData[i]);
                        }
                    }
                }
            }
            return this;
        },
        _setDataToViewMode: function(cell, data){
            if (cell && data !== undefined && data !== null){
                if(typeof cell.setData === "function"){
                    cell.setData(data);
                }
            }
            return this;
        },
        _setDataToEditMode: function(cell, data){
            var type,
                dataType;
            if(cell && data !== undefined && data !== null){
                type = cell.model.get("type"),
                dataType = cell.model.get("dataType") || "";
                if(type === "suggest" && typeof cell.setData === "function"){
                    cell.setData(data);
                } else if ( dataType === "boolean" && typeof cell.setValue === "function") {
                    cell.setValue(data['value'] || false);
                } else {
                    if (typeof cell.setValue === "function"){
                        cell.setValue(data['value'] || '');
                    }
                }                
            }
            return this;
        },
        _existVariableInSql: function (sql, nameField) {
            var parse, result, variable;
            parse = /\@(?:([\@\%\#\=\!Qq])([a-zA-Z\_]\w*)|([a-zA-Z\_][\w\-\>\:]*)\(((?:[^\\\\\)]*?)*)\))/g;
            while ((result = parse.exec(sql)) !== null) {
                if ($.isArray(result) && result.length) {
                    variable = result[0];
                    if (variable.substring(2, variable.length) === nameField) {
                        return true;
                    }
                }
            }
            return false;
        },
        _createHTLMCarucel: function () {
            if (this.block === true) {
                this.domCarousel = document.createElement("div");
                this.domCarousel.className = "pmdynaform-grid-section_" + this.section;
                if (this.model.get("layout") === "static") {
                    this.domCarousel.className += " pmdynaform-static";
                }
                this.domCarousel = $(this.domCarousel);
            }
            if (this.section === this.showPage) {
                this.domCarousel.addClass("item active");
            } else {
                this.domCarousel.addClass("item");
            }
            if (this.flagRow == this.pageSize) {
                this.block = true;
                this.section += 1;
                this.flagRow = 0;
            } else {
                this.block = false;
            }
            return this;
        },
        _createHtmlRow: function () {
            var tr;
            tr = document.createElement("div");
            tr.className = "pmdynaform-grid-row row form-group show-grid";
            if (this.model.get("layout") === "static") {
                tr.className += " pmdynaform-grid-static"
            }
            return tr;
        },
        _createHtmlCell: function (typeControl, colSpan, index) {
            var td, colSpan;
            td = document.createElement("div");
            if (this.model.attributes.layout === "form") {
                if (typeControl !== "hidden") {
                    this.checkColSpanResponsive();
                    colSpan = this.colSpanControlField(typeControl, index);
                    td.className = "col-xs-12 col-sm-" + colSpan + " col-md-" + colSpan + " col-lg-" + colSpan;
                } else {
                    jQuery(td).css({
                        width: 0 + "%",
                        display: "inline-block"
                    });
                }
            } else if (this.model.attributes.layout === "static") {
                if (typeControl !== "hidden") {
                    td.className = "pmdynaform-grid-field-static";
                }
            } else {
                if (typeControl !== "hidden") {
                    td.className = "col-xs-" + colSpan + " col-sm-" + colSpan + " col-md-" + colSpan + " col-lg-" + colSpan;
                    jQuery(td).css({
                        width: this.colSpanControlFieldResponsive() + "%",
                        display: "inline-block"
                    });
                } else {
                    jQuery(td).css({
                        width: 0 + "%",
                        display: "inline-block"
                    });
                }
            }
            return td;
        },
        setData: function (data) {
            var col,
                i,
                j,
                cloneData = data,
                grid = this.gridtable;

            if (typeof data === "object") {
                if (cloneData.length) {
                    for (j in cloneData) {
                        if (cloneData.hasOwnProperty(j)) {
                            for (col = 0; col < grid[0].length; col += 1) {
                                if (!_.isEmpty(grid[0][col].model.attributes.variable)) {
                                    if (grid[0][col].model.attributes.variable.var_name === j) {
                                        if (cloneData[j] instanceof Array) {
                                            for (i = 0; i < grid.length; i += 1) {

                                                if (!this.gridtable[i][col].model.get("formulator")) {
                                                    grid[i][col].model.set("value", cloneData[j][i]);
                                                    if (this.gridtable[i][col].onFieldAssociatedHandler) {
                                                        this.gridtable[i][col].onFieldAssociatedHandler()
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                //console.log("Error, The 'data' parameter is not valid. Must be an array.");
            }
            return this;
        },
        getData: function () {
            var i,
                k,
                gridpanel,
                fields,
                rowData = [],
                gridData = [],
                gridFieldData = {
                    name: this.model.get("name"),
                    gridtable: []
                },
                data = this.model.getData();

            gridpanel = this.gridtable;
            for (i = 0; i < gridpanel.length; i += 1) {
                rowData = [];
                for (k = 0; k < gridpanel[i].length; k += 1) {
                    if ((typeof gridpanel[i][k].getData === "function") &&
                        (gridpanel[i][k] instanceof PMDynaform.view.Field)) {
                        rowData.push(gridpanel[i][k].getData());
                    }
                }
                gridData.push(rowData);
            }
            gridFieldData.gridtable = gridData;

            return gridFieldData;
        },
        renderGridTable: function (newItem) {
            var i, j, k, rows, rowsData, rowData, row;
            this.tableBody = this.$el.find(".pmdynaform-grid-tbody");
            rows = this.model.get("rows");
            rowsData = this.model.get("data");
            if (this.model.get("layout") === "static") {
                this.tableBody.addClass("pmdynaform-static");
            }
            this.model.attributes.gridFunctions = [];
            if (!newItem) {
                this.dom = [];
                for (j = 0; j < rows; j += 1) {
                    rowData = rowsData[j + 1];
                    row = this.createHTMLRow(j, rowData);
                    if (row && row.data) {
                        this.model.attributes.gridFunctions.push(row.data);
                        for (i = 0; i < row.model.length; i += 1) {
                            if (row.model[i].get("operation") && row.model[i].get("operation").trim().length) {
                                row.view[i].onChangeCallbackOperation();
                            }
                            if (row.model[i].get("type") == "label" && row.model[i].get("operation")) {
                                this.createHTMLTotal();
                            }
                        }
                    }
                }
            } else {
                row = this.createHTMLRow(this.gridtable.length - 1);
                if (row && row.data) {
                    this.model.attributes.gridFunctions.push(row.data);
                }
            }
            this.model.setPaginationItems();
            this.createHTMLPager();
            return this;
        },
        /**
         * @Event
         * @param Event  This must be an event valid
         * @param Function Callback for the event
         **/
        on: function (e, fn) {
            var allowEvents = {
                remove: "setOnDeleteRowCallback",
                add: "setOnAddRowCallback",
                pager: "setOnClickPageCallback",
                beforeAdd: "setOnBeforeAddCallback"
            };

            if (allowEvents[e]) {
                this[allowEvents[e]](fn);
            } else {
                throw new Error("The event must be a valid event.\n The events available are remove, add and pager");
            }

            return this;
        },
        setOnDeleteRowCallback: function (fn) {
            if (typeof fn === "function") {
                this.onDeleteRowCallback = fn;
            } else {
                throw new Error("The callback must be a function");
            }

            return this;
        },
        setOnAddRowCallback: function (fn) {
            if (typeof fn === "function") {
                this.onAddRowCallback = fn;
            } else {
                throw new Error("The callback must be a function");
            }

            return this;
        },
        setOnBeforeAddCallback: function (fn) {
            if (typeof fn === "function") {
                this.onBeforeAddRowCallback = fn;
            } else {
                throw new Error("The callback must be a function");
            }
            return this;
        },
        setOnClickPageCallback: function (fn) {
            if (typeof fn === "function") {
                this.onClickPageCallback = fn;
            } else {
                throw new Error("The callback must be a function");
            }

            return this;
        },
        afterRender: function () {

        },
        /**
         * this method get data in json formated, of this field.
         * The suports controls are: 
         * - text
         * - textarea
         * - dropdown
         * - hidden
         * - checkbox
         * - suggest
         * - datetime 
         * @return {object} json
         */
        getData2: function () {
            var validControls,
                gridpanel,
                cellName,
                rowData,
                cell,
                data = {},
                key,
                k,
                i;
            validControls = ["text", "textarea", "dropdown", "hidden", "checkbox", "datetime", "suggest"];
            gridpanel = this.gridtable;
            for (i = 0; i < gridpanel.length; i += 1) {
                data[i + 1] = {};
                rowData = {};
                for (k = 0; k < gridpanel[i].length; k += 1) {
                    cell = gridpanel[i][k].model;
                    if (validControls.indexOf(cell.get("originalType")) > -1) {
                        cellName = cell.get("columnName");
                        rowData[cellName] = cell.get("data")["value"];
                        rowData[cellName + "_label"] = cell.get("data")["label"];
                    }
                }
                data[i + 1] = rowData;
            }
            return data;
        },
        setData2: function (data) {
            var rowIndex, grid, dataRow,
                colIndexm, cols, colIndex,
                cellModelItem, cellViewItem,
                modeItem, dataItem, newItem, value, richi, option, options, i;
            grid = this.gridtable;
            for (rowIndex in data) {
                if (parseInt(rowIndex, 10) > this.gridtable.length) {
                    newItem = this.addRow();
                    this.renderGridTable();
                    this.onAddRowCallback(newItem, this);
                }
                cols = grid[parseInt(rowIndex, 10) - 1].length;
                for (colIndex = 0; colIndex < cols; colIndex += 1) {
                    cellViewItem = grid[parseInt(rowIndex, 10) - 1][colIndex];
                    cellModelItem = grid[parseInt(rowIndex, 10) - 1][colIndex].model;
                    modeItem = cellModelItem.get("mode");
                    for (dataItem in data[rowIndex]) {
                        if (cellModelItem.get("columnName") === dataItem) {
                            if (modeItem === "edit" || modeItem === "disabled") {
                                if (cellModelItem.get("type") === "suggest") {

                                    for (richi = 0; richi < cellModelItem.get("localOptions").length; richi += 1) {
                                        option = cellModelItem.get("localOptions")[richi].value;
                                        if (option === data[rowIndex][dataItem]) {
                                            value = cellModelItem.get("localOptions")[richi].label;
                                            break;
                                        }
                                    }
                                    if (value && !value.length) {
                                        for (richi = 0; richi < cellModelItem.get("options").length; richi += 1) {
                                            option = cellModelItem.get("options")[richi].value;
                                            if (option === data[rowIndex][dataItem]) {
                                                value = cellModelItem.get("options")[richi].label;
                                                break;
                                            }
                                        }
                                    }

                                    $(cellViewItem.el).find(":input").val(value);
                                    cellModelItem.attributes.value = data[rowIndex][dataItem];
                                } else if (cellModelItem.get("type") === "checkbox") {
                                    options = cellModelItem.get("options");
                                    if (cellModelItem.get("dataType") === "boolean") {
                                        if (data[cellModelItem.get("name")] === options[0].value) {
                                            options[1].selected = false;
                                            options[0].selected = true;
                                        } else {
                                            delete options[0].selected;
                                            options[1].selected = true;
                                            options[0].selected = false;
                                        }
                                    } else {
                                        for (i = 0; i < options.length; i += 1) {
                                            delete options[i].selected;
                                            if (data[rowIndex][dataItem].indexOf(options[i]) > -1) {
                                                options[i].selected = true;
                                            }
                                        }
                                    }
                                    cellModelItem.set("options", options);
                                    cellModelItem.initControl();
                                    cellModelItem.set("value", [data[rowIndex][dataItem]])
                                } else {
                                    cellModelItem.set("value", data[rowIndex][dataItem]);
                                }
                            }
                            if (modeItem === "view") {
                                if (cellModelItem.get("originalType") === "checkbox") {
                                    cellModelItem.set("fullOptions", data[rowIndex][dataItem]);
                                } else if (cellModelItem.get("originalType") === "dropdown") {
                                    value = [];
                                    for (richi = 0; richi < cellModelItem.get("localOptions").length; richi += 1) {
                                        option = cellModelItem.get("localOptions")[richi].value;
                                        if (option === data[rowIndex][dataItem]) {
                                            value.push(cellModelItem.get("localOptions")[richi].label);
                                            cellModelItem.set("fullOptions", value);
                                            break;
                                        }
                                    }
                                    if (!value.length) {
                                        for (richi = 0; richi < cellModelItem.get("options").length; richi += 1) {
                                            option = cellModelItem.get("options")[richi].value;
                                            if (option === data[rowIndex][dataItem]) {
                                                value.push(cellModelItem.get("options")[richi].label);
                                                cellModelItem.set("fullOptions", value);
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    value = [];
                                    value.push(data[rowIndex][dataItem]);
                                    cellModelItem.set("fullOptions", value);
                                }
                            }
                        }
                    }
                }
            }
            return this;
        },
        render: function () {
            if (PMDynaform.core.ProjectMobile) {
                var gridMobile = new PMDynaform.model.GridMobile(this.model.attributes, null, this.project);
                this.$el.html(gridMobile.body);
                this.setData2 = function (data) {
                    gridMobile.setData(data);
                };
                this.getData2 = function () {
                    return gridMobile.getData();
                };
                return this;
            }
            var j,
                headerGrid,
                bodyGrid;

            this.$el.html(this.template(this.model.toJSON()));
            this.createHTMLTitle();
            this.renderGridTable(false);
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }

            if (this.model.get("layout") === "static") {
                headerGrid = this.$el.find(".pmdynaform-grid-thead");
                bodyGrid = this.$el.find(".pmdynaform-grid-tbody");
                bodyGrid.css("overflow", "auto");
                bodyGrid.scroll(function (event) {
                    headerGrid.scrollLeft(bodyGrid.scrollLeft());
                    event.stopPropagation();
                });
            }
            if (!this.model.get("addRow")) {
                this.$el.find(".pmdynaform-grid-new").find("button").hide();
            }
            if (this.model.get("layout") === "responsive") {
                var size = {
                    "1200": 5,
                    "992": 4,
                    "768": 3,
                    "767": 2
                };

                $(window).resize(function () {
                    var j,
                        k,
                        width = $(window).width();

                    if (width >= 1200) {
                        //console.log("1200");
                    }
                    if (width >= 992 && width < 1200) {
                        //console.log("992");
                    }
                    if (width >= 768 && width < 992) {
                        //console.log(">768");
                    }
                    if (width < 768) {
                        //console.log("<768");
                    }

                });
            }

            return this;
        },
        setValue: function (value, row, col) {
            if (value !== undefined) {
                if (row !== undefined && col !== undefined) {
                    if ((row > 0 && col > 0) && row <= this.gridtable.length && col <= this.columnsModel.length) {
                        return this.gridtable[row - 1][col - 1].setValue(value);
                    } else {
                        return null;
                    }
                }
            }
            return this;
        },
        createNameCell : function(rowIndex, columnName){
            return "form[" + this.model.get("variable") + "][" + rowIndex + "][" + columnName+ "]";
        },
        createNameToLabelCell : function(rowIndex, columnName){
            return "form[" + this.model.get("variable") + "][" + rowIndex + "][" + columnName+ "_label]";
        },
        hideColumn: function (col) {
            var field = this,
                table,
                row,
                cell,
                i,
                label;
            table = field.gridtable;
            if (col > 0 && col <= field.columnsModel.length) {
                if (field.hiddenColumns.indexOf(col) === -1) {
                    field.hiddenColumns.push(col);
                }
                for (i = 0; i < table.length; i += 1) {
                    row = table[i];
                    cell = row[col - 1];
                    if (cell.model.get("type") !== "hide") {
                        cell.$el.hide();
                        if (cell.$el.parent().length) {
                            cell.$el.parent().hide();
                        }
                        if (cell.model.get("operation") !== "") {
                            field.$el.find("." + "function-result-" + cell.model.get("columnName")).hide();
                        }
                    }
                }
                if (field.$el.find(".field-operation-result")) {
                    field.$el.find(".field-operation-result").eq(col - 1).hide();
                }
                for (i = 0; i < field.domTitleHeader.length; i += 1) {
                    if (field.domTitleHeader[i].find(".title-column").text() === cell.model.get("label")) {
                        field.domTitleHeader[i].hide();
                    }
                }
            }
        },
        showColumn: function (col) {
            var field = this,
                table,
                row,
                cell,
                label,
                i,
                index;
            table = field.gridtable;
            if (col > 0 && col <= field.columnsModel.length) {
                index = field.hiddenColumns.indexOf(col);
                if (index > -1) {
                    field.hiddenColumns.splice(index, 1);
                }
                for (i = 0; i < table.length; i += 1) {
                    row = table[i];
                    cell = row[col - 1];
                    if (cell.model.get("type") !== "hide") {
                        cell.$el.show();
                        if (cell.$el.parent().length) {
                            cell.$el.parent().show();
                        }
                    }
                }
                if (cell) {
                    if (cell.model.get("operation") !== "") {
                        field.$el.find("." + "function-result-" + cell.model.get("columnName")).show();
                    }
                    if (field.$el.find(".field-operation-result")) {
                        field.$el.find(".field-operation-result").eq(col - 1).show();
                    }
                    for (i = 0; i < field.domTitleHeader.length; i += 1) {
                        if (field.domTitleHeader[i].find(".title-column").text() === cell.model.get("label")) {
                            field.domTitleHeader[i].show();
                        }
                    }
                }
            }
        },
        getNumberRows : function (){
            return this.gridtable.length;
        },
        /**
         * this method  execute, when the grid undergoes a change that requires validation check
         * @returns {boolean}
         */
        validateGrid : function(){
            var valid = true;
            if (this.validator) {
                this.validator.$el.remove();
            }
            valid = this.model.isValid();
            if (!valid) {
                this.validator = new PMDynaform.view.Validator({
                    model: this.model.get("validator")
                });
                this.$el.find(".pmdynaform-grid").parent().append(this.validator.el);
                this.$el.find(".pmdynaform-grid").addClass("has-error");
            }else{
                this.$el.find(".pmdynaform-grid").removeClass("has-error");
            }
            if(this.model.get("rows") === 0){
                this.renderEmptyGrid();
            }else{
                this.removeEmptyGrid();
            }
            return valid;
        },
        /**
         * this template is append when the rows not exist in the grid
         * @returns {GridView}
         */
        renderEmptyGrid : function(){
            var emptyTag,
                container = this.$el.find("#"+this.model.get("id")+"-body");
                emptyTag = this.$el.find(".grid-empty");
            if(emptyTag instanceof jQuery && emptyTag.length === 0){
                emptyTag = this.templateEmptyGrid({
                    message : this.model.get("emptyMessage")
                });
                container.prepend(emptyTag);
            }
            return this;
        },
        /**
         * this template is removed when the rows exist in the grid
         * @returns {GridView}
         */
        removeEmptyGrid : function(){
            this.$el.find(".grid-empty").remove();
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.GridPanel", GridView);
}());

(function () {
    var GridMobile = function (property, renderTo, parent) {
        this.property = property;
        this.renderTo = renderTo;
        this.parent = parent;
        this.topClient = 0;
        this.pressTimer;
        GridMobile.prototype.init.call(this);
    };
    GridMobile.prototype.init = function () {
        var that = this;
        this.title = $("<span class='pm-form-grid-header-title'><span>");
        this.add = $("<img class='pm-form-grid-header-add' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wYRFA4ZqZ5P4gAAAP5JREFUOMul07EuRFEQBuCTuwmhU0sEES0dm3gC3kHiPbbcYmsq3YqodYJew2qIqNSylBJL4vo0c5N1c++y/MnJyfn/OXNmzsykVAJWsIs7POMJN+hgKdUBU+jiUz3e0UajfHkaF2H0ij2sYiZWM5znYXP8zQmOQnjA8ogoN9AP205BNiPsl3KO6OGsxK3jA29YTDgIj62KF0EFfxhSO+ExDnNjONgM6SpFOIbEyxFVOAmb2Tj3s4q/aqR6FFoeezacwvwYKWyF1MtSSufB76TfYzv204S1EWW8riljjgEWCnL/D43UKs9BVStPYPLHVv73MNWM83206wC3deP8BQS+B+NSJVuhAAAAAElFTkSuQmCC'>");
        this.add.on("click", function () {
            that.showRowEditor();
        });

        this.header = $("<div class='pm-form-grid-header'></div>");
        this.header.append(this.title);
        this.header.append(this.add);

        var a;
        this.carousel = $("<div class='pm-form-grid-body carousel slide' data-ride='carousel' data-interval=''><div class='carousel-inner' role='listbox'></div></div>");
        this.carousel.carousel();
        this.carousel.on("mousedown touchstart", function (e) {
            a = e.clientX || e.originalEvent.touches[0].clientX;
        });
        this.carousel.on("mouseup touchmove", function (e) {
            var sel = that.pagination.find(".active");
            var b = e.clientX || e.originalEvent.touches[0].clientX;
            if (b > a) {
                sel.removeClass("active");
                that.carousel.carousel("prev");
                if (sel.prev().length === 0)
                    that.pagination.find("li").last().addClass("active");
                else
                    sel.prev().addClass("active");
                that.setPageInfo(that.pagination.find("li").index(that.pagination.find(".active")));
            }
            if (b < a) {
                sel.removeClass("active");
                that.carousel.carousel("next");
                if (sel.next().length === 0)
                    that.pagination.find("li").first().addClass("active");
                else
                    sel.next().addClass("active");
                that.setPageInfo(that.pagination.find("li").index(that.pagination.find(".active")));
            }
        });

        this.plabel = $("<span class='pm-form-grid-footer-plabel'></span>");
        this.pagination = $("<ul class='pm-form-grid-footer-pagination carousel-indicators'></ul>");

        this.footer = $("<div class='pm-form-grid-footer'></div>");
        this.footer.append(this.plabel);
        this.footer.append(this.pagination);

        this.body = $("<div class='pm-form-grid'></div>");
        this.body.append(this.header);
        this.body.append(this.carousel);
        this.body.append(this.footer);

        //set property
        //property.addRow
        //property.colSpan
        //property.deleteRow
        //property.hint
        //property.id
        //property.label
        //property.layout
        //property.mode
        //property.name
        //property.pageSize
        this.pageSize = parseInt(this.property.pageSize);
        //property.title
        this.title.text(this.property.title);
        //property.type
        //property.variable
        //property.columns
        //property.data
        that.setData({});
    };
    GridMobile.prototype.addHeader = function () {
        var row = $("<div class='pm-form-grid-row-header row'></div>");
        return row;
    };
    GridMobile.prototype.addRow = function () {
        return $("<div class='pm-form-grid-row row'></div>");
    };
    GridMobile.prototype.delRow = function (index) {
        var that = this, i, dt, j = 1, dt2 = {};
        dt = that.getData();
        for (i in dt) {
            if (i !== index) {
                dt2[j] = dt[i];
                j++;
            }
        }
        that.setData(dt2);
    };
    GridMobile.prototype.addCell = function () {
        return $("<div class='pm-form-grid-row-cell'></div>");
    };
    GridMobile.prototype.setData = function (dt) {
        var that = this, i, j, table, columns, index, row, cell;
        var sw = parseInt(that.pageSize) === 0;
        var pn = 0;
        that.carousel.find(".carousel-inner").empty();
        that.pagination.empty();
        i = 0;
        for (index in dt) {
            if (i % that.pageSize === 0 || sw) {
                sw = false;
                table = that.buildHeader(i, pn);
                pn = pn + 1;
            }
            //setdata
            row = that.addRow();
            row.data("index", index);

            //long press
            row.mousedown(function () {
                var index = $(this).data("index");
                that.pressTimer = setTimeout(function () {
                    that.showOptions(index);
                    that.pressTimer = true;
                }, 1000);
                return false;
            }).mouseup(function () {
                if (that.pressTimer !== true) {
                    clearTimeout(that.pressTimer);
                    that.showRowEditor($(this).data("index"));
                }
                return false;
            });

            //long press mobile
            row.bind("touchstart", function (e) {
                var index = $(this).data("index");
                that.pressTimer = setTimeout(function () {
                    that.showOptions(index);
                    that.pressTimer = true;
                }, 1000);
                return false;
            }).bind("touchend", function (e) {
                if (that.pressTimer !== true) {
                    clearTimeout(that.pressTimer);
                    that.showRowEditor($(this).data("index"));
                }
                return false;
            });
            table.append(row);
            columns = that.property.columns;
            for (j = 0; j < columns.length; j++) {
                cell = that.addCell();
                if (dt[index][columns[j].id + "_label"] === undefined){
                    if (columns[j].options){
                        for (var k=0 ; k < columns[j].options.length ; k+=1){
                            if (columns[j].options[k].value === dt[index][columns[j].id]){
                                dt[index][columns[j].id + "_label"] = columns[j].options[k].label;
                                break;                            
                            }
                        }
                    }
                    if (dt[index][columns[j].id + "_label"] === undefined){
                        dt[index][columns[j].id + "_label"] = dt[index][columns[j].id];
                    }
                }
                cell.text(dt[index][columns[j].id + "_label"]);
                cell.data("index", columns[j].id);
                cell.data(columns[j].id, dt[index][columns[j].id]);
                cell.data(columns[j].id + "_label", dt[index][columns[j].id + "_label"]);
                row.append(cell);
            }
            i++;
        }
        that.size = i;
        if (i === 0) {
            table = that.buildHeader(i, pn);
            table.append("<div style='text-align:center;padding:5px;'>No rows</div>");
        }
        that.setPageInfo(0);
    };
    GridMobile.prototype.buildHeader = function (i, pn) {
        var that = this, row, cell, itemCarousel, itemPagination, columns, table, j;
        row = that.addHeader();
        columns = that.property.columns;
        for (j = 0; j < columns.length; j++) {
            cell = that.addCell();
            cell.text(columns[j].title);
            cell.removeClass("pm-form-grid-row-cell");
            cell.addClass("pm-form-grid-row-header-cell");
            row.append(cell);
            if (j === 0 || j === 1) {
                cell.css({"width": "50%"});
            }
        }
        table = $("<div class='pm-form-grid-body form-group'></div>");
        table.append(row);
        itemCarousel = $("<div class='item'>");
        itemCarousel.append(table);
        that.carousel.find(".carousel-inner").append(itemCarousel);
        //default
        if (i === 0)
            itemCarousel.addClass("active");

        //page
        itemPagination = $("<li class='item" + pn + "'></li>");
        itemPagination[0].pn = pn;
        itemPagination.on("click", function () {
            that.pagination.find(".active").removeClass("active");
            $(this).addClass("active");
            that.carousel.carousel(this.pn);
            that.setPageInfo(this.pn);
        });
        if (this.pageSize > 0) {
            that.pagination.append(itemPagination);
        }
        //default
        if (i === 0)
            itemPagination.addClass("active");
        return table;
    };
    GridMobile.prototype.getData = function () {
        var that = this, dt = {};
        var j = 1;
        that.carousel.find(".carousel-inner").find(".pm-form-grid-body").each(function (i, e) {
            $(e).find(".pm-form-grid-row").each(function (i, e) {
                dt[j] = {};
                $(e).find(".pm-form-grid-row-cell").each(function (i, e) {
                    dt[j][$(e).data("index")] = $(e).data($(e).data("index"));
                    dt[j][$(e).data("index") + "_label"] = $(e).data($(e).data("index") + "_label");
                });
                j++;
            });
        });
        return dt;
    };
    GridMobile.prototype.setPageInfo = function (start) {
        var that = this;
        var a = start * this.pageSize + 1;
        var b = start * this.pageSize + this.pageSize;
        if (this.size === 0) {
            a = b = 0;
        }
        var msg = "";
        if (this.pageSize > 0) {
            msg = a + "-" + b + " of ";
        }
        that.plabel.text("Showing " + msg + this.size);
    };
    GridMobile.prototype.showRowEditor = function (index) {
        if(this.aux){
            $(this.aux.view.el).remove();
        }
        this.modalShow();
        var that = this, keys;
        var i, items = [], field;
        for (i = 0; i < that.property.columns.length; i++) {
            field = that.property.columns[i];
            field.variable = field.id;//todo
            field.var_name = field.id;//todo
            field.colSpan = 12;
            items.push([field]);
        }
        items.push([{
                type: "button",
                id: "cancel",
                label: "Cancel",
                colSpan: 6
            }, {
                type: "button",
                id: "ok",
                label: "OK",
                colSpan: 6
            }
        ]);
        keys = that.parent.keys;
        keys.projectId = keys.processID;
        var rowEditor = new PMDynaform.core.Project({
            data: {
                items: [{
                        type: "form",
                        items: items
                    }
                ]
            },
            renderTo: document.body,
            submitRest: true,
            keys: keys,
            token: that.parent.token
        });
        if (index) {
            rowEditor.view.setData2(that.getData()[index]);
        }
        $(rowEditor.view.el).css({
            "position": "absolute",
            "height": "auto"
        });
        $("#shadow-form").remove();

        $(rowEditor.view.el).find("button").on("click", function () {
            if (this.id === "form[ok]") {
                $(rowEditor.view.el).remove();
                that.modalClose();
                var dt = that.getData();
                index = index ? index : that.size + 1;
                dt[index] = {};
                var form = rowEditor.getForms()[0];
                var fields = form.getFields();
                var field;
                for (i = 0; i < fields.length; i++) {
                    field = fields[i];
                    if (field.getInfo) {
                        dt[index][field.getInfo().id] = field.getData().value;
                        dt[index][field.getInfo().id + "_label"] = field.getDataLabel().value;
                        //todo
                        if (field.getInfo().type === "checkbox") {
                            dt[index][field.getInfo().id] = field.getData().value === 0 || field.getData().value === "0" ? "0" : "1";
                            dt[index][field.getInfo().id + "_label"] = field.model.getKeyLabel().value === "false" ? "false" : "true";
                        }
                    }
                }
                that.setData(dt);
            }
            if (this.id === "form[cancel]") {
                $(rowEditor.view.el).remove();
                that.modalClose();
            }
        });
        this.aux = rowEditor;
        return rowEditor;
    };
    GridMobile.prototype.showOptions = function (index) {
        if(this.aux){
            $(this.aux.view.el).remove();
        }
        this.modalShow();
        var that = this;
        var edit = $("<div class='pm-form-grid-modal-menu-button pm-form-grid-modal-menu-button-edit'>Edit</div>");
        edit.on("click", function () {
            $(".pm-form-grid-modal-menu").remove();
            that.modalClose();
            that.showRowEditor(index);
        });
        var del = $("<div class='pm-form-grid-modal-menu-button pm-form-grid-modal-menu-button-del'>Delete</div>");
        del.on("click", function () {
            $(".pm-form-grid-modal-menu").remove();
            that.modalClose();
            that.showWarning(function () {
                that.delRow(index);
            });
        });
        var cancel = $("<div class='pm-form-grid-modal-menu-button pm-form-grid-modal-menu-button-cancel'>Cancel</div>");
        cancel.on("click", function () {
            $(".pm-form-grid-modal-menu").remove();
            that.modalClose();
        });

        var options = $("<div class='pm-form-grid-modal-menu'></div>");
        options.css({"top": document.body.scrollTop + "px"});
        options.append(edit);
        options.append(del);
        options.append(cancel);
        $(document.body).append(options);
        return options;
    };
    GridMobile.prototype.showWarning = function (fn) {
        this.modalShow();
        var that = this, yes = $("<div class='pm-form-grid-modal-menu-button pm-form-grid-modal-menu-button-yes'>Yes</div>");
        yes.on("click", function () {
            fn();
            warning.remove();
            that.modalClose();
        });
        var no = $("<div class='pm-form-grid-modal-menu-button pm-form-grid-modal-menu-button-no'>No</div>");
        no.on("click", function () {
            warning.remove();
            that.modalClose();
        });
        var warning = $("<div class='pm-form-grid-modal-message'>" +
                "<div class='pm-form-grid-modal-message-title'>Warning</div>" +
                "<hr>" +
                "<div class='pm-form-grid-modal-message-label'>Are you sure you want to delete this row?</div>" +
                "</div>");
        warning.css({"top": document.body.scrollTop + "px"});
        warning.append(no);
        warning.append(yes);
        $(document.body).append(warning);
        return warning;
    };
    GridMobile.prototype.modalShow = function () {
        var that = this;
        this.parent.getForms()[0].$el.parent().parent().css({"display": "none"});
        this.sh = document.body.scrollTop;
        var modal = $("<div class='pm-form-grid-modal'></div>");
        modal.css({"height": "100%", "width": "100%"});
        $(window).on("resize", function () {
            $(that.aux.view.el).css({"height": "auto"});
            modal.css({"height": "100%", "width": "100%"});
        });
        $(document.body).append(modal);
    };
    GridMobile.prototype.modalClose = function () {
        this.parent.getForms()[0].$el.parent().parent().css({"display": "block"});
        document.body.scrollTop = this.sh;
        $(".pm-form-grid-modal").remove();
        $(document.body).css({"position": "static"});
    };
    PMDynaform.extendNamespace("PMDynaform.model.GridMobile", GridMobile);
}());
(function () {
    var ButtonView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-button").html()),
        events: {
            "keydown": "preventEvents"
        },
        initialize: function () {
            this.model.on("change", this.render, this);
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        on: function (e, fn) {
            var that = this;
            $(this.$el.find("button")[0]).on(e, function (event) {
                fn(event, that);

                event.stopPropagation();
            });
            return this;
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            this.tagControl = this.tagHiddenToLabel = this.$el.find("button span");
            return this;
        },
        setValue: function (text) {
            if (text !== undefined) {
                this.model.set("label", text);
                this.$el.find("button").text(text);
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Button", ButtonView);

}());

(function () {
    var DropDownView = PMDynaform.view.Field.extend({
        events: {
            "change select": "eventListener",
            "keydown select": "preventEvents",
            "focus select" : "runDependetOptions"
        },
        clicked: false,
        jsonData: {},
        firstLoad: true,
        dirty: false,
        previousValue: null,
        triggerCallback: false,
        dependentFields: [],
        dependentFieldsData: [],
        template: _.template($("#tpl-dropdown").html()),
        templateOptions: _.template($("#tpl-dropdown-options").html()),
        /**
         * runDependetOptions(): when this component is dependent the other field, the options 
         * are set for set full remote options
         * @return {Dropdown}
         */
        runDependetOptions : function(){
            var remoteOptions, value;
            if (this.model.get("parentDependents") && this.model.get("parentDependents").length) {
                if (this.firstLoad) {
                    if (!this.dirty) {
                        this.tagControl.empty();
                        value = this.model.get("value") || "";
                        this.jsonData = this.generateDataDependenField();
                        remoteOptions = this.executeQuery();
                        this.mergeOptions(remoteOptions);
                        this.tagControl.val(value);
                        this.model.set("value",value);
                        this.dirty = true;
                    }
                    this.firstLoad = false;
                }
            } else {
                this.firstLoad = false;
            }
            return this;
        },
        /**
         * removePlaceholder(), this method remove placeholder option,
         * when is change option. 
         * @return {object} 
         */
        removePlaceholder : function (){
            this.tagControl.find("#placeholder-option").remove();
            return this;
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        initialize: function () {
            this.formulaFieldsAssociated = [];
            this.model.on("change:value", this.eventListener, this, {chage: true});
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event, value) {
            this.onChange(event,value);
            this.checkBinding(event);
        },
        checkBinding: function (event) {
            var form = this.model.get("form");
            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }

            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            if (!this.firstLoad) {
                this.firstLoad = false;
            }
            this.previousValue = this.getValue();
            // For execute formulas in the fields associated
            this.onFieldAssociatedHandler();
            return this;
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        setValueDefault: function () {
            var val = $(this.el).find(":selected").val();
            if (val != undefined) {
                this.model.set("value", val);
            }
            else {
                this.model.set("value", "");
            }
        },
        updateValues: function (event, value) {
            var hiddenInput, label, newValue, option;
            if (!event.type){
                this.tagControl.val(value);
            }
            option = this.tagControl.find(":selected");
            this.tagControl.children().removeAttr("selected");
            option.prop("selected",true);
            option.attr("selected","selected");
            label = option.text().trim();
            hiddenInput = this.$el.find("input[type='hidden']");
            hiddenInput.val(label);
            newValue = this.tagControl.val();
            this.model.set("keyLabel", label);
            this.model.attributes.value = newValue;
            this.updateDataModel(newValue, label);
            return this;
        },
        updateDataModel: function (value, label) {
            var data;
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        onChange: function (event, value) {
            var i, dependents;
            if (!this.firstLoad) {
                if(this.model.get("therePlaceholder")){
                    this.removePlaceholder();
                }
                this.updateValues(event, value);
                this.validate();
                //find and execute dependent fields
                this.runOnDependentHandler();
                this.clicked = false;
            }
            return this;
        },
        onDependentHandler: function () {
            var execute = true, localOpt, remoteOptions, auxData, key;
            auxData = this.jsonData;
            this.jsonData = this.generateDataDependenField();
            remoteOptions = this.executeQuery();
            this.mergeOptions(remoteOptions);
            if (this.firstLoad) {
                this.render();
            }
            return this;
        },
        validate: function () {
            var drpValue;
            drpValue = this.$el.find("select").val() || "";
            this.model.set({value: drpValue}, {validate: true});
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator"),
                        domain: false
                    });
                    this.$el.find("select").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            return this;
        },
        updateValueControl: function () {
            var i,
                options = this.$el.find("select").find("option");
            loop:
                for (i = 0; i < options.length; i += 1) {
                    if (options[i].selected) {
                        this.model.set("value", options[i].value);
                        break loop;
                    }
                }
            return this;
        },
        on: function (e, fn) {
            var that = this,
                control = this.$el.find("select");
            if (control) {
                control.on(e, function (event) {
                    fn(event, that);
                    event.stopPropagation();
                });
            }
            return this;
        },
        getHTMLControl: function () {
            return this.$el.find("select");
        },
        render: function () {
            var that = this,
                hidden,
                name,
                containerOptions,
                fullOptions = [],
                option = "",
                thisValue,
                valueOption,
                fullOption;
            this.$el.html(this.template(this.model.toJSON()));
            this._setDataOption();
            this.$el.find("input[type='hidden']").val(this.model.get("data")["label"])
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[0];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
            }
            if (this.model.get("hint")) {
                this.enableTooltip();
            }
            this.setValueToDomain();
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("select").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find("select");
            this.tagControl.val(this.model.get("value") || "");
            this.tagControl.val(this.model.get("value").toString() || "");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            this.previousValue = this.getValue();
            return this;
        },
        mergeOptions: function (remoteOptions, returnOptions) {
            var k, remoteOpt = [], localOpt = [], options = [];
            for (k = 0; k < remoteOptions.length; k += 1) {
                remoteOpt.push({
                    value: remoteOptions[k].value,
                    label: remoteOptions[k].text
                });
            }
            localOpt = this.model.get("localOptions");
            this.model.attributes.remoteOptions = remoteOpt;
            this.model.attributes.optionsSql = remoteOpt;
            options = localOpt.concat(remoteOpt);
            this.model.attributes.options = options;
            this._setOptions(this.model.get("options"));
            if (returnOptions) {
                return options;
            }
            if (options.length) {
                this.model.attributes.data = {
                    value: options[0].value,
                    label: options[0].label
                };
                if (this.model.get("validator")) {
                    this.model.get("validator").set("valid", true);
                }
                this.model.set("value", options[0].value);
            } else {
                this.model.set("value", "");
            }
            return options;
        },
        _setOptions: function (options) {
            var htmlOptions,
                placeholderOption = {},
                selectControl = this.$el.find("select"),
                therePlaceholder = this.model.get("therePlaceholder");
            if (therePlaceholder){
                placeholderOption = this.model.get("placeholderOption"); 
            }
            selectControl.empty();
            htmlOptions = this.templateOptions({
                options : options,
                therePlaceholder : therePlaceholder,
                placeholderOption : placeholderOption 
            });
            selectControl.append(htmlOptions);
            return this;
        },
        _setDataOption: function () {
            var data = this.model.get("data");
            if (this.model.get("parentDependents").length) {
                if (data && data["value"]) {
                    this._setOptions([data]);
                }
            }
            return this;
        },
        setValue: function (value) {
            if (value !== undefined) {
                if (this.firstLoad){
                    this.firstLoad = false;
                }
                this.model.set("value", value);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Dropdown", DropDownView);

}());

(function () {
    var RadioView = PMDynaform.view.Field.extend({
        clicked: false,
        previousValue: null,
        template: _.template($("#tpl-radio").html()),
        events: {
            "change input": "eventListener",
            "keydown input": "preventEvents"
        },
        initialize: function () {
            // this property have a control of formulas field
            this.formulaFieldsAssociated = [];
            this.model.on("change:value", this.eventListener, this);
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        eventListener: function (event, value) {
            this.onChange(event, value);
            this.checkBinding(event);
        },
        checkBinding: function () {
            // For execute the formula field associated
            this.onFieldAssociatedHandler();

            var form = this.model.get("form");

            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }

            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            this.previousValue = this.getValue();
            return this;
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint")) {
                this.enableTooltip();
            }
            this.setValueToDomain();
            this.previousValue = this.model.get("value");
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='radio']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find(".pmdynaform-radio-items");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            return this;
        },
        validate: function () {
            this.model.set({}, {validate: true});
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find(".pmdynaform-control-radio-list").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            this.clicked = false;
            return this;
        },
        onChange: function (event, value) {
            var hidden, controls, label, i;
            this.clicked = true;
            this.updateValues(event, value);
            this.validate();
            hidden = this.$el.find("input[type='hidden']");
            controls = this.$el.find("input[type='radio']");
            if (hidden.length && controls.length) {
                for (i = 0; i < this.model.get("options").length; i += 1) {
                    if (this.model.get("options")[i]["value"] === this.model.get("value")) {
                        hidden.val(this.model.get("options")[i]["label"]);
                        this.model.attributes.keyLabel = this.model.get("options")[i]["label"] || "";
                        break;
                    }
                }
            }
        },
        updateValues: function (event, value) {
            var hiddenInput, label, newValue, optionSelected;
            if (!event.type) {
                optionSelected = this.tagControl.find("input[id='form\[" + this.model.get("name") + "\]'][value='"
                    + value + "']").prop("checked", true);
                this.tagControl.val(value);
            }
            optionSelected = this.$el.find('input[name=form\\[' + this.model.get("name") + '\\]]:checked');
            label = optionSelected.next().text();
            hiddenInput = this.$el.find("input[type='hidden']");
            hiddenInput.val(label);
            newValue = optionSelected.val();
            this.model.attributes.value = newValue;
            this.updateDataModel(newValue, label);
            return this;
        },
        updateDataModel: function (value, label) {
            var data;
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        getHTMLControl: function () {
            return this.$el.find(".pmdynaform-control-radio-list");
        },
        setValue: function (value) {
            if (value !== undefined) {
                this.model.set("value", value);
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Radio", RadioView);
}());

(function () {
    var SubmitView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-submit").html()),
        events: {
            "keydown": "preventEvents"
        },
        initialize: function (options) {
            this.model.on("change", this.render, this);
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        on: function (e, fn) {
            var that = this,
                control = this.$el.find("button");

            if (control) {
                control.on(e, function (event) {
                    fn(event, that);

                    event.stopPropagation();
                });
            }

            return this;
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            this.tagControl = this.tagHiddenToLabel = this.$el.find("button span");
            return this;
        },
        setValue: function (text) {
            if (text !== undefined) {
                this.model.set("label", text);
                this.$el.find("button").text(text);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Submit", SubmitView);

}());
(function(){
    var TextareaView = PMDynaform.view.Field.extend({
        template: _.template( $("#tpl-textarea").html()),
        validator: null,
        keyPressed: false,
        previousValue : "",
        dependentFields : [],
        dependentFieldsData : [],
        events: {
            "change textarea": "eventListener",
            "keydown textarea": "refreshBinding"
        },
        onChangeCallback: function (){},
        setOnChange : function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        initialize: function (){
            var that = this;
            // this property have a control of formulas field
            this.formulaFieldsAssociated = [];
            this.model.on("change:value", this.eventListener, this);
        },
        refreshBinding: function () {
            this.keyPressed = true;
            return this;
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event) {
            this.onChange(event);
            this.checkBinding(event);
            return this;
        },
        checkBinding: function (event) {
            this.onFieldAssociatedHandler();
            var form = this.model.get("form");
            if (this.model.get("operation")){
                this.onChangeCallbackOperation(this);
            }
            if ( typeof this.onChangeCallback === 'function' ) {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }
            if ( form && form.onChangeCallback ) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            this.previousValue = this.getValue();
        },
        updateValueInput: function () {
            var textInput, hiddenInput;
            textInput = this.$el.find("textarea");
            hiddenInput = this.$el.find("input[type='hidden']");
            if (this.model.get("data")) {
                textInput.val(this.model.get("data")["label"]);
                hiddenInput.val(this.model.get("data")["label"]);
            }
            return this;
        },
        onChange : function (event) {
            var i, dependents;
            if (event.type !== "change") {
                this.updateValueInput();
            }
            if (event.type === "change") {
                this.updateDataModel();
                this.updateValueInput();
            }
            this.validate(event);
            this.runOnDependentHandler();
            this.clicked = false;
            return this;
        },
        updateDataModel: function () {
            var data;
            data = {
                value: this.tagControl.val(),
                label: this.tagControl.val()
            };
            this.model.set("data", data);
            return this;
        },
        render : function () {
            var hidden, name;
            this.$el.html( this.template(this.model.toJSON()) );
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[0];
                name = this.model.get("name");
                name = name.substring(0,name.length-1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
            }
            this.previousValue = this.model.get("value");

            if (this.model.get("name").trim().length === 0){
                this.$el.find("input[type='textarea']").attr("name","");
                this.$el.find("input[type='hidden']").attr("name","");
            }
            this.tagControl = this.$el.find("textarea");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            return this;
        },
        validate: function(event){
            var originalValue;
            originalValue = this.tagControl.val();
            this.model.set("validate", true);
            this.model.attributes.value = originalValue;
            this.model.validate();
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.tagControl.parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            this.changeValuesFieldsRelated();
            this.keyPressed = false;
            // For execute the formula field associated
            this.onFieldAssociatedHandler();
            return this;
        },
        on: function (e, fn) {
            var that = this,
                control = this.$el.find("textarea");

            if (control) {
                control.on(e, function(event){
                    fn(event, that);

                    event.stopPropagation();
                });
            }

            return this;
        },
        getHTMLControl: function () {
            return this.$el.find("textarea");
        },
        mergeOptions : function (remoteOptions, click){
            var k, item;
            if (_.isArray(remoteOptions) && remoteOptions.length){
                item = remoteOptions[0];
                if (item.hasOwnProperty("value")){
                    this.model.attributes.data = {
                        value : item["value"],
                        label : item["value"]
                    };
                    this.model.set("value", item["value"]);
                }else{
                    this.model.set("value", "");
                    this.model.attributes.data = { value : "", label : "" };
                }
            }else{
                this.model.attributes.data = { value : "", label : "" };
                this.model.set("value", "");
            }
            return this;
        },
        fiendValueDependenField : function (dataLabel) {
            var i;
            if (!this.model.get("options").length){
                this.onDependentHandler();
            }
            for ( i = 0 ; i  < this.model.get("options").length ; i+=1 ) {
                if (this.model.get("options")[i]["label"] === dataLabel) {
                    return this.model.get("options")[i]["value"];
                }
            }
            return this.model.get("value");
        },
        setValue: function (value) {
            if (value !== undefined) {
                this.model.set("value", value);
            }
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.TextArea",TextareaView);
}());

(function () {
    var TextView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-text").html()),
        validator: null,
        keyPressed: false,
        fieldValid: [],
        previousValue: null,
        firstLoad: false,
        formulaFieldsAssociated: [],
        jsonData: {},
        dependentFields: [],
        dependentFieldsData: [],
        events: {
            "change input": "eventListener",
            "keydown input": "refreshBinding",
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        setOnChangeCallbackOperation: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallbackOperation = fn;
            }
            return this;
        },
        initialize: function () {
            var that = this;
            this.formulaFieldsAssociated = [];
            this.model.on("change:value", this.eventListener, this);
        },
        on: function (e, fn) {
            var that = this,
                control,
                localEvents = {
                    "changeValues": "setOnChangeCallbackOperation"
                };
            if (localEvents[e]) {
                this[localEvents[e]](fn);
            } else {
                control = this.$el.find("input");
                if (control) {
                    control.on(e, function (event) {
                        fn(event, that);
                        event.stopPropagation();
                    });
                } else {
                    throw new Error("Is not possible find the HTMLElement associated to field");
                }
            }
            return this;
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event) {
            this.onChange(event);
            this.checkBinding(event);
            return this;
        },
        checkBinding: function (event) {
            var form = this.model.get("form"), keyValue;

            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }
            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            this.previousValue = this.getValue();
        },
        /**
         * Updates the values in the inputs controls nodes
         * @returns {TextView}
         */
        updateValueInput: function () {
            var textInput, hiddenInput;
            textInput = this.$el.find("input[type='text']");
            hiddenInput = this.$el.find("input[type='hidden']");
            if (this.model.get("data")) {
                textInput.val(this.model.get("data")["label"]);
                hiddenInput.val(this.model.get("data")["label"]);
            }
            return this;
        },
        updateDataModel: function () {
            var data;
            data = {
                value: this.tagControl.val(),
                label: this.tagControl.val()
            };
            this.model.set("data", data);
            return this;
        },
        findKeyValue: function (value) {
            var i, options = this.model.get("options");
            for (i = 0; i < options.length; i += 1) {
                if (options[i]["label"] === value) {
                    return options[i]["value"];
                }
            }
            return null;
        },
        refreshBinding: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.keyPressed = true;
            return this;
        },
        onChange: function (event) {
            var i, dependents;
            if (event.type !== "change") {
                this.updateValueInput();
            }
            if (event.type === "change") {
                this.updateDataModel();
                this.updateValueInput();
            }
            this.onTextTransform(this.tagControl.val());
            this.validate(event);
            this.runOnDependentHandler();
            this.clicked = false;
            if (this.model.get("group") == "grid" && this.onChangeCallbackOperation) {
                if (typeof this.onChangeCallbackOperation === "function") {
                    this.onChangeCallbackOperation();
                }
            }
            this.onFieldAssociatedHandler();
            return this;
        },
        continueDependentFields: function (e) {
            var newValue,
                content;
            this.model.set("clickedControl", true);
            this.clicked = true;
            this.keyPressed = false;
            content = $(e.currentTarget).text();
            //newValue = $(this.el).find(":input").val();          
            $(this.el).find(":input[type='suggest']").val(content);
            this.model.set("value", $(e.currentTarget).find("span").data().value);
            this.containerList.remove();
            this.stackRow = 0;
            this.clicked = false;
            return this;
        },
        mergeOptions: function (remoteOptions, click) {
            var k, item;
            if (remoteOptions.length) {
                item = remoteOptions[0];
                if (item.hasOwnProperty("value")) {
                    this.model.attributes.data = {
                        value: item["value"],
                        label: item["value"]
                    };
                    this.model.set("value", item["value"]);
                } else {
                    this.model.set("value", "");
                    this.model.attributes.data = {value: "", label: ""};
                }
            } else {
                this.model.attributes.data = {value: "", label: ""};
                this.model.set("value", "");
            }
        },
        fiendValueDependenField: function (dataLabel) {
            var i;
            if (!this.model.get("options").length) {
                this.onDependentHandler();
            }
            for (i = 0; i < this.model.get("options").length; i += 1) {
                if (this.model.get("options")[i]["label"] === dataLabel) {
                    return this.model.get("options")[i]["value"];
                }
            }
            return this.model.get("value");
        },
        validate: function (event, b, c) {
            var originalValue;
            this.keyPressed = true;
            originalValue = this.tagControl.val();
            this.model.set("validate", true);
            this.model.attributes.value = originalValue;
            //this.model.set("value",originalValue);
            this.model.validate();
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.tagControl.parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            this.changeValuesFieldsRelated();
            this.onFieldAssociatedHandler();
            this.keyPressed = false;
            return this;
        },
        updateValueControl: function () {
            var inputVal = this.$el.find("input").val();
            this.model.set("value", inputVal);
            return this;
        },
        onFieldAssociatedHandler: function () {
            var i,
                fieldsAssoc = this.formulaFieldsAssociated;
            if (fieldsAssoc.length > 0) {
                for (i = 0; i < fieldsAssoc.length; i += 1) {
                    if (fieldsAssoc[i].model.get("formulator") instanceof PMDynaform.core.Formula) {
                        this.model.addFormulaTokenAssociated(fieldsAssoc[i].model.get("formulator"));
                        this.model.updateFormulaValueAssociated(fieldsAssoc[i]);
                    }
                }
            }
            return this;
        },
        onTextTransform: function (val) {
            var transformed,
                transform = this.model.get("textTransform"),
                availables = {
                    upper: function () {
                        return val.toUpperCase();
                    },
                    lower: function () {
                        return val.toLowerCase();
                    },
                    none: function () {
                        return val;
                    },
                    capitalizePhrase: function () {
                        return val.charAt(0).toUpperCase() + val.slice(1);
                    },
                    titleCase: function () {
                        return val.capitalize();
                    }
                };
            if (transform) {
                transformed = (availables[transform]) ? availables[transform]() : availables["none"]();
                this.$el.find("input[type='text']").val(transformed);
            }
            return this;
        },
        /*
         This function verify the fields valids for formulas and return a array of valid fields
         [Text, Label, suggest, dropdown, radio]
         */
        checkFieldsValidForFormula: function (allViews) {
            var indexViews = 0,
                indexFields = 0,
                responseArray = {},
                fieldsValidForFormula = [
                    "text",
                    "label",
                    "dropdown",
                    "suggest",
                    "textarea",
                    "radio",
                ];

            for (indexViews = 0; indexViews < allViews.length; indexViews++) {
                for (indexFields = 0; indexFields < fieldsValidForFormula.length; indexFields += 1) {
                    if (allViews[indexViews].model.get("type") === fieldsValidForFormula[indexFields]) {
                        responseArray[allViews[indexViews].model.get("id")] = allViews[indexViews];
                    }
                }
            }
            return responseArray;
        },

        onFormula: function (rows) {
            var fieldsList,
                that = this,
                allFields,
                allFieldsView,
                index,
                formulaField,
                idFields = {},
                fieldFormula,
                fieldValid,
                resultField,
                fieldAdded = [],
                fieldSelected,
                obj,
                i;
            if (this.model.get("group") == "grid") {
                fieldsList = rows;
            } else {
                fieldsList = this.parent.items;
            }
            //All Fields from the FORM
            allFieldsView = (fieldsList instanceof Array) ? fieldsList : fieldsList.asArray();

            idFields = this.checkFieldsValidForFormula(allFieldsView);
            fieldSelected = {};
            //Fields from the Formula PROPERTY
            formulaField = this.model.get("formula");
            fieldFormula = formulaField.split(/[\-(,|+*/\)]+/);
            if (this.model.get("group") == "grid") {
                for (var k = 0; k < rows.length; k += 1) {
                    if (fieldFormula.indexOf(rows[k].model.get("id")) > -1) {
                        rows[k].onFieldAssociatedHandler();
                    }
                }
            }
            this.fieldValid = fieldFormula.filter(function existElement(element) {
                var result = false;
                if ((idFields[element] !== undefined) && ($.inArray(element, fieldAdded) === -1)) {
                    fieldAdded.push(element);
                    result = true
                }
                return result;
            });
            //Insert the Formula object to fields selected
            for (var obj = 0; obj < this.fieldValid.length; obj += 1) {
                this.model.addFormulaFieldName(this.fieldValid[obj]);
                idFields[this.fieldValid[obj]].formulaFieldsAssociated.push(that);
                if (this.model.get("group") == "grid") {
                    if (idFields.hasOwnProperty(this.fieldValid[obj])) {
                        this.formulaFieldsAssociated.push(idFields[this.fieldValid[obj]]);
                    }
                }
            }
            return this;
        },
        getHTMLControl: function () {
            return this.$el.find("input");
        },

        render: function () {
            var hidden, name, that = this;
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.previousValue = this.model.get("value");
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[0];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
                hidden.value = this.model.get("value");
            }
            if (this.model.get("group") === "form" && this.model.get("formula")) {
                this.onFormula();
            }
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='text']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find("input[type='text']");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            this.onTextTransform(this.tagControl.val());
            return this;
        },
        setValue: function (value) {
            if (value !== undefined) {
                this.model.set("value", value);
            }
            return this;
        },
    });
    PMDynaform.extendNamespace("PMDynaform.view.Text", TextView);
}());
(function(){
	var File = PMDynaform.view.Field.extend({
		item: null,
		firstLoad : true,
		isIE : false,
		template: _.template( $("#tpl-file").html()),
		filesLength : 0,
		events: {
			"click .pmdynaform-file-container .form-control": "onClickButton",
			"click div[name='button-all'] .pmdynaform-file-buttonup": "onUploadAll",
			"click div[name='button-all'] .pmdynaform-file-buttoncancel": "onCancelAll",
			"click div[name='button-all'] .pmdynaform-file-buttonremove": "onRemoveAll"
		},
		initialize: function () {
			//this.setOnChangeFiles();
			//save the length the files before atachment
			if(_.isArray(this.model.get("value"))){
				this.filesLength = this.model.get("value").length;
			}
			this.model.on("change", this.render, this);
		},
		onClickButton: function (event) {
			if(!PMDynaform.core.ProjectMobile){
				this.$el.find("input").trigger( "click" );
				event.preventDefault();
				event.stopPropagation();
			}
			return this;
		},
		onUploadAll: function (event) {
			var i,
			length = this.model.get("items").length;

			this.$el.find("div[name='button-all'] .pmdynaform-file-buttonup").hide();
			this.$el.find("div[name='button-all'] .pmdynaform-file-buttonremove").hide();
			this.$el.find("div[name='button-all'] .pmdynaform-file-buttoncancel").show();
			for (i=0; i<length; i+=1) {
				this.model.uploadFile(i);	
			}

			event.preventDefault();
			event.stopPropagation();

			return this;
		},
		onCancelAll: function (event) {
			var i,
			length = this.model.get("items").length;

			this.$el.find("div[name='button-all'] .pmdynaform-file-buttonup").show();
			this.$el.find("div[name='button-all'] .pmdynaform-file-buttonremove").show();
			this.$el.find("div[name='button-all'] .pmdynaform-file-buttoncancel").hide();
			for (i=0; i<length; i+=1) {
				this.model.stopUploadFile(i);
			}
			event.preventDefault();
			event.stopPropagation();

			return this;
		},
		onRemoveAll: function (event) {

			this.model.set("items", []);
			this.render();
			event.preventDefault();
			event.stopPropagation();

			return this;
		},
		removeItem: function (event) {
			var items = this.model.get("items"),
			index = $(event.target).data("index");

			items.splice(index, 1);
			this.model.set("items", items);
			this.render();
			return this;
		},
		addNewItem: function (e, file) {
			if (this.validate(e, file)) {
				var items = this.model.get("items");
				items.push({
					event: e,
					file: file
				});
				this.model.set("items", items);
				/*
				 * The call to render method will not be necessary if the change event from initialize
				 * method would be working
				 **/
				this.render();
				if (items.length > 0) {
					this.$el.find(".pmdynaform-file-container div[name='button-all']").show();
				} else {
					this.$el.find(".pmdynaform-file-container div[name='button-all']").hide();
				}
			}
						
			return this;
		},
		onUploadItem: function (event) {
			var index = $(event.target).data("index");

			this.model.uploadFile(index);
			return this;
		},
		onCancelUploadItem: function () {
			var index = $(event.target).data("index");

			this.model.stopUploadFile(index);
			return this;
		},
		onToggleButtonUpload: function (event, action) {
			var i,
			j,
			buttons = $(event.target).parent().parent(),
			opt = {
				up: {
					show: [
						".pmdynaform-file-buttonstop",
						".pmdynaform-file-buttoncancel"
					],
					hide: [
						".pmdynaform-file-buttonup",
						".pmdynaform-file-buttonremove"
					]
				},
				cancel: {
					show: [
						".pmdynaform-file-buttonup",
						".pmdynaform-file-buttonremove"
					],
					hide: [
						".pmdynaform-file-buttonstop",
						".pmdynaform-file-buttoncancel"
					]
				}
			};

			for ( i=0; i<opt[action].hide.length; i+=1 ) {
				buttons.find(opt[action].hide[i]).hide();
			} 

			for ( j=0; j<opt[action].show.length; j+=1 ) {
				buttons.find(opt[action].show[j]).show();
			} 
			
			return this;
		},
		onClosePreview: function () {

			return this;
		},
		onPreviewItem: function (event) {
			var file,
			that = this,
			reader,
			previewFile,
			resource,
			previewFileName,
			heightContainer,
			shadow = document.createElement("div"),
			background = document.createElement("div"),
			preview = document.createElement("img"),
			index = $(event.target).data("index"),
			closeItem = document.createElement("span");
			closeItem.className = "glyphicon glyphicon-remove";

			closeItem.title = "Close";
			closeItem.setAttribute("data-placement", "bottom");
			
			$(closeItem).tooltip().click(function(e) {
				$(this).tooltip('toggle');
				event.preventDefault();
				event.stopPropagation();
			});

			heightContainer = $(document.documentElement).height();

			shadow.className = "pmdynaform-file-shadow";
			shadow.style.height = heightContainer + "px";
			background.className = "pmdynaform-file-preview-background"
			background.style.height = heightContainer + "px";
			background.setAttribute("contenteditable", "true");
			
			background.appendChild(closeItem);
			
			file = this.model.get("items")[index].file;
			
			if (file.type.match(/image.*/)) {
				reader  = new FileReader();
				reader.onloadend = function () {
					preview.src = reader.result;
				}
				reader.readAsDataURL(file);
				//preview.style.top = document.body.scrollTop + "px";	
			} else if(file.type.match(/audio.*/)) {
				resource = _.template( $("#tpl-audio").html());
				preview = resource({
					path: URL.createObjectURL(file),
					top: document.body.scrollTop + "px"
				});

			} else if(file.type.match(/video.*/)) {
				resource = _.template( $("#tpl-video").html());
				preview = resource({
					path: URL.createObjectURL(file),
					top: document.body.scrollTop + "px"
				});
			}/* else {

			}*/

			previewFile = document.createElement("div");
			previewFileName = document.createElement("p");
			previewFileName.name = "desc";
			previewFileName.appendChild(document.createTextNode(file.extra.nameNoExtension));
			previewFile.className = "pmdynaform-file-preview-image";
			
			$(previewFile).append(preview);
			$(previewFile).append(previewFileName);
			$(closeItem).on('click', function (event){
				document.body.removeChild(shadow);
				document.body.removeChild(background);
				that.$el.parents(".pmdynaform-container").css("position","");
				event.preventDefault();
				event.stopPropagation();
			});
			$(background).keyup(function(event){
				if (event.which === 27) {
					document.body.removeChild(shadow);
					document.body.removeChild(background);
					that.$el.parents(".pmdynaform-container").css("position","");
					event.preventDefault();
					event.stopPropagation();	
				}
			});
			
			$(background).append(previewFile);
			document.body.appendChild(shadow);
			document.body.appendChild(background);

			//Puts the parent node in Fixed mode
			this.$el.parents(".pmdynaform-container").css("position","fixed");

			return this;
		},
		renderFiles: function () {
			var i,
			that = this,
			items = this.model.get("items");

			for (i=0; i< items.length; i+=1) {
				if (that.model.get("preview")) {
					that.createBox(i, items[i].event,items[i].file);
				} else {
					that.createListBox(i, items[i].event, items[i].file);
				}
			}
			
			return this;
		},
		createButtonsHTML: function (index, opts) {
			var that = this,
			buttonGroups = document.createElement("div"),
			buttonGroupUp = document.createElement("div"),
			buttonGroupCancel = document.createElement("div"),
			buttonGroupRemove = document.createElement("div"),
			buttonGroupView = document.createElement("div"),
			buttonUp = document.createElement("button"),
			buttonCancel = document.createElement("button"),
			buttonRemove = document.createElement("button"),
			buttonView = document.createElement("button");

			/*
			 * Adding Options buttons to file
			 **/
			buttonGroups.className = "btn-group btn-group-justified";

			buttonGroupUp.className = "pmdynaform-file-buttonup btn-group";
			buttonGroupCancel.className = "pmdynaform-file-buttoncancel btn-group";
			buttonGroupCancel.style.display = "none";
			buttonGroupRemove.className = "pmdynaform-file-buttonremove btn-group";
			buttonGroupView.className = "pmdynaform-file-buttonview btn-group";

			buttonUp.className = "glyphicon glyphicon-upload btn btn-success btn-sm";
			buttonCancel.className = "glyphicon glyphicon-remove btn btn-danger btn-sm";
			buttonRemove.className = "glyphicon glyphicon-trash btn btn-danger btn-sm";
			buttonView.className = "glyphicon glyphicon-zoom-in btn btn-primary btn-sm";

			$(buttonUp).data("index", index);
			$(buttonCancel).data("index", index);
			$(buttonRemove).data("index", index);
			$(buttonView).data("index", index);
			
			$(buttonUp).on("click", function(event) {
				that.onToggleButtonUpload(event, "up");
				that.onUploadItem(event);
				event.stopPropagation();
				event.preventDefault();
			});
			$(buttonCancel).on("click", function(event) {
				that.onToggleButtonUpload(event, "cancel");
				that.onCancelUploadItem(event);
				event.stopPropagation();
				event.preventDefault();
			});
			$(buttonRemove).on("click", function(event) {
				that.removeItem(event);
				event.stopPropagation();
				event.preventDefault();
			});			
			$(buttonView).on("click", function(event) {
				that.onPreviewItem(event);
				event.stopPropagation();
				event.preventDefault();
			});

			buttonGroupUp.appendChild(buttonUp);
			buttonGroupCancel.appendChild(buttonCancel);
			buttonGroupRemove.appendChild(buttonRemove);
			buttonGroupView.appendChild(buttonView);
			if (opts.upload) {
				buttonGroups.appendChild(buttonGroupUp);
			}
			if (opts.cancel) {
				buttonGroups.appendChild(buttonGroupCancel);
			}
			if (opts.preview) {
				buttonGroups.appendChild(buttonGroupView);
			}
			if (opts.remove) {
				buttonGroups.appendChild(buttonGroupRemove);	
			}
			
			return buttonGroups;
		},
		createBox: function (index, e, file) {
			var buttonGroups,
			resource,
			enabledPreview = true,
			rand = Math.floor((Math.random()*100000)+3),
			imgName = file.name,
			src = e.target.result,
			template = document.createElement("div"),
			resizeImage = document.createElement("div"),
			preview = document.createElement("span"),
			progress = document.createElement("div"),
			imgPreview = document.createElement("img"),
			spanOverlay = document.createElement("span"),
			spanUpDone = document.createElement("span"),
			typeClasses = {
				video: {
					className: "pmdynaform-file-boxpreview-video",
					icon: "glyphicon glyphicon-facetime-video"
				},
				audio: {
					className: "pmdynaform-file-boxpreview-audio",
					icon: "glyphicon glyphicon-music"
				},
				file: {
					className: "pmdynaform-file-boxpreview-file",
					icon: "glyphicon glyphicon-book"
				}
			},
			fileName = file.extra.extension.toUpperCase();

			template.id = rand;
			template.className = "pmdynaform-file-containerimage";

			resizeImage.className = "pmdynaform-file-resizeimage";
			if (file.type.match(/image.*/)) {
				imgPreview.src = src;
				resizeImage.appendChild(imgPreview);
			} else if(file.type.match(/audio.*/)) {
				resizeImage.innerHTML = '<div class="'+ typeClasses['audio'].className +' thumbnail ' + typeClasses['audio'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else if(file.type.match(/video.*/)) {
				resizeImage.innerHTML = '<div class="'+ typeClasses['video'].className +' thumbnail ' + typeClasses['video'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else {
				enabledPreview = false,
				resizeImage.innerHTML = '<div class="'+ typeClasses['file'].className +' thumbnail ' + typeClasses['file'].icon+'"><div>'+ fileName +'</div></div>'; 
			}
			spanOverlay.className = "pmdynaform-file-overlay";
			spanUpDone.className = "pmdynaform-file-updone";
			spanOverlay.appendChild(spanUpDone);
			resizeImage.appendChild(spanOverlay);

			preview.id = rand;
			preview.className = "pmdynaform-file-preview";
			preview.appendChild(resizeImage);	    	

			progress.id = rand;
			progress.className = "pmdynaform-file-progress";
			progress.innerHTML = "<span></span>";

			template.appendChild(preview);
			buttonGroups = this.createButtonsHTML(index, {
				upload: true,
				preview: enabledPreview,
				cancel: true,
				remove: true
			});
			template.appendChild(buttonGroups);
			template.appendChild(progress);
			this.$el.find(".pmdynaform-file-droparea").append(template);
			
			return this;
			
		},
		createListBox: function (index, e, file) {
			var buttonGroups,
			enabledPreview = true,
			iconFile,
			rand = Math.floor((Math.random()*100000)+3),
			listItem = document.createElement("div"),
			label = document.createElement("div");

			listItem.className = "pmdynaform-file-listitem";
			if (file.type.match(/image.*/)) {
				//
			} else if(file.type.match(/audio.*/)) {
				//
			} else if(file.type.match(/video.*/)) {
				//
			} else {
				enabledPreview = false;				
			}
			buttonGroups = this.createButtonsHTML(index, {
				upload: true,
				preview: enabledPreview,
				cancel: true,
				remove: true
			});
			buttonGroups.style.width = "50%";
			label.className = "pmdynaform-label-nowrap";
			label.innerHTML = file.name;
			listItem.appendChild(label)
			listItem.appendChild(buttonGroups);
			

			this.$el.find(".pmdynaform-file-list").append(listItem);

			return this;
		},
		toggleButtonsAll: function () {
			//Select the name="button-all" for show the buttons

			return this;
		},
		render: function () {
			var that = this,
                fileContainer,
                fileControl,
                oprand,
                hidden,
                name,
                fileButton,
                fileControl;
            var title = 'Allowed file extensions: ' + this.model.get('extensions');
            var link;
            var i;
            var label = that.model.get("data")["label"];
			
			this.$el.html( this.template(this.model.toJSON()));
			fileControl = this.$el.find("input[type='file']");
			fileButton = that.$el.find("button[type='button']");
			if(PMDynaform.core.ProjectMobile) {
                fileButton.attr("disabled", "disabled");
            }
            link = this.$el.find("a.pmdynaform-control-file");
            fileButton.text(title);
            fileButton[0].title = title;
			hidden = this.$el.find("input[type='hidden']");
			fileContainer = this.$el.find(".pmdynaform-file-control")[0];
			
			if (this.model.get("hint")) {
				this.enableTooltip();
			}

            if (link.length > 0 && label.length > 0) {
                for (i = 0; i < label.length; i += 1) {
                    link.children()[i].title = label[i];
                }
            }
			$(hidden).val(JSON.stringify(this.model.get("data")["label"]));
			if (this.model.get("group") === "grid") {
				hidden = this.$el.find("input[type = 'hidden']")[0];
				name = this.model.get("name");
				name = name.substring(0, name.length - 1).concat("_label]");
				hidden.name = hidden.id = "form" + name;
				hidden.value = this.model.get("value");
			}
			if (this.model.get("name").trim().length === 0) {
				this.$el.find("input[type='file']").attr("name", "");
				this.$el.find("input[type='hidden']").attr("name", "");
			}
			fileControl.change(function(e, ui){
				var file = e.target, nameFileLoad;
                var fileButton;
				if (file.value  && that.isValid(file)){
                    fileButton = that.$el.find("button[type='button']");
					if (file.files){
						nameFileLoad = file.files[0].name;
					} else {
						nameFileLoad = file.value.split("\\")[2]
					}
                    fileButton.text(nameFileLoad);
                    fileButton[0].title = nameFileLoad;
                    if (that.model.get("data")) {
                        if ( that.model.get("data")["label"].length) {
                            if ( that.model.get("data")["label"].indexOf(nameFileLoad) === -1  ) {
                                if (that.firstLoad){
                                    that.model.get("data")["label"].push(nameFileLoad);
                                }else{
                                    that.model.get("data")["label"].splice(that.model.get("data")["label"].length-1);
                                    that.model.get("data")["label"].push(nameFileLoad);
                                }
                            }
                        } else {
                            that.model.get("data")["label"].push(nameFileLoad);
                        }
                    }
					$(hidden).val(JSON.stringify(that.model.get("data")["label"]));
					that.firstLoad = false;
				} else {
                    file.value = "";
                    file.files = null;
                }
			});
			return this;
		},
        getFileType: function (file) {
            var type;
            var fileTarget;
            if (file.files){
                if (file.files[0]){
                    type = file.files[0].name.substring(file.files[0].name.lastIndexOf(".")+1);
                    fileTarget = file.files[0].name;
                }
            } else {
                if (file.value.trim().length){
                    type = file.value.split("\\")[2].substring(file.value.split("\\")[2].lastIndexOf(".")+1);
                    fileTarget = file.value;
                }
            }
            return {
                type: type,
                fileTarget: fileTarget
            }
        },
		isValid : function (file) {
			var validated = false, extensions, maxSize, type, fileTarget;
            var that = this;
            var errorType = {};
            extensions = this.model.get("extensions");
            var getType = that.getFileType(file);
            var validatorModel = this.model.get('validator');
            var tagFile = this.$el.find("input[type='file']")[0];
            var fileButton = that.$el.find("button[type='button']");
            var title = 'Allowed file extensions: ' + extensions;
            var maxSizeInt = parseInt(this.model.get("size"), 10);
            var sizeUnity = this.model.get("sizeUnity");

			if (this.model.get("sizeUnity").toLowerCase() !== "kb" ){
				maxSize = maxSizeInt*1024;
			} else {
				maxSize = maxSizeInt;
			}
			if (extensions === "*" || extensions === ".*"){
				validated = true;
			} else {
				if (this.model.get("extensions").indexOf(getType.type) > -1) {
					validated = true;
				}else{
                    errorType = {
                        type: 'support',
                        message: 'The file extension is not supported. Supported extension(s): '
                        + extensions
                    };
                    validatorModel.set('fileOnly',  errorType);
                    fileButton.text(title);
                    fileButton[0].title = title;
                    validated = false;
				}
			}
			if (validated && file.files){
				if ( file.files[0] && (file.files[0].size/1024 <= maxSize) ){
					validated = true
				}else{
                    errorType = {
                        type: 'size',
                        message:  "The file size exceeds the limit. Max allowed limit is: " + maxSizeInt + sizeUnity
                    };
                    validatorModel.set('fileOnly',  errorType);
                    fileButton.text(title);
                    fileButton[0].title = title;
                    validated = false;
				}				
			}
			if (validated){
                validatorModel.set('fileOnly', null);
                this.updateValue(file.value);
				if (this.validator){
					this.validator.$el.remove();
					this.$el.removeClass('has-error has-feedback');
                    this.$el.removeClass('has-warning has-feedback');
				}
			} else {
                if(this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                    this.$el.removeClass('has-warning has-feedback');
                }
                if (!this.model.isValid() && validatorModel.get('fileOnly') !== null) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    $(tagFile).prev().append(this.validator.el);
                    if (this.model.get('validator').get('fileOnly')) {
                        this.applyStyleWarning();
                        this.validator.$el.children().removeClass('alert');
                        this.validator.$el.children().addClass('warning');
                    }
                    validatorModel.set('fileOnly', null);
                    this.model.attributes.value = "";
                }
            }
			return validated;
		},
		validate: function(e) {
			var tagFile = this.$el.find("input[type='file']")[0], validated = true;
			if (this.model.get("mode")=="view" || this.model.get("mode")=="disabled"){
				return true;
			}
			if (this.model.get("enableValidate")) {
				if(this.validator){
					this.validator.$el.remove();
					this.$el.removeClass('has-error has-feedback');
                    this.$el.removeClass('has-warning has-feedback');
				}
				if (!this.model.isValid()){
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    $(tagFile).prev().append(this.validator.el);
                    this.applyStyleError();
                    this.model.get('validator').get('fileOnly', null);
                    validated = false;
                    this.model.attributes.valid = false;
				}else{
					validated = true;
					this.model.attributes.valid = true;
				}
			}else{
				this.model.attributes.valid = true;
			}
			return validated;	
		},
        /**
         * updates the values of the value of the model
         * @param  {string} pathFile the new path to update
         * @return {this}
         */
        updateValue: function (pathFile) {
            var value = this.model.get("value");
            if (pathFile && _.isArray(value)) {
                value.push(pathFile);
                if (value.length === this.filesLength + 1) {
                    this.model.attributes.value = value;
                } else {
                    value.splice(this.filesLength, 1);
                    this.model.attributes.value = value;
                }
            }
            return this;
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.File",File);
}());

(function () {
    var CheckGroupView = PMDynaform.view.Field.extend({
        item: null,
        template: _.template($("#tpl-checkgroup").html()),
        previousValue: null,
        events: {
            "change input": "eventListener",
            "keydown input": "preventEvents"
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        initialize: function () {
            this.model.on("change:value", this.eventListener, this);
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event, value) {
            this.onChange(event, value);
            this.checkBinding(event, value);
        },
        checkBinding: function () {
            var form = this.model.get("form");
            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), JSON.stringify(this.previousValue));
            }
            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), JSON.stringify(this.model.get("value")), JSON.stringify(this.previousValue));
            }
            return this;
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }

            return this;
        },
        render: function () {
            var hidden, name;
            this.$el.html(this.template(this.model.toJSON()));
            this.$el.find(".form-control").css({
                height: "auto"
            });
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.setValueToDomain();
            this.$el.find("input[type='hidden']")[0].name = "form[" + this.model.get("name") + "_label]";
            this.setValueHideControl();
            this.previousValue = this.model.get("value");
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='checkbox']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find(".pmdynaform-checkbox-items");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            return this;
        },
        validate: function () {
            this.model.set({}, {validate: true});
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find(".pmdynaform-control-checkbox-list").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            return this;
        },
        onChange: function (event, value) {
            this.updateValues(event, value);
            this.validate();
        },
        updateValues: function (event, values) {
            var i,
                dataChecked = {
                    values: [],
                    labels: []
                },
                item,
                elements,
                data,
                inputsTag;

            if (values && _.isArray(values)) {
                inputsTag = this.$el.find("input[type='checkbox']");
                inputsTag.attr("checked", false);
                elements = this.$el.find("input[type='checkbox']");
                for (i = 0; i < values.length; i += 1) {
                    item = values[i];
                    data = this.checkValueInOptions(item);
                    if (data) {
                        elements[data["index"]].checked = true;
                        dataChecked.values.push(data['value']);
                        dataChecked.labels.push(data['label']);
                    }
                }
            } else {
                dataChecked = this.getDataChecked();
            }
            this.model.set("labelsSelected", dataChecked.labels);
            this.model.attributes.value = dataChecked.values;
            this.updateDataModel(dataChecked.values, dataChecked.labels);
            this.$el.find("input[type='hidden']").val(JSON.stringify(dataChecked.labels));
            return this;
        },
        getDataChecked: function () {
            var dataChecked = {
                    values: [],
                    labels: []
                },
                checkedControls = this.$el.find("input[type='checkbox']:checked");
            checkedControls.each(function (index, element) {
                dataChecked.values.push(element.value);
                dataChecked.labels.push($(element).next().text());
            });
            return dataChecked;
        },
        checkValueInOptions: function (item) {
            var options, i;
            options = this.model.get("options");
            for (i = 0; i < options.length; i += 1) {
                if (options[i]["value"] === item) {
                    return {
                        value: options[i]["value"],
                        label: options[i]["label"],
                        index: i
                    }
                }
            }
            return false;
        },
        updateDataModel: function (value, label) {
            var data;
            if (jQuery.isArray(label)) {
                label = JSON.stringify(label);
            }
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        getHTMLControl: function () {
            return this.$el.find(".pmdynaform-control-checkbox-list");
        },
        setValueHideControl: function () {
            var control;
            control = this.$el.find("input[type='hidden']");
            $(control).val(JSON.stringify(this.model.get("labelsSelected")));
            return this;
        },
        setValue: function (value) {
            if (value && $.isArray(value)) {
                this.model.set("value", value);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.CheckGroup", CheckGroupView);
}());
(function () {
    var CheckBoxView = PMDynaform.view.Field.extend({
        item: null,
        template: _.template($("#tpl-checkbox_yes_no").html()),
        previousValue: null,
        /**
         * This property is a handler the events
         * change input: when the change the control input type checkbox is fired the method handler event Listener
         */
        events: {
            "change input": "eventListener",
            "keydown input": "preventEvents"
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        initialize: function () {
            this.model.on("change:value", this.eventListener, this);
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event, value) {
            this.onChange(event, value);
            this.checkBinding(event, value);
        },
        checkBinding: function () {
            var form = this.model.get("form");
            try {
                if (typeof this.onChangeCallback === 'function') {
                    this.onChangeCallback(JSON.stringify(this.getValue()), JSON.stringify(this.previousValue));
                }
                if (form && form.onChangeCallback) {
                    form.onChangeCallback(this.model.get("id"), JSON.stringify(this.model.get("value")), JSON.stringify(this.previousValue));
                }
            } catch (e) {
                console.error(e);
            }
            return this;
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        render: function () {
            var hidden, name;
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.setValueToDomain();
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[0];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
            } else {
                this.$el.find("input[type='hidden']")[0].name = "form[" + this.model.get("name") + "_label]";
            }
            if (this.model.get("options").length) {
                this.$el.find("input[type='checkbox']").eq(0).data({
                    value: this.model.get("options")[0]["value"],
                    label: this.model.get("options")[0]["label"]
                });
                this.$el.find("input[type='checkbox']").eq(1).data({
                    value: this.model.get("options")[1]["value"],
                    label: this.model.get("options")[1]["label"]
                });
            }
            this.setValueHideControl();
            this.previousValue = this.model.get("value");

            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='checkbox']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find(".pmdynaform-checkbox-items");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            return this;
        },
        validate: function () {
            this.model.set({}, {validate: true});
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find(".pmdynaform-control-checkbox-list").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            return this;
        },
        /**
         * This method is fired when the property value is changed
         * @param event: is the event target tag
         * @param value: is the change model when use set method
         */
        onChange: function (event, value) {
            this.previousValue = this.model.get("value");
            this.updateValues(event, value);
            this.validate(event, value);
            return this;
        },


        getHTMLControl: function () {
            return this.$el.find(".pmdynaform-control-checkbox-list");
        },
        setValueHideControl: function () {
            var control;
            control = this.$el.find("input[type='hidden']");
            if (this.model.get("dataType") === "boolean") {
                $(control).val(this.model.get("data")["label"].toString());
            } else {
                try {
                    $(control).val(JSON.stringify(this.model.get("data")["label"]));
                }
                catch (e) {
                    console.error(e);
                }
            }
            return this;
        },
        updateValues: function (event, value) {
            var options, i, newValue, newLabel, element, element2, isChecked = false;
            options = this.model.get("options");
            if (event.type === "change") {
                element = event.target;
                if (element.checked){
                    this.$el.find("input[type='checkbox']").eq(1).prop("checked",false);
                    isChecked = true;
                }else{
                    this.$el.find("input[type='checkbox']").eq(1).prop("checked",true);
                    isChecked = false;
                }
            } else {
                element = this.$el.find("input[type='checkbox']").eq(0);
                element2 = this.$el.find("input[type='checkbox']").eq(1);
                if (value === 1 || value === "1" || value === true){
                    element.prop("checked",true);
                    element2.prop("checked",false);
                    isChecked = true;
                }else{
                    element.prop("checked",false);
                    element2.prop("checked",true);
                    isChecked = false;
                }
            }
            if(isChecked){
                newValue = options[0]["value"];
                newLabel = options[0]["label"];
            }else{
                newValue = options[1]["value"];
                newLabel = options[1]["label"];
            }
            this.model.attributes.value = newValue;
            this.updateDataModel(newValue, newLabel);
            this.$el.find("input[type='hidden']").val(newLabel);
            return this;
        },
        updateDataModel: function (value, label) {
            var data;
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        setValue : function (value){
            var valuesfortrue, valuesforFalse, value;
            valuesfortrue = [1, true, "1", "true"];
            valuesforFalse = [0, false, "0", "false"];
            value = (_.isArray(value) && value.length > 0)? value[0] : value;
            if (value !== undefined){
                if (valuesfortrue.indexOf(value) > -1){
                    this.model.set("value",1);
                }
                if (valuesforFalse.indexOf(value) > -1){
                    this.model.set("value",0);
                }
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.CheckBox", CheckBoxView);
}());

(function () {

    var SuggestView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-text").html()),
        templateList: _.template($("#tpl-suggest-list").html()),
        validator: null,
        elements: [],
        input: null,
        containerList: null,
        makeFlag: false,
        keyPressed: false,
        pointerItem: 0,
        orientation: "under",
        stackItems: [],
        stackRow: 0,
        clicked: false,
        firstLoad: true,
        dirty: false,
        jsonData: {},
        previousValue: "",
        dependentFields: [],
        dependentFieldsData: [],
        prevValueprevValue: "",
        newValue: "",
        prevValue: "",
        numberOfOptions : 10,
        containerOpened : false,
        events: {
            "click li": "continueDependentFields",
            "keyup input": "generateOptions",
            "change input": "eventListener",
            "keydown input": "refreshBinding"
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        initialize: function () {
            var that = this;
            this.formulaFieldsAssociated = [];
            this.model.on("change:value", this.eventListener, this);
            this.containerList = $(this.templateList());
            this._initListEventListener();
            this.enableKeyUpEvent();
        },
        _initListEventListener: function () {
            var currentTarget, label, value, that = this;
            $(this.containerList).on('keydown', function (e) {
                var code = e.which;
                e.stopPropagation();
                e.preventDefault();
                if (that.stackItems.length > 0) {
                    if (code === 38 && that.stackRow > 1) { // UP
                        that.stackRow -= 1;
                        that.toggleItemSelected();
                    } else if (code === 40 && that.stackRow < that.stackItems.length) { // DOWN
                        that.stackRow += 1;
                        that.toggleItemSelected();
                    } else if (code === 13) { //ENTER
                        currentTarget = $("body").find(".pmdynaform-suggest-list-keyboard")[0];
                        label = $(currentTarget).text();
                        value = $(currentTarget).find("a").data().value;
                        that.hideSuggest();
                        that.eventListener(e, {value:value,label:label});
                    }
                }

            }).on('mouseover', 'li', function (e) {
                that.stackRow = $(that.stackItems).index(this) + 1;
                that.toggleItemSelected();
            });
        },
        eventListener: function (event, value) {
            this.onChange(event, value);
            this.onFieldAssociatedHandler();
        },
        generateOptions: function (event) {
            var suggest, that = this;
            if (this.validator){
                this.validate();
            }
            suggest = this.$el.find("input[type='suggest']");
            this.model.attributes.value = suggest.val();
            if (suggest.val() !== this.prevValue) {
                this.keyPressed = false;
                this.prevValue = suggest.val();
                if (event && event.type !== "submit") {
                   if (this.timeoutHandler) {
                      clearTimeout(this.timeoutHandler);
                   } 
                   this.timeoutHandler = setTimeout(function () {
                      that.suggestPanelFactory(that.numberOfOptions, event);
                   }, 750);                   
                }
            }
        },
        refreshBinding: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.keyPressed = true;
            return this;
        },
        checkBinding: function (event) {
            //If the key is not pressed, executes the render method
            var form = this.model.get("form");
            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }
            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            this.previousValue = this.getValue();
        },
        updateValueInput: function () {
            var textInput, hiddenInput;
            textInput = this.$el.find("input[type='suggest']");
            hiddenInput = this.$el.find("input[type='hidden']");
            if (this.model.get("data")) {
                textInput.val(this.model.get("data")["label"]);
                hiddenInput.val(this.model.get("data")["value"]);
            }
            return this;
        },
        setValueDefault: function () {
            this.model.set("value", $(this.el).find(":input").val());
        },
        hideSuggest: function () {
            this.containerOpened = false;
            this.containerList.hide();
            this.stackRow = 0;
            this.containerList.empty();
        },
        showSuggest: function () {
            this.containerOpened = true;
            this.containerList.empty();
            this.containerList.show();
        },
        _calculatePosition: function () {
            var element, position, leftListElement, topListElement, fullHeight;
            element = this.$el.find("input[type='suggest']");
            if (element[0].getBoundingClientRect) {
                position = element[0] ? element[0].getBoundingClientRect() : {};
                if (position["top"] !== undefined) {
                    document.body.appendChild(this.containerList[0]);
                    leftListElement = position.left;
                }
                fullHeight = position.top + $(element).outerHeight() + this.containerList.outerHeight();
                if (fullHeight > $(window).outerHeight()) {
                    topListElement = position.top + this._getScrollOffsets() - this.containerList.outerHeight();
                } else {
                    topListElement = position.top + this._getScrollOffsets() + element.outerHeight();
                }
                this.containerList.css({
                    position: "absolute",
                    width: element.outerWidth(),
                    "min-width": 100,
                    left: leftListElement,
                    top: topListElement
                });
            }
        },
        _getScrollOffsets: function () {
            return document.body.scrollTop || document.documentElement.scrollTop || window.pageYOffset || getScrollTop();
        },
        _attachSuggestGlobalEvents: function () {
            if (this.containerList) {
                $(document).on("click." + this.$el, $.proxy(this.hideSuggest, this));
            }
        },
        _detachSuggestGlobalEvents: function () {
            if (!this.containerList) {
                $(document).off("click." + this.$el);
            }
        },
        onChange: function (event, value) {
            var i,
                valueSelected,
                hidden;

            this.updateValues(event, value);
            this.runOnDependentHandler();
            this.clicked = false;

            return this;
        },
        updateValues: function (event, value) {
            var hiddenInput, label, newValue, suggestControl;
            hiddenInput = this.$el.find("input[type='hidden']");
            suggestControl = this.$el.find("input[type='suggest']");
            if(event.type === "change"){
                hiddenInput.val(this.model.get("value"));
            }
            if(event.type === "click" || event.keyCode === 13) {
                hiddenInput.val(value.value);
                suggestControl.val(value.label);
            }
            if (!event.type && !event.keyCode){
                hiddenInput.val(value);
                suggestControl.val(value);
            }
            label = suggestControl.val();
            newValue = hiddenInput.val();
            this.model.set("keyLabel", label);
            this.model.attributes.value = newValue;
            this.updateDataModel(newValue, label);
            if(!this.containerOpened){
                //console.log(event.target, event.type);
                this.checkBinding(event);
            }
            return this;
        },
        updateDataModel: function (value, label) {
            var data;
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        /**
         * builds suggest panel
         * @param maxItems
         * @param event
         */
        suggestPanelFactory: function (maxItems, event) {
            var val,
                restData = [],
                localData = [],
                that = this;
            this.input = this.$el.find("input[type='suggest']");
            val = this.input.val();
            this.elements = [];
            this.elements = this.model.get("options");
            this._detachSuggestGlobalEvents();
            this.showSuggest();
            this.stackItems = [];

            if (this.containerList !== null) {
                this.containerList.empty();
            }
            if (val !== "") {
                localData = this.filterLocalOptions(val, this.numberOfOptions);
                if (this.model.get('sql') && this.model.get("sql") !== '') {
                    restData = this.executeSuggestQuery(function(data){
                        that.refreshSuggestList(localData.concat(data), val, that.numberOfOptions, event);
                        if (localData.length > 0 || data.length > 0) {
                            that._calculatePosition();
                        } else {
                            that.hideSuggest();
                        }
                    });
                } else {
                    this.refreshSuggestList(localData, val, this.numberOfOptions);
                    if (_.isArray(localData) && localData.length > 0 ) {
                        that._calculatePosition();
                    } else {
                        that.hideSuggest();
                    }
                }
                //this.refreshSuggestList(restData, val, 10);
                // to hide suggest panel

            } else {
                this.hideSuggest();
            }
        },
        /**
         * filter local options pased in html
         */
        filterLocalOptions: function (value, maxItems) {
            var options = this.model.get("localOptions"),
                itemLabel,
                that = this,
                count = 0,
                data = [];
            //filter data
            $.grep(that.elements, function (options, index) {
                itemLabel = options.label.toString();
                if ((itemLabel.toLowerCase().indexOf(value.toLowerCase()) !== -1) && count < maxItems) {
                    that.updatingItemsList(options);
                    data.push(options);
                    $(that.stackItems[that.stackRow]).addClass("pmdynaform-suggest-list-keyboard");
                    count += 1;
                }
            });
            return data;
        },
        /**
         * refresh suggest panel
         * @param data
         */
        refreshSuggestList: function (data, value, maxItems, event) {
            var i,
                max,
                listItem;
            this.stackItems = [];
            this.containerList.empty();
            for (i = 0, max = data.length; i < max; i += 1) {
                // parse text to label to correctly work
                data[i].label = data[i].text ? data[i].text : data[i].label;
                listItem = this.updatingItemsList(data[i]);
                this.stackItems.push(listItem);
            }
        },

        updatingItemsList: function (data) {
            var li = document.createElement("li"),
                link = document.createElement("a"),
                that = this;

            link.appendChild(document.createTextNode(data.label));
            link.href="#";
            link.setAttribute("data-value", data.value);
            link.setAttribute("selected", false);
            li.appendChild(link);
            li.className = "list-group-item";

            $(li).click(function (e) {
                that.continueDependentFields(e);
            });

            //this.stackItems.push(li);

            this.containerList.append(li);

            this.input.after(this.containerList);
            this.containerList.css("position", "absolute");
            this.containerList.css("zIndex", 2000);
            this.containerList.css("border-radius", "5px");

            if (this.stackItems.length > 4) {
                this.containerList.css("height", "200px");
            } else {
                this.containerList.css("height", "auto");
            }

            this._attachSuggestGlobalEvents();
            return li;
        },
        continueDependentFields: function (e) {
            var newValue,
                content,
                label,
                value;
            e.stopPropagation();
            e.preventDefault();
            this.model.set("clickedControl", true);
            this.clicked = true;
            this.keyPressed = false;
            label = $(e.currentTarget).text();
            value = $(e.currentTarget).find("a").data().value;
            this.hideSuggest();
            this.stackRow = 0;
            this.clicked = false;
            // For execute the formula field associated
            this.onChange(e,{value: value, label: label})
            this.onFieldAssociatedHandler();
            return this;
        },

        validate: function (event) {
            var that = this;
            if (event && (event.which === 9) && (event.which !== 0)) { //tab key
                this.keyPressed = true;
            }
            this.model.attributes.value = this.$el.find("input[type='suggest']").val();
            this.newValue = this.$el.find("input[type='suggest']").val();
            this.model.attributes.validator.set("valid", true);
            this.model.validate(this.model.toJSON());
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find("input[type='suggest']").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            return this;
        },
        render: function () {
            var data,
                that = this,
                hidden,
                name;
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            data = this.model.get("data");
            if (this.firstLoad) {
                if (this.model.get("value") && data) {
                    this.$el.find("input[type='suggest']").val(data["label"] ? data["label"] : "");
                } else {
                    this.model.emptyValue();
                }
            } else {
                this.$el.find("input[type='suggest']").val(data["label"] ? data["label"] : "");
            }
            this.setNameHiddenControl();
            this.$el.find("input[type='suggest']").focus();
            this.setValueToDomain();

            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='suggest']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find("input[type='hidden']");
            this.tagHiddenToLabel = this.$el.find("input[type='suggest']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            this.prevValue = this.$el.find("input[type='suggest']").val();
            this.previousValue = this.model.get("value");
            return this;
        },
        /**
         * Executes Suggest query to recovery all data
         * considering dependent fields
         * @returns {*}
         */
        executeSuggestQuery: function (callback) {
            var resp,
                i,
                prj,
                postData,
                parentDependents,
                variable,
                data = {
                    "filter": this.model.get('value'),
                    "order_by": "ASC",
                    "limit": 10
                };
            parentDependents = this.model.get("parentDependents");
            for (i = 0; i < parentDependents.length; i += 1) {
                if (parentDependents[i].get("variable") !== "") {
                    data[parentDependents[i].get("variable")] = parentDependents[i].get("value");
                } else {
                    if (parentDependents[i].get("group") === "grid") {
                        data[parentDependents[i].get("columnName")] = parentDependents[i].get("value");
                    } else {
                        data[parentDependents[i].get("id")] = parentDependents[i].get("value");
                    }
                }
            }
            postData = this.preparePostData();
            $.extend(true, data, postData);
            prj = this.model.get("project");
            if (this.model.get("group") === "grid") {
                variable = this.model.get("columnName");
            } else {
                if (this.model.get("variable") !== "") {
                    variable = this.model.get("variable");
                } else {
                    variable = this.model.get("id");
                }
            }
            resp = prj.webServiceManager.executeQuerySuggest(data, variable, callback);
            return resp;
        },
        mergeOptions: function (remoteOptions) {
            var k, remoteOpt = [], localOpt = [], options;
            for (k = 0; k < remoteOptions.length; k += 1) {
                remoteOpt.push({
                    value: remoteOptions[k].value,
                    label: remoteOptions[k].text
                });
            }
            localOpt = this.model.get("localOptions");
            this.model.attributes.remoteOptions = remoteOpt;
            this.model.attributes.optionsSql = remoteOpt;
            options = localOpt.concat(remoteOpt);
            this.model.attributes.options = options;
            if (options.length) {
                this.model.attributes.data = {
                    value: options[0]["value"],
                    label: options[0]["label"]
                }
                this.updateValueInput();
                this.model.set("value", options[0]["value"]);
            }
            return options;
        },
        toggleItemSelected: function () {
            $(this.stackItems).removeClass("pmdynaform-suggest-list-keyboard");
            $(this.stackItems[this.stackRow-1]).addClass("pmdynaform-suggest-list-keyboard");
            $(this.stackItems[this.stackRow-1]).find("a").focus();
            return this;
        },
        enableKeyUpEvent: function () {
            var that = this,
                containerScroll;

            this.$el.keyup(function (event) {
                var code;
                if (that.stackItems.length > 0) {
                    code = event.which;
                    if (code === 38) {
                        that.stackRow = that.stackItems.length;
                    } else if (code === 40) {
                        that.stackRow = 1;
                    } else {
                        return;
                    }
                    that.toggleItemSelected();
                }
            });

        },
        updateValueControl: function () {
            var inputVal = this.$el.find("input[type='suggest']").val();

            this.model.set("value", inputVal);

            return this;
        },
        getHTMLControl: function () {
            return this.$el.find("input[type='suggest']");
        },
        afterRender: function () {
            return this;
        },
        setNameHiddenControl: function () {
            var hidden;
            if (this.el) {
                if (this.model.get("group") === "grid") {
                    hidden = this.$el.find("input[type = 'hidden']")[0];
                    name = this.model.get("name");
                    name = name.substring(0, name.length - 1).concat("_label]");
                    this.$el.find("input[type='suggest']")[0].name = "form" + name;
                    this.$el.find("input[type='suggest']")[0].id = "form" + name;
                } else {
                    this.$el.find("input[type='suggest']")[0].name = "form[" + this.model.get("name") + "_label]";
                }
            }
            return this;
        },
        setValue: function (value) {
            if (value !== undefined) {
                this.model.set("value", value);
            }
            return this;
        },
        setData: function (data) {
            var dataObject, dependents;
            if (typeof data === "object") {
                dataObject = {
                    value: data['value'] !== undefined ? data['value'] : "",
                    label: data['label'] !== undefined ? data['label'] : ""
                }
                this.model.set("data", dataObject);
                this.model.attributes.value = dataObject["value"];
                dependents = this.model.get("dependents");
                if (_.isArray(dependents) && dependents.length) {
                    this.runOnDependentHandler();
                }
                this.render();
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Suggest", SuggestView);
}());
(function () {
    var LinkView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-link").html()),
        validator: null,
        initialize: function () {
            var that = this,
                href;
            this.model.on("change", this.render, this);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.tagControl = this.tagHiddenToLabel = this.$el.find(".pmdynaform-control-link span");
            return this;
        },
        setText: function (value) {
            if (value !== undefined) {
                this.model.set("text", value);
            }
            return this;
        },
        updateValues: function (value) {
            this.$el.find(".pmdynaform-control-link span").text(value);
            this.model.set("data", {
                value: value,
                label: value
            });
            return this;
        },
        validationURL :function (url){
            return this.model.validationURL(url);
        },
        reformatURL : function (url){
            return this.model.reformatURL(url);
        },
        setHref: function (href) {
            if (value !== undefined) {
                this.model.setHref(href);
            }
            return this;
        },
        setValue: function (value) {
            return this.model.set("value", value);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Link", LinkView);
}());

(function () {

    var Label = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-label").html()),
        validator: null,
        singleControl: [],
        fieldValid: [],
        formulaFieldsAssociated: [],
        initialize: function () {
            this.model.on("change:value", this.eventListener, this);
            this.optionsControl = ["dropdown", "checkgroup", "radio", "suggest", "checkbox"]
        },
        eventListener: function (event) {
            this.onChange(event);
            this.render();
            return this;
        },
        onChange: function (event) {
            var data;
            data = this.model.get("data");
            this.setFullOptions(data["label"] ? data["label"] : "");
            this.runOnDependentHandler();
            return this;
        },
        render: function () {
            var hidden, name, newDateTime, $textAreaContent, msie;
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            if (this.model.get("originalType") === "datetime") {
                newDateTime = this.renderDataTimeViewMode();
            }
            this.setDataInHiddenControls(this.model.get("originalType"));
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[0];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("]");
                hidden.name = hidden.id = "form" + name;

                hidden.name = hidden.id = "form" + name;
                hidden = this.$el.find("input[type = 'hidden']")[1];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
            }

            this.tagControl = this.$el.find("input[type='hidden']").eq(0);
            this.keyLabelControl = this.$el.find("input[type='hidden']").eq(1);
            if (newDateTime) {
                this.keyLabelControl.val(newDateTime);
                this.model.set('keyValue', newDateTime);
            }

            $textAreaContent = this.$el.find("span.label-textarea");
            if($textAreaContent.length) {
                $textAreaContent.html($textAreaContent.html().replace(/(?:\r\n|\r|\n)/g, "<br />"));

                msie = window.navigator.userAgent.indexOf("MSIE ");
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                    $textAreaContent.parent().css("display","inline-table");
                }
            }

            return this;
        },
        /**
         * render datetime field at view mode
         *
         **/
        renderDataTimeViewMode: function () {
            var formatedText = '', data;
            data = this.model.get('data');
            if (this.model.get("format") && data['value']) {
                formatedText = moment(data['value']).format(this.model.get('format'));
            } else {
                formatedText = this.model.get("data")['label'];
            }
            this.$el.find(".label-" + this.model.get("originalType")).text(formatedText);
            return formatedText;
        },
        setDataInHiddenControls: function (type) {
            var labels;
            if (type && type && type.trim().length) {
                if (this.optionsControl.indexOf(type) !== -1) {
                    if (type === "suggest") {
                        this.$el.find("input[type='hidden']")[0].value = this.model.get("data")["value"];
                        this.$el.find("input[type='hidden']")[1].value = this.model.get("data")["label"];
                    } else {
                        this.$el.find("input[type='hidden']")[0].value = this.model.get("data")["value"];
                    }
                    if (type === "checkgroup") {
                        labels = PMDynaform.core.Utils.isJsonAndParse(this.model.get("data")["label"]);
                        this.$el.find("input[type='hidden']")[1].value = JSON.stringify(labels);
                    }
                    if (type === "dropdown" || type === "radio") {
                        this.$el.find("input[type='hidden']")[1].value = this.model.get("data")["label"];
                    }
                } else {
                    if (type === "datetime") {
                        this.$el.find("input[type='hidden']")[0].value = this.model.get("data")["value"];
                        this.$el.find("input[type='hidden']")[1].value = this.model.get("data")["label"];
                    }
                }
            }
            return this;
        },
        onFieldAssociatedHandler: function () {
            var i,
                fieldsAssoc = this.formulaFieldsAssociated;
            if (fieldsAssoc.length > 0) {
                for (i = 0; i < fieldsAssoc.length; i += 1) {
                    if (fieldsAssoc[i].model.get("formulator") instanceof PMDynaform.core.Formula) {
                        this.model.addFormulaTokenAssociated(fieldsAssoc[i].model.get("formulator"));
                        this.model.updateFormulaValueAssociated(fieldsAssoc[i]);
                    }
                }
            }
            return this;
        },
        setOnChangeCallbackOperation: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallbackOperation = fn;
            }
            return this;
        },
        on: function (e, fn) {
            var that = this,
                control,
                localEvents = {
                    "changeValues": "setOnChangeCallbackOperation"
                };
            if (localEvents[e]) {
                this[localEvents[e]](fn);
            } else {
                control = this.$el.find("input");
                if (control) {
                    control.on(e, function (event) {
                        fn(event, that);
                        event.stopPropagation();
                    });
                } else {
                    throw new Error("Is not possible find the HTMLElement associated to field");
                }
            }
            return this;
        },
        onFormula: function (rows) {
            var fieldsList,
                that = this,
                allFields,
                allFieldsView,
                index,
                formulaField,
                idFields = {},
                fieldFormula,
                fieldValid,
                resultField,
                fieldAdded = [],
                fieldSelected,
                obj,
                i;
            if (this.model.get("group") == "grid") {
                fieldsList = rows;
            } else {
                fieldsList = this.parent.items;
            }
            //All Fields from the FORM
            allFieldsView = (fieldsList instanceof Array) ? fieldsList : fieldsList.asArray();

            for (index = 0; index < allFieldsView.length; index += 1) {
                if (allFieldsView[index] instanceof PMDynaform.view.Text) {
                    idFields[allFieldsView[index].model.get("id")] = allFieldsView[index];
                }
                if (allFieldsView[index] instanceof PMDynaform.view.Label) {
                    idFields[allFieldsView[index].model.get("id")] = allFieldsView[index];
                }
            }

            fieldSelected = {};
            //Fields from the Formula PROPERTY
            formulaField = this.model.get("formula");
            fieldFormula = formulaField.split(/[\-(,|+*/\)]+/);
            if (this.model.get("group") == "grid") {
                for (var k = 0; k < rows.length; k += 1) {
                    if (fieldFormula.indexOf(rows[k].model.get("id")) > -1) {
                        rows[k].onFieldAssociatedHandler();
                    }
                }
            }
            this.fieldValid = fieldFormula.filter(function existElement(element) {
                var result = false;
                if ((idFields[element] !== undefined) && ($.inArray(element, fieldAdded) === -1)) {
                    fieldAdded.push(element);
                    result = true
                }
                return result;
            });
            //Insert the Formula object to fields selected
            for (var obj = 0; obj < this.fieldValid.length; obj += 1) {
                this.model.addFormulaFieldName(this.fieldValid[obj]);
                idFields[this.fieldValid[obj]].formulaFieldsAssociated.push(that);
                if (this.model.get("group") == "grid") {
                    if (idFields.hasOwnProperty(this.fieldValid[obj])) {
                        this.model.attributes.formulaAssociatedObject.push(idFields[this.fieldValid[obj]]);
                    }
                }
            }
            return this;
        },
        setValue: function (value) {
            var originalType, value, newData;
            originalType = this.model.get("originalType");
            switch (originalType) {
                case "textarea":
                    newData = this.setTextareaValue(value);
                    break;
                case "suggest":
                    newData = this.setSuggestValue(value);
                    break;
                case "dropdown":
                    newData = this.setDropdownValue(value);
                    break;
                case "checkgroup":
                    newData = this.setChekgroupValue(value);
                    break;
                case "checkbox":
                    newData = this.setCheckboxValue(value);
                    break;
                case "radio":
                    newData = this.setRadioValue(value);
                    break;
                case "datetime":
                    newData = this.setDatetimeValue(value);
                    break;
                default :
                    newData = this.setTextValue(value);
                    break;
            }
            if (!newData){
                newData = {
                    value : "",
                    label : ""
                }
            }
            this.setNewData(newData);
            return this;
        },
        setText: function (text) {
            var originalType, value, newData;
            originalType = this.model.get("originalType");
            switch (originalType) {
                case "text":
                    newData = this.setTextValue(text);
                    break;
                case "textarea":
                    newData = this.setTextareaValue(text);
                    break;
                case "suggest":
                    newData = this.setSuggestValue(text);
                    break;
                case "dropdown":
                    newData = this.setDropdownText(text);
                    break;
                case "checkgroup":
                    newData = this.setChekgroupTexts(text);
                    break;
                case "checkbox":
                    newData = this.setCheckboxText(text);
                    break;
                case "radio":
                    newData = this.setRadioText(text);
                    break;
                case "datetime":
                    newData = this.setDatetimeValue(text);
                    break;
            }
            if (newData){
                this.setNewData(newData);
            }
            return this;
        },
        setDatetimeValue: function (value) {
            value = value.replace(/-/g, "/");
            if (new Date(value).toString() !== "Invalid Date") {
                return {
                    value : moment(value).format(this.model.get('YYYY-MM-DD HH:mm:ss')),
                    label : moment(value).format(this.model.get('format'))
                }
            }
            return this;
        },
        setTextValue : function (value){
            var dataObject;
            dataObject = {
                value : value,
                label : value
            }
            return dataObject;
        },
        setSuggestValue : function (data){
            var dataObject;
            if (typeof data === "object"){
                dataObject = {
                    value : data['value'] !== undefined ? data['value'] : "",
                    label : data['label'] !== undefined ? data['label'] : ""
                }
            }else{
                dataObject = {
                    value : data,
                    label : data
                }
            }
            return dataObject;
        },
        setTextareaValue : function (value){
            var dataObject;
            dataObject = {
                value : value,
                label : value
            }
            return dataObject;
        },
        setDropdownValue : function (value){
            var data;
            data = this.model.findOption(value, "value");
            return  data?data:{label:"",value:""};
        },
        setRadioValue : function (value){
            var data;
            data = this.model.findOption(value, "value");
            return  data?data:{label:"",value:""};
        },
        setChekgroupValue: function (values) {
            var data,
                parser,
                resp = {
                    value: [],
                    label: JSON.stringify([])
                },
                resultOptions;
            if (_.isString(values)) {
                values = values.split(",");
            }
            if (_.isArray(values)) {
                resultOptions = this.model.findOptions(values, "value");
                data = this.model.returnOptionsData(resultOptions);
                resp = {
                    value: data["value"],
                    label: JSON.stringify(data["label"])
                };
            }
            return resp;
        },
        setCheckboxValue: function (value) {
            var valuesfortrue, valuesforFalse, dataObject, options;
            options = this.model.get("options");
            dataObject = {};
            valuesfortrue = [1, true, "1", "true"];
            valuesforFalse = [0, false, "0", "false"];
            if (valuesfortrue.indexOf(value) > -1) {
                dataObject = {
                    value: options[0]["value"],
                    label: options[0]["label"]
                };
            }
            if (valuesforFalse.indexOf(value) > -1) {
                dataObject = {
                    value: options[1]["value"],
                    label: options[1]["label"]
                };
            }
            return dataObject;
        },
        setRadioText : function (text){
            var data;
            data = this.model.findOption(text, "label");
            return  data?data:{label:"",value:""};
        },
        setDropdownText : function (text){
            var data;
            data = this.model.findOption("text", "label");
            return  data?data:{label:"",value:""};
        },
        setChekgroupTexts : function (texts){
            var data,
                resultOptions;
            if (_.isArray(texts)){
                resultOptions = this.model.findOptions(texts, "label");
                data = this.model.returnOptionsData(resultOptions);
            }
            return {
                value : data["values"],
                label : JSON.stringify(data["label"])
            };
        },
        setCheckboxText: function (text) {
            var data;
            data = this.model.findOption(text, "label");
            return  data?data:{label:"",value:""};
        },
        setFullOptions : function (items){
            var options,
                element,
                showLabels = [];
            if (_.isArray(items)){
                this.model.set("fullOptions", items);
            }else{
                try{
                    options = JSON.parse(items);
                    if( _.isArray(options)){
                        this.model.set("fullOptions",options);
                    }else{
                        element = options;
                        showLabels.push(element);
                        this.model.set("fullOptions", showLabels);
                    }
                }catch(e){
                    element = items;
                    showLabels.push(element);
                    this.model.set("fullOptions",showLabels);
                }
            }
            return this;
        },
        mergeTextOptions : function (item) {
            if (item.hasOwnProperty("value")) {
                this.model.attributes.data = {
                    value: item["value"],
                    label: item["value"]
                };
                this.model.set("value", item["value"]);
            } else {
                this.model.set("value", "");
                this.model.attributes.data = {value: "", label: ""};
            }
            return this;
        },
        mergeDropdownOptions: function (remoteOpt, options) {
            var data = {value: "", label: ""};
            this.model.set("remoteOptions", remoteOpt);
            this.model.set("optionsSql", remoteOpt);
            this.model.set("options", options);
            if (_.isArray(options) && options.length) {
                data = this.model.findOption("defaultValue", "value");
                if (!data) {
                    data = {
                        value: options[0].value,
                        label: options[0].label
                    };
                }
            }
            this.model.setData(data);
            return this;
        },
        mergeOptions: function (remoteOptions) {
            var item,
                original,
                remoteOpt = [],
                localOpt = [],
                options = [];
            original = this.model.get("originalType")||"";
            if (_.isArray(remoteOptions) && remoteOptions.length) {
                switch (original){
                    case "text":
                        item = remoteOptions[0];
                        this.mergeTextOptions(item);
                        break;
                    case "textarea":
                        item = remoteOptions[0];
                        this.mergeTextOptions(item);
                        break;
                    case "dropdown":
                        localOpt = this.model.get("localOptions");
                        remoteOpt = this.formatResponse(remoteOptions);
                        options = localOpt.concat(remoteOpt);
                        this.mergeDropdownOptions(options, options);
                        break;
                }
            } else {
                this.model.attributes.data = {value: "", label: ""};
                this.model.set("value", "");
            }
            return this;
        },
        formatResponse : function(response){
            var k, remoteOpt = [];
            if (_.isArray(remoteOpt)){
                for (k = 0; k < response.length; k += 1) {
                    remoteOpt.push({
                        value: response[k].value,
                        label: response[k].text
                    });
                }
            }
            return remoteOpt;
        },
        setNewData : function (newData){
            this.model.set("data", newData);
            this.setFullOptions(newData["label"]);
            this.model.set("value", newData["value"]);
            return this;
        },
        setData: function (data) {
            var value, label;
            this.model.set("data",data);
            if(this.model.get("originalType") === "suggest"){
                this.setValue(data);
            }else{
                this.setValue(data["value"]);
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Label", Label);
}());

(function () {

    var Title = PMDynaform.view.Field.extend({
        template: null,
        validator: null,
        etiquete: {
            title: _.template($("#tpl-label-title").html()),
            subtitle: _.template($("#tpl-label-subtitle").html())
        },
        initialize: function () {
            var type = this.model.get("type");
            this.template = this.etiquete[type];

            this.model.on("change", this.render, this);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("type") === "title") {
                this.tagControl = this.tagHiddenToLabel = this.$el.find("h4").find("span[class='textlabel']");
            } else {
                this.tagControl = this.tagHiddenToLabel = this.$el.find("h5").find("span[class='textlabel']");
            }
            return this;
        },
        setValue: function (text) {
            if(text !== undefined){
                this.$el.find(".textlabel").text(text);
                this.model.set("label",text);
            }
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Title", Title);
}());

(function(){
	var Empty = Backbone.View.extend({
		item: null,
		template: _.template( $("#tpl-empty").html()),
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.Empty",Empty);
	
}());

(function () {
    var HiddenModel = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-hidden").html()),
        render: function (isConsole) {
            var data = {}, hidden;
            if (isConsole) {
                data["value"] = this.model.get("value");
                data["label"] = this.model.get("value");
                this.model.attributes.data = data;
            }
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("group") === "grid") {
                hidden = this.$el.find("input[type = 'hidden']")[1];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                hidden.name = hidden.id = "form" + name;
                hidden.value = this.model.get("value");
            }
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.tagControl = this.$el.find("input[type='hidden']").eq(0);
            this.keyLabelControl = this.$el.find("input[type='hidden']").eq(1);
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']").eq(1);
            return this;
        },
        setValue: function (value) {
            if (value !== undefined) {
                this.model.set("value", value);
                this.updateValues(value);
            }
            return this;
        },
        updateValues: function (value) {
            this.tagControl.val(value);
            this.model.set("data", {value: value, label: value});
            this.$el.find("input[type='hidden']").eq(1).val(value);
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Hidden", HiddenModel);

}());

(function () {
    var ImageView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-image").html()),
        events: {
            "keydown": "preventEvents"
        },
        initialize: function () {
            this.model.on("change", this.render, this);
        },
        preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.tagControl = this.tagHiddenToLabel = this.$el.find("img");
            return this;
        },
        getSrc: function () {
            return this.model.get("src");
        },
        setSrc: function (value) {
            this.model.set("src", value);
            this.tagControl.attr("src", value);
            return this;
        },
        setValue: function (phat) {
            if (phat !== undefined) {
                this.setSrc(phat);
                this.model.attributes.value = phat;
                this.model.set("data", {
                    value: phat,
                    label: phat
                });
            }
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Image", ImageView);

}());

(function(){
	var SubFormView = Backbone.View.extend({
        template:_.template($('#tpl-form').html()),
        formView: null,
        defaultElement: "empty",
        availableElements: null,
        parent: null,
		initialize: function (options) {
			var availableElements = [
                "text",
                "textarea",
                "checkbox",
				"checkgroup",
                "radio",
                "dropdown",
                "button",
                "datetime",
                "fieldset",
                "suggest",
                "link",
                "hidden",
                "title",
                "subtitle",
                "label",
                "empty",
                "file",
                "image",
                "grid",
                "panel",                
                "videomobile",
                "audiomobile",
                "imagemobile",
                "signature",
                "scannercode",
                "location"
            ];
            this.availableElements = availableElements;
            if(options.project) {
                this.project = options.project;
            }
            this.checkItems();
			this.makeSubForm();

		},
        checkItems: function () {
            var i,
            j,
            newItems = [],
            row = [],
            json = this.model.toJSON();

            if (json.items) {
                for (i=0; i<json.items.length; i+=1) {
                    row = [];
                    for(j=0; j<json.items[i].length; j+=1){
                        if (json.items[i][j].type){
                            if ($.inArray(json.items[i][j].type.toLowerCase(), this.availableElements) >=0) {
                                row.push(json.items[i][j]);
                            }
                        }else{
                            json.items[i][j].type = this.defaultElement;
                            row.push(json.items[i][j]);
                        }
                    }
                    if (row.length > 0) {
                        newItems.push(row);
                    }
                }
            }

            json.items = newItems;
            this.model.set("modelForm", json);
            
            return this;
        },
		makeSubForm: function() {
            var panelmodel = new PMDynaform.model.FormPanel(this.model.get("modelForm"));

            this.formView = new PMDynaform.view.FormPanel({
                model: panelmodel, 
                project: this.project
            });

            return this;
        },
        validate: function (event) {
            this.isValid(event);
        },
        getItems: function () {
            return this.formView.items.asArray();;
        },
        isValid: function (event) {
            var i, formValid = true,
            itemsField = this.formView.items.asArray(), itemField;

            if (itemsField.length > 0) {
                for (i = 0; i < itemsField.length; i+=1) {
                    if(itemsField[i].validate) {
						if(event){
							itemsField[i].validate(event);
							if (!itemsField[i].model.get("valid")) {
								formValid = itemsField[i].model.get("valid");
								if(itemField === undefined){
									itemField = itemsField[i];
								}
							}
						}else{
							itemsField[i].validate();
							formValid = itemsField[i].model.get("valid");
							if (!formValid){
								if (itemsField[i].model.get("type")!=="grid"){
									itemsField[i].setFocus();
								}
								this.model.attributes.valid = false;
								return false;
							} 
						}
                    }
                }
            }
			if (itemField && itemField.model.get("type")!=="grid"){
				itemField.setFocus();
			}
            this.model.set("valid", formValid );

            return formValid;
        },
        setData: function (data) {
            //using the same method of PMDynaform.view.FormPanel
            this.formView.setData(data);

            return this;
        },
        getData: function () {
            var i,
            k,
            field,
            fields,
            panels,
            formData;
            
            formData = this.model.getData();

                fields = this.formView.items.asArray();
                for (k=0; k<fields.length; k+=1) {
                    if ((typeof fields[k].getData === "function") &&
                        (fields[k] instanceof PMDynaform.view.Field)) {
                        //formData.fields.push(fields[k].getData());
                        field = fields[k].getData();
                        formData.variables[field.name] = field.value;
                    }
                }
            
            return formData;
        },
        render: function () {
            this.$el.html( this.template(this.model.toJSON()) );
            this.$el.find(".pmdynaform-field-form").append(this.formView.render(true).el);

            return this;
        },
        afterRender: function () {
            this.formView.afterRender();
            return this;
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.SubForm", SubFormView);
	
}());

(function(){
    
    var GeoMapView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-map").html()),
        validator: null,
        events: {
            "click .pmdynaform-map-fullscreen button": "applyFullScreen"
        },
        initialize: function (attributes){
            var that = this;            
        },
        onLoadGeoLocation: function () {
            var appData = this.project.mobileDataControls.data,
                that = this;
            if (appData && appData[this.model.get("name")]) {
                this.model.set("altitude",appData[this.model.get("name")]["altitude"]);
                this.model.set("latitude",appData[this.model.get("name")]["latitude"]);
                this.model.set("longitude",appData[this.model.get("name")]["longitude"]);
            }else{
                if(navigator.geolocation){
                    navigator.geolocation.getCurrentPosition(function(position){
                        var pos = position.Geoposition? position.Geoposition: position;                        
                        that.model.set("latitude", pos.coords.latitude || 0);
                        that.model.set("longitude", pos.coords.longitude || 0);
                        that.model.set("altitude", 0);                                        
                        that.onLoadLocation();
                    });
                }                
            }
            this.onLoadLocation();
            return this;
        },
        onLoadLocation: function () {
            var that = this,
            coords, 
            mapOptions,
            map,
            marker,
            appData,
            remoteCoors = {},
            canvasHTML = that.$el.find(".pmdynaform-map-canvas")[0]; 
            coords = new google.maps.LatLng(this.model.get("latitude"), this.model.get("longitude"));           
            mapOptions = {
                zoom: this.model.get("zoom"),
                center: coords,
                panControl: this.model.get("panControl"),
                zoomControl: this.model.get("zoomControl"),
                scaleControl: this.model.get("scaleControl"),
                streetViewControl: this.model.get("streetViewControl"),
                overviewMapControl: this.model.get("overviewMapControl"),
                mapTypeControl: this.model.get("mapTypeControl"),
                navigationControlOptions: {
                    style: google.maps.NavigationControlStyle.SMALL
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(canvasHTML, mapOptions);
            this.model.set("googlemap", map);
            
            marker = new google.maps.Marker({
              position: coords,
              map: map,
              draggable: this.model.get("dragMarker"),
              title: ""
            });
            google.maps.event.addListener(marker, 'dragend', function(event) {
                that.model.set("latitude", event.latLng.lat().toFixed(that.model.get("decimals")));
                that.model.set("longitude", event.latLng.lng().toFixed(that.model.get("decimals")));
                
            });
            this.model.set("marker", marker);
            //this.rightToLeftLabels();
            
            return this;
        },
        applyFullScreen: function () {
        	
            if (this.fullscreen.supported) {
            	this.fullscreen.toggle();
            } else {
            	this.$el(".pmdynaform-map-fullscreen").hide();
            }
            
            return this;
        },
        render: function () {
        	var that = this,
        	canvasMap;

        	that.$el.html( that.template( that.model.toJSON()) );
        	canvasMap = that.$el.find(".pmdynaform-map-canvas");
            this.onLoadGeoLocation();        		
            if (this.model.get("fullscreen")) {
            	this.fullscreen = new PMDynaform.core.FullScreen({
                element: this.$el.find(".pmdynaform-map-canvas")[0],
                onReadyScreen: function() {
		            setTimeout(function() {
		                that.$el.find(".pmdynaform-map-canvas").css("height", $(window).height() + "px");
		            }, 500);
		        },
		        onCancelScreen: function() {
		            setTimeout(function() {
		                that.$el.find(".pmdynaform-map-canvas").css("height", "");
		            }, 500);
		        }
            });
            }

			return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.GeoMap", GeoMapView);
}());

(function(){
    var Annotation = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-annotation").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            this.tagControl = this.tagHiddenToLabel = this.$el.find("span span");
            return this;
        },
        setValue: function (text) {
            if (text !== undefined) {
                this.model.set("label", text);
                this.$el.find('p span').text(text);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Annotation", Annotation);
}());

/**
 * The Datetime class was developed with the help of DateBootstrap plugin
 */
(function () {
    var DatetimeView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-datetime2").html()),
        validator: null,
        keyPressed: false,
        previousValue: null,
        datepickerObject: null,
        navigatorKeys : [37,38,39,40],
        events: {
            "blur input": "eventListener",
            "keydown input": "refreshBinding",
            "click #datetime-container-control" : "recalculateWidgetPostion"
        },
        outFocus: false,
        initialize: function () {
            var that = this;
            this.model.on("change:value", this.eventListener, this);
        },
        /**
         * events triggered "change" and "validate when the field undergoes a change,
         * this method is llamdo by the set function or action
         */
        eventListener: function (event, value) {
            this.onChange(event, value);
            this.checkBinding();
        },
        checkBinding: function () {
            var form = this.model.get("form"), data = {};
            if (typeof this.onChangeCallback === 'function') {
                this.onChangeCallback(this.getValue(), this.previousValue);
            }
            if (form && form.onChangeCallback) {
                form.onChangeCallback(this.model.get("id"), this.model.get("value"), this.previousValue);
            }
            this.previousValue = this.getValue();
            return this;
        },
        onChangeCallback: function () {
        },
        setOnChange: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }
            return this;
        },
        getDateControl: function () {
            var dataBaseValue, domValue;
            if (this.tagControl.length) {
                dataBaseValue = this.formatData(this.tagControl);
            }
            return dataBaseValue;
        },
        onChange: function (event, value) {
            this.updateValues(event, value);
            this.validate(event);
        },
        updateValues: function (event, value) {
            var newValue, label, hidden;
            if (value) {
                this.$el.find("#datetime-container-control").data()["DateTimePicker"].date(new Date(value));
                //this.datepickerObject.date(new Date(value));
            }
            newValue = this.getDateControl();
            label = this.tagControl.val();
            hidden = this.$el.find("input[type='hidden']");
            this.updateDataModel(newValue, label);
            hidden.val(newValue);
            this.model.attributes.value = newValue;
        },
        updateDataModel: function (value, label) {
            var data;
            data = {
                value: value,
                label: label
            };
            this.model.set("data", data);
            return this;
        },
        validate: function (event) {
            var newValue;
            this.model.set({validate: true});
            this.model.validate();
            if (this.model.get("enableValidate")) {
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error');
                }
                if (!this.model.isValid()) {
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find(".datetime-container").append(this.validator.el)
                    this.applyStyleError();
                }
            } else {
                this.model.attributes.valid = true;
            }
            return this;
        },
        refreshBinding: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.keyPressed = true;
            return this;
        },
        render: function () {
            var data = {}, date, that = this, clickEvent, hidden, name, dateInput;
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            this.$el.find('#datetime-container-control').datetimepicker({
                format: this.model.get("format"),
                stepping: this.model.get("stepping"),
                useCurrent: this.model.get("useCurrent"),
                collapse: this.model.get("collapse"),
                defaultDate: this.model.get("defaultDate"),
                disabledDates: this.model.get("disabledDates"),
                sideBySide: this.model.get("sideBySide"),
                daysOfWeekDisabled: this.model.get("daysOfWeekDisabled"),
                calendarWeeks: this.model.get("calendarWeeks"),
                viewMode: this.model.get("viewMode"),
                toolbarPlacement: this.model.get("toolbarPlacement"),
                showClear: this.model.get("showClear"),
                widgetPositioning: this.model.get("widgetPositioning"),
                date: this.model.get("value"),
                showTodayButton: true,
                "minDate": this.model.get("minDate").trim().length ? this.model.get("minDate") : false,
                "maxDate": this.model.get("maxDate").trim().length ? this.model.get("maxDate") : false,
                focusOnShow : PMDynaform.core.ProjectMobile ? false : true
            });
            this.datepickerObject = this.$el.find('#datetime-container-control').data()["DateTimePicker"];
            this.tagControl = this.$el.find("input[type='text']");
            this.keyLabelControl = this.$el.find("input[type='hidden']");
            this.tagHiddenToLabel = this.$el.find("input[type='hidden']");
            if (!this.model.get("defaultDate")) {
                this.setValue(this.model.get("value"));
            }
            if (this.model.get("group") === "grid") {
                dateInput = this.$el.find("input[type = 'text']")[0];
                name = this.model.get("name");
                name = name.substring(0, name.length - 1).concat("_label]");
                dateInput.name = dateInput.id = "form" + name;
            }
            if (this.model.get("name").trim().length === 0) {
                this.$el.find("input[type='text']").attr("name", "");
                this.$el.find("input[type='hidden']").attr("name", "");
            }
            this.updateValues();
            this.previousValue = this.model.get("value");
            this.keysNavigatorDefineEvents();  
            return this;
        },
        /**
         * navigation event handler Time control
         * only keyboards navigation is controlled, up, down, right and left
         */
        keysNavigatorDefineEvents : function() {
            var that = this;
            if(this.tagControl instanceof jQuery){
                this.tagControl.keyup(function(e){
                    if (that.navigatorKeys.indexOf(e.keyCode)>-1){
                        that.recalculateWidgetPostion();
                    }
                });
            }
            return this;
        },
        updateAttributeDatepicker: function (attribute, value) {
            if (this.datepickerObject && this.datepickerObject[attribute]) {
                this.datepickerObject[attribute](value);
            }
            return this;
        },
        formatData: function (date) {
            var valueDB, data, formatAux;
            data = this.$el.find('#datetime-container-control').data();
            data.DateTimePicker.format("YYYY-MM-DD HH:mm:ss");
            valueDB = data.date;
            if (!valueDB) {
                valueDB = "";
            }
            data.DateTimePicker.format(this.model.get("format"));
            return valueDB;
        },
        setValue: function (value) {
            if (value !== undefined) {
                value = value.replace(/-/g, "/");
                if (new Date(value).toString() !== "Invalid Date") {
                    this.model.set("value", value);
                }
            }
            return this;
        },
        getAppendPosition : function (text, widget) {
            var dimentionText = text[0].getBoundingClientRect(),
                appendPosition  = "bottom";
            if (dimentionText.top + text.outerHeight() + widget.outerHeight() > $(window).outerHeight()){
                appendPosition = "top";
            }
            return appendPosition; 
        },
        getScrollOffsets: function () {
            return document.body.scrollTop || document.documentElement.scrollTop || window.pageYOffset || getScrollTop();
        },
        recalculateWidgetPostion : function () {
            var icon = this.$el.find(".input-group-addon"),
                widget = this.$el.find(".bootstrap-datetimepicker-widget"),
                text = this.$el.find("input[type='text']"),
                auxHeight = widget.outerHeight(),
                leftOfset,
                topOffset,
                x,
                y;

            leftOfset = text.offset().left + text.outerWidth();
            topOffset = text.offset().top - this.getScrollOffsets();
            x = leftOfset - widget.outerWidth();
            if (this.getAppendPosition(text,widget) === "top"){
                y = topOffset - widget.outerHeight();
            } else {
                y = topOffset + text.outerHeight();

            }
            widget.css({
                position : 'fixed',
                left : x,
                top : y-2,
                height : auxHeight
            });
            return this;
        }
    })
    PMDynaform.extendNamespace("PMDynaform.view.Datetime", DatetimeView);
}());

(function(){
    var Audio_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Audio_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Audio_mobile", Audio_mobile);
}());

(function(){
    var Geomap_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Geomap_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Geomap_mobile", Geomap_mobile);
}());

(function(){
    var Image_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Image_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Image_mobile", Image_mobile);
}());

(function(){
    var Qrcode_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Qrcode_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Qrcode_mobile", Qrcode_mobile);
}());

(function(){
    var Signature_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Signature_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Signature_mobile", Signature_mobile);
}());

(function(){
    var Video_mobile = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-Video_mobile").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Video_mobile", Video_mobile);
}());

(function(){
    var PanelField = PMDynaform.view.Field.extend({
        validator: null,
        template: _.template($("#tpl-panelField").html()),
        initialize: function (){
        },
        render: function() {
            var content, footer;
            this.$el.html( this.template(this.model.toJSON()) );
            this.$el.find(".panel-body").html(this.model.get("content"));
            footer = $(this.model.get("footerContent"));
            if ( footer.length && footer instanceof jQuery){
                this.$el.find(".panel-footer").append(footer);
            } else {
                this.$el.find(".panel-footer").text(this.model.get("footerContent"));
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.PanelField", PanelField);
}());

(function () {
    /**
     * @class PMDynaform.util.ui.FlashMessageModel
     * A message to display for a while.
     *
     * Usage example:
     *
     *      @example
     *        flashModel = new PMDynaform.ui.FlashMessageModel({
	 *			message : "This is a flas message",
	 *			emphasisMessage: "Info",
	 *			startAnimation:5000,
	 *			closable:true,
	 *			type:"danger",
	 *			appendTo:document.body,
	 *			duration:5000
	 *		});
     *
     *
     * @constructor
     * Creates a new instance of the class.
     *
     * @cfg {String} [emphasisMessage=""] The object's emphasisMessage. It can be a single string
     * @cfg {String} [message=""] The object's message. It can be a single string
     * @cfg {Number} [duration=3000] The time in milliseconds the message will be displayed.
     * @cfg {String} [type="info"] The type for the message. Valid values: 'info', 'success', 'error', 'warning'.
     */
    var FlashMessageModel = Backbone.Model.extend({
        defaults: {
            /**
             * The message property sets a simple label that will be displayed in the component
             * @type {String}
             * @readonly
             */
            message: '',
            /**
             * The duration in milliseconds to show the message. Set by the config option
             * and the method.
             * @type {Number}
             * @readonly
             */
            duration: 3000,
            /**
             * The html element's object the message will be displayed in the DOM element
             * @type {HTMLElement}
             */
            appendTo: document.body,
            /**
             * The message's type. Set by the, config option with success, info, warning, danger
             * @type {String}
             * @readonly
             */
            type: 'info',
            /**
             * The duration in milliseconds to start the message. Set by the config option
             * and the method.
             * @type {Number}
             * @readonly
             */
            startAnimation: 1000,
            /**
             * The emphasisMessage property sets a emphasis label that will be displayed in the component
             * @type {String}
             * @readonly
             */
            emphasisMessage: '',
            /**
             * The valid Type 's set by config option with success, info, warning, danger
             * @type {Array}
             */
            validTypes : [],
            /**
             * add scroll of the scroll in the top, when show the flash message
             * @type {Boolean}
             */
            absoluteTop : false,
            closable : false
        },
        /**
         * When creating an instance of a model, you can pass in the initial values of the attributes
         * @param settings: properties with custom values
         * @returns {FlashMessageModel}
         */
        initialize: function (config) {
            this.set("validTypes",["success", "info", "warning", "danger"]);
            this.on("change:type", this.setType);
            this.on("change:appendTo", this.setAppendTo);
        },
        /**
         * This method, set the type for the message. Valid values: 'info', 'success', 'error', 'warning'.
         * @param {[type]} type [description]
         */
        setType: function (model, type) {
            if (this.get("validTypes").indexOf(type) > -1) {
                this.set("type", type);
            } else {
                this.set("type", "info");
            }
            return this;
        },
        /**
         * The html element's object the message will be displayed in the DOM element
         * @param {[type]} model  : is a object model
         * @param {[type]} parentNode : this a html element container.
         */
        setAppendTo: function (model, parentNode) {
            if (_.isObject(parentNode)) {
                if (parentNode instanceof jQuery || parentNode.ELEMENT_NODE) {
                    this.set("appendTo", parentNode);
                }
            } else {
                this.set("appendTo", document.body);
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.ui.FlashMessageModel", FlashMessageModel);
}());
(function(){
	var FileMobile = PMDynaform.view.Field.extend({
		template: _.template( $("#tpl-extfile").html()),		
		templateAudio: _.template( $("#tpl-extaudio").html()),
		templateVideo: _.template( $("#tpl-extvideo").html()),
		templateMediaVideo: _.template( $("#tpl-media-video").html()),		
		templateMediaAudio: _.template( $("#tpl-media-audio").html()),		
		templateImage: _.template( $("#tpl-extfile").html()),
		templatePlusImage: _.template( $("#tpl-extfile-plus-image").html()),
		templatePlusAudio: _.template( $("#tpl-extfile-plus-audio").html()),
		templatePlusVideo: _.template( $("#tpl-extfile-plus-video").html()),
		boxPlus:null,
		viewsFiles:[],
		mediaVideos:[],
		events: {
			"click buttonImage": "onClickButtonMobile",
	        "click .pmdynaform-file-container .form-control": "onClickButton",
	        "click div[name='button-all'] .pmdynaform-file-buttonup": "onUploadAll",
	        "click div[name='button-all'] .pmdynaform-file-buttoncancel": "onCancelAll",
	        "click div[name='button-all'] .pmdynaform-file-buttonremove": "onRemoveAll"
	    },
		initialize: function () {
			//this.setOnChangeFiles();
			//this.attributes.files= [];
			this.model.on("change", this.render, this);
		},
		onClickButtonMobile: function (event) {			
			var model;
			model = this.model;			
			switch (model.get("type")) {
			    case "imageMobile":
			        this.onClickButtonImage(event);
			        break; 
			    case "audioMobile":
			        this.onClickButtonAudio(event);
			        break;
			    case "videoMobile":
			        this.onClickButtonVideo(event);
			        break;
			    default: 
			        this.$el.find("input").trigger( "click" );
			}
			event.preventDefault();
			event.stopPropagation();			
			return this;
		},
		
		onClickButtonImage: function (event) {		
			var respData; 
			respData = {
				idField: this.model.get("name"),
				type:"image"					
			};			
			if(navigator.userAgent == "formslider-android"){				
				JsInterface.getPicture(JSON.stringify(respData));				
			}			
			if(navigator.userAgent == "formslider-ios"){
				this.model.attributes.project.setMemoryStack({"data":respData});
				this.model.attributes.project.projectFlow.executeFakeIOS("upload-file");
			}					
			return this;
		},	
		onClickButtonAudio: function (event) {		
			var respData; 
			respData = {
				idField: this.model.get("name"),
				type:"audio"					
			};			
			if(navigator.userAgent == "formslider-android"){
				JsInterface.getAudio(JSON.stringify(respData));				
			}			
			if(navigator.userAgent == "formslider-ios"){
				this.model.attributes.project.setMemoryStack({"data":respData});
				this.model.attributes.project.projectFlow.executeFakeIOS("upload-file");
			}						
			return this;
		},
		onClickButtonVideo: function (event) {		
			var respData; 
			respData = {
				idField: this.model.get("name"),
				type:"video"					
			};			
			if(navigator.userAgent == "formslider-android"){
				JsInterface.getVideo(JSON.stringify(respData));				
			}			
			if(navigator.userAgent == "formslider-ios"){
				this.model.attributes.project.setMemoryStack({"data":respData});
				this.model.attributes.project.projectFlow.executeFakeIOS("upload-file");
			}						
			return this;
		},		
		validate: function(e, file) {
			var validated = true;
			//extensions = this.model.get("extensions"),
			//maxSize = this.model.get("size");
			
			//Check the extension of the file
			/*if(extensions.indexOf("*") < 0) {
				type = file.extra.extension.toLowerCase().trim();
				if (extensions.indexOf(type) < 0) {
					alert("The extension of the file is not supported for the field...");
					validated = false;
				}
			}*/
			
			// check file size
			/*if ((parseInt(file.size / 1024) > parseInt(maxSize * 1024)) && validated === true) {
				alert("File \""+file.name+"\" is too big. \n Max allowed size is "+ maxSize +" MB.");
				validated = false;
			}*/

			return validated;			
		},
		removeItem: function (event) {
			var items = this.model.get("items"),
			index = $(event.target).data("index");

			items.splice(index, 1);
			this.model.set("items", items);
			this.render();
			return this;
		},		
		onClosePreview: function () {

			return this;
		},
		onPreviewItem: function (event) {
			var file,
			reader,
			shadow = document.createElement("div"),
			background = document.createElement("div"),
			preview = document.createElement("img"),
			index = $(event.target).data("index"),
			closeItem = document.createElement("span");
			closeItem.className = "glyphicon glyphicon-remove";
			closeItem.title = "close";
			$(closeItem).tooltip().click(function(e) {
                $(this).tooltip('toggle');
            });
			heightContainer = document.documentElement.clientHeight;

			shadow.className = "pmdynaform-file-shadow";
			shadow.style.height = heightContainer + "px";
			background.className = "pmdynaform-file-preview-image"
			background.style.height = heightContainer + "px";
			background.appendChild(closeItem);
			$(background).on('click', function (event){
				document.body.removeChild(shadow);
				document.body.removeChild(background);
			});
			file = this.model.get("items")[index].file;
			reader  = new FileReader();
			reader.onloadend = function () {
				preview.src = reader.result;
			}
			
			if (file) {
				reader.readAsDataURL(file);
			} else {
				preview.src = "";
			}
			background.appendChild(preview);
			document.body.appendChild(shadow);
			document.body.appendChild(background);
			return this;
		},
		renderFiles: function () {
			var i,
			that = this,
			items = this.model.get("items");

			for (i=0; i< items.length; i+=1) {
				if (that.model.get("preview")) {
					that.createBox(i, items[i].event,items[i].file);
				} else {
					that.createListBox(i, items[i].event, items[i].file);
				}	
			}
			
			return this;
		},
		createButtonsHTML: function (index) {
			var that = this,
			buttonGroups = document.createElement("div"),
	    	buttonGroupUp = document.createElement("div"),
	    	buttonGroupCancel = document.createElement("div"),
	    	buttonGroupRemove = document.createElement("div"),
	    	buttonGroupView = document.createElement("div");
	    	buttonUp = document.createElement("button"),
	    	buttonCancel = document.createElement("button"),
	    	buttonRemove = document.createElement("button"),
	    	buttonView = document.createElement("button");

	    	/*
	    	 * Adding Options buttons to file
	    	 **/
	    	buttonGroups.className = "btn-group btn-group-justified";

	    	buttonGroupUp.className = "pmdynaform-file-buttonup btn-group";
	    	buttonGroupCancel.className = "pmdynaform-file-buttoncancel btn-group";
	    	buttonGroupCancel.style.display = "none";
	    	buttonGroupRemove.className = "pmdynaform-file-buttonremove btn-group";
	    	buttonGroupView.className = "pmdynaform-file-buttonview btn-group";

	    	buttonUp.className = "glyphicon glyphicon-upload btn btn-success btn-sm";
	    	buttonCancel.className = "glyphicon glyphicon-remove btn btn-danger btn-sm";
	    	buttonRemove.className = "glyphicon glyphicon-trash btn btn-danger btn-sm";
	    	buttonView.className = "glyphicon glyphicon-zoom-in btn btn-primary btn-sm";

	    	$(buttonUp).data("index", index);
	    	$(buttonCancel).data("index", index);
	    	$(buttonRemove).data("index", index);
	    	$(buttonView).data("index", index);
	    	
	    	$(buttonUp).on("click", function(event) {
				that.onToggleButtonUpload(event, "up");
				that.onUploadItem(event);
				event.stopPropagation();
				event.preventDefault();
			});
			$(buttonCancel).on("click", function(event) {
				that.onToggleButtonUpload(event, "cancel");
				that.onCancelUploadItem(event);
				event.stopPropagation();
				event.preventDefault();
			});
	    	$(buttonRemove).on("click", function(event) {
				that.removeItem(event);
				event.stopPropagation();
				event.preventDefault();
			});			
			$(buttonView).on("click", function(event) {
				that.onPreviewItem(event);
				event.stopPropagation();
				event.preventDefault();
			});

	    	buttonGroupUp.appendChild(buttonUp);
	    	buttonGroupCancel.appendChild(buttonCancel);
			buttonGroupRemove.appendChild(buttonRemove);
			buttonGroupView.appendChild(buttonView);

			buttonGroups.appendChild(buttonGroupUp);
			buttonGroups.appendChild(buttonGroupCancel);
			buttonGroups.appendChild(buttonGroupView);
			buttonGroups.appendChild(buttonGroupRemove);

			return buttonGroups;
		},
		createBox: function (index, e, file) {
			var buttonGroups,
			rand = Math.floor((Math.random()*100000)+3),
	    	imgName = file.name,
	    	src = e.target.result,
	    	template = document.createElement("div"),
	    	resizeImage = document.createElement("div"),
	    	preview = document.createElement("span"),
	    	progress = document.createElement("div"),
	    	imgPreview = document.createElement("img"),
	    	spanOverlay = document.createElement("span"),
	    	spanUpDone = document.createElement("span"),
	    	typeClasses = {
	    		video: {
	    			Classes: "pmdynaform-file-boxpreview-video",
	    			icon: "glyphicon glyphicon-facetime-video"
	    		},
	    		audio: {
	    			Classes: "pmdynaform-file-boxpreview-audio",
	    			icon: "glyphicon glyphicon-music"
	    		},
	    		file: {
	    			Classes: "pmdynaform-file-boxpreview-file",
	    			icon: "glyphicon glyphicon-book"
	    		}
	    	},
	    	fileName = file.name.split(/\./)[1].toUpperCase();

	    	template.id = rand;
	    	template.className = "pmdynaform-file-containerimage";

			resizeImage.className = "pmdynaform-file-resizeimage";
			if (file.type.match(/image.*/)) {
				imgPreview.src = src;
				resizeImage.appendChild(imgPreview);
			} else if(file.type.match(/audio.*/)) {
				resizeImage.innerHTML = '<div class="'+ typeClasses['audio'].Classes +' thumbnail ' + typeClasses['audio'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else if(file.type.match(/video.*/)) {
				resizeImage.innerHTML = '<div class="'+ typeClasses['video'].Classes +' thumbnail ' + typeClasses['video'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else {
				resizeImage.innerHTML = '<div class="'+ typeClasses['file'].Classes +' thumbnail ' + typeClasses['file'].icon+'"><div>'+ fileName +'</div></div>'; 
			}
			spanOverlay.className = "pmdynaform-file-overlay";
			spanUpDone.className = "pmdynaform-file-updone";
			spanOverlay.appendChild(spanUpDone);
			resizeImage.appendChild(spanOverlay);

	    	preview.id = rand;
	    	preview.className = "pmdynaform-file-preview";
			preview.appendChild(resizeImage);	    	

	    	progress.id = rand;
	    	progress.className = "pmdynaform-file-progress";
	    	progress.innerHTML = "<span></span>";

	    	template.appendChild(preview);
	    	buttonGroups = this.createButtonsHTML(index);
	    	template.appendChild(buttonGroups);
	    	template.appendChild(progress);
	    	this.$el.find(".pmdynaform-file-droparea").append(template);
	    	
	    	return this;
			
		},
		/*createListBox: function (index, e, file) {
			var buttonGroups,
			rand = Math.floor((Math.random()*100000)+3),
			listItem = document.createElement("div"),
			label = document.createElement("div");

			listItem.className = "pmdynaform-file-listitem";

			buttonGroups = this.createButtonsHTML(index);
			label.className = "pmdynaform-label-nowrap";
			label.innerHTML = file.name;
			listItem.appendChild(label)
			listItem.appendChild(buttonGroups);
			

			this.$el.find(".pmdynaform-file-list").append(listItem);

			return this;
		},*/
		toggleButtonsAll: function () {
			//Select the name="button-all" for show the buttons

			return this;
		},
		render: function () {
			var that = this,
			oprand, files,
			container,
			itemElement,
			filesTag,
			itemMedia,
			item;
			if (PMDynaform.core.ProjectMobile) {
				this.createBoxPlus();
				this.$el.html( this.template(this.model.toJSON()) );
				if (this.model.get("hint")) {
					this.enableTooltip();
				}
				this.renderFiles();
				this.toggleButtonsAll();
				oprand = {
					dragClass : "pmdynaform-file-active",
					dnd: this.model.get("dnd"),
				    on: {
				        load: function (e, file) {
				        	that.addNewItem(e, file);
				        }
				    }
				};

				var fileContainer = this.$el.find(".pmdynaform-file-droparea-ext")[0];
				this.$el.find(".pmdynaform-file-droparea-ext").append(this.boxPlus);
				var fileControl = this.$el.find("input")[0];
				if (this.model.get("dnd") || this.model.get("preview")) {
					//PMDynaform.core.FileStream.setupDrop(fileContainer, oprand);
				}
			} else {
				this.$el.html( this.template(this.model.toJSON()));
				container = this.$el.find(".pmdynaform-file-control").empty();
				files = this.project.mobileDataControls;
				if (files.data){
					files = files.data[this.model.get("name")];
	                var data;
	                if (this.project.mobileDataControls){
	                    if ( this.project.mobileDataControls["data"]){
	                        var files = this.project.mobileDataControls["data"][that.model.get("name")];
	                        if (files && files.length > 0 ) {
								switch (this.model.get("type")) {
								    case "imageMobile":
										files = this.model.remoteProxyData(files); 
										for (var i = 0 ; i < files.length ; i+=1){
											itemElement = $("<img src=\"data:image/png;base64,"+files[i].base64 + "\"class='img-thumbnail' alt='Thumbnail Image'>");
											container.append(itemElement);
										}
								        break; 
								    case "audioMobile":
										for (var i=0 ;i< files.length ;i++){
											item = files[i];
											itemMedia = this.model.urlFileStreaming(item);
											itemElement = $("<audio src=\'"+ itemMedia.filePath + "\'id="+itemMedia.id+" style='margin:50px 0px 20px 0px;width: 100%;height: auto; background-color: black; display: block;' controls>");
											container.append(itemElement);
										}
								        break;
								    case "videoMobile":
										for (var i=0 ;i< files.length ;i++){
											item = files[i];
											itemMedia = this.model.urlFileStreaming(item);
											itemElement = $("<video type='video/mp4' src=\'"+ itemMedia.filePath + "\'id="+itemMedia.id+" style='width:300px; height:300px;background-color: black;' controls>");
											container.append(itemElement);
										}
								        break;
								    default:
								        //this.renderDefault();
								}
	                        }
	                    }
	                }
				}
				
			}
			return this;
		},
		renderFile : function (){
			var model;
			model = this.model;			
			switch (model.get("type")) {
			    case "imageMobile":
			        this.renderImage();
			        break; 
			    case "audioMobile":
			        this.renderAudio();
			        break;
			    case "videoMobile":
			        this.renderVideo();
			        break;
			    default: 
			        this.renderDefault();
			}
		},
		renderImage : function (){

		},
		createBoxImage : function(file){
			var src,
				newsrc,
				rand = Math.floor((Math.random()*100000)+3);
	    	//imgName = file.name, 
	    	// not used, Irand just in case if user wanrand to print it.
	    	if(file.filePath){
	    		src = file.filePath;
	    		newsrc = src;
	    	}
	    	if(file["base64"]){
	    		src = file["base64"];
	    		newsrc = this.model.makeBase64Image(src); 
	    	}	    	
	    	//src = file["thumbnails"];	    	
	    	
	    	var template = document.createElement("div");
	    	var resizeImage = document.createElement("div");
	    	var preview = document.createElement("span");
	    	var progress = document.createElement("div");

	    	template.id = rand;
	    	template.className = "pmdynaform-file-containerimage";

			resizeImage.className = "pmdynaform-file-resizeimage";
			resizeImage.innerHTML = '<img class="pmdynaform-image-ext" src="'+newsrc+'"><span class="pmdynaform-file-overlay"><span class="pmdynaform-file-updone"></span></span>';	    		    	
	    	preview.id = rand;
	    	preview.className = "pmdynaform-file-preview";
			preview.appendChild(resizeImage);
	    	progress.id = rand;
	    	progress.className = "pmdynaform-file-progress";
	    	progress.innerHTML = "<span></span>";
	    	template.appendChild(preview);	    	
	    	template.setAttribute("data-toggle","modal");
	    	template.setAttribute("data-target","#myModal");	
	    	this.viewsFiles.push({
	    		"id":file.id,				
				"data":template	    		
	    	});
	    	this.$el.find(".pmdynaform-file-droparea-ext").prepend(template);	    	
	    	return this;
		},
		renderAudio : function (){

		},
		createBoxAudio : function(file){
			var model,
				tplContainerAudio,
				tplContainer,				
				tplMediaAudio,
				mediaElement;
			model= {
				id: Math.floor((Math.random()*100000)+3),
				src : file.filePath?file.filePath:file,
				extension:file.extension?file.extension:null,
				name: file.name
			};

			tplMediaAudio = this.templateMediaAudio(model);
			mediaElement = new PMDynaform.core.MediaElement({
				el: $(tplMediaAudio),
				type : "audio"
			});

			tplContainerAudio = $(this.templateAudio(model)); 			
			tplContainerAudio.find(".pmdynaform-file-resizevideo").append(mediaElement.$el);			
			this.$el.find(".pmdynaform-file-droparea-ext").prepend(tplContainerAudio);
			
			this.viewsFiles.push({
	    		"id":file.id,				
				"data":tplContainerAudio	    		
	    	});
	    	return this;
		},
		renderVideo : function (){

		},
		/**
		 * [createBoxVideo Create a html of a video]
		 * @param  {[type]} file [description]
		 * @return {[type]}      [description]
		 */
		createBoxVideo : function(file){
			var model,
				tplContainerVideo,
				tplContainer,				
				tplMediaVideo,
				mediaElement,
				urlVideo;
			// The url for streaming consume a endpoint of a project			
			model= {
				id: Math.floor((Math.random()*100000)+3),
				src : file.filePath?file.filePath:file,
				name: file.name
			};

			tplMediaVideo = this.templateMediaVideo(model);
			mediaElement = new PMDynaform.core.MediaElement({
				el:$(tplMediaVideo),
				type:"video",
				streaming:file.filePath? false: true
			});

			tplContainerVideo = $(this.templateVideo(model)); 			
			tplContainerVideo.find(".pmdynaform-file-resizevideo").append(mediaElement.$el);			
			this.$el.find(".pmdynaform-file-droparea-ext").prepend(tplContainerVideo);
			
			this.viewsFiles.push({
	    		"id":file.id,				
				"data":tplContainerVideo	    		
	    	});
	    	return this;
		},
		renderDefault : function (){

		},

		setFilesRFC : function (arrayFiles){
            var array,model,item;
			model = this.model;			
			switch (model.get("type")) {
			    case "imageMobile":
			    		this.loadMixingSourceImages(arrayFiles);			        
			        break; 
			    case "audioMobile":
			    		this.loadMixingSourceMedia(arrayFiles);			        
			        break;
			    case "videoMobile":
			        	this.loadMixingSourceMedia(arrayFiles);
			        break;
			    default:
			        //this.renderDefault();
			}
        },

        loadMixingSourceImages : function (arrayFiles){        	
            var arrayRemoteData=[],
            	arrayFilePath=[],
            	array,
            	sw=false,
            	item;            
            for (var i=0 ;i< arrayFiles.length ;i++){
            	item = arrayFiles[i];
            	if(item.filePath){            		
            		this.validateFiles(item);
            		this.model.attributes.files.push(item);
            	}else{
            		arrayRemoteData.push(item);
            	}            	
            }

            if(arrayRemoteData.length != 0){
	            for (var index = 0 ; index< arrayRemoteData.length;index++){
	            	var arrayI = [];
	            	arrayI.push(arrayRemoteData[index]);
	            	array = this.model.remoteProxyData(arrayI);
	            	//array = this.model.remoteProxyData(arrayRemoteData);			        			        
			        if(array){
			            for (var i=0 ;i< array.length ;i++){            	
			            	this.validateFiles(array[i]);
			            	this.model.attributes.files.push(array[i]);
			            }
		        	}	
	            }	            
        	}
        },

        loadMixingSourceMedia : function (arrayFiles){        	
            var arrayRemoteData=[],
            	arrayFilePath=[],
            	array,
            	itemMedia,
            	sw=false,
            	item;            
            for (var i=0 ;i< arrayFiles.length ;i++){
            	item = arrayFiles[i];
            	if(typeof item == "string"){
            		itemMedia = this.model.urlFileStreaming(item);
            		this.validateFiles(itemMedia);
            		this.model.attributes.files.push(itemMedia);			        	
            	}
            	if(item.filePath){
            		this.validateFiles(item);
            		this.model.attributes.files.push(item);			        	
            	}            	
            }
        },
        /**
         * [setFiles Function for set files images, video and audio from a interface to mobile]
         * @param {[type]} arrayFiles [description]
         */
        setFiles : function (arrayFiles){        	
            var array;            
            for (var i=0 ;i< arrayFiles.length ;i++){            	
            	this.validateFiles(arrayFiles[i]);
            	this.model.attributes.files.push(arrayFiles[i]);
            }
        },
        validateFiles: function(file) {
        	this.createBoxFile(file);						
			return this;
		},
		createBoxFile : function (file){
			var model,
				response;
			model = this.model;			
			switch (model.get("type")) {
			    case "imageMobile":
			    	this.createBoxImage(file);			    	
			        break; 
			    case "audioMobile":
			        this.createBoxAudio(file);
			        break;
			    case "videoMobile":
			        this.createBoxVideo(file);
			        break;
			    default: 
			        //this.renderDefault();
			}
		},
		createBoxPlus: function () {
			var model;
			model = this.model;			
			switch (model.get("type")) {
			    case "imageMobile":
			        this.boxPlus=$(this.templatePlusImage());
			        break; 
			    case "audioMobile":
			        this.boxPlus=$(this.templatePlusAudio());
			        break;
			    case "videoMobile":
			        this.boxPlus=$(this.templatePlusVideo());
			        break;
			    default: 
			        //this.renderDefault();
			}			
			return this;
		},
		changeID : function (arrayNew){        	
            var array = this.model.attributes.files,
            	itemNew,
            	itemOld;           
            for (var i=0 ;i< arrayNew.length ;i++){            	
            	itemNew = arrayNew[i];
            	for (var j=0 ;j< array.length ;j++){
            		itemOld = array[j];
            		if(typeof itemOld === "string"){
            			if(itemNew["idOld"] === itemOld){
            				itemOld = itemNew["idNew"];
            			}            				
            		}
            		if(typeof itemOld === "object"){
            			if(itemNew["idOld"] === itemOld["id"]){
            				itemOld["id"] = itemNew["idNew"];
            			}	
            		}
            	}
            }
        },
		setData: function (data) {
			this.setFilesRFC(data["value"]);
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.FileMobile",FileMobile);
}());

(function(){
	var GeoMobile = PMDynaform.view.Field.extend({
		item: null,	
		template: _.template( $("#tpl-extgeo").html()),
		templatePlus: _.template( $("#tpl-extfile-plus").html()),
		templateGeoDesktop: _.template($("#tpl-map").html()),
		boxPlus: null,
		boxModal:null,
		boxBackground:null,
		viewsImages: [],
		imageOffLine : "geoMap.jpg",		
		events: {
	        "click button": "onClickButton"	        
	    },
		initialize: function () {			
		},		
		onClickButton: function (event) {			
			var respData;
			this.model.set("interactive",true);			
			respData = {
					idField: this.model.get("name"),
					interactive:true
				};			
			if(navigator.userAgent == "formslider-android"){
				JsInterface.getGeoTag(JSON.stringify(respData));				
			}
			if(navigator.userAgent == "formslider-ios"){
				this.model.attributes.project.setMemoryStack({"data":respData,"source":"IOS"});
				this.model.attributes.project.projectFlow.executeFakeIOS("show-map");
			}
			event.preventDefault();
			event.stopPropagation();			
			return this;
		},
		makeBase64Image : function (base64){
            return "data:image/png;base64,"+base64;
        },		
		createBox: function (data) {
			var rand,
				newsrc,
				template,
				resizeImage,
				preview,
				progress;

			if(data.base64){
				newsrc = this.makeBase64Image(data.base64);				 				
			}			
			if(data.filePath){
				newsrc = data.filePath;
			}

			rand = Math.floor((Math.random()*100000)+3);	    	
	    	template = document.createElement("div"),
	    	resizeImage = document.createElement("div"),
	    	preview = document.createElement("span"),
	    	progress = document.createElement("div");

	    	template.id = rand;
	    	template.className = "pmdynaform-file-containergeo";

			resizeImage.className = "pmdynaform-file-resizeimage";
			resizeImage.innerHTML = '<img src="'+newsrc+'">';	    	
	    	preview.id = rand;
	    	preview.className = "pmdynaform-file-preview";
			preview.appendChild(resizeImage);
	    	template.appendChild(preview);	    	
	    	this.$el.find(".pmdynaform-ext-geo").prepend(template);	    	
	    	this.hideButton();
	    	return this;
		},		
		hideButton : function (){
			var button;
			button = this.$el.find("button");
			button.hide();	    	
		},	
		render: function () {
			var that = this,
				fileContainer,
				fileControl,
				auxClass,
				geomapDesktop,
				that = this;
				if (PMDynaform.core.ProjectMobile){
					this.$el.html( this.template(this.model.toJSON()));			
					if (this.model.get("hint")) {
						this.enableTooltip();
					}
					fileContainer = this.$el.find(".pmdynaform-file-droparea-ext")[0];			
					fileControl = this.$el.find("input")[0];
				}else{
					this.$el.html(this.templateGeoDesktop(this.model.toJSON()));
					auxClass = function (params) {
						this.project = params.project;
					};
					auxClass.prototype.load = function () {
						var canvasMap = that.$el.find(".pmdynaform-map-canvas");
						var coords, mapOptions, map, marker;
						if (that.project.mobileDataControls){
							if ( that.project.mobileDataControls["data"]){
								var data = that.project.mobileDataControls["data"][that.model.get("name")];
								if (data) {
									var latitude  = data["latitude"] || 0;
									var longitude = data["longitude"] || 0;
									var altitude = data["altitude"] || 0;
									coords = new google.maps.LatLng(latitude, longitude);
						 			mapOptions = {
						                zoom: 15,
						                center: coords,
						                panControl: false,
						                zoomControl: false,
						                scaleControl: true,
						                streetViewControl: false,
						                overviewMapControl: false,
						                mapTypeControl: true,
						                navigationControlOptions: {
						                    style: google.maps.NavigationControlStyle.SMALL
						                },
						                mapTypeId: google.maps.MapTypeId.ROADMAP
						            };
						            map = new google.maps.Map(canvasMap[0], mapOptions);
									marker = new google.maps.Marker({
							        	position: coords,
							        	map: map,
							        	draggable: false,
							        	title: ""
							        });
								}
							}
						}			            
					};
					window.pmd = new auxClass({project:this});
					var script = document.createElement('script');
					script.type = 'text/javascript';
					$(script).data("script", "google");;
					script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&callback=pmd.load';
					document.body.appendChild(script);
				}
			return this;
		},
		setLocation: function (location){
			var model,obj={},
				response;
			model= this.model;
			model.set("geoData",location);
			if(this.model.get("interactive")&& location !=null){				
				if(location.id == "" || location.id == null){
					obj={
						location: {
							altitude : location.altitude,
					        latitude : location.latitude,
					        longitude : location.longitude
						}
					};
					response=model.remoteGenerateLocation(obj);
					if(response){
						if(response.success == true){
							obj["imageId"]=response.imageId;
							response=model.remoteProxyData(response);
							obj["data"]=response.data;							
							this.createBox(response);
							model.set("geoData",obj);
						}
					}
					else{
						this.createImageOffLine(location);
					}									
				}else{
					if(location.base64){
						this.createBox(location);		
					}else{
						response=model.remoteProxyData(location.id);								
						response.altitude = location.altitude;
						response.latitude = location.latitude;
						response.longitude = location.longitude;
						model.set("geoData",response);						
						this.createBox(response);
					}					
				}
			}
		},

		setLocationRFC: function (location){
			var model,
				obj={},
				response;
			model= this.model;
			model.set("geoData",location);

			//location.data is a string Base64 from device mobile
			if(location.data){
				this.createBox(response);	//ok	
			}else{
				response=model.getImagesNetwork(location);								
				this.createBox(response);		
			}
		},
		createImageOffLine: function (location){
			location["filePath"]=this.imageOffLine;
			this.createBox({
				filePath:this.imageOffLine
			});
		},
		setData: function (data) {
			if (data["value"] && data["value"] !== "")
				this.setLocation(data["value"]);
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.GeoMobile",GeoMobile);
}());
(function(){
    var MediaElement = function (settings) {
        this.el = settings.el;
        this.$el = settings.el;
        this.streaming = settings.streaming? settings.streaming: null;
        this.type = settings.type;
        if(this.type == "video"){            
            MediaElement.prototype.initVideo.call(this, this.el);
        }
        if(this.type== "audio"){
            MediaElement.prototype.initAudio.call(this, this.el);
        }        
    };

    MediaElement.prototype.initVideo = function (element) {
            var video = element.find("video");
            var control = element.find(".pmdynaform-media-control");
            //remove default control when JS loaded
            video[0].removeAttribute("controls");
            element.find('.pmdynaform-media-control').fadeIn(500);
            element.find('.pmdynaform-media-caption').fadeIn(500);
         
            //before everything get started
            video.on('loadedmetadata', function() {
                    
                //set video properties
                element.find('.current').text(timeFormat(0));
                element.find('.duration').text(timeFormat(video[0].duration));
                updateVolume(0, 0.7);
                    
                //start to get video buffering data 
                setTimeout(startBuffer, 150);
                //bind video events
            });
            
            //display video buffering bar
            var startBuffer = function() {
                var that= this;
                var currentBuffer = video[0].buffered.end(0);
                var maxduration = video[0].duration;
                var perc = 100 * currentBuffer / maxduration;
                element.find('.pmdynaform-media-bufferBar').css('width',perc+'%');
                    
                if(currentBuffer < maxduration) {
                    setTimeout(startBuffer, 500);
                }
            };  
            
            //display current video play time
            video.on('timeupdate', function() {
                var currentPos = video[0].currentTime;
                var maxduration = video[0].duration;
                var perc = 100 * currentPos / maxduration;
                element.find('.pmdynaform-media-timeBar').css('width',perc+'%');    
                element.find('.current').text(timeFormat(currentPos)); 
            });
            
            //CONTROLS EVENTS
            //video screen and play button clicked
            video.on('click', function() { playpause(); } );
            element.find('.btnPlay').on('click', function() { playpause(); } );
            var playpause = function() {
                if(kitKatMode != null){
                    JsInterface.startVideo(video[0].src,"video/mp4");                                    
                }else{
                    if(video[0].paused) {
                        element.find('.btnPlay').addClass('paused');
                        element.find('.btnPlay').find('.glyphicon.glyphicon-play').addClass('glyphicon glyphicon-pause').removeClass('glyphicon-play');
                        video[0].play();
                    }
                    else {
                        element.find('.btnPlay').removeClass('paused');
                        element.find('.btnPlay').find('.glyphicon.glyphicon-pause').addClass('glyphicon glyphicon-play').removeClass('glyphicon-pause');
                        video[0].pause();
                    }
                }
            };

            
            //fullscreen button clicked
            element.find('.btnFS').on('click', function() {
                if($.isFunction(video[0].webkitEnterFullscreen)) {
                    video[0].webkitEnterFullscreen();
                }   
                else if ($.isFunction(video[0].mozRequestFullScreen)) {
                    video[0].mozRequestFullScreen();
                }
                else {
                    alert('Your browsers doesn\'t support fullscreen');
                }
            });
            
            //sound button clicked
            element.find('.sound').click(function() {
                video[0].muted = !video[0].muted;
                $(this).toggleClass('muted');
                if(video[0].muted) {
                    element.find('.pmdynaform-media-volumeBar').css('width',0);
                }
                else{
                    element.find('.pmdynaform-media-volumeBar').css('width', video[0].volume*100+'%');
                }
            });
            
            //VIDEO EVENTS
            //video canplay event
            video.on('canplay', function() {
                element.find('.loading').fadeOut(100);
            });
            
            //video canplaythrough event
            //solve Chrome cache issue
            var completeloaded = false;
            video.on('canplaythrough', function() {
                completeloaded = true;
            });
            
            //video ended event
            video.on('ended', function() {
                element.find('.btnPlay').removeClass('paused');
                video[0].pause();
            });

            //video seeking event
            video.on('seeking', function() {
                //if video fully loaded, ignore loading screen
                if(!completeloaded) { 
                    element.find('.loading').fadeIn(200);
                }   
            });
            
            //video seeked event
            video.on('seeked', function() { });
            
            //video waiting for more data event
            video.on('waiting', function() {
                element.find('.loading').fadeIn(200);
            });
            
            //VIDEO PROGRESS BAR
            //when video timebar clicked
            var timeDrag = false;   /* check for drag event */
            element.find('.pmdynaform-media-progress').on('mousedown', function(e) {
                timeDrag = true;
                updatebar(e.pageX);
            });
            $(document).on('mouseup', function(e) {
                if(timeDrag) {
                    timeDrag = false;
                    updatebar(e.pageX);
                }
            });
            $(document).on('mousemove', function(e) {
                if(timeDrag) {
                    updatebar(e.pageX);
                }
            });
            var updatebar = function(x) {
                var progress = element.find('.pmdynaform-media-progress');
                
                //calculate drag position
                //and update video currenttime
                //as well as progress bar
                var maxduration = video[0].duration;
                var position = x - progress.offset().left;
                var percentage = 100 * position / progress.width();
                if(percentage > 100) {
                    percentage = 100;
                }
                if(percentage < 0) {
                    percentage = 0;
                }
                element.find('.pmdynaform-media-timeBar').css('width',percentage+'%');  
                video[0].currentTime = maxduration * percentage / 100;
            };

            //VOLUME BAR
            //volume bar event
            var volumeDrag = false;
            element.find('.pmdynaform-media-volume').on('mousedown', function(e) {
                volumeDrag = true;
                video[0].muted = false;
                element.find('.sound').removeClass('muted');
                updateVolume(e.pageX);
            });
            $(document).on('mouseup', function(e) {
                if(volumeDrag) {
                    volumeDrag = false;
                    updateVolume(e.pageX);
                }
            });
            $(document).on('mousemove', function(e) {
                if(volumeDrag) {
                    updateVolume(e.pageX);
                }
            });
            var updateVolume = function(x, vol) {
                var volume = element.find('.pmdynaform-media-volume');
                var percentage;
                //if only volume have specificed
                //then direct update volume
                if(vol) {
                    percentage = vol * 100;
                }
                else {
                    var position = x - volume.offset().left;
                    percentage = 100 * position / volume.width();
                }
                
                if(percentage > 100) {
                    percentage = 100;
                }
                if(percentage < 0) {
                    percentage = 0;
                }
                
                //update volume bar and video volume
                element.find('.pmdynaform-media-volumeBar').css('width',percentage+'%');    
                video[0].volume = percentage / 100;
                
                //change sound icon based on volume
                if(video[0].volume == 0){
                    element.find('.sound').removeClass('sound2').addClass('muted');
                }
                else if(video[0].volume > 0.5){
                    element.find('.sound').removeClass('muted').addClass('sound2');
                }
                else{
                    element.find('.sound').removeClass('muted').removeClass('sound2');
                }
                
            };

            //Time format converter - 00:00
            var timeFormat = function(seconds){
                var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
                var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
                return m+":"+s;
            };
            this.$el=element;    
    };


    MediaElement.prototype.initAudio = function (element) {
            var video = element.find("audio");
            var control = element.find(".pmdynaform-media-control");
            //remove default control when JS loaded
            video[0].removeAttribute("controls");
            element.find('.pmdynaform-media-control').fadeIn(500);
            element.find('.pmdynaform-media-caption').fadeIn(500);
         
            //before everything get started
            video.on('loadedmetadata', function() {
                    
                //set video properties
                element.find('.current').text(timeFormat(0));
                element.find('.duration').text(timeFormat(video[0].duration));
                updateVolume(0, 0.7);
                    
                //start to get video buffering data 
                setTimeout(startBuffer, 150);
                    
                //bind video events
            });
            
            //display video buffering bar
            var startBuffer = function() {
                var that= this;
                var currentBuffer = video[0].buffered.end(0);
                var maxduration = video[0].duration;
                var perc = 100 * currentBuffer / maxduration;
                element.find('.pmdynaform-media-bufferBar').css('width',perc+'%');
                    
                if(currentBuffer < maxduration) {
                    setTimeout(startBuffer, 500);
                }
            };  
            
            //display current video play time
            video.on('timeupdate', function() {
                var currentPos = video[0].currentTime;
                var maxduration = video[0].duration;
                var perc = 100 * currentPos / maxduration;
                element.find('.pmdynaform-media-timeBar').css('width',perc+'%');    
                element.find('.current').text(timeFormat(currentPos)); 
            });
            
            //CONTROLS EVENTS
            //video screen and play button clicked
            video.on('click', function() { playpause(); } );
            element.find('.btnPlay').on('click', function() { playpause(); } );
            var playpause = function() {
                if(kitKatMode != null){
                    JsInterface.startVideo(video[0].src,"audio/mp4");
                }else {
                    if (video[0].paused || video[0].ended) {
                        element.find('.btnPlay').addClass('paused');
                        element.find('.btnPlay').find('.glyphicon.glyphicon-play').addClass('glyphicon-pause').removeClass('glyphicon-play');
                        video[0].play();
                    }
                    else {
                        element.find('.btnPlay').removeClass('paused');
                        element.find('.btnPlay').find('.glyphicon.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');
                        video[0].pause();
                    }
                }
            };

            
            //fullscreen button clicked
            element.find('.btnFS').on('click', function() {
                if($.isFunction(video[0].webkitEnterFullscreen)) {
                    video[0].webkitEnterFullscreen();
                }   
                else if ($.isFunction(video[0].mozRequestFullScreen)) {
                    video[0].mozRequestFullScreen();
                }
                else {
                    alert('Your browsers doesn\'t support fullscreen');
                }
            });
            
            //sound button clicked
            element.find('.sound').click(function() {
                video[0].muted = !video[0].muted;
                $(this).toggleClass('muted');
                if(video[0].muted) {
                    element.find('.pmdynaform-media-volumeBar').css('width',0);
                }
                else{
                    element.find('.pmdynaform-media-volumeBar').css('width', video[0].volume*100+'%');
                }
            });
            
            //VIDEO EVENTS
            //video canplay event
            video.on('canplay', function() {
                element.find('.loading').fadeOut(100);
            });
            
            //video canplaythrough event
            //solve Chrome cache issue
            var completeloaded = false;
            video.on('canplaythrough', function() {
                completeloaded = true;
            });
            
            //video ended event
            video.on('ended', function() {
                element.find('.btnPlay').removeClass('paused');
                video[0].pause();
            });

            //video seeking event
            video.on('seeking', function() {
                //if video fully loaded, ignore loading screen
                if(!completeloaded) { 
                    element.find('.loading').fadeIn(200);
                }   
            });
            
            //video seeked event
            video.on('seeked', function() { });
            
            //video waiting for more data event
            video.on('waiting', function() {
                element.find('.loading').fadeIn(200);
            });
            
            //VIDEO PROGRESS BAR
            //when video timebar clicked
            var timeDrag = false;   /* check for drag event */
            element.find('.pmdynaform-media-progress').on('mousedown', function(e) {
                timeDrag = true;
                updatebar(e.pageX);
            });
            $(document).on('mouseup', function(e) {
                if(timeDrag) {
                    timeDrag = false;
                    updatebar(e.pageX);
                }
            });
            $(document).on('mousemove', function(e) {
                if(timeDrag) {
                    updatebar(e.pageX);
                }
            });
            var updatebar = function(x) {
                var progress = element.find('.pmdynaform-media-progress');
                
                //calculate drag position
                //and update video currenttime
                //as well as progress bar
                var maxduration = video[0].duration;
                var position = x - progress.offset().left;
                var percentage = 100 * position / progress.width();
                if(percentage > 100) {
                    percentage = 100;
                }
                if(percentage < 0) {
                    percentage = 0;
                }
                element.find('.pmdynaform-media-timeBar').css('width',percentage+'%');  
                video[0].currentTime = maxduration * percentage / 100;
            };

            //VOLUME BAR
            //volume bar event
            var volumeDrag = false;
            element.find('.pmdynaform-media-volume').on('mousedown', function(e) {
                volumeDrag = true;
                video[0].muted = false;
                element.find('.sound').removeClass('muted');
                updateVolume(e.pageX);
            });
            $(document).on('mouseup', function(e) {
                if(volumeDrag) {
                    volumeDrag = false;
                    updateVolume(e.pageX);
                }
            });
            $(document).on('mousemove', function(e) {
                if(volumeDrag) {
                    updateVolume(e.pageX);
                }
            });
            var updateVolume = function(x, vol) {
                var volume = element.find('.pmdynaform-media-volume');
                var percentage;
                //if only volume have specificed
                //then direct update volume
                if(vol) {
                    percentage = vol * 100;
                }
                else {
                    var position = x - volume.offset().left;
                    percentage = 100 * position / volume.width();
                }
                
                if(percentage > 100) {
                    percentage = 100;
                }
                if(percentage < 0) {
                    percentage = 0;
                }
                
                //update volume bar and video volume
                element.find('.pmdynaform-media-volumeBar').css('width',percentage+'%');    
                video[0].volume = percentage / 100;
                
                //change sound icon based on volume
                if(video[0].volume == 0){
                    element.find('.sound').removeClass('sound2').addClass('muted');
                }
                else if(video[0].volume > 0.5){
                    element.find('.sound').removeClass('muted').addClass('sound2');
                }
                else{
                    element.find('.sound').removeClass('muted').removeClass('sound2');
                }
                
            };

            //Time format converter - 00:00
            var timeFormat = function(seconds){
                var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
                var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
                return m+":"+s;
            };
            this.$el=element;    
    };
    PMDynaform.extendNamespace("PMDynaform.core.MediaElement", MediaElement);

}());


(function () {
    var Qrcode_mobile = PMDynaform.view.Field.extend({
        item: null,
        template: _.template($("#tpl-ext-scannercode").html()),
        templatePlus: _.template($("#tpl-extfile-plus").html()),
        templateCode: _.template($("#tpl-ext-scanner-code").html()),
        boxPlus: null,
        boxModal: null,
        boxBackground: null,
        viewsImages: [],
        events: {
            "click button": "onClickButton"
        },
        initialize: function () {
        },
        onClickButton: function (event) {
            var respData;
            respData = {
                idField: this.model.get("name")
            };
            if (navigator.userAgent === "formslider-android") {
                try {
                    JsInterface.getScannerCode(JSON.stringify(respData));
                } catch (e) {
                    console.error(e);
                }
            }
            if (navigator.userAgent === "formslider-ios") {
                this.model.attributes.project.setMemoryStack({"data": respData});
                this.model.attributes.project.projectFlow.executeFakeIOS("scannercode");
            }
            event.preventDefault();
            event.stopPropagation();
            return this;
        },
        hideButton: function () {
            var button;
            button = this.$el.find("button");
            button.hide();
        },
        showLabel: function (scannercode) {
            var container;
            container = this.$el.find("scanner").find(".pmdynaform-label-options");
            container.append(this.templateCode({label: scannercode}));
        },
        render: function () {
            var that = this;
            if (PMDynaform.core.ProjectMobile) {
                this.$el.html(this.template(this.model.toJSON()));
            } else {
                this.$el.html(this.template(this.model.toJSON()));
                var data;
                if (that.project.mobileDataControls) {
                    if (that.project.mobileDataControls["data"]) {
                        var data = that.project.mobileDataControls["data"][that.model.get("name")];
                        if (data) {
                            this.setScannerCode(data);
                        }
                    }
                }
                this.$el.find("button").detach();
            }
            return this;
        },
        setScannerCode: function (scannercode) {
            var model, i;
            model = this.model;
            if (_.isArray(scannercode)) {
                for (i = 0; i < scannercode.length; i += 1) {
                    model.addCode(scannercode[i]);
                    this.showLabel(scannercode[i]);
                }
            } else {
                model.addCode(scannercode.data);
                this.showLabel(scannercode.data);
            }
            return this;
        },
        setData: function (data) {
            this.setScannerCode(data["value"]);
            return this;
        },
        readFileDeviceScanner: function (data) {
            var str;
            $.ajax({
                url: data.data,
                dataType: 'text',
                async: false,
                success: function (data, xhr) {
                    str = data;
                }
            });
            this.setScannerCode({data: str});
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Qrcode_mobile", Qrcode_mobile);
}());
(function () {
    var Signature_mobile = PMDynaform.view.Field.extend({
        item: null,
        template: _.template($("#tpl-ext-signature").html()),
        templatePlus: _.template($("#tpl-extfile-plus").html()),
        viewsImages: [],
        imageOffLine: "geoMap.jpg",
        events: {
            "click button": "onClickButton"
        },
        initialize: function () {

        },
        onClickButton: function (event) {
            var respData;
            this.model.set("interactive", true);
            respData = {
                idField: this.model.get("name")
            };
            if (navigator.userAgent == "formslider-android") {
                JsInterface.getSignature(JSON.stringify(respData));
            }
            if (navigator.userAgent == "formslider-ios") {
                this.model.attributes.project.setMemoryStack({"data": respData, "source": "IOS"});
                this.model.attributes.project.projectFlow.executeFakeIOS("signature");
            }
            event.preventDefault();
            event.stopPropagation();
            return this;
        },
        makeBase64Image: function (base64) {
            return "data:image/png;base64," + base64;
        },
        createBox: function (data) {
            var rand,
                newsrc,
                template,
                resizeImage,
                preview,
                progress;

            if (data.filePath) {
                newsrc = data.filePath;
            } else {
                newsrc = this.makeBase64Image(data.base64);
            }
            rand = Math.floor((Math.random() * 100000) + 3);

            template = document.createElement("div"),
                resizeImage = document.createElement("div"),
                preview = document.createElement("span"),
                progress = document.createElement("div");

            template.id = rand;
            template.className = "pmdynaform-file-containergeo";

            resizeImage.className = "pmdynaform-file-resizeimage";
            resizeImage.innerHTML = '<img src="' + newsrc + '">';
            preview.id = rand;
            preview.className = "pmdynaform-file-preview";
            preview.appendChild(resizeImage);
            template.appendChild(preview);
            this.$el.find(".pmdynaform-ext-geo").prepend(template);
            this.hideButton();
            return this;
        },
        hideButton: function () {
            var button;
            button = this.$el.find("button");
            button.hide();
        },
        render: function () {
            var that = this,
                fileContainer,
                fileControl,
                signature,
                itemElement;
            if (PMDynaform.core.ProjectMobile) {
                this.$el.html(this.template(this.model.toJSON()));
                if (this.model.get("hint")) {
                    this.enableTooltip();
                }
                fileContainer = this.$el.find(".pmdynaform-file-droparea-ext")[0];
                fileControl = this.$el.find("input")[0];
            } else {
                this.$el.html(this.template(this.model.toJSON()));
                fileContainer = this.$el.find(".pmdynaform-geo-container").empty();
                if (this.project.mobileDataControls) {
                    signature = this.project.mobileDataControls;
                    if (signature.data && signature.data[this.model.get("name")] && signature.data[this.model.get("name")].length > 0) {
                        signature = signature.data[this.model.get("name")];
                        signature = this.model.remoteProxyData(signature[0]);
                        itemElement = $("<img src=\"data:image/png;base64," + signature.base64 + "\"class='img-thumbnail' alt='Thumbnail Image'>");
                        fileContainer.append(itemElement);
                    }
                }
            }
            return this;
        },
        setFiles: function (arrayFiles) {
            var array;
            for (var i = 0; i < arrayFiles.length; i++) {
                this.createBox(arrayFiles[i]);
                this.model.attributes.files.push(arrayFiles[i]);
            }
        },
        setSignature: function (arraySignature) {
            var i,
                response,
                obj = [],
                files = [];
            for (i = 0; i < arraySignature.length; i++) {
                if (typeof arraySignature[i] == "string") {
                    response = this.model.remoteProxyData(arraySignature[i]);
                    this.createBox(response);
                    files.push(response);

                } else {
                    this.createBox(arraySignature[i]);
                    files.push(arraySignature[i]);
                }
            }
            this.model.set("files", files);
        },
        changeID: function (arrayNew) {
            var array = this.model.attributes.files,
                itemNew,
                itemOld;
            for (var i = 0; i < arrayNew.length; i++) {
                itemNew = arrayNew[i];
                for (var j = 0; j < array.length; j++) {
                    itemOld = array[j];
                    if (typeof itemOld === "string") {
                        if (itemNew["idOld"] === itemOld) {
                            itemOld = itemNew["idNew"];
                        }
                    }
                    if (typeof itemOld === "object") {
                        if (itemNew["idOld"] === itemOld["id"]) {
                            itemOld["id"] = itemNew["idNew"];
                        }
                    }
                }
            }
        },
        setData: function (data) {
            this.setSignature(data["value"]);
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Signature_mobile", Signature_mobile);
}());
(function(){
	
	var Validator = Backbone.Model.extend({
        defaults: {
            message: {},
            title: "",
            type: "",
            dataType: "",
            value: "",
            valid: true,
            maxLength: null,
            required: false,
            domain: false,
            options: [],
            factory: {},
            valueDomain: null,
            regExp : null,
            requiredGrid : false,
            haveOptions: [
                "suggest",
                "checkbox",
                "radio",
                "dropdown"
            ]
        },
        initialize: function() {
        	var factoryValidator = {
        		"text": "requiredText",
    			"checkbox": "requiredCheckBox",
				"checkgroup": "requiredCheckGroup",
    			"radio": "requiredRadioGroup",
    			"dropdown": "requiredDropDown",
    			"textarea": "requiredText",
    			"datetime": "requiredText",
                "suggest": "requiredText" ,
                "file" : "requiredFile",
                "grid" : "requiredGrid"
        	};
        	this.setFactory(factoryValidator);
            this.checkDomainProperty();
        },
        setFactory: function(obj) {
        	this.set("factory", obj);
        	return this;
        },
        checkDomainProperty: function () {
            this.attributes.domain = ($.inArray(this.get("type"), this.get("haveOptions")) >= 0)? true: false;
            return this;
        },
        verifyValue: function () {
            var value = this.get('value'),
            valueDomain = this.get('valueDomain'),
            options = this.get('options'),
            validator = this.attributes.factory[this.get("type").toLowerCase()],
            regExp;

            this.set("valid", true);
            delete this.get("message")[validator];
            if (this.get('type') == 'file') {
                if (this.get('fileOnly') && this.get('fileOnly') !== null) {
                    if(this.get('fileOnly')['type'] == 'support') {
                        this.set('valid', false);
                        this.set('message', {
                            validator: this.get('fileOnly')['message']
                        });
                        return this;
                    }
                    if(this.get('fileOnly')['type'] == 'size') {
                        this.set('valid', false);
                        this.set('message',  {
                            validator: this.get('fileOnly')['message']
                        });
                        return this;
                    }
                }
            }
            if (this.get("required")) {
            	if (PMDynaform.core.Validators[validator].fn(value) === false) {
	                this.set("valid", false);
	                this.set("message", {
	                    validator: PMDynaform.core.Validators[validator].message
	                });
                    return this;
	            }
            }
            if(this.get("type") === "text" || this.get("type") === "textarea"){
                if (this.get("dataType") !== "" && value !== "") {
                    if (PMDynaform.core.Validators[this.get("dataType")] && PMDynaform.core.Validators[this.get("dataType")].fn(value) === false) {
                        this.set("valid", false);
                        this.set("message", {
                            "validator": PMDynaform.core.Validators[this.get("dataType")].message
                        });
                        return this;
                    }
                }

                if (this.get("maxLength")) {
                     if (PMDynaform.core.Validators.maxLength.fn( value, parseInt(this.get("maxLength"))) === false) {
                        this.set("valid", false);
                        this.set("message", {
                            validator: PMDynaform.core.Validators.maxLength.message + " " +this.get("maxLength") + " characters"
                        });
                        return this;
                    }
                }

                if (this.get("regExp") && this.get("regExp").validate !== ""){
                    regExp = new RegExp(this.get("regExp").validate);
                    if (value.length > 0 && !regExp.test(value)) {
                        this.set("valid", false);
                        this.set("message", {validator:this.get("regExp").message});
                    } else {
                        this.set('valid', true);
                    }
                    return this;
                }
            }
            return this;
        },
        /**
         * verifies that meets validation having at least one row
         * when the grid is required
         * @returns {Validator}
         */
        verifyGrid: function(){
            if (this.get("required")){
                if (PMDynaform.core.Validators["requiredGrid"].fn(this.get("rowsNumber")) === false) {
                    this.set("valid", false);
                    this.set("message", {
                        validator: PMDynaform.core.Validators["requiredGrid"].message
                    });
                }else{
                    this.set('valid', true);
                }
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Validator", Validator);

}());
(function(){
	var PanelModel = Backbone.Model.extend({
		defaults: {
			items: [],
			mode: "edit",
			namespace: "pmdynaform",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("form"),
			type: "form",
			onBeforePrintHandler : null,
			onAfterPrintHandler : null,
		},
		getData: function() {
			return {
				type: this.get("type"),
				name: this.get("name"),
				variables: {}
			}
		}
	});
	PMDynaform.extendNamespace("PMDynaform.model.Panel", PanelModel);

}());
(function(){
	var FormPanel = Backbone.Model.extend({
		defaults: {
			action: "",
			autocomplete: "on",
			script: {},
			data: [],
			items: [],
			name: 'PMDynaform-form',
			method: "get",
			namespace: "pmdynaform",
			target: null,
			type: "panel",
			inputDocuments : {},
			printable : false,
			project : null
		},
		getData: function(){
			return {
				type: this.get("type"),
				action: this.get("action"),
				method: this.get("method")
			}
		},
        /**
         * This method closes this form, stand alone version for mobile
         * @returns {FormPanel}
         */
        close: function () {
            var flow,
                project = this.get("project");
            if (project && project.projectFlow) {
                flow = project.projectFlow;
                flow.close();
            }
            return this;
        }
	});
	PMDynaform.extendNamespace("PMDynaform.model.FormPanel", FormPanel);
}());
(function(){
    var FieldModel = Backbone.Model.extend({
        defaults: {
            colSpan: 12,
            id: PMDynaform.core.Utils.generateID(),
            label: "Untitled",
            name: PMDynaform.core.Utils.generateName(),
            value: "",
            nameGridColum : null,
            text : "",
            data : null
        },
        initialize: function (options) {
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
        },
        defineModelEvents : function (){
            this.on("change:text", this.onChangeText, this);
            this.on("change:value", this.onChangeValue, this);
            this.on("change:options", this.onChangeOptions, this);
            this.on("change:label", this.onChangeLabel, this);
            return this;
        },
        onChangeValue: function (attrs, item) {
            var data;
            data = this.findOption(item,"value");
            if (data){
                this.set("data",data);
            }else{
                this.set("data",{value:"",label:""});
            }
            this.set("text",data["label"]);
            return this;
        },
        onChangeText : function (attrs, item){
            var data;
            data = this.findOption(item,"label");
            if (data){
                this.set("data",data);
            }else{
                this.set("data",{value:"",label:""});
            }
            this.set("value",data["value"]);
            return this;
        },
        getData: function() {
            return {
                name : this.get("name") ? this.get("name") : "",
                value :  this.get("value")
            }
        },
        parseLabel: function () {
            var currentLabel = this.get("label"),
                maxLength = this.get("maxLengthLabel"),
                currentSize,
                itemsLabel,
                k,
                parsed = false;

            itemsLabel = currentLabel.split(/\s/g);
            for (k=0; k<itemsLabel.length; k+=1) {
                if (itemsLabel[k].length > maxLength) {
                    parsed = true;
                }
            }
            if (parsed) {
                this.set("tooltipLabel", currentLabel);
                this.set("label", currentLabel.substr(0, maxLength-4)+"...");
            }
            return this;
        },
        validate: function (attrs) {
            this.set("value", attrs.value);
            this.set("label", attrs.label);

            return this;
        },
        getEndpointVariable: function (urlObj) {
            var prj = this.get("project"),
                endPointFixed,
                variable,
                endpoint;

            if (prj.endPointsPath[urlObj.type]) {
                endpoint = prj.endPointsPath[urlObj.type]
                for (variable in urlObj.keys) {
                    if (urlObj.keys.hasOwnProperty(variable)) {
                        endPointFixed =endpoint.replace(new RegExp(variable, "g"), urlObj.keys[variable]);
                    }
                }
            }

            return endPointFixed;
        },
        onChangeOptions: function () {
            var i,
                newOptions = [],
                options = this.get("options");

            for (i=0; i<options.length; i+=1) {
                newOptions.push(options[i]);
            }
            this.attributes.options = newOptions;

            return this;
        },
        /**
         * The method check all the fields related to the current field based of the variable and
         * set the same value to others. After set the value, all the fields are rendered.
         */
        changeValuesFieldsRelated: function () {
            var i,
                currentValue = this.get("value"),
                fieldsRelated = this.get("fieldsRelated") || [];

            for (i=0; i<fieldsRelated.length; i+=1) {
                fieldsRelated[i].model.attributes.value = currentValue;
                fieldsRelated[i].model.get("validator").set({
                    valueDomain: this.get("value"),
                    options: fieldsRelated[i].model.attributes.options || [],
                    domain: true
                });
                fieldsRelated[i].model.get("validator").verifyValue();
                fieldsRelated[i].render();
            }

            return this;
        },
        reviewRemoteVariable: function () {
            var prj = this.get("project"),
                url,
                restClient,
                that = this,
                endpoint,
                data = {};
            if (this.get("group") === "grid"){
                data["field_id"] = this.get("columnName");
            }else{
                data["field_id"] = this.get("id");
            }
            if ( this.get("form") ) {
                if ( this.get("form").model.get("form") ) {
                    data["dyn_uid"] = this.get("form").get("form").get("id");
                }else{
                    data["dyn_uid"] = this.get("form").model.get("id");
                }
            }

            endpoint = this.getEndpointVariable({
                type: "executeQuery",
                keys: {
                    "{var_name}": this.get("var_name") || ""
                }
            });
            url = prj.getFullURL(endpoint);
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'POST',
                data : data,
                keys : prj.token,
                successCallback: function (xhr, response) {
                    that.mergeOptions(response);
                }
            });
            this.set("proxy", restClient);
            return this;
        },
        mergeOptions : function (remoteOptions){
            var k, remoteOpt = [], localOpt = [], options = [];
            for ( k = 0; k < remoteOptions.length; k+=1) {
                remoteOpt.push({
                    value : remoteOptions[k].value,
                    label : remoteOptions[k].text
                });
            }
            localOpt = this.get("localOptions");
            options = localOpt.concat(remoteOpt);
            this.attributes.options = options;
            return this;
        },
        getKeyLabel : function (){
            var returnValue;
            if (this.get("group") == "grid"){
                return {
                    name : this.get("columnName") ? this.get("columnName"): "",
                    value :  this.get("value")
                }
            } else {
                if(this.get("keyLabel")){
                    returnValue =  this.get("keyLabel");
                }else{
                    returnValue = this.get("data")?this.get("data")["label"]:"";
                }
                return {
                    name : this.get("name") ? this.get("name").concat("_label") : "",
                    value :  returnValue
                }
            }
        },
        setLocalOptions: function () {
            if (this.get("options")){
                this.set("localOptions", this.get("options"));
            }
            return this;
        },
        setRemoteOptions :function (){
            if (this.get("remoteOptions")){
                this.set("remoteOptions", this.get("optionsSql"));
            }
            return this;
        },
        mergeOptionsSql : function (){
            var options = [];
            if (this.get("options") && this.get("optionsSql"))
                options = this.get("localOptions").concat(this.get("optionsSql"));
            this.set("options", options);
            return this;
        },
        /*
         This function work for formulas in the fields
         */
        addFormulaTokenAssociated: function(formulator) {
            if (formulator instanceof PMDynaform.core.Formula) {
                formulator.addTokenValue(this.get("id"), this.get("value"));
            }
            return this;
        },
        /*
         This function work for formulas in the fields
         */
        updateFormulaValueAssociated: function(field) {
            var resultField = field.model.get("formulator").evaluate();
            field.model.set("value", resultField);
            return this;
        },
        /**
         * findOption(): This method find and return a option in the array options if exist
         * @param value = the filter in the search  "value", "label" or "defaultValue"
         * @param criteria = is the criteria in the find the option should be a "value", "label"
         * @returns {boolean||object}
         */
        findOption : function (value, criteria) {
            var i,
                cloneLocal = this.get("localOptions".slice(0)),
                cloneSql = this.get("optionsSql".slice(0)),
                options,
                option = false;
            if (cloneLocal && _.isArray(cloneLocal)) {
                options = cloneLocal.concat(cloneSql);
                if(_.isArray(options) && value !== undefined && typeof criteria === "string"){
                    for ( i = 0 ; i < options.length ; i+=1) {
                        if(this.get("dataType") === "boolean"){
                            if( options[i] && (value == options[i][criteria]) ){
                                option = _.extend({},options[i]);
                                break;
                            }
                        }else{
                            if( options[i] && (value === options[i][criteria]) ){
                                option = _.extend({},options[i]);
                                break;
                            }
                        }
                    }
                }
            }
            return option;
        },
        /**
         * findOptions(): This method find and return multiple options in the array options if exist the values
         * @param values = the filter in the search "value" or  "label"
         * @param criteria = is the criteria in the find the option should be a "value" or "label"
         * @returns {Array}
         */
        findOptions : function (values, criteria) {
            var options = this.get("options"),
                filterOptions = [];
            if(_.isArray(values) && _.isArray(options) && typeof criteria === "string"){
                filterOptions = options.filter(function(item){
                    if( values.indexOf(item[criteria]) > -1 ){
                        return item;
                    }
                });
            }
            return filterOptions;
        },
        /**
         * returnOptionsData(): This build the data for the multiple options
         * @param options: this options the field
         * @returns {{value: Array, label: Array}}
         */
        returnOptionsData : function (options) {
            var i,
                labels = [],
                values = [],
                options = options || this.get("options");
            if (_.isArray(options)){
                for ( i = 0 ; i < options.length ; i+=1){
                    values.push(options[i]["value"]);
                    labels.push(options[i]["label"]);
                }
            }
            return {
                value : values,
                label : labels
            }
        },
        onDependentHandler: function () {
            var execute = true, localOpt, remoteOptions, auxData, key;
            auxData = this.jsonData;
            this.jsonData = this.generateDataDependenField();
            remoteOptions = this.executeQuery();
            this.mergeOptions(remoteOptions);
            if (this.get("options").length) {
                this.set("data",{
                    value: this.get("options")[0].value,
                    label: this.get("options")[0].label

                });
            }
            return this;
        },
        executeQuery: function (clicked) {
            var restClient, key, resp, prj, endpoint, url, data;
            data = this.preparePostData();
            prj = this.get("project");
            resp = prj.webServiceManager.executeQuery(data, this.get("variable") || "");
            return resp;
        },
        preparePostData: function () {
            var data;
            data = this.jsonData || {};
            if (this.get("group") === "grid") {
                data["field_id"] = this.get("columnName");
            } else {
                data["field_id"] = this.get("id");
            }
            if (this.get("form")) {
                if (this.get("form").model.get("form")) {
                    data["dyn_uid"] = this.get("form").model.get("form").model.get("id");
                } else {
                    data["dyn_uid"] = this.get("form").model.get("id");
                }
            }
            return data;
        },
        generateDataDependenField: function () {
            var i, parentDependents, data = {}, name;
            parentDependents = this.get("parentDependents");

            if(_.isArray(parentDependents)) {
                for (i = 0; i < parentDependents.length; i += 1) {
                    if (parentDependents[i].get("group") === "grid") {
                        name = parentDependents[i].get("columnName");
                    } else {
                        if (parentDependents[i].get("variable") && parentDependents[i].get("variable") !== "") {
                            name = parentDependents[i].get("variable");
                        } else {
                            name = parentDependents[i].get("id");
                        }
                    }
                    if (parentDependents[i].get("mode") === "view") {
                        data[name] = parentDependents[i].get("data")["value"];
                    } else {
                        data[name] = parentDependents[i].get("value");
                    }
                }
            }
            return data;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Field", FieldModel);
}());
(function(){
	var GridModel = PMDynaform.model.Field.extend({
		defaults: {
			title: "Grid",
			colSpan: 12,
			colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
			columns: [],
			data: [],
			disabled: false,
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("grid"),
			gridtable: [],
			layoutOpt: [
				"responsive",
				"static"
			],
			layout: "responsive",
			pager: true,
			paginationRotate: false,
			paginationItems: 1,
			pageSize: 5,
			mode: "edit",
			rows: 1,
			type: "grid",
			functions: false,
			totalrow: [],
			functionOptions: {
				"sum": "sumValues",
				"avg": "avgValues"
			},
			dataColumns: [],
			gridFunctions: [],
			titleHeader: [],
			valid : true,
			countHiddenControl : 0,
			newRow : true,
			deleteRow : true,
			variable : "",
			required : false,
			emptyMessage : "No records"
		},
        /**
         * initialize the validator object in the grid
         * @returns {GridModel}
         */
        initValidators: function () {
            this.set("validator", new PMDynaform.model.Validator({
                type: this.get("type"),
                required: this.get("required"),
                rowsNumber: this.get("rows")
            }));
            return this;
        },
        /**
         * verify if the grid is valid
         * @returns {boolean}
         */
        isValid: function () {
            this.validate();
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        /**
         * execute the validation of the validator associated object
         * and evaluate the grid validation
         * @returns {GridModel}
         */
        validate: function () {
            this.get("validator").set("rowsNumber", this.get("rows"));
            this.get("validator").verifyGrid();
            return this;
        },
		initialize: function (options) {
			var pagesize;
				options = options || {};
			if (options["addRow"] === undefined){
				this.set("addRow",true);
			}
			if(options["deleteRow"] === undefined){
				this.set("deleteRow",true);	
			}
			if ( jQuery.isNumeric(this.get("pageSize") ) ){
				pagesize = parseInt(this.get("pageSize"),10);
				if ( pagesize < 1 ) {
					pagesize = 1;
					this.set("pager",false);
				}
			} else {
				this.set("pager",false);
			}
			if (!PMDynaform.core.ProjectMobile) {
				this.set("pageSize", pagesize);
			}
			this.set("label", this.get("label"));
			this.on("change:label", this.onChangeLabel, this);
			if(options.project) {
                this.project = options.project;
            }
			if ( this.get("variable").trim().length === 0) {
				this.attributes.name = "";
			}else{
            	this.attributes.name = this.get("variable");
			}
            this.fixCoutFieldsHidden();
            this.setLayoutGrid();
            this.setPaginationItems();
            this.checkTotalRow();
			this.initValidators();
		},
        /**
         * Changes undefined layout properties to static
         * @param initial
         * @param final
         * @returns {GridModel}
         */
        setLayoutGrid: function () {
            if ($.inArray(this.get("layout"), this.get("layoutOpt")) < 0) {
                this.set("layout", "static");
            }

            return this;
        },
		setPaginationItems: function () {
			var rows = this.get("rows"),
			size = this.get("pageSize"),
			rotate = this.get("paginationRotate"),
			pagerItems;

			pagerItems = Math.ceil(rows/size) ? Math.ceil(rows/size) : 1;
            
            this.set("paginationRotate", rotate);
			this.set("paginationItems", pagerItems);

			return this;
		},
		checkTotalRow: function () {
			var i;

			loop_total:
			for (i=0; i<this.attributes.columns.length; i+=1) {
				if(this.attributes.columns[i].operation) {
					if(this.attributes.functionOptions[this.attributes.columns[i].operation.toLowerCase()]) {
						this.attributes.functions = true;
						break loop_total;
					}

				}
			}
			return this;
		},
		applyFunction: function () {
			var i;

			for (i=0; i<this.attributes.columns.length; i+=1) {
				if(this.attributes.columns[i].operation) {
					if(this.attributes.functionOptions[this.attributes.columns[i].operation.toLowerCase()]) {
						this.attributes.totalrow[i] = this[this.attributes.functionOptions[this.attributes.columns[i].operation.toLowerCase()]](i);
					}
				}
			}

			return this;
		},
		sumValues: function (colIndex) {
			var i,
			sum = 0,
			grid = this.attributes.gridFunctions;

			for (i=0; i<grid.length; i+=1) {
				sum += grid[i][colIndex];
			}

			return sum;
		},
		avgValues: function (colIndex) {
			var i,
			sum = 0,
			grid = this.attributes.gridFunctions;

			for (i=0; i<grid.length; i+=1) {
				sum += grid[i][colIndex];
			}
			
			return Math.round ((sum/grid.length) * 100 ) /100 ;
		},
		getData: function () {
			var i, j, cell, row, dataGrid = [], dataRow = [];
			if (this.get("view")) {
				for (i = 0 ; i < this.attributes.view.gridtable.length ; i+=1){
					dataRow = [];
					row = this.attributes.view.gridtable[i];
					for ( j = 0 ; j < row.length ; j +=1){
						cell = row[j];
						dataRow.push(cell.getValue());
					}
					dataGrid.push(dataRow);
				}
			}
			return {
				name : this.get("name"),
				value : dataGrid
			}
		},
		fixCoutFieldsHidden : function(){
        	var i, countHiddenControl = 0;
        	for ( i = 0 ; i < this.get("columns").length ; i +=1) {
        		if (this.get("columns")[i].type === "hidden"){
        			countHiddenControl +=1;
        		}
        	}
        	this.set("countHiddenControl",countHiddenControl);
        	return this;
        }	
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.GridPanel", GridModel);
}());
(function(){
	var ButtonModel = PMDynaform.model.Field.extend({
		defaults: {
			colSpan: 12,
			disabled: false,
            namespace: "pmdynaform",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("button"),
			label: "untitled label",
			type: "button"
		}
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.Button", ButtonModel);
}());
(function () {
    var DropdownModel = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "string",
            defaultValue: "",
            dependenciesField: [],
            disabled: false,
            executeInit: true,
            group: "form",
            hint: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("dropdown"),
            label: "untitled label",
            localOptions: [],
            mode: "edit",
            options: [
                {
                    label: "Empty",
                    value: "empty"
                }
            ],
            remoteOptions: [],
            required: false,
            type: "text",
            valid: true,
            validator: null,
            variable: null,
            var_uid: null,
            var_name: null,
            variableInfo: {},
            value: "",
            columnName: null,
            originalType: null,
            data: null,
            itemClicked: false,
            keyLabel: "",
            optionsSql: [],
            enableValidate: true,
            placeholder: "",
            text: "",
            /**
             * this parameter verify if exist placeholder in the component
             */
            therePlaceholder : false,
            /**
             * this property fix the custom placeholder options
             */
            placeholderOption : null
        },
        initValidators: function () {
            this.set("validator", new PMDynaform.model.Validator({
                domain: true
            }));
            return this;
        },
        initialize: function (options) {
            var data;
            this.initValidators();
            this.set("dependenciesField", []);
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
            //verify the exist a placeholder an set the therePlaceholder parameter
            this.verifyExistPlaceholder();
            this.setDefaultValue();
            data = this.get("data");
            if (data && data["value"] !== "") {
                this.attributes.value = data["value"];
                this.attributes.keyLabel = data["label"];
            } else {
                this.setDataOfOptions();
            }
            if (this.get("variable") && this.get("variable").trim().length === 0) {
                if (this.get("group") === "form") {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.set("text", data["label"]);
            //create a placeholder option if exist
            if(this.get("therePlaceholder")){
                this.set("placeholderOption",this.createPlaceHolderOption());
            }
            this.defineModelEvents();
        },
        /**
         * Verify if exist placeholder, when exist set paremeter therePlacehodler = true
         * @returns {DropdownModel}
         */
        verifyExistPlaceholder: function () {
            var placeholder = this.get("placeholder"),
                therePlaceholder = false;
            if (typeof placeholder === "string") {
                if (placeholder.trim().length === 0) {
                    therePlaceholder = false;
                }else{
                    therePlaceholder = true;
                }
            } else {
                therePlaceholder = false;
            }
            this.set("therePlaceholder",therePlaceholder);
            return this;
        },
        /**
         * setDataOfOptions(): this method set data with the first option if no
         * exist placeholder option
         * @returns {DropdownModel}
         */
        setDataOfOptions : function (){
            var options = this.get("options");
            if (_.isArray(options)){
                if (options.length && !this.get("therePlaceholder")) {
                    this.set("value", this.get("options")[0]["value"]);
                    this.set("data", {
                        value: this.get("options")[0]["value"],
                        label: this.get("options")[0]["label"]
                    });
                } else {
                    this.set("data", {value: "", label: ""});
                    this.set("value", "");
                }
            }
            return this;
        },
        getData: function () {
            if (this.get("group") == "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }

            } else {
                return {
                    name: this.get("name") ? this.get("name") : "",
                    value: this.get("value")
                }
            }
        },
        getKeyLabel: function () {
            if (this.get("group") == "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }
            } else {
                return {
                    name: this.get("name") ? this.get("name").concat("_label") : "",
                    value: this.attributes.data["label"]
                }
            }
        },
        setDefaultValue: function () {
            var options = this.get("options"),
                defaultValue = this.get("defaultValue");

            if ($.inArray(defaultValue, ["", null, undefined]) > 0) {
                this.set("defaultValue", options[0].value);
                this.set("value", options[0].value);
            }

            return this;
        },
        setDependencies: function (newDependencie) {
            var arrayDep, i, result, newArray = [];
            arrayDep = this.get("dependenciesField");
            if (arrayDep.indexOf(newDependencie) === -1) {
                arrayDep.push(newDependencie);
            }
            this.set("dependenciesField", arrayDep);
        },
        isValid: function () {
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        validate: function (attrs) {
            var valueFixed = attrs.value.trim();
            this.attributes.value = valueFixed;
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("value", valueFixed);

            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        reviewRemotesOptions: function () {
            var sql;
            if ((this.get("variable") && this.get("variable").trim().length) || this.get("group") == "grid") {
                sql = this.get("sql");
                if (sql) {
                    this.reviewRemoteVariable();
                }
            }
            return this;
        },
        setLocalOptions: function () {
            var item = {};
            if (this.get("options")) {
                this.set("localOptions", this.get("options"));
            }
            return this;
        },
        onDependentHandler: function () {
            var execute = true, localOpt, remoteOptions, auxData, key;
            auxData = this.jsonData;
            this.jsonData = this.generateDataDependenField();
            remoteOptions = this.executeQuery();
            this.mergeOptions(remoteOptions);
            if (this.get("options").length) {
                this.set("data", {
                    value: this.get("options")[0].value,
                    label: this.get("options")[0].label

                });
            }
            return this;
        },
        executeQuery: function (clicked) {
            var restClient, key, resp, prj, endpoint, url, data;
            data = this.preparePostData();
            prj = this.get("project");
            resp = prj.webServiceManager.executeQuery(data, this.get("variable") || "");
            return resp;
        },
        preparePostData: function () {
            var data;
            data = this.jsonData || {};
            if (this.get("group") === "grid") {
                data["field_id"] = this.get("columnName");
            } else {
                data["field_id"] = this.get("id");
            }
            if (this.get("form")) {
                if (this.get("form").model.get("form")) {
                    data["dyn_uid"] = this.get("form").model.get("form").model.get("id");
                } else {
                    data["dyn_uid"] = this.get("form").model.get("id");
                }
            }
            return data;
        },
        generateDataDependenField: function () {
            var i, parentDependents, data = {}, name;
            parentDependents = this.get("parentDependents");
            for (i = 0; i < parentDependents.length; i += 1) {
                if (parentDependents[i].get("group") === "grid") {
                    name = parentDependents[i].get("columnName");
                } else {
                    if (parentDependents[i].get("variable") && parentDependents[i].get("variable") !== "") {
                        name = parentDependents[i].get("variable");
                    } else {
                        name = parentDependents[i].get("id");
                    }
                }
                data[name] = parentDependents[i].get("data")["value"];
            }
            return data;
        },
        mergeOptions: function (remoteOptions) {
            var k, remoteOpt = [], localOpt = [], options = [];
            for (k = 0; k < remoteOptions.length; k += 1) {
                remoteOpt.push({
                    value: remoteOptions[k].value,
                    label: remoteOptions[k].text
                });
            }
            localOpt = this.get("localOptions");
            this.attributes.optionsSql = remoteOpt;
            options = localOpt.concat(remoteOpt);
            this.attributes.options = options;
            return this;
        },
        /**
         * createPlaceHolderOption(), when the property 'therePlaceholder' is true
         * then the placeholder option is created
         * @returns {{}}
         */
        createPlaceHolderOption : function(){
            var option = {};
                option["label"] = this.get("placeholder");
                option["value"] = undefined;
            return option;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Dropdown", DropdownModel);

}());
(function(){
	var RadioboxModel =  PMDynaform.model.Field.extend({
		defaults: {
			colSpan: 12,
			colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("radio"),
			dataType: "string",
            dependenciesField: [],
            disabled: false,
            defaultValue: "",
            label: "",
            localOptions: [],
            group: "form",
            hint: "",
            options: [
            	{
                    label : "empty",
                    value: "empty"
                }
            ],
            mode: "edit",
            type: "radio",
            readonly: false,
            remoteOptions: [],
            required: false,
            validator: null,
            valid: true,
            variable: null,
            var_uid: null,
            var_name: null,
            variableInfo: {},
            value: "",
            columnName : null,
            originalType : null,
            data : null,
            itemClicked : false,
            keyLabel  : "",
            optionsSql : [],
			enableValidate : true
		},
		initialize: function(attrs) {
			var data;
			this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.on("change:options", this.onChangeOptions, this);
			this.set("validator", new PMDynaform.model.Validator({
				domain: true
			}));
			this.set("dependenciesField",[]);
			this.verifyControl();
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
			this.initControl();
            data = attrs["data"] || this.get("data");
            if ( data && data["value"].toString() ) {
                this.attributes.value = data["value"];
                this.attributes.keyLabel = data["label"];
				this.set("data",data);
            } else {
                    this.set("data",{value:"", label:""});
                    this.set("value","");
            }
            if (this.get("group") === "form"){
				if(this.get("variable").trim().length === 0){
					this.attributes.name = this.get("id");
				}
            }else{
                this.attributes.name = this.get("id");
            }
			//this.reviewRemoteVariable();
            
		},
		initControl: function() {
			var opts = this.get("options"), 
			i,
			newOpts = [],
			itemsSelected = [];

			if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
			for (i=0; i<opts.length; i+=1) {
				if(opts[i].selected) {
					itemsSelected.push(opts[i].value.toString());
				}
				newOpts.push({
                    label: opts[i].label,
                    value: opts[i].value,
                    selected: opts[i]? opts[i]: false
                });
			}
                
            this.set("options", newOpts);
			this.set("selected", itemsSelected);
		},
		setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
		isValid: function() {
            this.set("valid", this.get("validator").get("valid"));
        	return this.get("valid");
        },
        verifyControl: function() {
			var opts = this.get("options"), i;
			for (i=0; i<opts.length; i+=1) {
				opts[i].value = opts[i].value.toString();
			}
			this.set("value", this.get("value").toString());
		},
		validate: function(attrs) {
			
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("value", attrs.value.length);
            this.get("validator").set("valueDomain", attrs.value);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
		},
		setItemClicked: function(itemUpdated) {
			var opts = this.get("options"),
				selected = this.get("selected"),
				position,
				newSelected,
				i;
            this.itemClicked = true;
			if (opts) {
				for(i=0; i< opts.length; i+=1) {
					if(opts[i].value.toString() === itemUpdated.value.toString()) {
						this.set("value", itemUpdated.value.toString());
					}
				}
			}
			return this;
		},
		getData: function() {

            //console.log("getData text")
            /*return {
                name: this.get("variable") ? this.get("variable").var_name : this.get("name"),
                value: this.get("value")
            };*/
            if (this.get("group") == "grid"){
                return {
                    name : this.get("columnName") ? this.get("columnName"): "",
                    value :  this.get("value")
                }

            } else {
                return {
                    name : this.get("name") ? this.get("name") : "",
                    value :  this.get("value")
                }
            }
		},
        onChangeValue: function (attrs, options) {
            var i, opts, data = {};
            this.attributes.value = attrs.attributes.value;
            if (this.attributes.options) {
                this.get("validator").set({
                    valueDomain: this.get("value"),
                    options: this.get("options") || []
                });
                this.get("validator").verifyValue();
            }
            if (!this.itemClicked){
                opts = this.get("options");
                for ( i = 0 ; i < opts.length ; i+=1 ) {
                    if (opts[i]["value"] === this.get("value")){
                        data["value"] = opts[i]["value"];
                        data["label"] = opts[i]["label"];
                        break;
                    }
                }
                this.attributes.data = data;
            }
            this.itemClicked = false;
            return this;
        }        
	});
	PMDynaform.extendNamespace("PMDynaform.model.Radio",RadioboxModel);
}());
(function(){
	var SubmitModel =  PMDynaform.model.Field.extend({
		defaults: {
			type: "submit",
			namespace: "pmdynaform",
			placeholder: "untitled",
			label: "untitled label",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("submit"),
			disabled: false,
			colSpan: 12
		}
	});
	PMDynaform.extendNamespace("PMDynaform.model.Submit", SubmitModel);
}()); 
(function(){
    var TextAreaModel =  PMDynaform.model.Field.extend({
        defaults: {
            type: "text",
            placeholder: "untitled",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("textarea"),
            colSpan: 12,
            value: "",
            defaultValue: "",
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            maxLengthLabel: 15,
            rows: 2,
            group: "form",
            dataType: "string",
            hint: "",
            disabled: false,
            maxLength: null,
            mode: "edit",
            required: false,
            validator: null,
            valid: true,
            columnName : null,
            originalType : null,
            options : [],
            data : null,
            localOptions: [],
            remoteOptions: [],
            keyLabel : "",
            optionsSql : [],
            enableValidate : true,
            text : ""
        },
        getData: function() {
            if (this.get("group") == "grid"){
                return {
                    name : this.get("columnName") ? this.get("columnName"): "",
                    value :  this.get("value")
                }
            } else {
                return {
                    name : this.get("name") ? this.get("name") : "",
                    value :  this.get("value")
                }
            }
        },
        defineModelEvents : function (){
            this.on("change:text", this.onChange, this);
            this.on("change:value", this.onChange, this);
            return this;
        },
        initialize: function(attrs) {
            var data, maxLength;
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.set("validator", new PMDynaform.model.Validator({
                "type"  : this.get("type"),
                "required" : this.get("required"),
                "maxLength" : this.get("maxLength"),
                "dataType" : this.get("dataType") || "string",
                "regExp" : {
                    validate : this.get("validate"),
                    message : this.get("validateMessage")
                }
            }));
            this.set("dependenciesField",[]);
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
            data = this.get("data");
            if ( data && data["value"] !== "") {
                data = {
                    value : data["value"],
                    label : data["value"]
                };
                this.set("data",data);
                this.set("value", data["value"]);
                this.set("defaultValue",data["value"]);
            } else {
                this.set("data",{value:"", label:""});
                this.set("value","");
            }
            this.initControl();

            if ( this.get("variable").trim().length === 0) {
                if ( this.get("group") === "form" ) {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.set("text",this.get("data")["label"]);
            this.defineModelEvents();
        },
        initControl: function() {
            if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
        },
        isValid: function(){
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        validate: function (attrs) {
            var valueFixed = this.get("value");
            this.set("value", valueFixed);
            this.get("validator").set("value", valueFixed);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        onChange: function (attrs, item) {
            var data;
            data = {
                value : item || "",
                label : item || ""
            };
            this.set("data",data);
            this.set({text:item,value:item});
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.TextArea", TextAreaModel);
}());
(function () {

    var TextModel = PMDynaform.model.Field.extend({
        defaults: {
            type: "text",
            placeholder: "",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("text"),
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            maxLengthLabel: 15,
            namespace: "pmdynaform",
            operation: null,
            tooltipLabel: "",
            value: "",
            group: "form",
            defaultValue: "",
            dataType: "string",
            hint: "",
            mask: "",
            disabled: false,
            maxLength: null,
            mode: "edit",
            autoComplete: "off",
            required: false,
            formulator: null,
            validator: null,
            textTransform: "",
            valid: true,
            variable: null,
            var_uid: null,
            var_name: null,
            columnName: null,
            originalType: null,
            data: null,
            localOptions: [],
            options: [],
            keyValue: null,
            keyLabel: "",
            formulaAssociatedObject: [],
            optionsSql: [],
            remoteOptions: [],
            enableValidate: true,
            text: ""
        },
        defineModelEvents: function () {
            this.on("change:text", this.onChange, this);
            this.on("change:value", this.onChange, this);
            this.on("change:label", this.onChangeLabel, this);
            return this;
        },
        initialize: function (attrs) {
            var data, maxLength;
            this.set("dataType", this.get("dataType").trim().length ? this.get("dataType") : "string");
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.set("validator", new PMDynaform.model.Validator({
                "type": this.get("type"),
                "required": this.get("required"),
                "maxLength": this.get("maxLength"),
                "dataType": this.get("dataType") || "string",
                "regExp": {
                    validate: this.get("validate"),
                    message: this.get("validateMessage")
                }
            }));
            this.set("dependenciesField", []);
            if (this.get("formula").trim().length) {
                this.attributes.formula = this.get("formula").replace(/\s/g, '');
            }
            if (this.attributes._extended && this.attributes._extended.formula) {
                this.attributes._extended.formula = this.attributes._extended.formula.replace(/\s/g, '');
            }
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();

            data = this.get("data");
            if (data && data["value"] !== "") {
                this.set("keyValue", data["value"]);
                if (data["label"] !== "") {
                    data = {
                        value: data["value"],
                        label: data["label"]
                    };
                } else {
                    data = {
                        value: data["value"],
                        label: data["value"]
                    };
                }
                this.set("data", data)
                this.set("value", data["value"]);
                this.set("defaultValue", data["value"]);
                this.set("keyLabel", data["value"]);
            } else {
                this.set("data", {value: "", label: ""});
                this.set("value", "");
                this.set("keyLabel", "");
            }
            this.initControl();
            if (this.get("variable").trim().length === 0) {
                if (this.get("group") === "form") {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.set("text", this.get("data")["label"]);
            this.defineModelEvents();
        },
        initControl: function () {
            if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
            if (typeof this.get("formula") === "string" &&
                this.get('formula') !== "undefined" &&
                this.get('formula') !== "null" &&
                this.get('formula').length > 1) {
                this.set("formulator", new PMDynaform.core.Formula(this.get("formula")));
                this.set("disabled", true);
            }
        },
        addFormulaTokenAssociated: function (formulator) {
            if (formulator instanceof PMDynaform.core.Formula) {
                //formulator.addField("field", this.get("name"));
                formulator.addTokenValue(this.get("id"), this.get("value"));
            }
            return this;
        },
        setDependencies: function (newDependencie) {
            var arrayDep, i, result, newArray = [];
            arrayDep = this.get("dependenciesField");
            if (arrayDep.indexOf(newDependencie) === -1) {
                arrayDep.push(newDependencie);
            }
            this.set("dependenciesField", arrayDep);
        },
        addFormulaFieldName: function (otherField) {
            this.get("formulator").addField("field", otherField);
            return this;
        },
        updateFormulaValueAssociated: function (field) {
            var resultField = field.model.get("formulator").evaluate();

            field.model.set("value", resultField);
            return this;
        },
        isValid: function () {
            this.attributes.valid = this.get("validator").get("valid");
            return this.get("valid");
        },
        validate: function (attrs) {
            var valueFixed = this.get("value");
            this.set("value", valueFixed);
            this.get("validator").set("value", valueFixed);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        getData: function () {
            if (this.get("group") == "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }
            } else {
                return {
                    name: this.get("name") ? this.get("name") : "",
                    value: this.get("value")
                }
            }
        },
        getData2: function () {
            var data = {}, name, value;
            name = this.get("variable") ? this.get("variable").var_name : this.get("name");
            value = this.get("value");
            data[name] = value;
            return data;
        },
        reviewRemotesOptions: function () {
            var sql;
            if (this.get("variable") && this.get("variable").trim().length) {
                sql = this.get("sql");
                if (sql) {
                    this.reviewRemoteVariable();
                }
            }
            return this;
        },
        onChange: function (attrs, item) {
            var data;
            item = (item === null || item === undefined) ?  '' : item;
            data = {
                value: item,
                label: item
            };
            this.set("data", data);
            this.set({text: item, value: item});
            return this;
        },
        mergeOptions: function (remoteOptions) {
            var item;
            if (remoteOptions.length) {
                item = remoteOptions[0];
                if (item.hasOwnProperty("value")) {
                    this.set("data", {
                        value: item["value"],
                        label: item["value"]
                    });
                    this.set("value", item["value"]);
                } else {
                    this.set("value", "");
                    this.attributes.data = {value: "", label: ""};
                }
            } else {
                this.set("data", {value: "", label: ""});
                this.set("value", "");
            }
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Text", TextModel);
}());
(function () {
    var File = PMDynaform.model.Field.extend({
        defaults: {
            autoUpload: false,
            camera: true,
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            defaultValue: "",
            disabled: false,
            dnd: false,
            dndMessage: "Drag or choose local files",
            extensions: "pdf, png, jpg, mp3, doc, txt",
            group: "form",
            height: "200px",
            hint: "",
            id: PMDynaform.core.Utils.generateID(),
            items: [],
            label: "Untitled label",
            labelButton: "Choose Files",
            mode: "edit",
            multiple: false,
            name: PMDynaform.core.Utils.generateName("file"),
            variable: null,
            inputDocuments: null,
            preview: false,
            required: false,
            size: 1, //1 MB
            type: "file",
            proxy: [],
            valid: true,
            validator: null,
            value: "",
            columnName: null,
            originalType: null,
            data: null,
            enableValidate: true,
            sizeUnity: ""
        },
        initialize: function (properties) {
            var data, size;
            size = parseInt(properties.size) || 0;
            if (size <= 0) {
                this.set("size", 99999);
                this.set("sizeUnity", "MB");
            }
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.on("change:label", this.onChangeLabel, this);
            this.initControl();
            this.set("items", []);
            this.set("proxy", []);

            this.set("validator", new PMDynaform.model.Validator({
                "type": "file",
                "required": this.get("required")
            }));

            data = this.get("data");
            if (data && (typeof data === "object") && (this.get("group") !== "grid")) {
                if (!jQuery.isArray(data["value"])) {
                    if (data["value"].trim().length) {
                        if (data["value"][0] === "[" && data["value"][data["value"].length - 1] === "]") {
                            data["value"] = JSON.parse(data["value"]);
                        } else {
                            data["value"] = [];
                        }
                    } else {
                        data["value"] = [];
                    }
                }
                if (!jQuery.isArray(data["label"])) {
                    if (data["label"].trim().length) {
                        if (data["label"][0] === "[" && data["label"][data["label"].length - 1] === "]") {
                            data["label"] = JSON.parse(data["label"]);
                        } else {
                            data["label"] = [];
                        }
                    } else {
                        data["label"] = [];
                    }
                } else {
                    data["label"] = [];
                }
                this.set("data", data);
            } else {
                this.set("data", {
                    value: [],
                    label: []
                })
            }
            // fill data;
            this.attributes.value = this.get("data")["value"];
            this.set("appDocUID",this.get("data")["app_doc_uid"]);
            return this;
        },
        initControl: function () {
            if (this.get("dnd")) {
                //this.set("preview", true);
            }
            return this;
        },
        isValid: function () {
            this.get("validator").set("value", this.get("value").toString());
            this.get("validator").verifyValue();
            if (this.get("validator").get("valid")) {
                return true;
            } else {
                return false;
            }
        },
        uploadSuccess: function (a, b, c) {
            //console.log("SUCCESS",a, b, c);

            return this;
        },
        uploadFailure: function (a, b, c) {
            //console.log("FAILURE",a, b, c);

            return this;
        },

        uploadFile: function (indexItem) {
            var file = this.get("items")[indexItem].file,
                rand = Math.floor((Math.random() * 100000) + 3),
                that = this,
                proxy = this.get("proxy"),
                proxyItem,
                formdata = new FormData();

            if (formdata) {
                formdata.append("images[]", file);

                proxyItem = $.ajax({
                    url: "server.php",
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    onprogress: function (progress) {
                        // calculate upload progress
                        var percentage = Math.floor((progress.total / progress.totalSize) * 100);
                        // log upload progress to console
                        //console.log('progress', percentage);
                        if (percentage === 100) {
                            //console.log('DONE!');
                        }
                    },
                    success: that.uploadSuccess,
                    failure: that.uploadFailure
                });
                proxy.push(proxyItem);
                this.set("proxy", proxy);
            }

            return this;
        },
        stopUploadFile: function (index) {
            var proxy = this.get("proxy");

            proxy[index].abort();
            return this;
        },
        /**
         * Gets appDocUID to file field
         *
         */
        getAppDocUID: function() {
           return this.get("appDocUID") || null;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.model.File", File);
}());
(function(){
	var CheckGroupModel =  PMDynaform.model.Field.extend({
		defaults: {
			colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "string",
            dependenciesField: [],
            disabled: false,
            group: "form",
            hint: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("checkgroup"),
			label: "",
            localOptions: [],
            maxLengthLabel: 15,
            mode: "edit",
            options: [],
            readonly: false,
            required: false,
            optionsSql: [],
            selected: [],
            type: "checkgroup",
            validator: null,
            valid: true,
            value: [],
            columnName : null,
            originalType : null,
            data : {},
            defaultValue : [],
			labelsSelected : [],
            variable : "",
			enableValidate : true
		},
		initialize: function (attrs) {
            var i,j,d,data, that = this, option;
            this.on("change:options", this.onChangeOptions, this);
            this.on("change:value", this.updateItemSelected, this);
			this.set("validator", new PMDynaform.model.Validator({
                type: attrs.type,
                required: attrs.required
            }));

            if (attrs.hasOwnProperty("optionsSql") || attrs.hasOwnProperty("options")){
                this.set("localOptions",attrs.options);
                this.mergeOptions();
            }
            if (attrs.hasOwnProperty("data") || attrs.hasOwnProperty("value") || attrs.hasOwnProperty("defaultValue")) {
                this.set("data",this.initData(attrs.defaultValue, attrs.value, attrs.data, attrs.variable));
                this.attributes.value = this.get("data")["value"];
            }
            this.initControl();
            this.get("validator").set("value",this.get("value"));
            this.set("dependenciesField",[]);
			if ( this.get("variable") === "") {
                this.attributes.name = "";
			}
            return this;
		},
        initData : function (defaultV, value, data) {
            var auxData = {}, existData = false;
            if ( data ) {
                if ( typeof data === "object" ) {
                    if ( data.hasOwnProperty("value") && $.isArray(data["value"]) ){
                        if ( data.hasOwnProperty("label") && data["label"].toString().trim() === "" ) {
                            auxData["value"] = data["value"];
                            auxData["label"] = [];
                            for ( var i= 0; i < this.get("options").length ; i+=1 ) {
                                if ( data["value"].indexOf(this.get("options")[i]["value"]) > -1 ) {
                                    auxData["label"].push(this.get("options")[i]["label"]);
                                }
                            }
                            this.attributes.labelsSelected = auxData["label"];
                        } else {
                            if ($.isArray(data["label"])){
                                this.attributes.labelsSelected = data["label"];
                                data["label"] = JSON.stringify(this.attributes.labelsSelected);
                                auxData = data;
                            }else{
                                if (data["label"].indexOf("[") === 0 && data["label"].lastIndexOf("]") === data["label"].length-1){
                                    this.attributes.labelsSelected = JSON.parse(data["label"]);
                                    data["label"] = JSON.stringify(this.attributes.labelsSelected);
                                    auxData = data;
                                }
                            }
                        }
                        existData = true;
                    } else {
						if (typeof data["value"] === "string" && data["value"].length){
							data["value"] = data["value"].split(/,/g);
							if (data["label"].indexOf("[") === 0 && data["label"].lastIndexOf("]") === data["label"].length-1){
                                this.attributes.labelsSelected = JSON.parse(data["label"]);
                                data["label"] = JSON.stringify(this.attributes.labelsSelected);
                            }
							auxData = data;
						}
                        if (!data.hasOwnProperty("value") || data["value"] === ""){
                            auxData["value"] = [];
                            auxData["label"] = [];
                            this.attributes.labelsSelected = [];
                        }
                        existData = true;
                    }
                } else {
                    auxData["value"] = [];
                    auxData["label"] = [];
                    this.attributes.labelsSelected = [];
                }
            }else{
                auxData["value"] = [];
                auxData["label"] = [];
                this.attributes.labelsSelected = [];
            }
            if (defaultV && !existData ){
                var defaultV = defaultV.split("|");
                for ( var i=0; i< this.get("options").length ; i+=1 ) {
                    if ( defaultV.indexOf(this.get("options")[i]["value"]) > -1) {
                        auxData["value"].push(this.get("options")[i]["value"]);
                        auxData["label"].push(this.get("options")[i]["label"]);
                    }
                }
            }
            if ($.isArray(auxData["label"])){
                this.attributes.labelsSelected = auxData["label"];
                auxData["label"] = JSON.stringify(auxData["label"]);
            }
            return auxData;
        },
		initControl: function() {
			var opts = this.get("options"), 
            i,
            newOpts = [],
			itemsSelected = [];

			for (i=0; i<opts.length; i+=1) {
                if (this.get("data") && this.get("data").value){
                    if (this.get("data").value.indexOf(opts[i].value) > -1 ) {
                        opts[i].selected = true;
						itemsSelected.push(opts[i]);
                    }
                }
                newOpts.push({
                    label: opts[i].label,
                    value: opts[i].value,
                    selected: opts[i].selected? true : false
                });
			}
            this.set("options", newOpts);
			this.set("selected", itemsSelected);
		},
        mergeOptions: function () {
            var options = [];
            if (this.get("options") && this.get("optionsSql")) {
                options = this.get("localOptions").concat(this.get("optionsSql"));
            }
            this.set("options", options);
            return this;
        },
		setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
		getData: function() {
            return {
                name : this.get("name") ? this.get("name") : "",
                value : this.get("value")
            };
		},
        getKeyLabel : function (){
            return {
                name : this.get("name") ? this.get("name").concat("_label") : "",
                value :  JSON.stringify(this.get("labelsSelected"))
            }            
        },
		validate: function(attrs) {
            this.get("validator").set("value", attrs.value.length);
            if(this.get("options").length){
                this.get("validator").set("options",this.attributes.options); 
            }
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
		},
		isValid: function(){
            this.attributes.valid = this.get("validator").get("valid");
        	return this.get("valid");
        },
		setItemChecked: function(itemUpdated) {
			var opts = this.get("options"),
				selected = [],
				i;
			this.attributes.labelsSelected = [];
			if (opts) {
                for(i=0; i<opts.length; i+=1) {
                	if(opts[i].value.toString() === itemUpdated.value.toString()) {
                		opts[i].selected = itemUpdated.checked;
                	}
                }
                this.set("options", opts);
                for ( i = 0; i < opts.length; i+=1 ) {
                    if ( i === opts.length-1 && selected.length) {
                        opts[i].selected = false;
                    }
                    if ( opts[i].selected) {
                        selected.push(opts[i].value);
						this.attributes.labelsSelected.push(opts[i].label);
                    }
                }
                if (selected.length) {
                    this.attributes.value = selected;
                }else{
                    this.attributes.value = [];
                }
                this.set("selected", selected);
			}
            return this;
		},
        setItemsChecked: function(items) {
            for (var index = 0; index < items.length; index ++){
                this.setItemChecked({
                    value: items[index],
                    checked: true
                });
            }
            return this;
        },
        updateItemSelected: function () {
            var i, data = {},
            selected = this.get("selected"), auxValue, opts = this.get("options");
            if ($.isArray(this.get("value"))) {
                this.set("selected",[]);
                selected = this.get("selected");
                for ( i = 0 ; i < opts.length ; i+=1 ){
                    opts[i].selected = false;
                }
                this.set("options",opts);
                auxValue = this.get("value");
                for ( i = 0 ; i < auxValue.length ; i+=1 ){
                    this.setItemChecked({
                        value: auxValue[i],
                        checked: true
                    });
                }
            } else {
                this.setItemChecked({
                    value: this.attributes.value,
                    checked: true
                });
            }
            for (i=0; i<selected.length; i+=1) {
                this.setItemChecked({
                    value: selected[i].trim ? selected[i].trim() : selected[i],
                    checked: true
                });
            }
            if (!this.attributes.disabled) {
                this.get("validator").set({
                    valueDomain: this.get("value"),
                    options: this.get("options")
                });
                this.get("validator").set("value", this.get("selected").length);
                this.get("validator").verifyValue();
            }
            if (this.attributes.data) {
                this.attributes.data["value"] = this.get("value");
            }
            return this;
        }
	});
	PMDynaform.extendNamespace("PMDynaform.model.CheckGroup",CheckGroupModel);
}());
(function () {
    var CheckBoxModel = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "boolean",
            disabled: false,
            group: "form",
            hint: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("checkgroup"),
            label: "",
            localOptions: [],
            mode: "edit",
            options: [],
            required: false,
            type: "checkbox",
            validator: null,
            valid: true,
            value: null,
            columnName: null,
            originalType: null,
            data: {},
            defaultValue: null,
            labelsSelected: null,
            optionsToTrue: [true, 1, "true", "1"],
            optionsToFalse: [false, 0, "false", "0"],
            variable: "",
            enableValidate: true
        },
        initialize: function (attrs) {
            this.on("change:value", this.updateItemSelected, this);
            this.set("validator", new PMDynaform.model.Validator({
                type: attrs.type,
                required: attrs.required
            }));
            if (attrs.options && !attrs.options.length) {
                this.attributes.options = [
                    {
                        "value": "1",
                        "label": "true"
                    },
                    {
                        "value": "0",
                        "label": "false"
                    }
                ];
                this.attributes.dataType = "boolean";
            }
            if (attrs.hasOwnProperty("data") || attrs.hasOwnProperty("value") || attrs.hasOwnProperty("defaultValue")) {
                this.set("data", this.initData(attrs.defaultValue, attrs.value, attrs.data, attrs.variable));
                this.attributes.value = this.get("data")["value"];
            } else {
                this.attributes.data["value"] = "";
                this.attributes.data["label"] = "";
                this.attributes.value = this.get("data")["value"];
            }


            this.initControl();
            this.attributes.value = this.get("data").value;
            this.get("validator").set("value", this.get("value"));
            this.set("dependenciesField", []);
            this.setLocalOptions();
            if (this.get("variable").trim().length === 0) {
                if (this.get("group") === "form") {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.defineModelEvents();
            return this;
        },
        /**
         * This initialize data
         * @param defData: json with valid options value and label
         * @returns {CheckBoxModel}
         */
        initData: function (defaultV, value, data) {
            var options = this.get("options"), index, i;
            var auxData = {}, existData = false;
            if (typeof data === "object" && data !== null) {
                if (data.hasOwnProperty("value") && data["value"].toString() !== "") {
                    data["value"] = data["value"].toString();
                    index = this.get("optionsToFalse").indexOf(data["value"]);
                    if (index > -1) {
                        auxData["value"] = "0";
                    }
                    index = this.get("optionsToTrue").indexOf(data["value"]);
                    if (index > -1) {
                        auxData["value"] = "1";
                    }
                    for (i = 0; i < options.length; i += 1) {
                        if (options[i].value == auxData["value"]) {
                            auxData["label"] = options[i].label;
                        }
                    }
                } else {
                    if (!data.hasOwnProperty("value") || data["value"] === "" || _.isArray(data["value"])) {
                        if (defaultV) {
                            if (this.get("optionsToFalse").indexOf(defaultV) > -1) {
                                auxData["label"] = options[1].label;
                                auxData["value"] = options[1].value
                            }
                            if (this.get("optionsToTrue").indexOf(defaultV) > -1) {
                                auxData["label"] = options[0].label;
                                auxData["value"] = options[0].value
                            }
                        } else {
                            for (i = 0; i < options.length; i += 1) {
                                if (this.get("optionsToFalse").indexOf(options[i].value) > -1) {
                                    auxData["value"] = options[i].value;
                                    auxData["label"] = options[i].label;
                                }
                            }
                        }
                    }
                }
                existData = true;
            } else {
                for (i = 0; i < options.length; i += 1) {
                    if (this.get("optionsToFalse").indexOf(options[i].value) > -1) {
                        auxData["value"] = options[i].value;
                        auxData["label"] = options[i].label;
                    }
                }
            }
            //defaultV 0, 1,"0", "1", true, false, "true", "false" 
            if (defaultV && !existData) {
                if (this.get("optionsToFalse").indexOf(defaultV) > -1) {
                    auxData["label"] = options[1].label;
                    auxData["value"] = options[1].value
                }
                if (this.get("optionsToTrue").indexOf(defaultV) > -1) {
                    auxData["label"] = options[0].label;
                    auxData["value"] = options[0].value
                }
            }
            return auxData;
        },
        initControl: function () {
            var opts = this.get("options"),
                i,
                newOpts = [],
                itemsSelected = [];
            if (_.isArray(opts)) {
                for (i = 0; i < opts.length; i += 1) {
                    if (!opts[i].value && (typeof opts[i].value !== "number")) {
                        opts[i].value = opts[i].label;
                    }
                    if (this.get("data") && this.get("data").value) {
                        if (this.get("data").value.indexOf(opts[i].value) > -1) {
                            opts[i].selected = true;
                        } else {
                            opts[i].selected = false;
                        }
                    }
                    newOpts.push({
                        label: opts[i].label,
                        value: opts[i].value,
                        selected: opts[i].selected ? true : false
                    });
                }
            }
            this.set("options", newOpts);
            this.set("selected", itemsSelected);
        },
        setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
        getData: function () {
            if (this.get("group") === "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: [this.get("value")]
                }

            } else {
                return {
                    name: this.get("name") ? this.get("name") : "",
                    value: [this.get("value")]
                }
            }
            return this;
        },
        getKeyLabel: function () {
            if (this.get("group") === "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }
            } else {
                return {
                    name: this.get("name") ? this.get("name").concat("_label") : "",
                    value: this.get("data")["label"]
                }
            }
        },
        validate: function (attrs) {
            var value;
            value = parseInt(attrs.value);
            this.get("validator").set("value", value);
            if (this.get("options").length) {
                this.get("validator").set("options", this.attributes.options);
            }
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        isValid: function () {
            this.attributes.valid = this.get("validator").get("valid");
            return this.get("valid");
        },
        updateItemSelected: function () {
            if (!this.attributes.disabled) {
                this.get("validator").set({
                    valueDomain: this.get("value"),
                    options: this.get("options")

                });
                this.get("validator").set("value", this.get("selected").length);
                this.get("validator").verifyValue();
            }
            if (this.attributes.data) {
                this.attributes.data["value"] = this.get("value");
            }
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.CheckBox", CheckBoxModel);
}());
(function () {
    var DatetimeModel = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "date",
            group: "form",
            hint: "",
            id: "",
            name: "",
            placeholder: "",
            required: false,
            validator: null,
            originalType: null,
            disabled: false,
            format: "YYYY-MM-DD",
            mode: "edit",
            data: null,
            value: "",
            stepping: 1,
            minDate: false,
            maxDate: false,
            useCurrent: false,
            collapse: true,
            defaultDate: false,
            disabledDates: [],
            sideBySide: false,
            daysOfWeekDisabled: false,
            calendarWeeks: true,
            viewMode: "days",
            toolbarPlacement: "default",
            showTodayButton: true,
            showClear: true,
            widgetPositioning: {
                horizontal: "left",
                vertical: "bottom"
            },
            keepOpen: false,
            dayViewHeaderFormat: "MMMM YYYY",
            pickType: "datetime",
            keyLabel: "",
            enableValidate: true,
            text : ""
        },
        getData: function () {
            if (this.get("group") == "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }

            } else {
                return {
                    name: this.get("name") ? this.get("name") : "",
                    value: this.get("value")
                }
            }
            return this;
        },
        initialize: function (options) {
            var useDefaults = {
                    showClear: false,
                    useCurrent: true
                },
                useCurrentOptions = ['year', 'month', 'day', 'hour', 'minute'],
                viewMode = ['years', 'months', 'days'],
                data = {
                    value: "",
                    label: ""
                },
                defaultDate,
                maxOrMinDate,
                flag = true;
            options = this.redefinepropertiesV4(options);
            if (options.format === "") {
                this.set("format", "YYYY-MM-DD");
            }
            if (this.get("useCurrent") === "true") {
                this.attributes.useCurrent = JSON.parse(this.get("useCurrent"));
            }
            if (useCurrentOptions.indexOf(this.get("useCurrent")) === -1) {
                this.attributes.useCurrent = useDefaults["useCurrent"];
            }

            if (this.get("showClear") === "true") {
                this.attributes.showClear = JSON.parse(this.get("showClear"));
            }

            if (this.get("showClear") === "false") {
                this.attributes.showClear = JSON.parse(this.get("showClear"));
            }
            if (typeof this.get("showClear") !== "boolean") {
                this.attributes.showClear = useDefaults["showClear"];
            }

            if (this.get("format") === "false") {
                this.attributes.format = JSON.parse(this.get("format"));
            }

            if (viewMode.indexOf(options["viewMode"]) === -1) {
                this.attributes.viewMode = "days";
            }

            this.customPickTimeIcon(this.get("pickType"));

            if (!_.isEmpty(this.get("data")) && (this.get("data")["value"] !== "" || this.get("data")["label"] !== "")) {
                this.set("defaultDate", false);
            } else {
                this.set("value",this.get("defaultDate"));
                this.set("data", data);
            }
            if (typeof this.get("maxDate") === "boolean") {
                this.set("maxDate", "");
            }

            if (!this.isDate(this.get("maxDate"))) {
                this.set("maxDate", "");
            }

            if (!this.isDate(this.get("minDate"))) {
                this.set("minDate", "");
            }

            if (!this.isDate(this.get("defaultDate"))) {
                this.set("defaultDate", "");
            }

            if (this.get("maxDate").trim().length && this.get("defaultDate") && this.get("defaultDate").trim().length) {
                defaultDate = this.get("defaultDate").split("-");
                maxOrMinDate = this.get("maxDate").split("-");
                if ((parseInt(defaultDate[0]) <= parseInt(maxOrMinDate[0]))) {
                    if ((parseInt(defaultDate[1]) <= parseInt(maxOrMinDate[1]))) {
                        if ((parseInt(defaultDate[2]) <= parseInt(maxOrMinDate[2]))) {
                            flag = true;
                        } else {
                            flag = false;
                        }
                    } else {
                        flag = false;
                    }
                } else {
                    flag = false;
                }
                if (!flag) {
                    this.set("defaultDate", false);
                }
            }
            if (flag) {
                if (typeof this.get("minDate") === "boolean") {
                    this.set("minDate", "");
                }
                if (this.get("minDate").trim().length && this.get("defaultDate") && this.get("defaultDate").trim().length) {
                    defaultDate = this.get("defaultDate").split("-");
                    maxOrMinDate = this.get("minDate").split("-");
                    if ((parseInt(defaultDate[0]) >= parseInt(maxOrMinDate[0]))) {
                        if ((parseInt(defaultDate[1]) >= parseInt(maxOrMinDate[1]))) {
                            if ((parseInt(defaultDate[2]) >= parseInt(maxOrMinDate[2]))) {
                                flag = true;
                            } else {
                                flag = false;
                            }
                        } else {
                            flag = false;
                        }
                    } else {
                        flag = false;
                    }
                    if (!flag) {
                        this.set("defaultDate", false);
                    }
                }
            }
            if (this.get("data") && this.get("data")["value"]!==""){
                this.attributes.value = this.get("data")["value"];
                this.attributes.keyLabel = this.get("data")["label"];
            }else{
                if (this.get("defaultDate")!== ""){
                    this.attributes.data = {
                        value :this.get("defaultDate"),
                        label :this.get("defaultDate")
                    };
                }else{
                    this.attributes.data = {
                        value : "",
                        label : ""
                    };
                }
            }
            this.set("validator", new PMDynaform.model.Validator({
                required: this.get("required"),
                type: this.get("type"),
                dataType: this.get("dataType")
            }));

            if (this.get("variable").trim().length === 0) {
                if (this.get("group") === "form") {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.defineModelEvents();
            this.set("text",this.get("data")["label"]);
            return this;
        },
        customPickTimeIcon: function (format) {

        },
        isValid: function () {
            //this.set("valid", this.get("validator").get("valid"));
            this.attributes.valid = this.get("validator").get("valid");
            return this.get("valid");
        },
        validate: function (attrs) {
            var valueFixed = this.get("value");
            this.set("value", valueFixed);
            //this.attributes.value = valueFixed;
            this.get("validator").set("value", valueFixed);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        isDate: function (dateValue) {
            var pattern = /@@|@\$|@=/;
            var d = new Date(dateValue);
            if (pattern.test(dateValue) || d == "Invalid Date" || typeof d == "undefined" || !d) {
                return false;
            }
            return true;
        },
        validateDate : function (date){
            var valid, data, value;
            value = date.replace(/-/g, "/");
            if (new Date(value).toString() !== "Invalid Date") {
                valid = true;
            } else {
                valid = false;
            }
            return  valid;
        },
        formatedData: function (value) {
            var newData,
                format = 'YYYY-MM-DD HH:mm:ss';
            if (value) {
                newData = {
                    value: moment(value).format(format),
                    label: moment(value).format(this.get('format'))
                };
            }
            return newData;
        },
        onChangeValue: function (attrs, item) {
            var data = { value : "", label : ""};
            if(item !== undefined){
                if(this.validateDate(item)){
                    data = this.formatedData(item);
                }
            }
            this.set("data",data);
            this.attributes.value = data["value"];
            this.attributes.text = data["label"];
            return this;
        },
        onChangeText: function (attrs, item) {
            // This method is not support to this control type 
            return this;
        },
        /**
         * Some property values that were modified in updating v3 to v4 is redefined necessary
         * @param  {Object} settings json configuration stored in definitiong 
         * @return {Object} new json configuration
         */
        redefinepropertiesV4 : function (settings){
            var propConf;
            if(typeof settings === "object"){
                if(settings.hasOwnProperty("daysOfWeekDisabled")){
                    propConf = settings["daysOfWeekDisabled"];
                    propConf = (_.isArray(propConf) && _.isEmpty(propConf))? false : propConf;
                    settings["daysOfWeekDisabled"] = propConf;
                    this.set("daysOfWeekDisabled",propConf);
                }
            }
            return settings;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.model.Datetime", DatetimeModel);
}());
(function(){

    var SuggestModel = PMDynaform.model.Field.extend({
        defaults: {
            autoComplete: "off",
            type: "text",
            placeholder: "untitled",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("suggest"),
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            value: "",
            group: "form",
            defaultValue: "",
            maxLengthLabel: 15,
            mode: "edit",
            tooltipLabel: "",
            disabled: false,
            dataType: "string",
            executeInit: true,
            required: false,
            maxLength: null,
            validator: null,
            valid: true,
            proxy: null,
            variable: null,
            var_uid: null,
            var_name: null,
            options:[],
            localOptions: [],
            remoteOptions: [],
            dependenciesField: [],
            columnName : null,
            originalType : null,
            mask : "",
            clickedControl : true,
            keyLabel : "",
            optionsSql : [],
            enableValidate : true,
            text : "",
            data : null
        },
        initialize: function(attrs) {
            var data;
            this.set("dataType", this.get("dataType").trim().length?this.get("dataType"):"string");
            this.set("dependenciesField",[]);
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.set("validator", new PMDynaform.model.Validator());
            this.initControl();
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
            data = this.get("data");
            if ( data && data["value"] !== "" && data["label"] !== "" ) {
                this.set("value",data["value"]);
                this.set("keyLabel",data["label"]);
            } else {
                this.set("data",{value:"", label:""});
                this.set("value","");
            }
            if ( this.get("variable").trim().length === 0) {
                if ( this.get("group") === "form" ) {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.set("text",data["label"]);
            this.defineModelEvents();
        },
        initControl: function() {
            if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
        },
        setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
        isValid: function(){
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        emptyValue: function (){
            this.set("value","");
        },
        setDependencies: function(newDependencie) {
            var arrayDep,i, result, newArray = [];
            arrayDep = this.get("dependenciesField");
            if(arrayDep.indexOf(newDependencie) == -1){
                arrayDep.push(newDependencie);
            }
            this.set("dependenciesField",[]);
            this.set("dependenciesField",arrayDep);
        },
        validate: function (attrs) {
            var valueFixed = attrs.value.trim();

            this.set("value", valueFixed);
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("value", valueFixed);
            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        },
        getData: function() {
            if (this.get("group") == "grid"){
                return {
                    name : this.get("columnName") ? this.get("columnName"): "",
                    value :  this.get("value")
                }

            } else {
                return {
                    name : this.get("name") ? this.get("name") : "",
                    value :  this.get("data")["value"]
                }
            }
        },
        reviewRemotesOptions : function () {
            var sql;
            if ( this.get("variable") && this.get("variable").trim().length) {
                sql = this.get("sql");
                if (sql){
                    this.reviewRemoteVariable();
                }
            }
            return this;
        },
        onChangeValue: function (attrs, item) {
            var data;
            data = this.findOption(item,"value");
            if (!data){
                data = {
                    value : item,
                    label : item
                }
            }
            this.set("data",data);
            this.set("text",data["label"]);
            return this;
        },
        onChangeText: function (attrs, item) {
            var data;
            data = this.findOption(item,"label");
            if (!data){
                data = {
                    value : item,
                    label : item
                }
            }
            this.set("data",data);
            this.set("text",data["label"]);
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Suggest", SuggestModel);
}());
(function(){

	var LinkModel = PMDynaform.model.Field.extend({
		defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "string",
            defaultValue: "",
            disabled: false,
            group: "form",
            hint: "",
            href: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("link"),
            label: "untitled label",
            mode: "edit",
            required: false,
            target: "_blank",
            targetOptions: {
                blank: "_blank",
                parent: "_parent",
                self: "_self",
                top: "_top"
            },
            type: "link",
            valid: true,
            value: "",
            columnName : null,
            originalType : null,
            text : "",
        },
        initialize: function(options) {
            this.set("text", options.value? options.value:"");
            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.setHref(this.get("href"));

            this.on("change:label", this.onChangeLabel, this);
            this.on("change:text", this.onChangeText, this);
            this.on("change:value", this.onChangeValue, this);
            this.setTarget();
        },
        setTarget: function () {
            var opt = this.get("targetOptions"),
            target;

            target = opt[this.get("target")]? opt[this.get("target")] : "_blank";
            this.set("target", target);
        },
        getData: function() {
            return this.get("data");
        },
        validationURL :function (url){
            var reg = /^(?:(http|https):)?(\/{2,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/;
            return reg.test(url);
        },
        reformatURL : function (url){
            var newHref = url;
            if(!(url.indexOf("http://") === 0 || url.indexOf("https://") === 0)){
                newHref = "http://"+url;
            }
            return newHref;
        },
        setHref : function (href){
            var newHref = href;
            if (!this.validationURL(href)){
                newHref = this.reformatURL(href);
            }
            this.set("href", newHref);
            this.updateData(newHref, this.get("text"));

            return this;
        },
        onChangeValue: function (attrs, item) {
            this.setHref(this.get("value"));
            return this;
        },
        onChangeText: function (attrs, item) {
            if (item) {
                this.set("text", item);
                this.attributes.value = item;
                this.updateData(this.get("href"), item);
            }
            return this;
        },
        updateData : function (href, dText){
            this.set("data", {
                value: href,
                label: dText
            });
            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Link", LinkModel);
}());
(function(){

    var Label = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            group: "form",
            hint: "",
            namespace: "pmdynaform",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("label"),
            label: "untitled label",
            mode: "view",
            options: [],
            required: false,
            type: "label",
            columnName : null,
            originalType : null,
            variable: "",
            var_uid: null,
            var_name: null,
            localOptions : [],
            remoteOptions : [],
            fullOptions : [""],
            data : null,
            value : null,
            dataType : null,
            keyValue : null,
            optionsSql : [],
            enableValidation : true
        },
        getData: function() {
            var value= "";
            if (this.get("group") == "grid"){
                if (this.get("originalType") !== "label"){
                    return {
                        name : this.get("columnName") ? this.get("columnName"): "",
                        value :  this.get("keyValue")
                    }
                }else{
                    return {
                        name : this.get("columnName") ? this.get("columnName"): "",
                        value :  this.get("value")
                    }
                }
            } else {
                if (this.get("originalType") !== "label"){
                    value = this.get("data")["value"];
                    if (this.get('originalType') === 'checkbox') {
                        value =  this.get("value");
                    }
                    return {
                        name : this.get("name") ? this.get("name") : "",
                        value :  value
                    }
                }else{
                    return {
                        name : this.get("name") ? this.get("name") : "",
                        value :  this.get("value")
                    }
                }
            }
        },
        initialize: function(options) {
            var i, aux,
                newOptions = [],
                data;

            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:options", this.onChangeOptions, this);

            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
            this.setData(options.data);
            if (this.get("group") === "form"){
                if ( this.get("variable") && this.get("variable").trim().length === 0) {
                    if ( this.get("group") === "form" ) {
                        this.attributes.name = "";
                    } else {
                        this.attributes.name = this.get("id");
                    }
                }
            }
            if (typeof this.get("formula") === "string" &&
                this.get('formula') !== "undefined" &&
                this.get('formula') !== "null" &&
                this.get('formula').length > 1) {
                this.set("formulator", new PMDynaform.core.Formula(this.get("formula")));
                this.set("disabled", true);
            }
            return this;
        },
        setData : function (data){
            var data;
            if ( data && !_.isEmpty(data) ) {
                this.set("data",data);
                this.set("value", data["value"]);
            } else {
                this.set("data",this.getDataWithDefaultValue());
            }
            this.set("fullOptions",this.obtainingLabelsToShow());
            return this;
        },
        getDataWithDefaultValue : function (){
            var data, options, i;
            data = {
                label : this.get("defaultValue") || "",
                value : this.get("defaultValue") || ""
            };
            options = this.get("options") || [];
            for ( i = 0 ; i < options.length ; i+=1){
                if (this.get("defaultValue") === options[i].value){
                    data = options[i];
                    break;
                }
            }
            return data;
        },
        setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
        addFormulaTokenAssociated: function(formulator) {
            if (formulator instanceof PMDynaform.core.Formula) {
                formulator.addTokenValue(this.get("id"), this.get("value"));
            }
            return this;
        },
        updateFormulaValueAssociated: function(field) {
            var resultField = field.model.get("formulator").evaluate();
            field.model.set("value", resultField);
            return this;
        },
        addFormulaFieldName: function(otherField) {
            this.get("formulator").addField("field", otherField);
            return this;
        },
        /**
         * Gets all labels to show
         * @returns {Array}
         */
        obtainingLabelsToShow: function () {
            var data,
                labels = [];
            data = this.get("data");
            if (data && !_.isEmpty(data)){
                if (this.get("originalType") === "checkgroup") {
                    labels = PMDynaform.core.Utils.isJsonAndParse(data["label"]);
                }else{
                    labels.push(data["label"]);
                }
            }
            return labels;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Label", Label);
}());
(function(){

	var Title = PMDynaform.model.Field.extend({
		defaults: {
            type: "title",
            label: "untitled label",
            mode: "view",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform",
            className: {
                title: "pmdynaform-label-title",
                subtitle: "pmdynaform-label-subtitle"
            }
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Title", Title);
}());
(function(){
	var Empty = Backbone.Model.extend({
		defaults: {
			colSpan: 12,
			namespace: "pmdynaform",
			id: PMDynaform.core.Utils.generateID(),
			type: "empty"
		}
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.Empty", Empty);
}());
(function () {
    var HiddenModel = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            dataType: "string",
            namespace: "pmdynaform",
            defaultValue: null,
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("hidden"),
            type: "hidden",
            valid: true,
            value: "",
            group: "form",
            var_name: "",
            data: null,
            keyLabel: "",
            options: [],
            optionsSql: [],
            remoteOptions: [],
            text: ""
        },
        defineModelEvents: function () {
            this.on("change:value", this.onChange, this);
            this.on("change:text", this.onChange, this);
            return this;
        },
        initialize: function (options) {
            var data = {};
            this.set("defaultValue", this.get("defaultValue"));
            if (!this.get("data")) {
                data = {
                    value: this.get("defaultValue"),
                    label: this.get("defaultValue")
                };
                this.attributes.data = data;
            }
            this.set("value", this.get("data")["value"]);
            this.set("keyLabel", this.get("data")["label"]);
            this.setLocalOptions();
            this.setRemoteOptions();
            this.mergeOptionsSql();
            this.initControl();
            if (this.get("variable").trim().length === 0) {
                if (this.get("group") === "form") {
                    this.attributes.name = "";
                } else {
                    this.attributes.name = this.get("id");
                }
            }
            this.defineModelEvents();
            this.set("text", this.get("data")["label"]);
            return this;
        },
        initControl: function () {
            if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
        },
        onChangeValue: function () {
        },
        getData: function () {
            if (this.get("group") == "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }
            } else {
                return {
                    name: this.get("name") ? this.get("name") : "",
                    value: this.get("value")
                }
            }
        },
        getKeyLabel: function () {
            if (this.get("group") === "grid") {
                return {
                    name: this.get("columnName") ? this.get("columnName") : "",
                    value: this.get("value")
                }
            } else {
                return {
                    name: this.get("name") ? this.get("name").concat("_label") : "",
                    value: this.get("value")
                }
            }
        },
        onChange: function (attrs, item) {
            var data;
            data = {
                value: item || "",
                label: item || ""
            };
            this.set("data", data);
            this.set({text: item, value: item});
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.model.Hidden", HiddenModel);
}());
(function () {
    var ImageModel = PMDynaform.model.Field.extend({
        defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            disabled: false,
            defaultValue: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("image"),
            label: "untitled label",
            crossorigin: "anonymous",
            alt: "",
            src: "",
            height: "",
            width: "",
            mode: "view",
            shape: "thumbnail",
            shapeTypes: {
                thumbnail: "img-thumbnail",
                rounded: "img-rounded",
                circle: "img-circle"
            },
            type: "image",
            columnName: null,
            originalType: null,
            group: "form"
        },
        initialize: function (options) {
            var defaults;

            this.set("label", this.get("label"));
            this.set("defaultValue", this.get("defaultValue"));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            if (options.project) {
                this.project = options.project;
            }
            this.setShapeType();
        },
        setShapeType: function () {
            var shape = this.get("shape"),
                types = this.get("shapeTypes"),
                selected;

            selected = types[shape] ? types[shape] : types["thumbnail"];
            this.set("shape", selected);
            return;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.model.Image", ImageModel);
}());

(function(){
	var SubFormModel = Backbone.Model.extend({
		defaults: {
			colSpan: 12,
			namespace: "pmdynaform",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("form"),
			type: "form",
			mode: "edit",
			valid: true,
			modelForm: null
		},
		initialize: function () {
			
		},
		getData: function () {
			return {
				name: this.get("name"),
				id: this.get("id"),
				variables: {}
			}
		}
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.SubForm", SubFormModel);
}());
(function(){

	var GeoMapModel = PMDynaform.model.Field.extend({
		defaults: {
            colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dragMarker: false,
            dataType: "string",
            disabled: false,
            decimals: 6,
            group: "form",
            hint: "",
            fullscreen: false,
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("link"),
            googlemap: null,
            label: "untitled label",
            mode: "edit",
            required: false,
            valid: true,
            value: "",
            navigator: true,
            currentLocation: false,
            supportNavigator: false,
            altitude : 0,
            latitude : 0,
            longitude: 0,
            marker: null,
            zoom: 15,
            tooltipLabel: "",
            panControl: false,
            zoomControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            mapTypeControl: false,
            title: ""
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.checkSupportGeoLocation();
        },
        checkSupportGeoLocation: function () {
            var supportNavigator = navigator.geolocation? true : false;

            this.set("supportNavigator", supportNavigator);

            return this;
        },
        rightToLeftLabels: function () {
            var marker = this.get("marker"),
            infowindow = new google.maps.InfoWindow();

            infowindow.setContent('<b>القاهرة</b>');
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(this.get("googlemap"), marker);
            });
        },
        getData: function() {
            return {
                name: this.get("variable")? this.get("variable").var_name : this.get("name"),
                value: this.get("longitude") + "|" + this.get("latitude")
            };
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.GeoMap", GeoMapModel);
}());
(function(){
	var Annotation = PMDynaform.model.Field.extend({
		defaults: {
            type: "annotation",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Annotation", Annotation);
}());
(function(){
	var Audio_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "audio_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Audio_mobile", Audio_mobile);
}());
(function(){
	var Geomap_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "geomap_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Geomap_mobile", Geomap_mobile);
}());
(function(){
	var Image_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "image_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Image_mobile", Image_mobile);
}());
(function(){
	var Qrcode_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "qrcode_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Qrcode_mobile", Qrcode_mobile);
}());
(function(){
	var Signature_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "signature_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Signature_mobile", Signature_mobile);
}());
(function(){
	var Video_mobile = PMDynaform.model.Field.extend({
		defaults: {
            type: "video_mobile",
            label: "untitled label",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("title"),
            colSpan: 12,
            namespace: "pmdynaform"
        },
        initialize: function() {
            this.set("label", this.get("label"));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Video_mobile", Video_mobile);
}());
(function(){
	var PanelField = PMDynaform.model.Field.extend({
		defaults: {
            type: "panel",
            showHeader : false,
            showFooter : false,
            title : "untitled-panel",
            footerContent : "<div>footer pmdynaform!</div>",
            content : "<div>content Body in panel PMDynaform</div>",
            id: PMDynaform.core.Utils.generateID(),
            colSpan: 12,
            namespace: "pmdynaform",
            typePanel : "default",
            border : "1px"
        },
        initialize : function (options) {
            var length;
            if (options["border"]){
                length = this.verifyLenght(options["border"]);
                this.set("border",length);
            }

        },
        verifyLenght : function (length) {
            var length;
            if(typeof length === 'number') {
                length = length + "px";   
            } else if(Number(length).toString() != "NaN"){
                length = length + "px";
            }
            else if(/^\d+(\.\d+)?px$/.test(length)) {
                length = length;
            } else if(/^\d+(\.\d+)?%$/.test(length)) {
                length = length;
            } else if(/^\d+(\.\d+)?em$/.test(length)) {
                length = length;
            } else if(length === 'auto' || length === 'inherit') {
                length = length;
            } else {
                length = "1px";   
            }
            return length;
        }

    });
    PMDynaform.extendNamespace("PMDynaform.model.PanelField", PanelField);
}());
(function () {
    /**
     * @class PMDynaform.view.FlashMessageView
     * A message to display for a while.
     *
     * Usage example:
     *
     *      @example
     *      flashModel = new PMDynaform.ui.FlashMessageModel({
     *           message : "This is a flas message",
     *           emphasisMessage: "Info",
     *           startAnimation:5000,
     *           closable:true,
     *           type:"danger",
     *           appendTo:document.body,
     *           duration:5000
     *      });
     *      flashView = new PMDynaform.ui.FlashMessageView({model:flashModel})
     *      flashView.render();
     *
     */
    var FlashMessageView = Backbone.View.extend({
        template: _.template($('#tpl-flashMessage').html()),
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function () {
            var offsetTarget;
            this.$el.html(this.template(this.model.toJSON()));
            this.configurateAnimation();
            return this;
        },
        /**
         * This method sets the necessary parameters for the effect shown
         * the message using animation Jquery
         * @return {[type]} [description]
         */
        configurateAnimation: function () {
            var offsetTarget, target;
            target = this.model.get('appendTo');
            if (!(target instanceof jQuery)) {
                target = jQuery(target);
            }
            offsetTarget = this.calculateContainerPosition(target);
            this.fixPosition(offsetTarget);
            this.$el.finish().css({
                'top': offsetTarget.top - 50
            }).fadeTo(1, 0).animate({
                top: offsetTarget.top,
                opacity: 1
            }, this.model.get('startAnimation'), 'swing').delay(this.model.get('duration'))
                .animate({
                    top: this.model.get("absoluteTop")? 0 : offsetTarget.top,
                    opacity: 0,
                    zIndex: '0'
                });
            $(document.body).append(this.$el);
        },
        /**
         * This method calculates the position of parent container
         * to place the message at the head of it
         * @param  {[HTMLElement]} target : this a HTML element target
         * @return {[type]}  return a positions left, top, width
         */
        calculateContainerPosition: function (target) {
            var offset,
                width,
                target = target || this.model.get('appendTo');
            if (!(target instanceof jQuery)) {
                target = jQuery(target);
            }
            offset = target.offset();
            width = target.outerWidth();
            if (this.model.get("absoluteTop")){
                offset.top = this.getAbsoluteTopScrollElement(target);
            }
            return {
                top: offset.top || 0,
                left: offset.left || 0,
                width: width || 0
            }
        },
        /**
         * This method recalculates and sets the position of the component flash message
         * @param  {[type]} offset this object with positions for to set in the component
         */
        fixPosition: function (offset) {
            var showWidth = offset.width / 2,
                showLeft = offset.width / 4 + offset.left;
            if (this.$el.length) {
                this.$el.css({
                    top: offset.top,
                    width: showWidth,
                    left: showLeft,
                    position: 'absolute'
                });
            }
            return this;
        },
        getAbsoluteTopScrollElement : function(){
            var scrollTop = 0;
            if(document.body){
                scrollTop = $(document).scrollTop();
            }
            return scrollTop;
        }
    });
    PMDynaform.extendNamespace('PMDynaform.ui.FlashMessageView', FlashMessageView);
}());

(function(){
	var FileMobile =  PMDynaform.model.Field.extend({
		defaults: {
            autoUpload: false,
			camera: true,
            colSpan: 12,
            disabled: false,
            dnd: false,
            dndMessage: "Drag or choose local files",
            extensions: "pdf, png, jpg, mp3, doc, txt",
            group: "form",
            height: "100%",
            hint: "",
			id: PMDynaform.core.Utils.generateID(),
            items: [],
            label: "Untitled label",
            labelButton: "Choose Files",
            mode: "edit",
            multiple: false,
            name: PMDynaform.core.Utils.generateName("file"),
            preview: false,
            required: false,
            size: 1, //1 MB
            type: "file",
            proxy: [],
            valid: true,
            validator: null,
            value: "",
            files:[]               
        },

        initialize: function() {
            this.attributes.files= [];
            this.initControl();
            this.set("items", []);
            this.set("proxy", []);
        },
        initControl: function() {
            if (this.get("dnd")) {
                this.set("preview", true);
            }
            return this;
        },
        getData: function() {
            var respData=[];
            for (var i = 0;i< this.attributes.files.length ;i++){
                if(this.attributes.files[i].id){
                    respData.push(this.attributes.files[i].id);
                }else{
                    respData.push(this.attributes.files[i]);
                }
            }
            return {
                name: this.get("name"),
                value: respData
            };
        },
        getDataComplete: function() {
            var respData=[];
            for (var i = 0;i< this.attributes.files.length ;i++){
                if(this.attributes.files[i]["base64"]){
                    respData.push(this.attributes.files[i].id);
                }else{
                    respData.push(this.attributes.files[i]);
                }                
            }
            return {
                name: this.get("name"),
                value: respData
            };
        },
        validate: function (attrs) {
            
        },
        getIDImage: function (index){
           return this.attributes.images[index].id;   
        },
        getBase64Image: function (index){
           return this.attributes.images[index].value;   
        },
        makeBase64Image : function (base64){
            return "data:image/png;base64,"+base64;
        },        
        remoteProxyData : function (arrayImages){           
                var prj = this.get("project"),
                url,
                restClient,
                that = this,
                endpoint,
                that= this,
                respData,
                data;
                data = this.formatArrayImagesToSend(arrayImages);                
                respData = this.formatArrayImages(prj.webServiceManager.imagesInfo(data));                                  
                return respData;            
        },
        formatArrayImagesToSend : function (arrayImages){
            var imageId,
                dataToSend = [],
                item = {};

            for (var i = 0; i< arrayImages.length ; i++){
                imageId = arrayImages[i];
                if(PMDynaform.core.ProjectMobile){
                    item = {
                        fileId: imageId,
                        width : "100",
                        version :1
                    };
                }else{
                    item = {
                        fileId: imageId,                        
                        version :1
                    };
                }                
                dataToSend.push(item);
            }
            return dataToSend;
        },
        formatArrayImages : function (arrayImages){
            var itemReceive, imageReceive,
                dataToSend = [],
                item = {};

            for (var i = 0; i< arrayImages.length ; i++){
                imageReceive = arrayImages[i];
                item = {
                    id: imageReceive.fileId,
                    base64: imageReceive.fileContent
                };
                dataToSend.push(item);
            }
            return dataToSend;
        },
        remoteProxyDataMedia : function (id){
                var prj = this.get("project"),
                url,
                restClient,
                that = this,
                endpoint,
                that= this,
                respData;                
                endpoint = this.getEndpointVariables({
                    type: "fileStreaming",
                    keys: {
                        "{fileId}": id,
                        "{caseID}": prj.webServiceManager.options.keys.caseID                           
                    }
                });                
                url = prj.webServiceManager.getFullURLStreaming(id);             
                return url;            
        },
        urlFileStreaming : function (id){
            var prj = this.get("project"),
                url,
                that = this,
                endpoint,                
                dataToSend;                                                
                url = prj.webServiceManager.getFullURLStreaming(id);
                dataToSend = {
                    id:id,
                    filePath: url
                };
                return dataToSend;
        },        
        getEndpointVariables: function (urlObj) {
            var prj = this.get("project"),
            endPointFixed,
            variable,
            endpoint;

            if (prj.endPointsPath[urlObj.type]) {
                endpoint = prj.endPointsPath[urlObj.type]
                for (variable in urlObj.keys) {
                    if (urlObj.keys.hasOwnProperty(variable)) {
                        endPointFixed =endpoint.replace(new RegExp(variable, "g"), urlObj.keys[variable]);  
                        endpoint= endPointFixed;
                    }
                }
            }

            return endPointFixed;
        }
	});

	PMDynaform.extendNamespace("PMDynaform.model.FileMobile", FileMobile);
}());
(function(){
    var GeoMobile =  PMDynaform.model.Field.extend({
        defaults: {
            id: PMDynaform.core.Utils.generateID(),
            type: "location",
            label: "Untitled label",
            mode: "edit",
            group: "form",
            labelButton: "Map",
            name: "name",
            colSpan: 12,
            height: "auto",            
            value: "",
            required: false,
            hint: "",
            disabled: false,
            preview: false,
            valid: true,

            geoData:null,
            interactive: true            
        },
        initialize: function() {
            this.initControl();
        },
        initControl: function() {
            this.attributes.images = [];
            //if (this.get("dnd")) {
                this.set("preview", true);
            //}
            return this;
        },
        isValid: function() {
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        getDataRFC: function() {
            var geoValue = this.attributes.geoData;
            if(geoValue == "null" || geoValue == null){
                geoValue=null;                    
            }else{
                if(geoValue.imageId == "" || geoValue.imageId == null || typeof geoValue.imageId == "undefined"){
                    geoValue = geoValue;
                }
            }
            if(geoValue){
                if(geoValue.data){
                    delete geoValue.data;
                }
            }
            return {
                name: this.get("name"),
                value: geoValue
            };
        },
        getData: function() {
            var geoValue = this.attributes.geoData;            
            if(geoValue){
                if(geoValue.base64){
                    delete geoValue.base64;
                }
            }
            return {
                name: this.get("name"),
                value: geoValue
            };
        },
        
        validate: function (attrs) {
            
        },        
        remoteProxyData : function (id){           
            return this.get("project").webServiceManager.imageInfo(id,600);
        },
        getImagesNetwork : function (location){           
            var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint,
            that= this,
            respData;            
            endpoint = this.getEndpointVariables({
                        type: "getImageGeo",
                        keys: {
                            "{fileID}": location.imageId,
                            "{caseID}": prj.keys.caseID,                                                           
                        }
                    });
                    url = prj.getFullURL(endpoint);
            url = prj.getFullURL(endpoint);            
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'POST',
                data: {
                    fileId: location.imageId,
                    width : "600",
                    version :1
                },                
                keys: prj.token,
                successCallback: function (xhr, response) {
                    respData = response;
                }
            });
            this.set("proxy", restClient);
            return respData;            
        },        
        remoteGenerateLocation : function (location){           
            var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint,
            that= this,
            dataToSend = new FormData(),
            respData;           
                        
            dataToSend.append("tas_uid",location);                        
            dataToSend.append("tas_uid",prj.keys.taskID);
            dataToSend.append("app_doc_comment","");
            dataToSend.append("latitude",location.latitude);
            dataToSend.append("longitude",location.longitude);
     
            endpoint = this.getEndpointVariable({
                        type: "generateImageGeo",
                        keys: {
                            "{caseID}": prj.keys.caseID                          
                        }
                    });
                    url = prj.getFullURL(endpoint);
            url = prj.getFullURL(endpoint);

            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'POST',
                data:dataToSend,                
                keys: prj.token,
                successCallback: function (xhr, response) {
                    respData = response;
                }
            });
            this.set("proxy", restClient);
            return respData;
            
        },
        getEndpointVariables: function (urlObj) {
            var prj = this.get("project"),
            endPointFixed,
            variable,
            endpoint;

            if (prj.endPointsPath[urlObj.type]) {
                endpoint = prj.endPointsPath[urlObj.type]
                for (variable in urlObj.keys) {
                    if (urlObj.keys.hasOwnProperty(variable)) {
                        endPointFixed =endpoint.replace(new RegExp(variable, "g"), urlObj.keys[variable]);  
                        endpoint= endPointFixed;
                    }
                }
            }

            return endPointFixed;
        }   
    });

    PMDynaform.extendNamespace("PMDynaform.model.GeoMobile", GeoMobile);
}());
(function(){
    var Qrcode_mobile = PMDynaform.model.Field.extend({
        defaults: {
            id: PMDynaform.core.Utils.generateID(),
            type: "scannercode",
            label: "Untitled label",
            mode: "edit",
            group: "form",
            labelButton: "Scanner Code",
            name: "name",
            colSpan: 12,
            height: "auto",            
            value: "",
            required: false,
            hint: "",
            disabled: false,
            preview: false,
            valid: true,
            codes: [],

            geoData:null,
            interactive: true            
        },
        initialize: function() {
            this.initControl();
        },
        initControl: function() {
            this.attributes.codes = [];
            this.set("preview", true);
            return this;
        },
        isValid: function() {
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        getData: function() {            
            return {
                name: this.get("name"),
                value: this.get("codes")
            };
        },
        addCode: function(newCode) {            
            var codes = this.get("codes");
            codes.push(newCode);
        },
        validate: function (attrs) {
            
        },                
        getEndpointVariables: function (urlObj) {
            var prj = this.get("project"),
            endPointFixed,
            variable,
            endpoint;

            if (prj.endPointsPath[urlObj.type]) {
                endpoint = prj.endPointsPath[urlObj.type]
                for (variable in urlObj.keys) {
                    if (urlObj.keys.hasOwnProperty(variable)) {
                        endPointFixed =endpoint.replace(new RegExp(variable, "g"), urlObj.keys[variable]);  
                        endpoint= endPointFixed;
                    }
                }
            }
            return endPointFixed;
        } 
    });
    PMDynaform.extendNamespace("PMDynaform.model.Qrcode_mobile", Qrcode_mobile);
}());
(function(){
    var Signature_mobile =  PMDynaform.model.Field.extend({
        defaults: {
            id: PMDynaform.core.Utils.generateID(),
            type: "signature",
            label: "Untitled label",
            mode: "edit",
            group: "form",
            labelButton: "Signature",
            name: "name",
            colSpan: 12,
            height: "auto",            
            value: "",
            required: false,
            hint: "",
            disabled: false,
            preview: false,
            valid: true,
            files:[]            
        },
        initialize: function() {
            this.initControl();
        },
        initControl: function() {
            this.attributes.files= [];
            this.set("preview", true);            
            return this;
        },
        isValid: function() {
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        getData: function() {
            var i,
                response = [],
                signatureValue = this.attributes.files;
            for (i = 0;i< signatureValue.length;i++){
                if(typeof signatureValue[i].id  != "undefined" && signatureValue[i].id != null){
                    response.push(signatureValue[i].id);
                }
            }
            return {
                name: this.get("name"),
                value: response
            };             
        },
        getDataCustom: function() {
            var signatureValue = this.attributes.files;            
            return {
                name: this.get("name"),
                value: signatureValue
            };
        },
        validate: function (attrs) {
            
        },        
        remoteProxyData : function (id){           
            return this.get("project").webServiceManager.imageInfo(id,300);
            /*var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint,
            that= this,
            respData,
            itemElement;
            endpoint = this.getEndpointVariables({
                type: "getImageGeo",
                keys: {                           
                    "{caseID}": prj.keys.caseID,                                                           
                }
            });
            if (PMDynaform.core.ProjectMobile){
                url = prj.getFullURL(endpoint);   
            }else{
                url = prj.getFullURLMobile(prj.endPointsPath.imageInfo);                    
            }
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'POST',
                data:[{
                    fileId: id,
                    width : "300",
                    version :1
                }],                
                keys: prj.token,
                successCallback: function (xhr, response) {
                    respData = response;
                }
            });
            if (respData){
                return {
                    id: respData[0].fileId,
                    base64: respData[0].fileContent
                };
            }else{
                 return {
                    id: "",
                    base64: ""
                };
            }*/
        },
        remoteGenerateID : function (location){           
            var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint,
            that= this,
            respData;            
            endpoint = this.getEndpointVariable({
                        type: "generateImageGeo",
                        keys: {
                            "{caseID}": prj.keys.caseID                          
                        }
                    });
                    url = prj.getFullURL(endpoint);
            url = prj.getFullURL(endpoint);
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'POST',
                data:location,                
                keys: prj.token,
                successCallback: function (xhr, response) {
                    respData = response;
                }
            });
            this.set("proxy", restClient);
            return respData;
            
        },
        getEndpointVariables: function (urlObj) {
            var prj = this.get("project"),
            endPointFixed,
            variable,
            endpoint;

            if (prj.endPointsPath[urlObj.type]) {
                endpoint = prj.endPointsPath[urlObj.type]
                for (variable in urlObj.keys) {
                    if (urlObj.keys.hasOwnProperty(variable)) {
                        endPointFixed =endpoint.replace(new RegExp(variable, "g"), urlObj.keys[variable]);  
                        endpoint= endPointFixed;
                    }
                }
            }

            return endPointFixed;
        }   
    });

    PMDynaform.extendNamespace("PMDynaform.model.Signature_mobile", Signature_mobile);
}());
