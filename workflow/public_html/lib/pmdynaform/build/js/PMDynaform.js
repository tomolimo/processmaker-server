"use strict";
/**
 * @class PMDynaform
 * Base class PMDynaform
 * @singleton
 */
var PMDynaform = {
  VERSION: "0.1.0",
  view: {},
  model:{},
  collection:{},
  Extension: {}, 
  restData:{}
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
		}
	};
	PMDynaform.extendNamespace("PMDynaform.core.Utils", Utils);
	
}());
(function(){
    var Mask = function (el, mask, options) {
        var jMask = this, old_value, regexMask;
        el = $(el);

        mask = typeof mask === "function" ? mask(el.val(), undefined, el,  options) : mask;

        var p = {
            getCaret: function () {
                try {
                    var sel,
                        pos = 0,
                        ctrl = el.get(0),
                        dSel = document.selection,
                        cSelStart = ctrl.selectionStart;

                    // IE Support
                    if (dSel && !~navigator.appVersion.indexOf("MSIE 10")) {
                        sel = dSel.createRange();
                        sel.moveStart('character', el.is("input") ? -el.val().length : -el.text().length);
                        pos = sel.text.length;
                    }
                    // Firefox support
                    else if (cSelStart || cSelStart === '0') {
                        pos = cSelStart;
                    }
                    
                    return pos;    
                } catch (e) {}
            },
            setCaret: function(pos) {
                try {
                    if (el.is(":focus")) {
                        var range, ctrl = el.get(0);

                        if (ctrl.setSelectionRange) {
                            ctrl.setSelectionRange(pos,pos);
                        } else if (ctrl.createTextRange) {
                            range = ctrl.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', pos);
                            range.moveStart('character', pos);
                            range.select();
                        }
                    }
                } catch (e) {}
            },
            events: function() {
                el
                .on('keydown.mask', function() {
                    old_value = p.val();
                })
                .on('keyup.mask', p.behaviour)
                .on("paste.mask drop.mask", function() {
                    setTimeout(function() {
                        el.keydown().keyup();
                    }, 100);
                })
                .on("change.mask", function() {
                    el.data("changed", true);
                })
                .on("blur.mask", function(){
                    if (old_value !== el.val() && !el.data("changed")) {
                        el.trigger("change");
                    }
                    el.data("changed", false);
                })
                // clear the value if it not complete the mask
                .on("focusout.mask", function() {
                    if (options.clearIfNotMatch && !regexMask.test(p.val())) {
                       p.val('');
                   }
                });
            },
            getRegexMask: function() {
                var maskChunks = [], translation, pattern, optional, recursive, oRecursive, r;

                for (var i = 0; i < mask.length; i++) {
                    translation = jMask.translation[mask[i]];

                    if (translation) {
                        
                        pattern = translation.pattern.toString().replace(/.{1}$|^.{1}/g, "");
                        optional = translation.optional;
                        recursive = translation.recursive;
                        
                        if (recursive) {
                            maskChunks.push(mask[i]);
                            oRecursive = {digit: mask[i], pattern: pattern};
                        } else {
                            maskChunks.push(!optional && !recursive ? pattern : (pattern + "?"));
                        }

                    } else {
                        maskChunks.push("\\" + mask[i]);
                    }
                }
                
                r = maskChunks.join("");
                
                if (oRecursive) {
                    r = r.replace(new RegExp("(" + oRecursive.digit + "(.*" + oRecursive.digit + ")?)"), "($1)?")
                         .replace(new RegExp(oRecursive.digit, "g"), oRecursive.pattern);
                }

                return new RegExp(r);
            },
            destroyEvents: function() {
                el.off(['keydown', 'keyup', 'paste', 'drop', 'change', 'blur', 'focusout', 'DOMNodeInserted', ''].join('.mask '))
                .removeData("changeCalled");
            },
            val: function(v) {
                var isInput = el.is('input');
                return arguments.length > 0 
                    ? (isInput ? el.val(v) : el.text(v)) 
                    : (isInput ? el.val() : el.text());
            },
            getMCharsBeforeCount: function(index, onCleanVal) {
                for (var count = 0, i = 0, maskL = mask.length; i < maskL && i < index; i++) {
                    if (!jMask.translation[mask.charAt(i)]) {
                        index = onCleanVal ? index + 1 : index;
                        count++;
                    }
                }
                return count;
            },
            caretPos: function (originalCaretPos, oldLength, newLength, maskDif) {
                var translation = jMask.translation[mask.charAt(Math.min(originalCaretPos - 1, mask.length - 1))];

                return !translation ? p.caretPos(originalCaretPos + 1, oldLength, newLength, maskDif)
                                    : Math.min(originalCaretPos + newLength - oldLength - maskDif, newLength);
            },
            behaviour: function(e) {
                e = e || window.event;
                var keyCode = e.keyCode || e.which;

                if ($.inArray(keyCode, jMask.byPassKeys) === -1) {

                    var caretPos = p.getCaret(),
                        currVal = p.val(),
                        currValL = currVal.length,
                        changeCaret = caretPos < currValL,
                        newVal = p.getMasked(),
                        newValL = newVal.length,
                        maskDif = p.getMCharsBeforeCount(newValL - 1) - p.getMCharsBeforeCount(currValL - 1);
                   
                    if (newVal !== currVal) {
                        p.val(newVal);
                    }

                    // change caret but avoid CTRL+A
                    if (changeCaret && !(keyCode === 65 && e.ctrlKey)) {
                        // Avoid adjusting caret on backspace or delete
                        if (!(keyCode === 8 || keyCode === 46)) {
                            caretPos = p.caretPos(caretPos, currValL, newValL, maskDif);
                        }
                        p.setCaret(caretPos);
                    }

                    return p.callbacks(e);
                }
            },
            getMasked: function (skipMaskChars) {
                var buf = [],
                    value = p.val(),
                    m = 0, maskLen = mask.length,
                    v = 0, valLen = value.length,
                    offset = 1, addMethod = "push",
                    resetPos = -1,
                    lastMaskChar,
                    check;

                if (options.reverse) {
                    addMethod = "unshift";
                    offset = -1;
                    lastMaskChar = 0;
                    m = maskLen - 1;
                    v = valLen - 1;
                    check = function () {
                        return m > -1 && v > -1;
                    };
                } else {
                    lastMaskChar = maskLen - 1;
                    check = function () {
                        return m < maskLen && v < valLen;
                    };
                }

                while (check()) {
                    var maskDigit = mask.charAt(m),
                        valDigit = value.charAt(v),
                        translation = jMask.translation[maskDigit];

                    if (translation) {
                        if (valDigit.match(translation.pattern)) {
                            buf[addMethod](valDigit);
                             if (translation.recursive) {
                                if (resetPos === -1) {
                                    resetPos = m;
                                } else if (m === lastMaskChar) {
                                    m = resetPos - offset;
                                }

                                if (lastMaskChar === resetPos) {
                                    m -= offset;
                                }
                            }
                            m += offset;
                        } else if (translation.optional) {
                            m += offset;
                            v -= offset;
                        }
                        v += offset;
                    } else {
                        if (!skipMaskChars) {
                            buf[addMethod](maskDigit);
                        }
                        
                        if (valDigit === maskDigit) {
                            v += offset;
                        }

                        m += offset;
                    }
                }
                
                var lastMaskCharDigit = mask.charAt(lastMaskChar);
                if (maskLen === valLen + 1 && !jMask.translation[lastMaskCharDigit]) {
                    buf.push(lastMaskCharDigit);
                }
                
                return buf.join("");
            },
            callbacks: function (e) {
                var val = p.val(),
                    changed = val !== old_value;
                if (changed === true) {
                    if (typeof options.onChange === "function") {
                        options.onChange(val, e, el, options);
                    }
                }

                if (changed === true && typeof options.onKeyPress === "function") {
                    options.onKeyPress(val, e, el, options);
                }

                if (typeof options.onComplete === "function" && val.length === mask.length) {
                    options.onComplete(val, e, el, options);
                }
            }
        };


        // public methods
        jMask.remove = function() {
            var caret;
            p.destroyEvents();
            p.val(jMask.getCleanVal()).removeAttr('maxlength');
            
            caret = p.getCaret();
            p.setCaret(caret - p.getMCharsBeforeCount(caret));
        };

        // get value without mask
        jMask.getCleanVal = function() {
           return p.getMasked(true);
        };

       jMask.init = function() {
            options = options || {};

            jMask.byPassKeys = [9, 16, 17, 18, 36, 37, 38, 39, 40, 91];
            jMask.translation = {
                //'0': {pattern: /\d/},
                //'9': {pattern: /\d/, optional: true},
                '#': {pattern: /\d/, recursive: true},
                'A': {pattern: /[a-zA-Z0-9]/},
                'S': {pattern: /[a-zA-Z]/}
            };

            jMask.translation = $.extend({}, jMask.translation, options.translation);
            jMask = $.extend(true, {}, jMask, options);

            regexMask = p.getRegexMask();

            if (options.maxlength !== false) {
                el.attr('maxlength', mask.length);
            }

            if (options.placeholder) {
                el.attr('placeholder' , options.placeholder);
            }
            
            el.attr('autocomplete', 'off');
            p.destroyEvents();
            p.events();
            
            var caret = p.getCaret();

            p.val(p.getMasked());
            p.setCaret(caret + p.getMCharsBeforeCount(caret, true));
            
        }();

    };

    var watchers = {},
        live = 'DOMNodeInserted.mask',
        HTMLAttributes = function () {
            var input = $(this),
                options = {},
                prefix = "data-mask-";

            if (input.attr(prefix + 'reverse')) {
                options.reverse = true;
            }

            if (input.attr(prefix + 'maxlength') === 'false') {
                options.maxlength = false;
            }

            if (input.attr(prefix + 'clearifnotmatch')) {
                options.clearIfNotMatch = true;
            }

            input.mask(input.attr('data-mask'), options);
        };

    $.fn.mask = function(mask, options) {
        var selector = this.selector,
            maskFunction = function(e) {
                if (!e.originalEvent || !($(e.originalEvent.relatedNode)[0] === $(this)[0])) {
                    return $(this).data('mask', new Mask(this, mask, options));    
                }
                
            };
        
        this.each(maskFunction);

        if (selector && !watchers[selector]) {
            // dynamically added elements.
            watchers[selector] = true;
            setTimeout(function(){
                $(document).on(live, selector, maskFunction);
            }, 500);
        }
    };

    $.fn.unmask = function() {
        try {
            return this.each(function() {
                $(this).data('mask').remove();
            });
        } catch(e) {};
    };

    $.fn.cleanVal = function() {
        return this.data('mask').getCleanVal();
    };

    // looking for inputs with data-mask attribute
    $('*[data-mask]').each(HTMLAttributes);

    // dynamically added elements with data-mask html notation.
    $(document).on(live, '*[data-mask]', HTMLAttributes);
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
        /*
         * 
         * @type type
         */
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
        /*
         * 
         * @type type
         */
        email: {
            message: "Invalid value for field email",
            fn: function(value) {
                if (!(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value))) {
                    return false;
                }
                return true;
            }
        },
        /*
         * 
         * @type type
         */
        date: {
            message: "",
            format: "",
            fn: function(ano, mes, dia) {
                var date = new Date(ano, mes, dia);
                if (!isNaN(date)) {
                    return false;
                }
                return true;
            }
        },
        /*
         * 
         * @type type
         */
        password: {
            message: "",
            fn: function(field) {
                if (!/^(?=.*\d)(?=.*[a-z])\w{8,}/i.test(field.value)) {
                    return false;
                }
                return true;
            }
        },
        mask: {
            fn: function(value, mask) {
                
            }
        },
        domain: {
            message: "The value is not valid for the options domain",
            fn: function(value, options) {
                var i, 
                validated = false;

                for (i=0; i<options.length; i+=1) {
                    if (options[i].value.toString() === value.toString()) {
                        validated = true;
                    }
                }

                return validated;
            }
        }
    };
    
    PMDynaform.extendNamespace("PMDynaform.core.Validators", Validators);
}());

(function(){
	var Project = function(options) {
		this.model = null;
		this.view = null;
		this.data = null;
		this.fields = null;
		this.keys = null;
		this.token = null;
		this.renderTo = null;
		this.urlFormat = null;
		this.endPointsPath = null;
		this.forms = null;
		this.dependentLibraries = null;

		Project.prototype.init.call(this, options);
	};

	Project.prototype.init = function(options) {
		var defaults = {
			data: {},
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
				uploadFile: "uploadfile/{field_name}"
			},
			renderTo: document.body
		};
		jQuery.extend(true, defaults, options);
		this.setData(defaults.data)
			.setUrlFormat(defaults.urlFormat)
			.setKeys(defaults.keys)
			.setToken(defaults.token)
			.setRenderTo(defaults.renderTo)
			.setEndPointsPath(defaults.endPointsPath)
			.checkDependenciesLibraries();
		//this.loadProject();
	};
	Project.prototype.setData = function(data) {
		if (typeof data === "object") {
			this.data = data;
		}
		if (this.view) {
			this.destroy();
			this.loadProject();
		}
		
		return this;
	};
	Project.prototype.setUrlFormat = function(url) {
		if (typeof url === "string") {
			this.urlFormat = url;
		}

		return this;
	};
	Project.prototype.setKeys = function (keys) {
		var keysFixed = {},
			key,
			leftBracket;

		if (typeof keys === "object") {
			for (key in keys) {
				leftBracket = (keys[key][0]==="/")? keys[key].substring(1) : keys[key];
            	keysFixed[key] = (leftBracket[leftBracket.length-1]==="/")? leftBracket.substring(0, leftBracket.length-1) : leftBracket;
			}
			keysFixed.server = keysFixed.server.replace(/\https:\/\//,"").replace(/\http:\/\//,"");
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
	            leftBracket = (endpoints[point][0]==="/")? endpoints[point].substring(1) : endpoints[point];
	            endpointsVerified[point] = (endpoints[point][endpoints[point].length-1]==="/")? 
	            							endpoints[point].substring(0, endpoints[point].length-1) : 
	            							endpoints[point];
	        } else {
	        	throw new Error("The endpoint path is not correct, " + endpoints[point]);
	        }
		}
		this.endPointsPath = endpointsVerified;

		return this;
	};
	Project.prototype.checkDependenciesLibraries = function () {
		var i,
		libs = [],
		enableGeoMap =  false,
		searchingMap,
		forms = this.data.items;

		searchingMap = function (fields) {
			var j,
			k,
			l,
			dependent = [
				"geomap",
				"other"
			];

			outer_loop:
			for (j=0; j<fields.length; j+=1) {
				for (k=0; k<fields[j].length; k+=1) {
					if ($.inArray(fields[j][k].type, dependent) >= 0) {
						enableGeoMap = true;
						break outer_loop;
					} else if (fields[j][k].type === "subform") {
						searchingMap(fields[j][k].items);
					}
				}
			}
		};
		
		for (i=0; i<forms.length; i+=1) {
			searchingMap(forms[i].items);
		}

		if (enableGeoMap) {
			this.loadGeoMapDependencies();
		} else {
			this.loadProject();
		}

		return this;
	};
	Project.prototype.checkScript = function () {
		var i,
		code;

		for (i=0; i<this.forms.length; i+=1) {
			if (!_.isEmpty(this.forms[i].model.get("script"))) {
	            code = new PMDynaform.core.Script({
	                script: this.forms[i].model.get("script").code
	            });
	            code.render();
	        }
		}
		
	};
	Project.prototype.setAllFields = function (fields) {
		if (typeof fields === "object") {
			this.fields = fields;
			this.selector.setFields(fields);
		}

		return this;
	};
	Project.prototype.loadProject = function() {
		var that = this;

		this.model = new PMDynaform.model.Panel(this.data);
		this.view = new PMDynaform.view.Panel({
			tagName: "div",
			renderTo: this.renderTo,
			model: this.model,
			project: this
		});
		this.forms = this.view.getPanels();
		this.createGlobalPmdynaformClass(this.view);
		this.createSelectors();
		this.checkScript();
		this.createMessageLoading();

		that.view.afterRender();
		that.view.$el.find(".pmdynaform-form-message-loading").remove();
		$("#shadow-form").remove();

		return this;
	};
	Project.prototype.createMessageLoading = function () {
		var msgTpl = _.template($('#tpl-loading').html());
		this.view.$el.prepend(msgTpl({
			title : "Loading",
			msg: "Please wait while the data is loading..."
		}));
		this.view.$el.find("#shadow-form").css("height",this.view.$el.height()+"px");
	};
	Project.prototype.createSelectors = function () {
		var i,
		eachForm,
		fields = [];

		eachForm = function (items) {
			var jFields;

			for (jFields=0; jFields<items.length; jFields+=1) {
				if (items[jFields].model.get("type") === "subform") {
					eachForm(items[jFields].formView.getFields());
				} else {
					fields.push(items[jFields]);
				}
			}
		};
		
		//Each Form
		for (i=0; i<this.forms.length; i+=1) {
			eachForm(this.forms[i].getFields());
		}

		this.fields = fields;
		this.selector = new PMDynaform.core.Selector({
			fields: fields
		});
		
		return this;
	};
	Project.prototype.createGlobalPmdynaformClass = function (form) {
		var pmdynaform = function (classForm) {
			this.form = classForm;
		};
		pmdynaform.prototype.getForms = function () {
			return this.form.getPanels();
		};
		window.pmdynaform = new pmdynaform(this.view);

		return this;
	};
	Project.prototype.loadGeoMapDependencies = function () {
		var i,
		auxClass,
		instanceClass,
		that = this,
		loadScript = true,
		libs = "";

		libs = document.body.getElementsByTagName("script");
		outer_script:
		for (i=0; i< libs.length; i+=1) {
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
				this.project.loadProject();
			};
			window.pmd = new auxClass({project:this});
			var script = document.createElement('script');
			script.type = 'text/javascript';
			$(script).data("script", "google");;
			script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&callback=pmd.load';

			document.body.appendChild(script);	
		} else {
			this.loadProject();
		}
		

		return this;
	};
	Project.prototype.registerKey = function (key, value) {
		if ((typeof key === "string") && (typeof value === "string")) {
			if (!this.keys[key]) {
				this.keys[key] = value;
			} else {
				throw new Error ("The key already exists.");
			}
		} else {
			throw new Error ("The parameters must be strings.");
		}
		
		return this;
	};	
	Project.prototype.getEndPoint = function (type) {
		return this.endPointsPath[type];
	};
	Project.prototype.setModel = function(model) {
		if (model instanceof Backbone.Model) {
			this.model = model;
		}
		return this;
	};
	Project.prototype.setView = function(view) {
		if (view instanceof Backbone.View) {
			this.view = view;
		}
		return this;
	};
	Project.prototype.getFullURL = function (endpoint) {
		var k, 
		keys  = this.keys,
		urlFormat = this.urlFormat;

		for (k in keys) {
			if (keys.hasOwnProperty(k)) {
				urlFormat = urlFormat.replace(new RegExp("{"+ k +"}" ,"g"), keys[k]);
				//endPointFixed =endpoint.replace(new RegExp(variable, "g"), keys[variable]);	
			}
		}
		urlFormat = window.location.protocol + "//"+ urlFormat.replace(/{endPointPath}/, endpoint);
		
		return urlFormat;
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
		this.tokens[name] = expr;
	};
	/**
     * Sets the value for the field selected
     * @param {String} name Corresponds to name of the field
     * @param {String||Number} value Value for the field
     * @private
     */
	Tokenizer.prototype.addTokenValue = function (name, value) {
		this.tokenFields[name] = (!parseFloat(value))? 0 : parseFloat(value);
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

        e = expr.join('');
        
        try {
        	valueFixed = (new Function('return ' + e))();
        } catch(e) {
        	throw new Error("Error in the formula property")
        	valueFixed = 0;
        }
        return valueFixed;
    };


    PMDynaform.extendNamespace("PMDynaform.core.Formula", Formula);
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
            options: [
            	field.defaultValue || field.value
            ]
		};
	};
	TransformJSON.prototype.textArea = function (field) {
		return {
			type: "label",
            colSpan: field.colSpan,
            label: field.label,
            options: [
            	field.defaultValue || field.value
            ]
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
            options: validOpt
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
            options: validOpt
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
            options: validOpt
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
            options: [
            	field.defaultValue || field.value
            ]
		};
	};
	TransformJSON.prototype.suggest = function (field) {
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
            options: validOpt
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
            "label"
        ];

        return ($.inArray(type, disabled) < 0) ? true : false;
    };
	TransformJSON.prototype.reviewField = function (field) {
		var jsonBuilt = field;

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

		Selector.prototype.init.call(this, options);
	};
	Selector.prototype.init = function (options) {
		var defaults = {
			fields: [],
			queries: [],
			onSupportSelectorFields: {
				text: "onTextField",
				textarea: "onTextAreaField"
			}
		};

		$.extend(true, defaults, options);
		
		this.setOnSupportSelectorFields(defaults.onSupportSelectorFields)
			.setFields(defaults.fields)
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
	Selector.prototype.onTextField = function (selector) {
		console.log("selector text field", selector);

		return this;
	};
	Selector.prototype.onTextAreaField = function (selector) {
		console.log("selector textarea field", selector);
		
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

		return this;
	};

	PMDynaform.extendNamespace("PMDynaform.core.Selector", Selector);
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
		$(html).text(this.script);
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
(function(){
    var PanelView = Backbone.View.extend({
        content : null,
        colsIndex: null,
        template: null,
        collection: null,
        items: null, 
        views: [],
        renderTo: document.body,
        project: null,
        initialize: function(options) {
            var defaults = {
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
            if(options.project) {
                this.project = options.project;
            }
            this.views = [];

            this.makePanels();
            this.render();

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
            for (i = 0; i < panels.length; i+=1) {
                fields = this.views[i].items.asArray();
                for (k = 0; k < fields.length; k+=1) {
                    if ((typeof fields[k].getData === "function") && 
                        (fields[k] instanceof PMDynaform.view.Field)) {
                        field = fields[k].getData();
                        formData.variables[field.name] = field.value;
                    } else if ((typeof fields[k].getData === "function") && 
                        (fields[k] instanceof PMDynaform.view.SubForm)) {
                        subform = fields[k].getData();
                        $.extend(true, formData.variables, subform.variables);
                    }
                }
            }

            return formData;
        },
        makePanels: function() {  
            var i = 0,
            items,
            panelmodel,
            view;

            this.views = [];
            items = this.model.get("items");

            for(i=0; i<items.length; i+=1){
                if ($.inArray(items[i].type, ["panel","form"]) >= 0) {

                    panelmodel = new PMDynaform.model.FormPanel(items[i]);
                    view = new PMDynaform.view.FormPanel({
                        model: panelmodel,
                        project: this.project
                    });
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
            j;

            this.$el = $(this.el);
            for(i=0; i<this.views.length; i+=1){
                this.$el.append(this.views[i].render().el);
            }
            this.$el.addClass("pmdynaform-container");
            $(this.renderTo).append(this.el);

            return this;
        },
        afterRender: function () {
            var i;

            for(i=0; i<this.views.length; i+=1) {
                this.views[i].afterRender();
            }

            return this;
        }
    });
    PMDynaform.extendNamespace("PMDynaform.view.Panel", PanelView);
    
}());

(function(){
    var FormPanel = Backbone.View.extend({
        tagName: "form",
        content : null,    
        template: null,
        items: new PMDynaform.util.ArrayList(),
        views:[],
        templateRow: _.template($('#tpl-row').html()),
        colSpanLabel: 3,
        colSpanControl: 9,
        project: null,
        events: {
            'submit': 'onSubmit'
        },
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
        ],
        initialize: function(options) {
            var defaults = {
                factory : {
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
                            model: PMDynaform.model.Checkbox,
                            view: PMDynaform.view.Checkbox
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
                        "file": {
                            model: PMDynaform.model.File,
                            view: PMDynaform.view.File
                        },
                        "image": {
                            model: PMDynaform.model.Image,
                            view: PMDynaform.view.Image
                        },
                        "geomap": {
                            model: PMDynaform.model.GeoMap,
                            view: PMDynaform.view.GeoMap
                        },
                        "grid": {
                            model: PMDynaform.model.GridPanel,
                            view: PMDynaform.view.GridPanel
                        },
                        "subform": {
                            model: PMDynaform.model.SubForm,
                            view: PMDynaform.view.SubForm
                        },
                        "annotation": {
                            model: PMDynaform.model.Annotation,
                            view: PMDynaform.view.Annotation  
                        }
                    },
                    defaultProduct: "empty"
                }       
            };
            this.items = new PMDynaform.util.ArrayList();
            if(options.project) {
                this.project = options.project;
            }
            this.setFactory(defaults.factory);
            this.makeItems();
            this.setFieldRelated();
        },
        setAction: function() {
            this.$el.attr("action", this.model.get("action"));

            return this;
        },
        setMethod: function() {
            this.$el.attr("method", this.model.get("method"));

            return this;
        },
        setFactory: function (factory) {
            this.factory = factory;
            return this;
        },
        getData: function() {
            return this.model.getData();
        },
        setData: function (data) {
            var i,
            j,
            cloneData = data,
            items = this.items.asArray();
            if (typeof data === "object") {
                for (i=0; i<items.length; i+=1) {
                    for (j in cloneData) {
                        if (items[i].model.attributes.variable) {
                            if (items[i].model.attributes.variable.var_name === j) {
                                items[i].model.set("value", cloneData[j]);
                            }
                        }
                    }
                    if (items[i] instanceof PMDynaform.view.SubForm) {
                        items[i].setData(data);
                    }

                    if (items[i] instanceof PMDynaform.view.GridPanel) {
                        items[i].setData(data);
                        //Nothing
                    }
                }
            } else {
                console.log("Error, The 'data' parameter is not valid. Must be an array.");
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
        makeItems: function() {
            var i,
            j,
            factory = this.factory, 
            product, 
            variableEnabled,
            productBuilt, 
            rowView,
            productModel,
            jsonFixed,
            fieldModel,
            fields;
            
            fields =  this.model.get("items");
            this.viewsBuilt = [];
            this.items.clear();

            for(i=0; i<fields.length; i+=1) {
                rowView = [];
                for(j=0; j<fields[i].length; j+=1) {
                    variableEnabled = this.validateVariableField(fields[i][j]);
                    if (fields[i][j] !== null && (variableEnabled === true || variableEnabled === "NOT") ) {
                        if (fields[i][j].type) {
                            jsonFixed  = new PMDynaform.core.TransformJSON({
                                parentMode: this.model.get("mode"),
                                field: fields[i][j]
                            });
                            product =   factory.products[jsonFixed.getJSON().type.toLowerCase()] ? 
                                factory.products[jsonFixed.getJSON().type.toLowerCase()] : factory.products[factory.defaultProduct];
                        } else {
                            product = factory.products[factory.defaultProduct];
                        }
                        
                        //The number 12 is related to 12 columns from Bootstrap framework
                        fieldModel = {
                            colSpanLabel: fields[i].length*2,
                            colSpanControl: 12 - fields[i].length*2,
                            project: this.project,
                            parentMode: this.model.get("mode"),
                            namespace: this.model.get("namespace"),
                            variable: (variableEnabled !== "NOT")? this.getVariable(fields[i][j].var_uid) : null,
                            fieldsRelated: []
                        };
                        if (fields[i][j].type === "subform" || fields[i][j].type === "grid") {
                            fieldModel.variables = this.model.get("variables") || [];
                            fieldModel.data = this.model.get("data") || [];
                        }
                        
                        $.extend(true, fieldModel, jsonFixed.getJSON());
                        
                        productModel = new product.model(fieldModel);
                        productBuilt = new product.view({
                            model: productModel,
                            project:this.project,
                            parent: this
                        });

                        productBuilt.parent = this;
                        productBuilt.project = this.project;
                        rowView.push(productBuilt);
                        this.items.insert(productBuilt);
                        productBuilt.model.set("view", productBuilt);
                    } else {
                        console.error ("The field must have the variable property and must to be an object: ", fields[i][j]);
                    }
                }

                if (rowView.length) {
                    this.viewsBuilt.push(rowView);
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

            for (i=0; i<fields.length; i+=1) {
                fieldA = fields[i].model.get("variable");
                if (fieldA) {
                    for (j=0; j<fields.length; j+=1) {
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

                if (fields[i].model.get("type") === "subform") {
                    fieldsSubForm = fields[i].getItems();
                    for (k=0; k<fields.length; k+=1) {
                        fieldA = fields[k].model.get("variable");
                        if (fieldA) {
                            for (l=0; l<fieldsSubForm.length; l+=1) {
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
            for (i=0; i<variables.length; i+=1) {
                if (variables[i].var_uid === var_uid) {
                    varSelected = variables[i];
                    break loop_variables;
                }
            }
            return varSelected;
        },
        getFields: function () {
            return (this.items.getSize() > 0)? this.items.asArray(): [];
        },
        beforeRender: function (){
            return this;
        },
        disableContextMenu: function() {
            this.$el.on("contextmenu", function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            return this;
        },
        onSubmit: function(event) {
            var booResponse, i, restData, restClient;

            if (!this.isValid(event)) {
                booResponse =  false;
            } else {
                for (i=0; i<this.items.length; i+=1) {
                    if(this.items[i].applyStyleSuccess) {
                        this.items[i].applyStyleSuccess();
                    }
                }
                booResponse =  true;
            }            
            if(booResponse){
                if(this.project.submitRest){
                    event.preventDefault();
                    restData= this.project.getData();                  
                    this.project.executeSubmit(restData);
                }
            }
            return booResponse;
        },
        isValid: function(event) {
            var i, formValid = true,
            itemsField = this.items.asArray();

            if (itemsField.length > 0) {
                for (i = 0; i < itemsField.length; i+=1) {
                    if(itemsField[i].validate) {
                        itemsField[i].validate(event);
                        if (!itemsField[i].model.get("valid")) {
                            formValid = itemsField[i].model.get("valid");    
                        }
                    }
                }
            }
            return formValid;
        },
        render : function (){
            var i,j, $rowView;
            for(i=0; i<this.viewsBuilt.length; i+=1){
                $rowView = $(this.templateRow());
                for(j=0; j<this.viewsBuilt[i].length; j+=1){
                    $rowView.append(this.viewsBuilt[i][j].render().el);
                }
                this.$el.attr("role","form");
                this.$el.addClass("form-horizontal pmdynaform-form");
                
                this.setAction();
                this.setMethod();
                if (this.model.get("target")) {
                    this.$el.attr("target", this.model.get("target"));
                }
                
                this.$el.append($rowView);
            }
            this.disableContextMenu();
          return this;
        },
        afterRender: function () {
            var i,
            j,
            items = this.items.asArray();;

            for (i=0; i<items.length; i+=1) {
                if (items[i].afterRender) {
                    items[i].afterRender();
                }
            }
            /*for(i=0; i<this.viewsBuilt.length; i+=1){
                for(j=0; j<this.viewsBuilt[i].length; j+=1){
                    if (this.viewsBuilt[i][j].afterRender) {
                        this.viewsBuilt[i][j].afterRender();
                    }
                }
            }*/

            if (this.model.attributes.data) {
                this.setData(this.model.get("data"));
            }
            
            
            return this;
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.FormPanel", FormPanel);
    
}());

(function(){
	var FieldView = Backbone.View.extend({
		tagName: "div",

		initialize: function (options) {
			if(options.project) {
                this.project= options.project;
            }

			this.setClassName()
				.render();
		},
		setClassName: function() {
			//this.$el.addClass(this.model.get("container").style.cssClasses.toString().replace(/,/g," "));			
			return this;
		},
		getData: function() {
			if( this.updateValueControl) {
				this.updateValueControl();
			}

            return this.model.getData();
        },
        enableTooltip: function(){
        	this.$el.find("[data-toggle=tooltip]").tooltip().click(function(e) {
                $(this).tooltip('toggle');
            });
        	return this;
        },
        applyStyleError: function () {
            this.$el.addClass("has-error has-feedback");
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
                
                if(!this.model.isValid()){    
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
        		control.on(e, function(event){
	        		fn(event, that);

	        		event.stopPropagation();
	        	});
        	} else {
        		throw new Error ("Is not possible find the HTMLElement associated to field");
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
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			if (this.model.get("hint")) {
				this.enableTooltip();
			}
            this.setValueToDomain();
			return this;
		}
	});	

	PMDynaform.extendNamespace("PMDynaform.view.Field",FieldView);
}());

(function(){
	var GridView = PMDynaform.view.Field.extend({
		block: true,
		template: _.template( $("#tpl-grid").html()),
		templatePager: _.template( $("#tpl-grid-pagination").html() ),
		templateTotal: _.template( $("#tpl-grid-totalcolumn").html() ),
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
			dropdown: 6,
			text: 7,
			button: 8,
			link: 9,
			default: 0
		},
        titleHeader: [],
        thereArePriority: 0,
        onRemoveRowCallback: function(){},
        onAddRowCallback: function(){},
        onClickPageCallback: function(){},
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
        factory : {},
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
	                    model: PMDynaform.model.Checkbox,
	                    view: PMDynaform.view.Checkbox
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
                    }
	            },
	            defaultProduct: "text"
	        },
	        k,
	        rows = parseInt(this.model.get("rows"), 10);

	        this.items = [];
	        this.row = [];
	        this.dom = [];
	        this.cols = [];
	        this.showPage = 1;
			this.gridtable = [];
			this.titleHeader = [];
			this.checkColSpanResponsive();
			this.setFactory(factory);
			this.buildColumns({
				executeInit: true
			});
			for (k=0; k<rows; k+=1) {
				this.addRow();
			}
			this.model.attributes.titleHeader = this.titleHeader;
			
		},
		buildColumns: function () {
			var row;
			row = this.makeColumns({
            	executeInit: true
            });
            this.model.attributes.dataColumns = row.model;
            //this.model.attributes.totalRow = row.data;
            this.items = [];
            this.model.attributes.gridFunctions = [];
			return this;
		},
		onClickNew: function () {
			var currentRows = this.model.get("rows"),
			newItem;

			this.block = true;
			//this.model.set("rows", parseInt(currentRows + 1, 10));
			this.model.attributes.rows = parseInt(currentRows + 1, 10);
			newItem = this.addRow();
			this.renderGridTable();
			//Calling to callBack associated
			this.onAddRowCallback(newItem, this);

			return this;
		},
		makeTitleHeader: function (columns) {
			var j,
			nroLabel = "Nro";
			
			this.titleHeader.push(nroLabel);
			for (j=0; j<columns.length; j+=1) {
				this.titleHeader.push(columns[j].title);
			}

			return this;
		},
		setTitleHeader: function (titles) {
			var j;
			
			for (j = 0; j < titles.length; j+=1) {
				this.titleHeader.push(titles[j]);
			}

			return this;
		},
		verifyPageNumber: function () {
			var i,
			rows = this.model.get("rows"),
			size = this.model.get("pageSize"),
			pagerItems,
			currentPage = this.showPage,
			children = this.$el.find(".pmdynaform-grid-tbody").children();

			if (children.length > 0) {
				for (i=0; i<children.length; i+=1) {
					if (children[i].className.indexOf("active") > 0) {
			 			currentPage = i+1;
						break;
					}
				}
			}

			pagerItems = Math.ceil(rows/size) ? Math.ceil(rows/size) : 1;
			if (currentPage > pagerItems) {
				currentPage-=1;
			}
			this.showPage = currentPage;

			return this;
		},
		addRow: function () {
			var i,
			size = this.gridtable.length,
            row,
            product;

            row = this.makeColumns({
            	executeInit: false
            });

            this.model.attributes.gridFunctions.push(row.data);
            this.gridtable.push(row.view);

			return row;
		},
		removeRow: function (row) {
			var currentRows = this.model.get("rows"),
			itemRemoved;

			itemRemoved = this.gridtable.splice(row, 1);
			this.dom.splice(row, 1);
			this.model.attributes.rows = parseInt(currentRows - 1, 10);
			
			return itemRemoved;
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
		makeColumns: function (properties) {
			var that = this,
			columns = this.model.get("columns"),
			dataColumns = this.model.get("dataColumns"),
			data = this.model.get("data"),
			columnModel,
			suc,
			rowData = [],
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
            size = this.gridtable.length,
            i,
            parentItems = new PMDynaform.util.ArrayList();

            for (i = 0; i < columns.length; i+=1) {
            	newNameField = "";
            	mergeModel = properties.executeInit ? columns[i] : dataColumns[i].toJSON();
            	mergeModel.name = properties.executeInit ? mergeModel.name : mergeModel._extended.name;
            	mergeModel.formula = properties.executeInit ? mergeModel.formula : mergeModel._extended.formula;
            	mergeModel.dependentFields = properties.executeInit ? mergeModel.dependentFields : mergeModel._extended.dependentFields;
            	
            	
            	mergeModel.mode = this.model.get("mode");
            	mergeModel.executeInit = properties.executeInit;

            	jsonFixed  = new PMDynaform.core.TransformJSON({
                    parentMode: this.model.get("parentMode"),
                    field: mergeModel
                });
	            if (jsonFixed.getJSON().type) {
	                product =   factory.products[jsonFixed.getJSON().type.toLowerCase()] ? 
	                    factory.products[jsonFixed.getJSON().type.toLowerCase()] : factory.products[factory.defaultProduct];
	            } else {
	                product = factory.products[factory.defaultProduct];
	            }
	            /**
            	 * The executeInit property is for enable or disable the execution 
            	 * of the query associated to variable (field)
            	 **/
            	colSpanControl = this.colSpanControlField(columns, jsonFixed.getJSON().type, i);

            	/**
            	 * The current method is for check if the controls needs the variable parameter
            	 * for execute its model
            	 */
            	variableEnabled = this.validateVariableField(mergeModel);
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
	                group: "grid"
	            };
            	jQuery.extend(true, columnModel, jsonFixed.getJSON());
            	columnModel.row = this.gridtable.length;
            	columnModel.col = i;
				productModel = new product.model(columnModel);
				
				//Step for change the name to field
				newNameField = this.changeNameField(productModel.get("_extended").name, this.gridtable.length+1, i+1);
				newIdField = this.changeNameField(productModel.get("_extended").id, this.gridtable.length+1, i+1);
				productModel.attributes.name = newNameField;
				productModel.attributes.id = newIdField;
				
				rowModel.push(productModel);
				
	            productBuilt = new product.view({
	                model: productModel,
	            	project: this.project,
	            	parent: this
	            });
	            //Adding CallBack to TextField when the field is part of a column that has a function enabled
				if (productBuilt.model.get("function")) {
					productBuilt.on("changeValues", function(){
						that.setValuesGridFunctions({
							row: this.model.attributes.row,
							col: this.model.attributes.col,
							data: this.model.attributes.value
						});
						that.createHTMLTotal();
					});
				}

	            parentItems.insert(productBuilt);
            	rowView.push(productBuilt);

            	if(!properties.executeInit) {
            		this.items.push(productBuilt);
            		rowData.push(0);
            	}
            }

            for (suc=0; suc<rowView.length; suc+=1) {
            	rowView[suc].parent = {
            		items: parentItems,
            		parent: this
            	};
            	
            }
            
        	this.updateNameFields(rowView);

			return {
				model: rowModel,
				view: rowView,
				data: rowData
			};
		},
		setValuesGridFunctions: function (field) {

			if (this.model.attributes.functions) {
				this.model.attributes.gridFunctions[field.row][field.col] = isNaN(parseFloat(field.data))? 0: parseFloat(field.data);
				this.model.applyFunction();
			}
			
			return this;
		},
		getVariable: function (var_uid) {
            var i,
            varSelected,
            variables = this.model.attributes.variables;

            loop_variables:
            for (i=0; i<variables.length; i+=1) {
                if (variables[i].var_uid === var_uid) {
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
				this.numberRest = 10%columns.length;

				if (this.numberRest > 0) {
					for (i=0; i<columns.length; i+=1) {
						if (this.priority[columns[i].type] <= 6) {
							thereArePriority +=1;
						}
					}
				}
				this.thereArePriority = thereArePriority;
			}
			

			return this;
		},
		colSpanControlField: function (columns, type, indexColumn) {
			var rest,
			itemsLength = columns.length,
			layout = this.model.get("layout"),
			defaultColSpan = 8;
			
			if (this.numberRest > 0) {
				if (this.priority[type] <= 6 && this.thereArePriority > 0) {
					defaultColSpan = parseInt(10/itemsLength) +1;
					this.numberRest -=1;
					this.thereArePriority -=1;
				} else {
					if (this.numberRest >= parseInt(itemsLength - indexColumn)) {
						defaultColSpan = parseInt(10/itemsLength) +1;
						this.numberRest -=1;
					} else {
						defaultColSpan = parseInt(10/itemsLength);
					}
				}
			} else {
				defaultColSpan = parseInt(10/itemsLength);
			}

			return defaultColSpan;
		},
		changeNameField: function (name, row, column) {
			return name + "_" + row + "_" + column;
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
			
			for (i=0; i< rowView.length; i+=1) {
				newDependentFields = [];
				dependentFields = rowView[i].model.get("dependentFields");
				if (dependentFields) {
					for (j=0; j < dependentFields.length; j+=1) {
						label = dependentFields[j];
						for (k=0; k< rowView.length; k+=1) {
							if ((label === rowView[k].model.get("_extended").name)
								&& ($.inArray(label, newDependentFields) < 0) ) {
								newDependentFields.push(rowView[k].model.get("name"));
							}
						}
					}	
					rowView[i].model.attributes.dependentFields = newDependentFields;
				}

				formulaFields = rowView[i].model.get("_extended").formula;
				if (typeof formulaFields === "string") {
					for (l=0; l< rowView.length; l+=1) {
						if (i !== l) {
							formulaFields = formulaFields.replace(new RegExp(rowView[l].model.get("_extended").name, 'g'), rowView[l].model.get("name"));
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
		validate: function() {
			
		},
		onRemoveRow: function (event) {
			var rowNumber, itemRemoved;

			if (event) {
				this.block = true;
				rowNumber = $(event.target).data("row");
				jQuery(this.dom[rowNumber]).remove();
				itemRemoved = this.removeRow(rowNumber);
				this.refreshButtonsGrid();
				
				this.renderGridTable();
				this.onRemoveRowCallback(itemRemoved, this);
			}

			return this;
		},
		onClickPage: function (event) {
			var objData = $(event.currentTarget.children).data(),
			parentNode = $(event.currentTarget).parent();

			parentNode.children().removeClass('active');
			$(event.currentTarget).addClass("active");

			this.onClickPageCallback(event, this);

			return this;
		},
		refreshButtonsGrid: function () {
			var i,
			tdNumber,
			buttonRemove,
			trs = this.dom;

			for (i=0; i<trs.length; i+=1) {
				// refresh html
				$(trs[i]).html();
				tdNumber = this.createRowNumber(i+1);
				buttonRemove = this.createRemoveButton(i);
				$(trs[i].firstChild).replaceWith( tdNumber );
				$(trs[i].lastChild).replaceWith( buttonRemove );
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

			tdNumber.className = (layout === "responsive") ? "col-xs-1 col-sm-1 col-md-1 col-lg-1": 
								"col-xs-12 col-sm-1 col-md-1 col-lg-1";

			label.className = "hidden-lg hidden-md hidden-sm visible-xs control-label col-xs-4";
			labelSpan.innerHTML = "Nro";
			label.appendChild(labelSpan);

			divNumber.className = "col-xs-4 col-sm-12 col-md-12 col-lg-12 pmdynaform-grid-label";
			spanNumber.innerHTML = index;
			divNumber.appendChild(spanNumber);
			if (layout === "form") {
				containerField.appendChild(label);	
				
				tdRemove = this.createRemoveButton(index-1);
				tdRemove.className = "col-xs-1 visible-xs hidden-sm hidden-md hidden-lg";
				tdRemove.style.cssText = "float: right; margin-right: 15%";
				containerField.appendChild(tdRemove);
			}
			

			containerField.appendChild(divNumber);
			formgroup.className = "row form-group";
			formgroup.appendChild(containerField);
			tdNumber.appendChild(formgroup);
			return tdNumber;
		},

		createRemoveButton: function (index) {
			var that = this,
			tdRemove,
			buttonRemove,
			layout = this.model.get("layout");

			tdRemove = document.createElement("div");
			tdRemove.className = (layout === "form") ? "pmdynaform-grid-removerow hidden-xs col-xs-1 col-sm-1 col-md-1 col-lg-1":
			(layout === "static") ? "pmdynaform-grid-removerow-static": "col-xs-1 col-sm-1 col-md-1 col-lg-1";
			
			buttonRemove = document.createElement("button");
			
			buttonRemove.className = "glyphicon glyphicon-trash btn btn-danger btn-sm";
			buttonRemove.setAttribute("data-row", index);

			$(buttonRemove).data("row", index);
			$(buttonRemove).on("click", function(event) {
				that.onRemoveRow(event);
			});

			tdRemove.appendChild(buttonRemove);
			return tdRemove;
		},
		createHTMLTitle: function (	) {
			var k,
			dom,
			title,
			td,
			colSpan,
			label,
			layout = this.model.get("layout");

			dom = this.$el.find(".pmdynaform-grid-thead");
			td = document.createElement("div");
			label = document.createElement("span");
			
			if (layout === "static") {
				dom.addClass("pmdynaform-grid-thead-static");
			} else {
				//For the case: responsive and form
				td.className = "col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center";
			}
			//label.innerHTML = "Nro";
			td.appendChild(label);
			dom.append(td);

			for (k=0; k< this.gridtable[0].length; k+=1) {
				colSpan = this.gridtable[0][k].model.get("colSpan");
				title = this.gridtable[0][k].model.get("title");
				td = document.createElement("div");
				label = document.createElement("span");
				td.className = (layout === "form")? "hidden-xs col-xs-"+colSpan+" col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-" + colSpan + " text-center" : 
				(layout === "static")? "pmdynaform-grid-field-static": "col-xs-"+colSpan+" col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-" + colSpan + " text-center";
				label.innerHTML = title;
				td.appendChild(label);
				dom.append(td);
			}

			return this;
		},
		createHTMLPager: function () {
			var i,
			that = this,
			pager = this.templatePager({
				id: this.model.get("id"),
				paginationItems: this.model.get("paginationItems")
			}),
			pagerContainer = this.$el.find(".pmdynaform-grid-pagination");

			pagerContainer.children().remove();
			pagerContainer.append(pager);

			return this;
		},
		createHTMLTotal: function () {
			var k,
			dom,
			title,
			td,
			fn,
			colSpan,
			label,
			icon,
			totalrow = this.model.get("totalrow"),
			layout = this.model.get("layout"),
			iconTotal = {
				sum: "&#8721;",
				avg: "&#935;",
				other: "&#989;"
			};

			if (totalrow.length) {
				dom = this.$el.find(".pmdynaform-grid-functions");
				dom.children().remove();
				td = document.createElement("div");
				label = document.createElement("span");
				
				if (layout === "static") {
					dom.addClass("pmdynaform-grid-thead-static");
				} else {
					//For the case: responsive and form
					td.className = "col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center";
				}
				//label.innerHTML = "Nro";
				td.appendChild(label);
				dom.append(td);

				for (k=0; k< this.gridtable[0].length; k+=1) {
					colSpan = this.gridtable[0][k].model.get("colSpan");
					title = totalrow[k]? totalrow[k] : "";
					td = document.createElement("div");
					label = document.createElement("span");
					td.className = (layout === "form")? "hidden-xs col-xs-"+colSpan+" col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-" + colSpan + " text-center" : 
					(layout === "static")? "pmdynaform-grid-field-static": "col-xs-"+colSpan+" col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-" + colSpan + " text-center";
					fn = this.gridtable[0][k].model.attributes.function;
					if (fn) {
						$(td).addClass("total");
						icon =  iconTotal[fn] ? iconTotal[fn] : iconTotal["other"];
						label.innerHTML = icon + ": " + title;
					} else {
						label.innerHTML	= "";
					}
					td.appendChild(label);
					dom.append(td);
				}
			}

			return this;
		},
		createHTMLContainer: function () {
			var k,
			dom = this.$el.find(".pmdynaform-grid-tbody"),
			pageSize = this.model.get("pageSize"),
			domCarousel,
			flagRow = 0,
			section = 1;

			this.verifyPageNumber();
			dom.children().remove();

			//Applying overflow:auto to container
			if (this.model.get("layout") === "static") {
				dom.addClass("pmdynaform-static");
			}
			

			for (k=0; k<this.dom.length; k+=1) {
				flagRow+=1;	
				if (this.model.get("pager")) {
					if (this.block === true) {
						domCarousel = document.createElement("div");
						domCarousel.className = "pmdynaform-grid-section_"+section;
						if (this.model.get("layout") === "static") {
							domCarousel.className += " pmdynaform-static";
						}
						domCarousel = $(domCarousel);
					} else {
						domCarousel = this.$el.find(".pmdynaform-grid-section_"+section);
					}

					if (section === this.showPage) {
						domCarousel.addClass("item active");
					} else {
						domCarousel.addClass("item");
					}
					if (flagRow === pageSize) {
						this.block = true;
						section+=1;
						flagRow = 0;
					} else {
						this.block = false;
					}
					
					domCarousel.append(this.dom[k]);
					dom.append(domCarousel);
				} else {

					dom.append(this.dom[k]);	
				}
			}
			
			return this;
		},
		createHTMLFields: function (numberRow) {
			var tr, 
			td,
			k,
			tdRemove,
			tdNumber,
			dom,
			element,
			domCarousel,
			colSpan;

			tr = document.createElement("div");
			tr.className = "pmdynaform-grid-row row form-group show-grid";
			if (this.model.get("layout") === "static") {
				tr.className += " pmdynaform-grid-static"
			}
			
			//tr.id = PMDynaform.core.Utils.generateID();
			tdNumber = this.createRowNumber(numberRow+1);
			tr.appendChild(tdNumber);

			for (k=0; k<this.gridtable[numberRow].length; k+=1) {
				colSpan = this.gridtable[numberRow][k].model.get("colSpan");

				td = document.createElement("div");
				if (this.model.get("layout") === "form") {
					td.className = "col-xs-12 col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-"+colSpan;
				} else if(this.model.get("layout") === "static") {
					td.className = "pmdynaform-grid-field-static";
				} else {
					td.className = "col-xs-"+colSpan+" col-sm-"+colSpan+" col-md-"+colSpan+" col-lg-"+colSpan;
				}
				
				
				element = this.gridtable[numberRow][k].render().el;
				$(element).addClass("row form-group");
				td.appendChild(element);
				tr.appendChild(td);
			}
			tdRemove = this.createRemoveButton(numberRow);
			tr.appendChild(tdRemove);
			
			this.dom.push(tr);

			return tr;
		},
		setData: function (data) {
            var col,
            i,
            j,
            cloneData = data,
            grid = this.gridtable;

            if (typeof data === "object") {
                for (j in cloneData) {
                    if(cloneData.hasOwnProperty(j)) {
                    	for (col=0; col<grid[0].length; col+=1) {
                    		if (!_.isEmpty(grid[0][col].model.attributes.variable)) {
                    			if (grid[0][col].model.attributes.variable.var_name === j) {
	                    			if (cloneData[j] instanceof Array) {
	                    				for (i=0; i<grid.length;i+=1) {
	                    					grid[i][col].model.set("value", cloneData[j][i]);
	                    				}
	                    			}	
	                    		}	
                    		}
                    		
                    	}
                    }
                }
                
            } else {
                console.log("Error, The 'data' parameter is not valid. Must be an array.");
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
			for (i=0; i<gridpanel.length; i+=1) {
				rowData = [];
				for (k=0; k<gridpanel[i].length; k+=1) {
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
		renderGridTable: function () {
			var j;

			this.dom = [];
			for(j = 0; j<this.gridtable.length; j+=1){
				this.createHTMLFields(j);
            }
			this.createHTMLContainer();
			this.model.setPaginationItems();
			this.createHTMLPager();
			this.createHTMLTotal();

			return this;
		},
		/**
		 * @Event
		 * @param Event  This must be an event valid
		 * @param Function Callback for the event
		 **/
		on: function (e, fn) {
			var allowEvents = {
				remove: "setOnRemoveRowCallback",
				add: "setOnAddRowCallback",
				pager: "setOnClickPageCallback"
			};

			if (allowEvents[e]) {
				this[allowEvents[e]](fn);
			} else {
				throw new Error ("The event must be a valid event.\n The events available are remove, add and pager");
			}

			return this; 
		},
		setOnRemoveRowCallback: function (fn){
			if (typeof fn === "function") {
				this.onRemoveRowCallback = fn;
			} else {
				throw new Error ("The callback must be a function");
			}
			
			return this;
		},
		setOnAddRowCallback: function(fn){
			if (typeof fn === "function") {
				this.onAddRowCallback = fn;
			} else {
				throw new Error ("The callback must be a function");
			}
			
			return this;
		},
		setOnClickPageCallback: function(fn){
			if (typeof fn === "function") {
				this.onClickPageCallback = fn;
			} else {
				throw new Error ("The callback must be a function");
			}
			
			return this;
		},
		render: function() {
			var j,
			headerGrid,
			bodyGrid;

			this.$el.html( this.template( this.model.toJSON()) );
			this.createHTMLTitle();
			this.renderGridTable();
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }

            if (this.model.get("layout") === "static") {
            	headerGrid = this.$el.find(".pmdynaform-grid-thead");
				bodyGrid = this.$el.find(".pmdynaform-grid-tbody");
				bodyGrid.css("overflow","auto");
				bodyGrid.scroll(function (event) { 
			        headerGrid.scrollLeft(bodyGrid.scrollLeft());
			        event.stopPropagation();
			    });
            }

            if (this.model.get("layout") === "responsive") {
            	var size = {
            		"1200": 5,
            		"992": 4,
            		"768": 3,
            		"767": 2
            	};
            
	            $( window ).resize(function() {
	            	var j, 
	            	k,
	            	width = $( window ).width();

	            	if ( width >= 1200) {
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
		afterRender: function () {
			
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.GridPanel",GridView);
}());

(function(){
	var ButtonView = Backbone.View.extend({
		template: _.template( $("#tpl-button").html()),
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
        	$(this.$el.find("button")[0]).on(e, function(event){
        		fn(event, that);
        		
        		event.stopPropagation();
        	});
        	return this;
        },
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.Button",ButtonView);
	
}());

(function(){
	var DropDownView = PMDynaform.view.Field.extend({
		events: {
			"change select": "continueDependentFields",
            "blur select": "validate",
            "keydown select": "preventEvents"
		},
		clicked: false,	
		template: _.template( $("#tpl-dropdown").html()),
		initialize: function () {
			this.model.on("change", this.checkBinding, this);
		},
		checkBinding: function (event) {
			if (!this.clicked) {
				this.render();
				this.validate();
			}
			this.onChange(event);
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
		setValueDefault: function(){
			var val = $(this.el).find(":selected").val();
			if(val!= undefined) {
				this.model.set("value",val);
			}
			else {
				this.model.set("value","");	
			}
		},
		onChange: function (event)  {
			var i, j, item, dependents, viewItems, valueSelected;
			
			dependents = this.model.get("dependentFields") ? this.model.get("dependentFields"): [];
			viewItems = this.parent.items.asArray();			
			if (dependents.length > 0) {
				for (i = 0; i < viewItems.length; i+=1) {
					for (j = 0; j < dependents.length; j+=1) {
						item = viewItems[i].model.get("name");	
						if(dependents[j] === item) {
							if (event) {
								if (viewItems[i].onDependentHandler) {
									viewItems[i].onDependentHandler(item,valueSelected);
									viewItems[i].render();
									viewItems[i].setValueDefault();																
								}
							}
						}
					}
				}
			}
			this.clicked = false;

			return this;
		},
		createDependencies : function () {
			var i, j, item, dependents, viewItems;
			dependents = this.model.get("dependentFields") ? this.model.get("dependentFields"): [];
			viewItems = this.parent.items.asArray();			
			if (dependents.length > 0) {
				for (i = 0; i < viewItems.length; i+=1) {
					for (j = 0; j < dependents.length; j+=1) {
						item = viewItems[i].model.get("name");
						if(dependents[j] === item) {
							if (viewItems[i].model.setDependencies) {
								viewItems[i].model.setDependencies(this);
							}
						}
					}
				}
			}

			return this;
		},		
		continueDependentFields: function () {
			var newValue, 
			auxValue;

			this.clicked = true;
			auxValue = $(this.el).find(":selected").val();
			newValue = (auxValue === undefined)? "" : auxValue;			
			this.model.set("value", newValue);
			this.changeValuesFieldsRelated();
			
			return this;
		},
		onDependentHandler: function (name,value) {
			this.model.remoteProxyData(true);
			return this;
		},							
        validate: function(){
        	var drpValue;
        	if(!this.model.get("disabled")) {
        		drpValue = (this.model.get("options").length > 0)? this.$el.find("select").val() : "";
	            this.model.set({value: drpValue}, {validate: true});
	            if (this.validator) {
	                this.validator.$el.remove();
	                this.$el.removeClass('has-error');
	            }
	            if(!this.model.isValid()){
	                this.validator = new PMDynaform.view.Validator({
	                    model: this.model.get("validator"),
	                    domain: false
	                });  
	                this.$el.find("select").parent().append(this.validator.el);
	                this.applyStyleError();
	            }
        	}        	
            return this;
        },
        updateValueControl: function () {
            var i,
            options = this.$el.find("select").find("option");

            loop:
            for (i=0; i<options.length; i+=1) {
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
        		control.on(e, function(event){
	        		fn(event, that);
	        		event.stopPropagation();
	        	});
        	}
        	
        	return this;
        },
        getHTMLControl: function () {
            return this.$el.find("select");
        },
		render: function() {
			this.createDependencies();
			this.$el.html(this.template(this.model.toJSON()));
			if (this.model.get("hint")) {
				this.enableTooltip();
			}
			this.setValueToDomain();
			
			return this;
		},
		afterRender : function () {
			this.continueDependentFields();
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.Dropdown",DropDownView);
	
}());

(function(){
	var RadioView = PMDynaform.view.Field.extend({
		clicked: false,
		template: _.template( $("#tpl-radio").html()),
		events: {
	        "click input": "onChange",
	        "blur input": "validate",
	        "keydown input": "preventEvents"
	    },
		initialize: function (){
			this.model.on("change", this.checkBinding, this);
		},
		preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            return this;
        },
		checkBinding: function () {
			if (!this.clicked) {
				this.render();
				//this.validate();
			}
			return this;
		},
		validate: function() {
			if (!this.model.get("disabled")) {
				this.model.set({},{validate: true});
				if (this.validator) {
	                this.validator.$el.remove();
	                this.$el.removeClass('has-error has-feedback');
	            }
				if(!this.model.isValid()){
	                this.validator = new PMDynaform.view.Validator({
	                    model: this.model.get("validator")
	                });
	                this.$el.find(".pmdynaform-control-radio-list").parent().append(this.validator.el);
	                this.applyStyleError();
	            }
			}
			this.clicked = false;
			return this;
		},
		updateValueControl: function () {
            var i,
            inputs = this.$el.find("input");
            
            for (i=0; i<inputs.length; i+=1) {
            	if (inputs[i].checked) {
            		this.model.setItemClicked({
						value: inputs[i].value,
						checked: true
					});
            	}
            }
        	
            return this;
        },
		onChange: function (event) {
			this.clicked = true;
			this.model.setItemClicked({
				value: event.target.value,
				checked: event.target.checked
			});
			this.validate();
			this.changeValuesFieldsRelated();
		},
		getHTMLControl: function () {
            return this.$el.find(".pmdynaform-control-radio-list");
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.Radio",RadioView);
}());

(function(){
	var SubmitView = Backbone.View.extend({
		template: _.template( $("#tpl-submit").html()),
		events: {
	        "keydown": "preventEvents"
	    },
		initialize: function (options){
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
        		control.on(e, function(event){
	        		fn(event, that);

	        		event.stopPropagation();
	        	});
        	}
        	
        	return this;
        },
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		}
	});
	
	PMDynaform.extendNamespace("PMDynaform.view.Submit",SubmitView);
	
}());
(function(){
	var TextareaView = PMDynaform.view.Field.extend({
		template: _.template( $("#tpl-textarea").html()),
        validator: null,
        keyPressed: false,
        events: {
                "blur textarea": "validate",
                "keyup textarea": "validate",
                "keydown textarea": "refreshBinding"
        },
        initialize: function (){
            var that = this;
            this.model.on("change", this.checkBinding, this);
        },
        refreshBinding: function () {
            this.keyPressed = true;
            return this;
        },
        checkBinding: function () {
            //If the key is not pressed, executes the render method
            if (!this.keyPressed) {
                this.render();
            }
        },
        validate: function(event){
            if (event) {
                if ((event.which === 9) && (event.which !==0)) { //tab key
                    this.keyPressed = true;
                }
            }
            if (!this.model.get("disabled")) {
                this.model.set({value: this.$el.find("textarea").val()}, {validate: true});
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error');
                }
                if(!this.model.isValid()){
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });  
                    this.$el.find("textarea").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            }
            this.changeValuesFieldsRelated();
            this.keyPressed = false;
            return this;
        },
        updateValueControl: function () {
            var inputVal = this.$el.find("textarea").val();

            this.model.set("value", inputVal);

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
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.TextArea",TextareaView);
		
}());


(function(){

	var TextView = PMDynaform.view.Field.extend({
		template: _.template($("#tpl-text").html()),
        validator: null,
        keyPressed: false,
        formulaFieldsAssociated: [],
        events: {
                "blur input": "validate",
                "keyup input": "validate",
                "keydown input": "refreshBinding"
        },
        onChangeCallback: function (){},
        initialize: function () {
            var that = this;

            this.formulaFieldsAssociated = [];
            this.model.on("change", this.checkBinding, this);
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
        checkBinding: function () {
            this.onChangeCallback(this);
            //If the key is not pressed, executes the render method
            if (!this.keyPressed) {
                this.render();
            }
        },
        mask: function(stringValue, mask) {
            var newString = "^", 
            iDigits = 0,
            blocks = 0,
            sections = "",
            masked;

            for (var i = 0; i < mask.length; i+=1) {
                if(mask[i] === "#") {
                    iDigits++;
                }
                if(iDigits > 0 && mask[i+1] !== "#") {
                    newString += "(\\d{" + iDigits + "})";
                    iDigits = 0;
                    blocks++;
                    sections+= "$" + blocks;
                } else if (mask[i] !== "#") {
                    sections += mask[i];
                }
            }
            newString += ".*";
            masked = stringValue.replace(/\D/g,"");
            masked = masked.replace(/^0/,"");
            masked = masked.replace(new RegExp(newString), sections);

            return masked;
        },
        validate: function (event,b,c) {
            var $inputField, 
            fieldsAssoc,
            originalValue,
            fieldsObj,
            maskLength,
            inputValue,
            i;
            
            this.keyPressed = true;
            if (event) {
                if ((event.which === 9) && (event.which !==0)) { //tab key
                    //this.keyPressed = false;
                }
            }
            
            if (!this.model.get("disabled")) {
                $inputField = this.$el.find("input");
                if (this.model.get("mask")) {
                    inputValue = $inputField.val();
                    originalValue = $inputField.cleanVal();
                
                    maskLength = this.model.get("mask").length;
                    if (inputValue.length > maskLength ) {
                        $inputField.val(inputValue.substring(0,maskLength));
                    }
                } else {
                    originalValue = $inputField.val();
                }
                
                
                //Before save an object
                this.onTextTransform(originalValue);
                this.model.set({value: originalValue}, {validate: true});
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if(!this.model.isValid()){
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    $inputField.parent().append(this.validator.el);
                    this.applyStyleError();
                }
            }
            
            this.onFieldAssociatedHandler();
            this.changeValuesFieldsRelated();
            this.keyPressed = false;
            return this;
        },
        updateValueControl: function () {
            var inputVal = this.$el.find("input").val();

            this.model.set("value", inputVal);

            return this;    
        },
        onFieldAssociatedHandler: function() {
            var i,
            fieldsAssoc = this.formulaFieldsAssociated;

            if (fieldsAssoc.length > 0) {
                for (i=0; i<fieldsAssoc.length; i+=1) {
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
                none: function() {
                    return val;
                }
            };

            if (transform) {
                transformed = (availables[transform]) ? availables[transform]() : availables["none"]();
                this.$el.find("input").val(transformed);
            }
            
            return this;
        },
        onDependentHandler: function () {
        },
        setValueDefault: function () {
        },
        onFormula: function() {
            var fieldsList = this.parent.items,
            that = this,
            allFields,
            allFieldsView,
            index,
            formulaField,
            namesField = {},
            fieldFormula,
            fieldValid,
            resultField,
            fieldAdded = [],
            fieldSelected,
            obj,
            i;
            
            //All Fields from the FORM
          
            allFieldsView = (fieldsList instanceof Array)? fieldsList: fieldsList.asArray();

            for (index in allFieldsView) {
                if (allFieldsView[index] instanceof PMDynaform.view.Text) {
                    namesField[allFieldsView[index].model.get("name")] = allFieldsView[index];
                }    
            }
            fieldSelected = {};
            //Fields from the Formula PROPERTY
            formulaField = this.model.get("formula");
            fieldFormula = formulaField.split(/[\-(,|+*/\)]+/);
            for (i=0; i<fieldFormula.length; i+=1) {
                fieldFormula[i] = fieldFormula[i].trim();
            }

            fieldValid = fieldFormula.filter(function existElement(element) {
                var result = false;
                if ((namesField[element] !== undefined) && ($.inArray(element, fieldAdded) === -1)) {
                    fieldAdded.push(element);
                    result = true
                }
                return result;
            });

            //Insert the Formula object to fields selected
            for (obj in fieldValid) {
                this.model.addFormulaFieldName(fieldValid[obj]);
                
                namesField[fieldValid[obj]].formulaFieldsAssociated.push(that);
            }


            return this;
        },
        setOnChangeCallback: function (fn) {
            if (typeof fn === "function") {
                this.onChangeCallback = fn;
            }

            return this;
        },
        on: function (e, fn) {
            var that = this, 
            control,
            localEvents = {
                "changeValues": "setOnChangeCallback"
            };

            if (localEvents[e]) {
                this[localEvents[e]](fn);
            } else {
                control = this.$el.find("input");
                if (control) {
                    control.on(e, function(event){
                        fn(event, that);

                        event.stopPropagation();
                    });
                } else {
                    throw new Error ("Is not possible find the HTMLElement associated to field");
                }    
            }
            
            
            return this;
        },
        getHTMLControl: function () {
            return this.$el.find("input");
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            if (this.model.get("hint") !== "") {
                this.enableTooltip();
            }
            // 0: Only Numbers.
            // 9: Only Numbers but optional.
            // #: Only Numbers but recusive.
            // A: Numbers and Letters.
            // S: Only A-Z and a-z characters.
            if (this.model.get("mask")) {
                this.$el.find("input").mask(this.model.get("mask"));
            }
            if (this.model.get("formula")) {
                this.onFormula();
            }
            return this;
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.Text",TextView);
}());

(function(){
	var File = PMDynaform.view.Field.extend({
		item: null,	
		template: _.template( $("#tpl-file").html()),
		events: {
	        "click .pmdynaform-file-container .form-control": "onClickButton",
	        "click div[name='button-all'] .pmdynaform-file-buttonup": "onUploadAll",
	        "click div[name='button-all'] .pmdynaform-file-buttoncancel": "onCancelAll",
	        "click div[name='button-all'] .pmdynaform-file-buttonremove": "onRemoveAll"
	    },
		initialize: function () {
			//this.setOnChangeFiles();
			this.model.on("change", this.render, this);
		},
		onClickButton: function (event) {
			this.$el.find("input").trigger( "click" );
			event.preventDefault();
			event.stopPropagation();
			
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
		validate: function(e, file) {
			var validated = true,
			type,
			extensions = this.model.get("extensions"),
			maxSize = this.model.get("size");
			
			//Check the extension of the file
			if(extensions.indexOf("*") < 0) {
				type = file.extra.extension.toLowerCase().trim();
				if (extensions.indexOf(type) < 0) {
					alert("The extension of the file is not supported for the field...");
					validated = false;
				}
			}
			
			// check file size
			if ((parseInt(file.size / 1024) > parseInt(maxSize * 1024)) && validated === true) {
				alert("File \""+file.name+"\" is too big. \n Max allowed size is "+ maxSize +" MB.");
				validated = false;
			}

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
	    			class: "pmdynaform-file-boxpreview-video",
	    			icon: "glyphicon glyphicon-facetime-video"
	    		},
	    		audio: {
	    			class: "pmdynaform-file-boxpreview-audio",
	    			icon: "glyphicon glyphicon-music"
	    		},
	    		file: {
	    			class: "pmdynaform-file-boxpreview-file",
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
				resizeImage.innerHTML = '<div class="'+ typeClasses['audio'].class +' thumbnail ' + typeClasses['audio'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else if(file.type.match(/video.*/)) {
				resizeImage.innerHTML = '<div class="'+ typeClasses['video'].class +' thumbnail ' + typeClasses['video'].icon+'"><div>'+ fileName +'</div></div>'; 
			} else {
				enabledPreview = false,
				resizeImage.innerHTML = '<div class="'+ typeClasses['file'].class +' thumbnail ' + typeClasses['file'].icon+'"><div>'+ fileName +'</div></div>'; 
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
			oprand;

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

			fileContainer = this.$el.find(".pmdynaform-file-droparea")[0];
			fileControl = this.$el.find("input")[0];
			if (this.model.get("dnd") || this.model.get("preview")) {
				PMDynaform.core.FileStream.setupDrop(fileContainer, oprand);
			}

			PMDynaform.core.FileStream.setupInput(fileControl, oprand); 
			
			
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.File",File);
}());

(function(){
	var CheckboxView = PMDynaform.view.Field.extend({
		item: null,	
		template: _.template( $("#tpl-checkbox").html()),
		events: {
	        "click input": "onChange",
            "blur input": "validate",
            "keydown input": "preventEvents"
	    },
		initialize: function (){
			this.model.on("change", this.checkBinding, this);
		},
		preventEvents: function (event) {
            //Validation for the Submit event
            if (event.which === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            return this;
        },
		checkBinding: function () {
			this.render();
			//this.validate();
			return this;
		},
		validate: function() {
			if (!this.model.get("disabled")) {
				this.model.set({},{validate: true});
				if (this.validator) {
	                this.validator.$el.remove();
	                this.$el.removeClass('has-error has-feedback');
	            }
				if(!this.model.isValid()) {
	                this.validator = new PMDynaform.view.Validator({
	                    model: this.model.get("validator")
	                });  
	                this.$el.find(".pmdynaform-control-checkbox-list").parent().append(this.validator.el);
	                this.applyStyleError();
	            }
			}
			
			return this;
		},
		updateValueControl: function () {
            var i,
            inputs = this.$el.find("input");

            for (i=0; i<inputs.length; i+=1) {
            	if (inputs[i].checked) {
            		this.model.setItemChecked({
						value: inputs[i].value,
						checked: true
					});
            	}
            }
        	
            return this;    
        },
		onChange: function (event){			
			$(event.target).val();
			this.model.setItemChecked({
				value: event.target.value,
				checked: event.target.checked
			});
			this.validate();
		},
		getHTMLControl: function () {
            return this.$el.find(".pmdynaform-control-checkbox-list");
        }
	});

	PMDynaform.extendNamespace("PMDynaform.view.Checkbox",CheckboxView);
}());

/**
 * The Datetime class was developed with the help of DateBootstrap plugin	
 */
(function(){


	var DatetimeView = PMDynaform.view.Field.extend({
		item: null,
		template: _.template( $("#tpl-date").html()),
		validator: null,
		flagPick: false,
		keyPressed: false,
		rendered: false,
		keys:[],
		events: {
                "blur input": "validate",
                "keydown input": "refreshBinding"
        },
		dates:{
		    en: {
				days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
				daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
				daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
				months: ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"],
				monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"]
		    }
		},
		formatComponent : null,
		formatReplacer : null,
		dateFormatComponents : {
		    dd: {property: 'UTCDate', getPattern: function() { return '(0?[1-9]|[1-2][0-9]|3[0-1])\\b';}},
		    MM: {property: 'UTCMonth', getPattern: function() {return '(0?[1-9]|1[0-2])\\b';}},
		    yy: {property: 'UTCYear', getPattern: function() {return '(\\d{2})\\b'}},
		    yyyy: {property: 'UTCFullYear', getPattern: function() {return '(\\d{4})\\b';}},
		    hh: {property: 'UTCHours', getPattern: function() {return '(0?[0-9]|1[0-9]|2[0-3])\\b';}},
		    mm: {property: 'UTCMinutes', getPattern: function() {return '(0?[0-9]|[1-5][0-9])\\b';}},
		    ss: {property: 'UTCSeconds', getPattern: function() {return '(0?[0-9]|[1-5][0-9])\\b';}},
		    ms: {property: 'UTCMilliseconds', getPattern: function() {return '([0-9]{1,3})\\b';}},
		    HH: {property: 'Hours12', getPattern: function() {return '(0?[1-9]|1[0-2])\\b';}},
		    PP: {property: 'Period12', getPattern: function() {return '(AM|PM|am|pm|Am|aM|Pm|pM)\\b';}}
		},
		
		DPGlobal: null,
		TPGlobal: null,

		defaults :{ 
			maskInput: false,
		    pickDate: true,
		    pickTime: true,
		    pick12HourFormat: true,
		    pickSeconds: true,
		    startDate: -Infinity,
		    endDate: Infinity,
		    collapse: true
		},		
		initialize: function(options) {
			
			this.model.on("change", this.checkBinding, this);
			//this.model.on("change:value", this.setValuePicker, this);

			//this.renderInitialize(options);
			
		},
		renderInitialize: function (options) {
			var that = this, DPGlobal;
			DPGlobal = {
			    modes: [
			     	{
				    	clsName: 'days',
				      	navFnc: 'UTCMonth',
				      	navStep: 1
				    },
				    {
				      	clsName: 'months',
				      	navFnc: 'UTCFullYear',
				      	navStep: 1
				    },
				    {
				      	clsName: 'years',
				      	navFnc: 'UTCFullYear',
				      	navStep: 10
				    }
			    ],
			    isLeapYear: function (year) {
			      	return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
			    },
			    getDaysInMonth: function (year, month) {
			      	return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
			    },
			    headTemplate:
			      '<thead>' +
			        '<tr>' +
			          '<th class="prev">&lsaquo;</th>' +
			          '<th colspan="5" class="switch"></th>' +
			          '<th class="next">&rsaquo;</th>' +
			        '</tr>' +
			      '</thead>',
			    contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>'
			};

		  	DPGlobal.template =
		    '<div class="datepicker-days">' +
		      '<table class="table-condensed">' +
		        DPGlobal.headTemplate +
		        '<tbody></tbody>' +
		      '</table>' +
		    '</div>' +
		    '<div class="datepicker-months">' +
		      '<table class="table-condensed">' +
		        DPGlobal.headTemplate +
		        DPGlobal.contTemplate+
		      '</table>'+
		    '</div>'+
		    '<div class="datepicker-years">'+
		      '<table class="table-condensed">'+
		        DPGlobal.headTemplate+
		        DPGlobal.contTemplate+
		      '</table>'+
		    '</div>';

		    this.DPGlobal = DPGlobal;
		    var TPGlobal = {
			    hourTemplate: '<span data-action="showHours" data-time-component="hours" class="timepicker-hour"></span>',
			    minuteTemplate: '<span data-action="showMinutes" data-time-component="minutes" class="timepicker-minute"></span>',
			    secondTemplate: '<span data-action="showSeconds" data-time-component="seconds" class="timepicker-second"></span>'
			};

		  	TPGlobal.getTemplate = function(is12Hours, showSeconds) {
		    	return (
			    	'<div class="timepicker-picker">' +
			      	'<table class="table-condensed"' +
			        (is12Hours ? ' data-hour-format="12"' : '') +
			        '>' +
				        '<tr>' +
							'<td><a href="#" class="btn btn-default" data-action="incrementHours"><i class="glyphicon glyphicon-chevron-up"></i></a></td>' +
							'<td class="separator"></td>' +
							'<td><a href="#" class="btn btn-default" data-action="incrementMinutes"><i class="glyphicon glyphicon-chevron-up"></i></a></td>' +
							(showSeconds ?
							'<td class="separator"></td>' +
							'<td><a href="#" class="btn btn-default " data-action="incrementSeconds"><i class="glyphicon glyphicon-chevron-up"></i></a></td>': '')+
							(is12Hours ? '<td class="separator"></td>' : '') +
				        '</tr>' +
				        '<tr>' +
							'<td>' + TPGlobal.hourTemplate + '</td> ' +
							'<td class="separator">:</td>' +
							'<td>' + TPGlobal.minuteTemplate + '</td> ' +
							(showSeconds ?
							'<td class="separator">:</td>' +
							'<td>' + TPGlobal.secondTemplate + '</td>' : '') +
							(is12Hours ?
							'<td class="separator"></td>' +
							'<td>' +
							'<button type="button" class="btn btn-primary" data-action="togglePeriod"></button>' +
							'</td>' : '') +
				        '</tr>' +
				        '<tr>' +
							'<td><a href="#" class="btn btn-default" data-action="decrementHours"><i class="glyphicon glyphicon-chevron-down"></i></a></td>' +
							'<td class="separator"></td>' +
							'<td><a href="#" class="btn btn-default" data-action="decrementMinutes"><i class="glyphicon glyphicon-chevron-down"></i></a></td>' +
							(showSeconds ?
							'<td class="separator"></td>' +
							'<td><a href="#" class="btn btn-default" data-action="decrementSeconds"><i class="glyphicon glyphicon-chevron-down"></i></a></td>': '') +
							(is12Hours ? '<td class="separator"></td>' : '') +
							'<td><button type="button" class="btn btn-primary" data-action="done">DONE</button></td>'+
				        '</tr>' +

					'</table>' +
			    '</div>' +
			    '<div class="timepicker-hours" data-action="selectHour">' +
			      '<table class="table-condensed">' +
			      '</table>'+
			    '</div>'+
			    '<div class="timepicker-minutes" data-action="selectMinute">' +
			      '<table class="table-condensed">' +
			      '</table>'+
			    '</div>'+
			    (showSeconds ?
			    '<div class="timepicker-seconds" data-action="selectSecond">' +
			      '<table class="table-condensed">' +
			      '</table>'+
			    '</div>': '')
			    );
			};
			this.TPGlobal = TPGlobal;

            
			
			for (var k in this.dateFormatComponents) this.keys.push(k);
			this.keys[this.keys.length - 1] += '\\b';
			this.keys.push('.');
			this.formatComponent = new RegExp(this.keys.join('\\b|'));
			this.keys.pop();
			this.formatReplacer = new RegExp(this.keys.join('\\b|'), 'g');

			var icon;
			//this.template= _.template( $("#tpl-date").html());

			if (!(this.defaults.pickTime || this.defaults.pickDate))
			throw new Error('Must choose at least one picker');
			this.options = this.defaults;

			this.language = this.defaults.language in this.dates ? options.language : 'en';
		    
			if (this.model.attributes.pickType){
				if(this.model.attributes.pickType=="datetime"){
					this.pickDate = true;
					this.pickTime = true;
				}
				if(this.model.attributes.pickType=="date"){		      		      
					this.pickDate = true;
					this.pickTime = false;
				}
				if(this.model.attributes.pickType=="time"){		      		      
					this.pickDate = false;
					this.pickTime = true;
				}
			} else {
				this.pickDate = this.options.pickDate;
				this.pickTime = this.options.pickTime;
			}     

		      
		    //this.$el = $(this.template(this.model.toJSON()));
		    //Adding the button icon to Date field
		    //dateIcon = '<span class="pmdynaform-datetime-icon input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>';
			//$(dateIcon).insertAfter(this.$el.find("input"));
			
			//this.isInput = this.$el.is('input');
			this.component = false;
			if (this.$el.find('.input-append') || this.$el.find('.input-prepend'))
				this.component = this.$el.find('.add-on');
	
		      this.format = this.options.format;
		      if (!this.format) {
		      if (this.isInput) this.format = this.$el.data('format');
		        else this.format = this.$el.find('input').data('format');
		        if (!this.format) this.format = 'MM/dd/yyyy';
		      }
		    
		      this._compileFormat();
		      if (this.component) {
		        icon = this.component.find('i');
		      }
		      if (this.pickTime) {
		        if (icon && icon.length) this.timeIcon = icon.data('glyphicon glyphicon-dashboard');
		        if (!this.timeIcon) this.timeIcon = 'glyphicon glyphicon-dashboard';
		        icon.addClass(this.timeIcon);
		      }
		      if (this.pickDate) {
		        if (icon && icon.length) this.dateIcon = icon.data('glyphicon glyphicon-calendar');
		        if (!this.dateIcon) this.dateIcon = 'glyphicon glyphicon-calendar';
		        icon.removeClass(this.timeIcon);
		        icon.addClass(this.dateIcon);
		      }
		      this.widget = $(this.getTemplate(this.timeIcon, this.pickDate, this.pickTime, this.defaults.pick12HourFormat, this.defaults.pickSeconds, this.defaults.collapse)).appendTo(this.el);
		      this.minViewMode = this.options.minViewMode||this.$el.data('date-minviewmode')||0;
		      if (typeof this.minViewMode === 'string') {
		        switch (this.minViewMode) {
		          case 'months':
		            this.minViewMode = 1;
		          break;
		          case 'years':
		            this.minViewMode = 2;
		          break;
		          default:
		            this.minViewMode = 0;
		          break;
		        }
		      }
		      this.viewMode = options.viewMode||this.$el.data('date-viewmode')||0;
		      if (typeof this.viewMode === 'string') {
		        switch (this.viewMode) {
		          case 'months':
		            this.viewMode = 1;
		          break;
		          case 'years':
		            this.viewMode = 2;
		          break;
		          default:
		            this.viewMode = 0;
		          break;
		        }
		      }
		      this.startViewMode = this.viewMode;
		      this.weekStart = options.weekStart||this.$el.data('date-weekstart')||0;
		      this.weekEnd = this.weekStart === 0 ? 6 : this.weekStart - 1;
		      this.setStartDate(options.startDate || this.$el.data('date-startdate'));
		      this.setEndDate(options.endDate || this.$el.data('date-enddate'));
		      this.fillDow();
		      this.fillMonths();
		      this.fillHours();
		      this.fillMinutes();
		      this.fillSeconds();
		      this.update();
		      this.showMode();
		      this._attachDatePickerEvents();
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
        checkBinding: function () {
            //If the key is not pressed, executes the render method
            this.$el.find(".bootstrap-datetimepicker-widget").remove();
            if (!this.keyPressed) {
                this.render();
            }
        },
		show: function(e) {
			if(this.flagPick){
				this.hide();
				this.flagPick = false;
			}
			else{
		        this.widget.show();
		    	this.height = this.component ? this.component.outerHeight() : this.$el.outerHeight();
		      	this.place();
		      	this.$el.trigger({
		        	type: 'show',
		        	date: this._date
		      	});
		      	this._attachDatePickerGlobalEvents();
		      	if (e) {
		       		e.stopPropagation();
		        	e.preventDefault();
		      	}
		      	this.flagPick = true;
	      	}
    	},
    	disable: function(){
        	this.$el.find('input').prop('disabled',true);
          	this._detachDatePickerEvents();
    	},
    	enable: function(){
            this.$el.find('input').prop('disabled',false);
        	this._attachDatePickerEvents();
    	},
    	hide: function() {
        	// Ignore event if in the middle of a picker transition
            var collapse = this.widget.find('.collapse');
            
	      	for (var i = 0; i < collapse.length; i++) {
	        	var collapseData = collapse.eq(i).data('collapse');
	        	if (collapseData && collapseData.transitioning)
	          	return;
	      	}
	      	this.widget.hide();
	      	this.viewMode = this.startViewMode;
	      	this.showMode();
	      	this.set();
	      	this.$el.trigger({
	        	type: 'hide',
	        	date: this._date
	      	});
	      	this._detachDatePickerGlobalEvents();
	      	//this.flagPick = false;
	    },
	    set: function() {
	        var formatted = '',
	        input;

	      	if (!this._unset) formatted = this.formatDate(this._date);
	      	if (!this.isInput) {
	        	if (this.component){
	          	input = this.$el.find('input');
	          	input.val(formatted);
	          	this._resetMaskPos(input);
	        	}
	        	this.$el.data('date', formatted);
	      	} else {
	        	this.$el.val(formatted);
	        	this._resetMaskPos(this.$el);
	      	}
	      	//OmarSuca
	      	//this.model.set({value: formatted}, {validate: false});
	      	 
	    },
	    setValue: function(newDate) {
			if (!newDate) {
				this._unset = true;
			} else {
				this._unset = false;
			}
			if (typeof newDate === 'string') {
				this._date = this.parseDate(newDate);
			} else if(newDate) {
				this._date = new Date(newDate);
			}
			
			this.set();
			this.viewDate = this.UTCDate(this._date.getUTCFullYear(), this._date.getUTCMonth(), 1, 0, 0, 0, 0);
			this.fillDate();
			this.fillTime();
	    },
	    getDate: function() {
	        if (this._unset) return null;
	      	return new Date(this._date.valueOf());
	    },
	    setDate: function(date) {
			if (!date) this.setValue(null);
			else this.setValue(date.valueOf());
	    },
	    setStartDate: function(date) {
			if (date instanceof Date) {
				this.startDate = date;
			} else if (typeof date === 'string') {
				this.startDate = new this.UTCDate(date);
				if (! this.startDate.getUTCFullYear()) {
					this.startDate = -Infinity;
				}
			} else {
				this.startDate = -Infinity;
			}
			if (this.viewDate) {
				this.update();
			}
	    },
	    setEndDate: function(date) {
			if (date instanceof Date) {
				this.endDate = date;
			} else if (typeof date === 'string') {
				this.endDate = new this.UTCDate(date);
				if (! this.endDate.getUTCFullYear()) {
				  this.endDate = Infinity;
				}
			} else {
				this.endDate = Infinity;
			}
			if (this.viewDate) {
				this.update();
			}
	    },
	    getLocalDate: function() {
			if (this._unset) return null;
			var d = this._date;
			return new Date(d.getUTCFullYear(), 
							d.getUTCMonth(), 
							d.getUTCDate(),
	                      	d.getUTCHours(), 
	                      	d.getUTCMinutes(), 
	                      	d.getUTCSeconds(), 
	                      	d.getUTCMilliseconds());
	    },
	    setLocalDate: function(localDate) {
			if (!localDate) this.setValue(null);
			else
	        	this.setValue(Date.UTC(
					localDate.getFullYear(),
					localDate.getMonth(),
					localDate.getDate(),
					localDate.getHours(),
					localDate.getMinutes(),
					localDate.getSeconds(),
					localDate.getMilliseconds()));
	    },

	    place: function(){
			var position = 'absolute';
			var offset = this.component ? this.component.offset() : this.$el.offset();
			this.width = this.component ? this.component.outerWidth() : this.$el.outerWidth();
			offset.top = offset.top + this.height;

			var $window = $(window);

			if ( this.options.width != undefined ) {
				this.widget.width( this.options.width );
			}

			if ( this.options.orientation == 'left' ) {
				this.widget.addClass( 'left-oriented' );
				offset.left   = offset.left - this.widget.width() + 20;
			}

			if (this._isInFixed()) {
				position = 'fixed';
				offset.top -= $window.scrollTop();
				offset.left -= $window.scrollLeft();
			}

			if ($window.width() < offset.left + this.widget.outerWidth()) {
				offset.right = $window.width() - offset.left - this.width;
				offset.left = 'auto';
				this.widget.addClass('pull-right');
			} else {
				offset.right = 'auto';
				this.widget.removeClass('pull-right');
			}

			this.widget.css({
				position: position,
				top: offset.top,
				left: offset.left,
				right: offset.right
			});
	    },

	    notifyChange: function(){
			this.$el.trigger({
				type: 'changeDate',
				date: this.getDate(),
				localDate: this.getLocalDate()
			});
	    },

	    update: function(newDate){
	      var dateStr = newDate;
	      if (!dateStr) {
	        if (this.isInput) {
	          dateStr = this.$el.val();
	        } else {
	          dateStr = this.$el.find('input').val();
	        }
	        if (dateStr) {
	          this._date = this.parseDate(dateStr);
	        }
	        if (!this._date) {
	          var tmp = new Date()
	          this._date =this.UTCDate(tmp.getFullYear(),
	                              tmp.getMonth(),
	                              tmp.getDate(),
	                              tmp.getHours(),
	                              tmp.getMinutes(),
	                              tmp.getSeconds(),
	                              tmp.getMilliseconds())
	        }
	      }
	      this.viewDate = this.UTCDate(this._date.getUTCFullYear(), this._date.getUTCMonth(), 1, 0, 0, 0, 0);
	      this.fillDate();
	      this.fillTime();
	    },

	    fillDow: function() {
	      var dowCnt = this.weekStart;
	      var html = $('<tr>');
	      while (dowCnt < this.weekStart + 7) {
	        html.append('<th class="dow">' + this.dates[this.language].daysMin[(dowCnt++) % 7] + '</th>');
	      }
	      this.widget.find('.datepicker-days thead').append(html);
	    },

	    fillMonths: function() {
	      var html = '';
	      var i = 0
	      while (i < 12) {
	        html += '<span class="month">' + this.dates[this.language].monthsShort[i++] + '</span>';
	      }
	      this.widget.find('.datepicker-months td').append(html);
	    },

	    fillDate: function() {
	      var year = this.viewDate.getUTCFullYear();
	      var month = this.viewDate.getUTCMonth();
	      var currentDate = this.UTCDate(
	        this._date.getUTCFullYear(),
	        this._date.getUTCMonth(),
	        this._date.getUTCDate(),
	        0, 0, 0, 0
	      );
	      var startYear  = typeof this.startDate === 'object' ? this.startDate.getUTCFullYear() : -Infinity;
	      var startMonth = typeof this.startDate === 'object' ? this.startDate.getUTCMonth() : -1;
	      var endYear  = typeof this.endDate === 'object' ? this.endDate.getUTCFullYear() : Infinity;
	      var endMonth = typeof this.endDate === 'object' ? this.endDate.getUTCMonth() : 12;

	      this.widget.find('.datepicker-days').find('.disabled').removeClass('disabled');
	      this.widget.find('.datepicker-months').find('.disabled').removeClass('disabled');
	      this.widget.find('.datepicker-years').find('.disabled').removeClass('disabled');

	      this.widget.find('.datepicker-days th:eq(1)').text(
	        this.dates[this.language].months[month] + ' ' + year);

	      var prevMonth = this.UTCDate(year, month-1, 28, 0, 0, 0, 0);
	      var day = this.DPGlobal.getDaysInMonth(
	        prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
	      prevMonth.setUTCDate(day);
	      prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7) % 7);
	      if ((year == startYear && month <= startMonth) || year < startYear) {
	        this.widget.find('.datepicker-days th:eq(0)').addClass('disabled');
	      }
	      if ((year == endYear && month >= endMonth) || year > endYear) {
	        this.widget.find('.datepicker-days th:eq(2)').addClass('disabled');
	      }

	      var nextMonth = new Date(prevMonth.valueOf());
	      nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
	      nextMonth = nextMonth.valueOf();
	      var html = [];
	      var row;
	      var clsName;
	      while (prevMonth.valueOf() < nextMonth) {
	        if (prevMonth.getUTCDay() === this.weekStart) {
	          row = $('<tr>');
	          html.push(row);
	        }
	        clsName = '';
	        if (prevMonth.getUTCFullYear() < year ||
	            (prevMonth.getUTCFullYear() == year &&
	             prevMonth.getUTCMonth() < month)) {
	          clsName += ' old';
	        } else if (prevMonth.getUTCFullYear() > year ||
	                   (prevMonth.getUTCFullYear() == year &&
	                    prevMonth.getUTCMonth() > month)) {
	          clsName += ' new';
	        }
	        if (prevMonth.valueOf() === currentDate.valueOf()) {
	          clsName += ' active';
	        }
	        if ((prevMonth.valueOf() + 86400000) <= this.startDate) {
	          clsName += ' disabled';
	        }
	        if (prevMonth.valueOf() > this.endDate) {
	          clsName += ' disabled';
	        }
	        row.append('<td class="day' + clsName + '">' + prevMonth.getUTCDate() + '</td>');
	        prevMonth.setUTCDate(prevMonth.getUTCDate() + 1);
	      }
	      this.widget.find('.datepicker-days tbody').empty().append(html);
	      var currentYear = this._date.getUTCFullYear();

	      var months = this.widget.find('.datepicker-months').find(
	        'th:eq(1)').text(year).end().find('span').removeClass('active');
	      if (currentYear === year) {
	        months.eq(this._date.getUTCMonth()).addClass('active');
	      }
	      if (currentYear - 1 < startYear) {
	        this.widget.find('.datepicker-months th:eq(0)').addClass('disabled');
	      }
	      if (currentYear + 1 > endYear) {
	        this.widget.find('.datepicker-months th:eq(2)').addClass('disabled');
	      }
	      for (var i = 0; i < 12; i++) {
	        if ((year == startYear && startMonth > i) || (year < startYear)) {
	          $(months[i]).addClass('disabled');
	        } else if ((year == endYear && endMonth < i) || (year > endYear)) {
	          $(months[i]).addClass('disabled');
	        }
	      }

	      html = '';
	      year = parseInt(year/10, 10) * 10;
	      var yearCont = this.widget.find('.datepicker-years').find(
	        'th:eq(1)').text(year + '-' + (year + 9)).end().find('td');
	      this.widget.find('.datepicker-years').find('th').removeClass('disabled');
	      if (startYear > year) {
	        this.widget.find('.datepicker-years').find('th:eq(0)').addClass('disabled');
	      }
	      if (endYear < year+9) {
	        this.widget.find('.datepicker-years').find('th:eq(2)').addClass('disabled');
	      }
	      year -= 1;
	      for (var i = -1; i < 11; i++) {
	        html += '<span class="year' + (i === -1 || i === 10 ? ' old' : '') + (currentYear === year ? ' active' : '') + ((year < startYear || year > endYear) ? ' disabled' : '') + '">' + year + '</span>';
	        year += 1;
	      }
	      yearCont.html(html);
	    },

	    fillHours: function() {
	      var table = this.widget.find(
	        '.timepicker .timepicker-hours table');
	      table.parent().hide();
	      var html = '';
	      if (this.options.pick12HourFormat) {
	        var current = 1;
	        for (var i = 0; i < 3; i += 1) {
	          html += '<tr>';
	          for (var j = 0; j < 4; j += 1) {
	             var c = current.toString();
	             html += '<td class="hour">' + this.padLeft(c, 2, '0') + '</td>';
	             current++;
	          }
	          html += '</tr>'
	        }
	      } else {
	        var current = 0;
	        for (var i = 0; i < 6; i += 1) {
	          html += '<tr>';
	          for (var j = 0; j < 4; j += 1) {
	             var c = current.toString();
	             html += '<td class="hour">' + this.padLeft(c, 2, '0') + '</td>';
	             current++;
	          }
	          html += '</tr>'
	        }
	      }
	      table.html(html);
	    },

	    fillMinutes: function() {
	      var table = this.widget.find(
	        '.timepicker .timepicker-minutes table');
	      table.parent().hide();
	      var html = '';
	      var current = 0;
	      for (var i = 0; i < 5; i++) {
	        html += '<tr>';
	        for (var j = 0; j < 4; j += 1) {
	          var c = current.toString();
	          html += '<td class="minute">' + this.padLeft(c, 2, '0') + '</td>';
	          current += 3;
	        }
	        html += '</tr>';
	      }
	      table.html(html);
	    },

	    fillSeconds: function() {
	      var table = this.widget.find(
	        '.timepicker .timepicker-seconds table');
	      table.parent().hide();
	      var html = '';
	      var current = 0;
	      for (var i = 0; i < 5; i++) {
	        html += '<tr>';
	        for (var j = 0; j < 4; j += 1) {
	          var c = current.toString();
	          html += '<td class="second">' + this.padLeft(c, 2, '0') + '</td>';
	          current += 3;
	        }
	        html += '</tr>';
	      }
	      table.html(html);
	    },

	    fillTime: function() {
	      if (!this._date)
	        return;
	      var timeComponents = this.widget.find('.timepicker span[data-time-component]');
	      var table = timeComponents.closest('table');
	      var is12HourFormat = this.options.pick12HourFormat;
	      var hour = this._date.getUTCHours();
	      var period = 'AM';
	      if (is12HourFormat) {
	        if (hour >= 12) period = 'PM';
	        if (hour === 0) hour = 12;
	        else if (hour != 12) hour = hour % 12;
	        this.widget.find(
	          '.timepicker [data-action=togglePeriod]').text(period);
	      }
	      hour = this.padLeft(hour.toString(), 2, '0');
	      var minute = this.padLeft(this._date.getUTCMinutes().toString(), 2, '0');
	      var second = this.padLeft(this._date.getUTCSeconds().toString(), 2, '0');
	      timeComponents.filter('[data-time-component=hours]').text(hour);
	      timeComponents.filter('[data-time-component=minutes]').text(minute);
	      timeComponents.filter('[data-time-component=seconds]').text(second);
	    },

	    click: function(e) {
	      e.stopPropagation();
	      e.preventDefault();
	      this._unset = false;
	      var target = $(e.target).closest('span, td, th');
	      if (target.length === 1) {
	        if (! target.is('.disabled')) {
	          switch(target[0].nodeName.toLowerCase()) {
	            case 'th':
	              switch(target[0].className) {
	                case 'switch':
	                  this.showMode(1);
	                  break;
	                case 'prev':
	                case 'next':
	                  var vd = this.viewDate;
	                  var navFnc = this.DPGlobal.modes[this.viewMode].navFnc;
	                  var step = this.DPGlobal.modes[this.viewMode].navStep;
	                  if (target[0].className === 'prev') step = step * -1;
	                  vd['set' + navFnc](vd['get' + navFnc]() + step);
	                  this.fillDate();
	                  this.set();
	                  break;
	              }
	              break;
	            case 'span':
	              if (target.is('.month')) {
	                var month = target.parent().find('span').index(target);
	                this.viewDate.setUTCMonth(month);
	              } else {
	                var year = parseInt(target.text(), 10) || 0;
	                this.viewDate.setUTCFullYear(year);
	              }
	              if (this.viewMode !== 0) {
	                this._date = this.UTCDate(
	                  this.viewDate.getUTCFullYear(),
	                  this.viewDate.getUTCMonth(),
	                  this.viewDate.getUTCDate(),
	                  this._date.getUTCHours(),
	                  this._date.getUTCMinutes(),
	                  this._date.getUTCSeconds(),
	                  this._date.getUTCMilliseconds()
	                );
	                this.notifyChange();
	              }
	              this.showMode(-1);
	              this.fillDate();
	              this.set();
	              break;
	            case 'td':
	              if (target.is('.day')) {
	                var day = parseInt(target.text(), 10) || 1;
	                var month = this.viewDate.getUTCMonth();
	                var year = this.viewDate.getUTCFullYear();
	                if (target.is('.old')) {
	                  if (month === 0) {
	                    month = 11;
	                    year -= 1;
	                  } else {
	                    month -= 1;
	                  }
	                } else if (target.is('.new')) {
	                  if (month == 11) {
	                    month = 0;
	                    year += 1;
	                  } else {
	                    month += 1;
	                  }
	                }
	                this._date = this.UTCDate(
	                  year, month, day,
	                  this._date.getUTCHours(),
	                  this._date.getUTCMinutes(),
	                  this._date.getUTCSeconds(),
	                  this._date.getUTCMilliseconds()
	                );
	                this.viewDate = this.UTCDate(
	                  year, month, Math.min(28, day) , 0, 0, 0, 0);
	                this.fillDate();
	                this.set();
	                this.notifyChange();
	                this.hide();
	                this.flagPick = false;
	              }
	              	if (this.validator) {
                		this.validator.$el.remove();
                		this.$el.removeClass('has-error');
            		}
	              break;
	          }
	        }
	      }
	    },

	    actions: {
			incrementHours: function(e) {
				this._date.setUTCHours(this._date.getUTCHours() + 1);
			},

			incrementMinutes: function(e) {
				this._date.setUTCMinutes(this._date.getUTCMinutes() + 1);
			},

			incrementSeconds: function(e) {
				this._date.setUTCSeconds(this._date.getUTCSeconds() + 1);
			},

			decrementHours: function(e) {
				this._date.setUTCHours(this._date.getUTCHours() - 1);
			},

			decrementMinutes: function(e) {
				this._date.setUTCMinutes(this._date.getUTCMinutes() - 1);
			},

			decrementSeconds: function(e) {
				this._date.setUTCSeconds(this._date.getUTCSeconds() - 1);
			},

			togglePeriod: function(e) {
				var hour = this._date.getUTCHours();
				if (hour >= 12) hour -= 12;
				else hour += 12;
				this._date.setUTCHours(hour);
			},

			showPicker: function() {
				this.widget.find('.timepicker > div:not(.timepicker-picker)').hide();
				this.widget.find('.timepicker .timepicker-picker').show();
			},

			showHours: function() {
				this.widget.find('.timepicker .timepicker-picker').hide();
				this.widget.find('.timepicker .timepicker-hours').show();
			},

			showMinutes: function() {
				this.widget.find('.timepicker .timepicker-picker').hide();
				this.widget.find('.timepicker .timepicker-minutes').show();
			},

			showSeconds: function() {
				this.widget.find('.timepicker .timepicker-picker').hide();
				this.widget.find('.timepicker .timepicker-seconds').show();
			},

			selectHour: function(e) {
				var tgt = $(e.target);
				var value = parseInt(tgt.text(), 10);
				if (this.options.pick12HourFormat) {
				  var current = this._date.getUTCHours();
				  if (current >= 12) {
				    if (value != 12) value = (value + 12) % 24;
				  } else {
				    if (value === 12) value = 0;
				    else value = value % 12;
				  }
				}
				this._date.setUTCHours(value);
				this.actions.showPicker.call(this);
			},

			selectMinute: function(e) {
				var tgt = $(e.target);
				var value = parseInt(tgt.text(), 10);
				this._date.setUTCMinutes(value);
				this.actions.showPicker.call(this);
			},

			selectSecond: function(e) {
				var tgt = $(e.target);
				var value = parseInt(tgt.text(), 10);
				this._date.setUTCSeconds(value);
				this.actions.showPicker.call(this);
			},

			done: function(e) {
				this.hide();
				this.flagPick = false;
				if (this.validator) {
					this.validator.$el.remove();
					this.$el.removeClass('has-error');
				}
			}
	    },

		doAction: function(e) {
	      e.stopPropagation();
	      e.preventDefault();
	      if (!this._date) this._date = this.UTCDate(1970, 0, 0, 0, 0, 0, 0);
	      var action = $(e.currentTarget).data('action');
	      var rv = this.actions[action].apply(this, arguments);
	      this.set();
	      this.fillTime();
	      this.notifyChange();
	      return rv;
	    },

	    stopEvent: function(e) {
	      e.stopPropagation();
	      e.preventDefault();
	    },

    
	    keydown: function(e) {
	      var self = this, k = e.which, input = $(e.target);
	      if (k == 8 || k == 46) {
	        // backspace and delete cause the maskPosition
	        // to be recalculated
	        setTimeout(function() {
	          self._resetMaskPos(input);
	        });
	      }
	    },

	    keypress: function(e) {
	      var k = e.which;
	      if (k == 8 || k == 46) {
	        // For those browsers which will trigger
	        // keypress on backspace/delete
	        return;
	      }
	      var input = $(e.target);
	      var c = String.fromCharCode(k);
	      var val = input.val() || '';
	      val += c;
	      var mask = this._mask[this._maskPos];
	      if (!mask) {
	        return false;
	      }
	      if (mask.end != val.length) {
	        return;
	      }
	      if (!mask.pattern.test(val.slice(mask.start))) {
	        val = val.slice(0, val.length - 1);
	        while ((mask = this._mask[this._maskPos]) && mask.character) {
	          val += mask.character;
	          // advance mask position past static
	          // part
	          this._maskPos++;
	        }
	        val += c;
	        if (mask.end != val.length) {
	          input.val(val);
	          return false;
	        } else {
	          if (!mask.pattern.test(val.slice(mask.start))) {
	            input.val(val.slice(0, mask.start));
	            return false;
	          } else {
	            input.val(val);
	            this._maskPos++;
	            return false;
	          }
	        }
	      } else {
	        this._maskPos++;
	      }
	    },

	    change: function(e) {
	      var input = $(e.target);
	      var val = input.val();
	      if (this._formatPattern.test(val)) {
	        this.update();
	        this.setValue(this._date.getTime());
	        this.notifyChange();
	        this.set();
	      } else if (val && val.trim()) {
	        this.setValue(this._date.getTime());
	        if (this._date) this.set();
	        else input.val('');
	      } else {
	        if (this._date) {
	          this.setValue(null);
	          // unset the date when the input is
	          // erased
	          this.notifyChange();
	          this._unset = true;
	        }
	      }
	      this._resetMaskPos(input);
	    },

	    showMode: function(dir) {
	      if (dir) {
	        this.viewMode = Math.max(this.minViewMode, Math.min(
	          2, this.viewMode + dir));
	      }
	      this.widget.find('.datepicker > div').hide().filter(
	        '.datepicker-'+this.DPGlobal.modes[this.viewMode].clsName).show();
	    },

		destroy: function() {
	      this._detachDatePickerEvents();
	      this._detachDatePickerGlobalEvents();
	      this.widget.remove();
	      this.$el.removeData('datetimepicker');
	      this.component.removeData('datetimepicker');
	    },

	    formatDate: function(d) {
	    	var that = this;
	      return this.format.replace(this.formatReplacer, function(match) {
	        var methodName, property, rv, len = match.length;
	        if (match === 'ms')
	          len = 1;
	        property = that.dateFormatComponents[match].property
	        if (property === 'Hours12') {
	          rv = d.getUTCHours();
	          if (rv === 0) rv = 12;
	          else if (rv !== 12) rv = rv % 12;
	        } else if (property === 'Period12') {
	          if (d.getUTCHours() >= 12) return 'PM';
	          else return 'AM';
		} else if (property === 'UTCYear') {
	          rv = d.getUTCFullYear();
	          rv = rv.toString().substr(2);   
	        } else {
	          methodName = 'get' + property;
	          rv = d[methodName]();
	        }
	        if (methodName === 'getUTCMonth') rv = rv + 1;
	        return that.padLeft(rv.toString(), len, '0');
	      });
	    },

	    parseDate: function(str) {
	      var match, i, property, methodName, value, parsed = {};
	      if (!(match = this._formatPattern.exec(str)))
	        return null;
	      for (i = 1; i < match.length; i++) {
	        property = this._propertiesByIndex[i];
	        if (!property)
	          continue;
	        value = match[i];
	        if (/^\d+$/.test(value))
	          value = parseInt(value, 10);
	        parsed[property] = value;
	      }
	      return this._finishParsingDate(parsed);
	    },

	    _resetMaskPos: function(input) {
	      var val = input.val();
	      for (var i = 0; i < this._mask.length; i++) {
	        if (this._mask[i].end > val.length) {
	          // If the mask has ended then jump to
	          // the next
	          this._maskPos = i;
	          break;
	        } else if (this._mask[i].end === val.length) {
	          this._maskPos = i + 1;
	          break;
	        }
	      }
	    },

	     _finishParsingDate: function(parsed) {
	      var year, month, date, hours, minutes, seconds, milliseconds;
	      year = parsed.UTCFullYear;
	      if (parsed.UTCYear) year = 2000 + parsed.UTCYear;
	      if (!year) year = 1970;
	      if (parsed.UTCMonth) month = parsed.UTCMonth - 1;
	      else month = 0;
	      date = parsed.UTCDate || 1;
	      hours = parsed.UTCHours || 0;
	      minutes = parsed.UTCMinutes || 0;
	      seconds = parsed.UTCSeconds || 0;
	      milliseconds = parsed.UTCMilliseconds || 0;
	      if (parsed.Hours12) {
	        hours = parsed.Hours12;
	      }
	      if (parsed.Period12) {
	        if (/pm/i.test(parsed.Period12)) {
	          if (hours != 12) hours = (hours + 12) % 24;
	        } else {
	          hours = hours % 12;
	        }
	      }
	      return this.UTCDate(year, month, date, hours, minutes, seconds, milliseconds);
	    },

	    _compileFormat: function () {
	      var match, component, components = [], mask = [],
	      str = this.format, propertiesByIndex = {}, i = 0, pos = 0;
	      while (match = this.formatComponent.exec(str)) {
	        component = match[0];
	        if (component in this.dateFormatComponents) {
	          i++;
	          propertiesByIndex[i] = this.dateFormatComponents[component].property;
	          components.push('\\s*' + this.dateFormatComponents[component].getPattern(
	            this) + '\\s*');
	          mask.push({
	            pattern: new RegExp(this.dateFormatComponents[component].getPattern(
	              this)),
	            property: this.dateFormatComponents[component].property,
	            start: pos,
	            end: pos += component.length
	          });
	        }
	        else {
	          components.push(this.escapeRegExp(component));
	          mask.push({
	            pattern: new RegExp(this.escapeRegExp(component)),
	            character: component,
	            start: pos,
	            end: ++pos
	          });
	        }
	        str = str.slice(component.length);
	      }
	      this._mask = mask;
	      this._maskPos = 0;
	      this._formatPattern = new RegExp(
	        '^\\s*' + components.join('') + '\\s*$');
	      this._propertiesByIndex = propertiesByIndex;
	    },

	    _attachDatePickerEvents: function() {
	      var self = this;
	      // this handles date picker clicks
	      this.widget.on('click', '.datepicker *', $.proxy(this.click, this));
	      // this handles time picker clicks
	      this.widget.on('click', '[data-action]', $.proxy(this.doAction, this));
	      this.widget.on('mousedown', $.proxy(this.stopEvent, this));
	      if (this.pickDate && this.pickTime) {
	        this.widget.on('click.togglePicker', '.accordion-toggle', function(e) {
	          e.stopPropagation();
	          var $this = $(this);
	          var $parent = $this.closest('ul');
	          var expanded = $parent.find('.collapse.in');
	          var closed = $parent.find('.collapse:not(.in)');

	          if (expanded && expanded.length) {
	            var collapseData = expanded.data('collapse');
	            if (collapseData && collapseData.transitioning) return;
	            //expanded.collapse('hide');
	            //closed.collapse('show')
	            expanded.hide("slow");
	            expanded.removeClass();
	            expanded.addClass("collapse");	            
	            closed.show("slow");
	            closed.removeClass();
	            closed.addClass("collapse in");
	            $this.find('i').toggleClass(self.timeIcon + ' ' + self.dateIcon);
	            self.$el.find('.add-on i').toggleClass(self.timeIcon + ' ' + self.dateIcon);
	          }
	        });
	      }
	      if (this.isInput) {
	        this.$el.on({
	          'focus': $.proxy(this.show, this),
	          'change': $.proxy(this.change, this)
	        });
	        if (this.options.maskInput) {
	          this.$el.on({
	            'keydown': $.proxy(this.keydown, this),
	            'keypress': $.proxy(this.keypress, this)
	          });
	        }
	      } else {
	        this.$el.on({
	          'change': $.proxy(this.change, this)
	        }, 'input');
	        if (this.options.maskInput) {
	          this.$el.on({
	            'keydown': $.proxy(this.keydown, this),
	            'keypress': $.proxy(this.keypress, this)
	          }, 'input');
	        }
	        if (this.component){
	          this.component.on('click', $.proxy(this.show, this));
	        } else {
	          this.$el.on('click', $.proxy(this.show, this));
	        }
	      }
	    },

	    _attachDatePickerGlobalEvents: function() {
	      $(window).on(
	        'resize.datetimepicker' + this.id, $.proxy(this.place, this));
	      if (!this.isInput) {
	        $(document).on(
	          'mousedown.datetimepicker' + this.id, $.proxy(this.hide, this));
	      }
	    },

	    _detachDatePickerEvents: function() {
	      this.widget.off('click', '.datepicker *', this.click);
	      this.widget.off('click', '[data-action]');
	      this.widget.off('mousedown', this.stopEvent);
	      if (this.pickDate && this.pickTime) {
	        this.widget.off('click.togglePicker');
	      }
	      if (this.isInput) {
	        this.$el.off({
	          'focus': this.show,
	          'change': this.change
	        });
	        if (this.options.maskInput) {
	          this.$el.off({
	            'keydown': this.keydown,
	            'keypress': this.keypress
	          });
	        }
	      } else {
	        this.$el.off({
	          'change': this.change
	        }, 'input');
	        if (this.options.maskInput) {
	          this.$el.off({
	            'keydown': this.keydown,
	            'keypress': this.keypress
	          }, 'input');
	        }
	        if (this.component){
	          this.component.off('click', this.show);
	        } else {
	          this.$el.off('click', this.show);
	        }
	      }
	    },

	    _detachDatePickerGlobalEvents: function () {
	      $(window).off('resize.datetimepicker' + this.id);
	      if (!this.isInput) {
	        $(document).off('mousedown.datetimepicker' + this.id);
	      }
	    },

	    _isInFixed: function() {
	      if (this.$el) {
	        var parents = this.$el.parents();
	        var inFixed = false;
	        for (var i=0; i<parents.length; i++) {
	            if ($(parents[i]).css('position') == 'fixed') {
	                inFixed = true;
	                break;
	            }
	        };
	        return inFixed;
	      } else {
	        return false;
	      }
	    },
	    validate: function(event){
	    	if (event) {
	    		if ((event.which === 9) && (event.which !==0)) { //tab key
	                this.keyPressed = true;
	            }
	    	}
	    	
	    	if(!this.model.get("disabled")) {
	    		this.model.set({value: this.$el.find("input").val()}, {validate: true});
	            if (this.validator) {
	                this.validator.$el.remove();
	                this.$el.removeClass('has-error');
	            }
	            if(!this.model.isValid()){
	                this.validator = new PMDynaform.view.Validator({
	                    model: this.model.get("validator")
	                });  
	                this.$el.find(".input-group").parent().append(this.validator.el);
	                this.applyStyleError();
	            }
	    	}
            
            return this;
        },
	    escapeRegExp: function(str) {
			return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		},
		padLeft: function(s, l, c) {
			if (l < s.length) return s;
			else return Array(l - s.length + 1).join(c || ' ') + s;
		},
		UTCDate: function () {
			return new Date(Date.UTC.apply(Date, arguments));
		},
		getTemplate: function(timeIcon, pickDate, pickTime, is12Hours, showSeconds, collapse) {
			if (pickDate && pickTime) {
			  return (
			    '<div class="bootstrap-datetimepicker-widget dropdown-menu">' +
			      '<ul>' +
			        '<li' + (collapse ? ' class="collapse in"' : '') + '>' +
			          '<div class="datepicker">' +
			            this.DPGlobal.template +
			          '</div>' +
			        '</li>' +
			        '<li class="picker-switch accordion-toggle"><a><i class="' + timeIcon + '"></i></a></li>' +
			        '<li' + (collapse ? ' class="collapse"' : '') + '>' +
			          '<div class="timepicker">' +
			            this.TPGlobal.getTemplate(is12Hours, showSeconds) +
			          '</div>' +
			        '</li>' +
			      '</ul>' +
			    '</div>'
			  );
			} else if (pickTime) {
			  return (
			    '<div class="bootstrap-datetimepicker-widget dropdown-menu">' +
			      '<div class="timepicker">' +
			        this.TPGlobal.getTemplate(is12Hours, showSeconds) +
			      '</div>' +
			    '</div>'
			  );
			} else {
			  return (
			    '<div class="bootstrap-datetimepicker-widget dropdown-menu">' +
			      '<div class="datepicker">' +
			        this.DPGlobal.template +
			      '</div>' +
			    '</div>'
			  );
			}
		},
	    setValuePicker: function (){
	    	this.setDate(this.model.attributes.value);
	    },
	    updateValueControl: function () {
            var inputVal = this.$el.find("input").val();

            this.model.set("value", inputVal);

            return this;    
        },
        getHTMLControl: function () {
            return this.$el.find(".input-group");
        },
	    render: function(){
	    	
	    	//this.$el = $(this.template(this.model.toJSON()));
	    	//$(".bootstrap-datetimepicker-widget").remove();
	    	this.$el.html(this.template(this.model.toJSON()));
    		this.renderInitialize({
	    		model: this.model
	    	});
	    	this.rendered = true;
	    	
	    	//this.setElement(this.$el);
	    	if (this.model.get("disabled")) {
	    		this.disable();
	    	}
	    	if (this.model.get("hint")) {
				this.enableTooltip();
			}
	    	if (this.model.get("mask")) {
                this.$el.find("input").mask(this.model.get("mask").replace(/\w/g,"#"));
            }
	    	return this;
	    }

	});

	PMDynaform.extendNamespace("PMDynaform.view.Datetime",DatetimeView);
}());

(function(){

    var SuggestView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-text").html()),
        templateList: _.template($("#tpl-suggest-list").html()),
        //templateElement: _.template($("#tpl-suggest-element").html()),        
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
        events: {
                "click li": "continueDependentFields",
                "keyup input": "validate",
                "keydown input": "refreshBinding"
        },
        initialize: function () {
            var that = this;
            //this.model.on("change:value", this.onChange, this);
            this.model.on("change", this.checkBinding, this);
            this.containerList = $(this.templateList());
            this.enableKeyUpEvent();
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
            if ((this.keyPressed === false) &&
                (this.clicked === false)) {
                this.render();
                
            }
            if (this.clicked) {
                this.onChange(event);
            }
        },
        setValueDefault: function(){
            this.model.set("value",$(this.el).find(":input").val()); 
        },
        hideSuggest : function (){
            this.containerList.hide();
            this.stackRow = 0;
        },
        showSuggest : function (){
            this.containerList.show();
        },
        _attachSuggestGlobalEvents: function() {
          if (this.containerList) {
             $(document).on("click."+this.$el, $.proxy(this.hideSuggest, this)); 
          }
        },
        _detachSuggestGlobalEvents: function() {
          if (!this.containerList) {
             $(document).off("click."+this.$el); 
          }
        },
        onChange: function (event)  {
            var i, 
            j, 
            item, 
            dependents, 
            viewItems, 
            valueSelected;

            dependents = this.model.get("dependentFields") ? this.model.get("dependentFields"): [];
            viewItems = this.parent.items.asArray();            
            if (dependents.length > 0) {
                for (i = 0; i < viewItems.length; i+=1) {
                    for (j = 0; j < dependents.length; j+=1) {
                        item = viewItems[i].model.get("name");  
                        if(dependents[j] === item) {
                            if (event) {
                                if (viewItems[i].onDependentHandler) {
                                    viewItems[i].onDependentHandler(item,valueSelected);
                                    viewItems[i].setValueDefault();    
                                    viewItems[i].render();                                            
                                }
                            }
                        }
                    }
                }
            }
        },
        createDependencies : function () {
            var i, 
            j, 
            item, 
            dependents, 
            viewItems;

            dependents = this.model.get("dependentFields") ? this.model.get("dependentFields"): [];
            viewItems = this.parent.items.asArray();            
            if (dependents.length > 0) {
                for (i = 0; i < viewItems.length; i+=1) {
                    for (j = 0; j < dependents.length; j+=1) {
                        item = viewItems[i].model.get("name");
                        if (dependents[j] === item) {
                            if (viewItems[i].model.setDependencies) {
                                viewItems[i].model.setDependencies(this);
                            }
                        }
                    }
                }
            }
            return this;
        },
        makeElements: function (event){
            var that = this,
            elementTpl,
            itemLabel,
            val,
            founded = false;

            this.input = this.$el.find("input");
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
                $.grep(that.elements, function(data, index){
                    itemLabel = data.label;
                    if (itemLabel.toLowerCase().indexOf(val.toLowerCase()) !== -1) {
                        that.updatingItemsList(data);
                        founded = true;

                        $(that.stackItems[that.stackRow]).addClass("pmdynaform-suggest-list-keyboard");
                    }
                });
                if (!founded) {
                    that.hideSuggest();
                }
            } else {
                this.hideSuggest();
            }
        },
        updatingItemsList: function (data) {
            var li = document.createElement("li"),
            span = document.createElement("span");

            span.innerHTML = data.label;
            span.setAttribute("data-value", data.value);
            span.setAttribute("selected", false);
            li.appendChild(span);
            li.className = "list-group-item";
            /*var elementTpl = this.templateElement({
                value: data.value, 
                label:data.label,
                selected: false
            });*/

            this.stackItems.push(li);

            this.containerList.append(li); 

            this.input.after(this.containerList);
            this.containerList.css("position", "absolute");
            this.containerList.css("zIndex", 3);
            this.containerList.css("border-radius", "5px");
            
            if (this.stackItems.length > 4) {
                this.containerList.css("height","200px");
            } else {
                this.containerList.css("height","auto");
            }
            
            this._attachSuggestGlobalEvents();
            //this.onChange(event);
            return this;
        },
        continueDependentFields: function (e) {
            var newValue,
            content;

            this.clicked = true;
            this.keyPressed = false;
            content = $(e.currentTarget).text();
            //newValue = $(this.el).find(":input").val();          
            $(this.el).find(":input").val(content);
            this.model.set("value", $(e.currentTarget).find("span").data().value);
            this.containerList.remove();
            this.stackRow = 0;
            this.clicked = false; 
            return this;
        },
        validate: function(event){
            if ((event.which === 9) && (event.which !==0)) { //tab key
                this.keyPressed = true;
            }
            if (!this.model.get("disabled")) {
                this.model.set({value: this.$el.find("input").val()}, {validate: true});
                if (this.validator) {
                    this.validator.$el.remove();
                    this.$el.removeClass('has-error has-feedback');
                }
                if(!this.model.isValid()){
                    this.validator = new PMDynaform.view.Validator({
                        model: this.model.get("validator")
                    });
                    this.$el.find("input").parent().append(this.validator.el);
                    this.applyStyleError();
                }
            }
            this.keyPressed = false;
            this.makeElements();
            return this;
        },
        getData: function() {
            return this.model.getData();
        },
        render: function() {
            this.createDependencies();            
            this.input = this.$el.find("input");
            this.model.emptyValue();            
            this.$el.html( this.template(this.model.toJSON()) );            
            return this;
        },
        onDependentHandler: function (name,value) {
            this.model.remoteProxyData(true);
            return this;
        },
        toggleItemSelected: function () {
            $(this.stackItems).removeClass("pmdynaform-suggest-list-keyboard");
            $(this.stackItems[this.stackRow]).addClass("pmdynaform-suggest-list-keyboard");

            return this;
        },
        enableKeyUpEvent: function () {
            var that = this, 
            code,
            containerScroll;
            
            this.$el.keyup(function (event) {
                if (that.stackItems.length >0) {
                    code = event.which;
                    if (code === 38) { // UP
                        if (that.stackRow > 0) {
                            that.stackRow-=1;
                            that.toggleItemSelected();
                        }
                        that.containerList.scrollTop(-10*parseInt(that.stackRow+1));
                    } 
                    if (code === 40) { // DOWN
                        if (that.stackRow < that.stackItems.length-1) {
                            that.stackRow+=1;
                            that.toggleItemSelected();
                        }
                        that.containerList.scrollTop(+10*parseInt(that.stackRow+1));
                    }
                    if ((code === 13)) { //ENTER
                        that.continueDependentFields({
                            currentTarget: $(event.currentTarget).find(".pmdynaform-suggest-list-keyboard")[0]
                        });
                    }
                }
            });
            
        },
        updateValueControl: function () {
            var inputVal = this.$el.find("input").val();

            this.model.set("value", inputVal);

            return this;    
        },
        getHTMLControl: function () {
            return this.$el.find("input");
        },
        afterRender : function () {
            //this.continueDependentFields();
            return this;
        },
    });

    PMDynaform.extendNamespace("PMDynaform.view.Suggest",SuggestView);
}());
(function(){
    
    var LinkView = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-link").html()),
        validator: null,
        
        initialize: function (){
            var that = this;
            this.model.on("change", this.render, this);
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Link",LinkView);
}());

(function(){
    
    var Label = PMDynaform.view.Field.extend({
        template: _.template($("#tpl-label").html()),
        validator: null,
        initialize: function (){
            this.model.on("change", this.render, this);
        }
    });

    PMDynaform.extendNamespace("PMDynaform.view.Label", Label);
}());

(function(){
    
    var Title = Backbone.View.extend({
        template: null,
        validator: null,
        etiquete: {
            title: _.template($("#tpl-label-title").html()),
            subtitle: _.template($("#tpl-label-subtitle").html())
        },
        initialize: function (){
            var type = this.model.get("type");
            this.template = this.etiquete[type];
            
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
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

(function(){
	var HiddenModel = Backbone.View.extend({
		template: _.template( $("#tpl-hidden").html()),
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.Hidden", HiddenModel);
	
}());

(function(){
	var ImageView = Backbone.View.extend({
		template: _.template( $("#tpl-image").html()),
		events: {
	        "keydown": "preventEvents"
	    },
		initialize: function (){
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
		render: function() {
			this.$el.html( this.template(this.model.toJSON()) );
			
			return this;
		}
	});

	PMDynaform.extendNamespace("PMDynaform.view.Image", ImageView);
	
}());

(function(){
	var SubFormView = Backbone.View.extend({
        template:_.template($('#tpl-subform').html()),
        formView: null,
        availableElements: null,
        parent: null,
		initialize: function (options) {
			var availableElements = [
                "text",
                "textarea",
                "checkbox",
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
                "grid"
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
                        if ($.inArray(json.items[i][j].type, this.availableElements) >=0) {
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
        validate: function () {
            this.isValid();
        },
        getItems: function () {
            return this.formView.items.asArray();;
        },
        isValid: function () {
            var i, formValid = true,
            itemsField = this.formView.items.asArray();

            if (itemsField.length > 0) {
                for (i = 0; i < itemsField.length; i+=1) {
                    if(itemsField[i].validate) {
                        itemsField[i].validate(event);
                        if (!itemsField[i].model.get("valid")) {
                            formValid = itemsField[i].model.get("valid");
                        }
                    }
                }
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
            this.$el.find(".pmdynaform-field-subform").append(this.formView.render().el);

            return this;
        }
	});
	
	//.pmdynaform-formcontainer
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
            //this.model.on("change", this.render, this);
            
        },
        onLoadGeoLocation: function () {
            if (this.model.get("currentLocation") && this.model.get("supportNavigator")) {
                this.geoLocation();
            } else {
                this.onLoadLocation();
            }
            return this;
        },
        geoLocation: function () {
            var that = this;

            navigator.geolocation.getCurrentPosition(function(position){
                that.model.set("latitude", position.coords.latitude);
                that.model.set("longitude", position.coords.longitude);
                that.onLoadLocation();
            });

            return this;
        },
        onLoadLocation: function () {
            var that = this,
            coords, 
            mapOptions,
            map,
            marker,
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
    var Annotation = Backbone.View.extend({
        validator: null,
        template: _.template($("#tpl-annotation").html()),
        initialize: function (){
            this.model.on("change", this.render, this);
        },
        render: function() {
            this.$el.html( this.template(this.model.toJSON()) );
            return this;
        }
    });
    
    PMDynaform.extendNamespace("PMDynaform.view.Annotation", Annotation);
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
    			"radio": "requiredRadioGroup",
    			"dropdown": "requiredDropDown",
    			"textarea": "requiredText",
    			"datetime": "requiredText",
                "suggest": "requiredText"                  
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
            validator = this.attributes.factory[this.get("type").toLowerCase()];

            this.set("valid", true);
            delete this.get("message")[validator];
            if (this.get("required")) {
            	if (PMDynaform.core.Validators[validator].fn(value) === false) {
	                this.set("valid", false);
	                this.set("message", {
	                    validator: PMDynaform.core.Validators[validator].message
	                });
	            }
            }

            if (this.get("maxLength")) {
                 if (PMDynaform.core.Validators.maxLength.fn(parseInt(value), parseInt(this.get("maxLength"))) === false) {
                    this.set("valid", false);
                    this.set("message", {
                        validator: PMDynaform.core.Validators.maxLength.message + " " +this.get("maxLength") + " characters"
                    });
                }
            }

            if (this.get("dataType") !== "" && value !== "") {
                if (PMDynaform.core.Validators[this.get("dataType")].fn(value) === false) {
                    this.set("valid", false);
                    this.set("message", {
                        "validator": PMDynaform.core.Validators[this.get("dataType")].message
                    });
                }
            }

            /*if (this.get("domain") === true && value !== "") {
                if (PMDynaform.core.Validators["domain"].fn(valueDomain, options) === false) {
                    this.set("valid", false);
                    this.set("message", {
                        validator: PMDynaform.core.Validators['domain'].message
                    });
                }
            }*/
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
			type: "form"
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
			type: "panel"
		},
		getData: function(){
			return {
				type: this.get("type"),
				action: this.get("action"),
				method: this.get("method")
			}
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
			value: ""
		},
        initialize: function () {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
        },
		getData: function() {
            return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
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
        checkHTMLtags: function (value) {
            var i,
            newValue = value;

            if (typeof value === "string") {
                if (value.match(/([\<])([^\>]{1,})*([\>])/i) !== null) {
                    value = value.replace(/</g, "&lt;");
                    newValue = value.replace(/>/g, "&gt;");
                }
                if (/\"|\'/g.test(newValue)) {
                    newValue = newValue.replace(/"/g, "&quot;");
                    newValue = newValue.replace(/'/g, "&#39;");
                }   
            }
            

            return newValue;
        },
        validate: function (attrs) {
            this.set("value", this.checkHTMLtags(attrs.value));
            this.set("label", this.checkHTMLtags(attrs.label));

            return this;
        },
        reviewRemoteVariable: function () {
            var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint;

            /*endpoint = this.getEndpointVariable({
                type: "variableInfo",
                keys: {
                    "{var_uid}": this.get("var_uid") || ""
                }
            });
            url = prj.getFullURL(endpoint);
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'GET',
                data: {},
                keys: prj.token,
                successCallback: function (xhr, response) {
                    that.set("variableInfo", response);*/
                    that.remoteProxyData();
                /*}
            });
            this.set("proxy", restClient);*/
            return this;
        },
        remoteProxyData: function (dependentParam) {
            var prj = this.get("project"),
            url,
            varInfo = this.attributes.variable || {},
            data = {}, arrayDep,
            currentOpt, k, i, 
            that = this, 
            restClient,
            endpoint, 
            variablesSql,
            remote = true;
            
            // If the var_sql parameter is empty, the remote proxy is not going to execute
            // By Default the min length for the string is 5, based in SELECT 
            if (!$.isEmptyObject(varInfo)) {

                if (!dependentParam) {
                    if (varInfo.var_sql) {
                        remote = (varInfo.var_sql.search(/@#|@@/) !== -1)? false: true; 
                    }
                }
                if ((typeof varInfo.var_sql !== "string") &&
                    (varInfo.var_sql.length < 6)) {
                    remote = false;
                }
                
                if ((typeof prj.endPointsPath['executeQuery'] === "string")
                    && (typeof this.attributes.variable === "string")
                    && remote === true) {

                    endpoint = this.getEndpointVariable({
                        type: "executeQuery",
                        keys: {
                            "{var_name}": this.get("variable") || ""
                        }
                    });
                    url = prj.getFullURL(endpoint);

                    arrayDep = this.get("dependenciesField");
                    
                    if (varInfo.var_sql.match(/@#[a-zA-Z_]+|@@[a-zA-Z_]+/g)) {
                        variablesSql = varInfo.var_sql.match(/@#[a-zA-Z_]+|@@[a-zA-Z_]+/g).toString().replace(/@@|@#/g,"").split(",");
                        for (i=0; i< variablesSql.length; i+=1) {
                            data[variablesSql[i]] = "";
                        }    
                    }
                    
                    for (i=0; i< arrayDep.length; i+=1){
                        view = arrayDep[i];
                        data[view.model.get("variable")] = view.model.get("value");
                    }

                    restClient = new PMDynaform.core.Proxy ({
                        url: url,
                        method: 'POST',
                        data: data,
                        keys: prj.token,
                        successCallback: function (xhr, response) {
                            var k, remoteOpt = [];
                            for (k =0; k< response.length; k+=1){
                                remoteOpt.push({
                                    value: response[k].value,
                                    label: response[k].text
                                })
                            }
                            localOpt = that.get("localOptions");
                            that.set("remoteOptions", remoteOpt);
                            that.set("options", localOpt.concat(remoteOpt));
                        }
                    });
                    this.set("proxy", restClient);
                }
            }
            					
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
        onChangeLabel: function (attrs, options) {
            this.attributes.label = this.checkHTMLtags(attrs.attributes.label);
            
            return this;
        },
        onChangeValue: function (attrs, options) {
            this.attributes.value = this.checkHTMLtags(attrs.attributes.value);
            
            if (this.attributes.options) {
                this.get("validator").set({
                    valueDomain: this.get("value"),
                    options: this.get("options") || []
                });
                this.get("validator").verifyValue();
            }

            return this;
        },
        onChangeOptions: function () {
            var i,
            newOptions = [],
            options = this.get("options");

            for (i=0; i<options.length; i+=1) {
                newOptions.push(this.checkHTMLtags(options[i]));
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
                //fieldsRelated[i].model.set("value", currentValue);
                fieldsRelated[i].render();
            }

            return this;
        }
	});
	PMDynaform.extendNamespace("PMDynaform.model.Field", FieldModel);
}());
(function(){
	var GridModel = PMDynaform.model.Field.extend({
		defaults: {
			caption: "Working on grids - pmdynaform",
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
			label: "Untitled label",
			layoutOpt: [
				"responsive",
				"static",
				"form"
			],
			layout: "responsive",
			pager: false,
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
			titleHeader: []
		},
		initialize: function (options) {
			this.set("label", this.checkHTMLtags(this.get("label")));
			this.on("change:label", this.onChangeLabel, this);
			if(options.project) {
                this.project = options.project;
            }
            this.setLayoutGrid();
            this.setPaginationItems();
            this.checkTotalRow();
		},
		setLayoutGrid: function () {
			if ($.inArray(this.get("layout"), this.get("layoutOpt")) < 0) {
				this.set("layout", "responsive");
			}

			return this;
		},
		setPaginationItems: function () {
			var rows = this.get("rows"),
			size = this.get("pageSize"),
			pagerItems;

			pagerItems = Math.ceil(rows/size) ? Math.ceil(rows/size) : 1;

			this.set("paginationItems", pagerItems);

			return this;
		},
		checkTotalRow: function () {
			var i;

			loop_total:
			for (i=0; i<this.attributes.columns.length; i+=1) {
				if(this.attributes.columns[i].function) {
					if(this.attributes.functionOptions[this.attributes.columns[i].function.toLowerCase()]) {
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
				if(this.attributes.columns[i].function) {
					if(this.attributes.functionOptions[this.attributes.columns[i].function.toLowerCase()]) {
						this.attributes.totalrow[i] = this[this.attributes.functionOptions[this.attributes.columns[i].function.toLowerCase()]](i);
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
			return {
				type: this.get("type"),
				name: this.get("name"),
				gridtable: null
			}
            return formData;
			
		}
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.GridPanel", GridModel);
}());
(function(){
	var ButtonModel = Backbone.Model.extend({
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
(function(){
	var DropdownModel =  PMDynaform.model.Field.extend({
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
            value: ""
		},
		initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.on("change:options", this.onChangeOptions, this);
            this.set("validator", new PMDynaform.model.Validator({
                domain: true
            }));
            this.set("dependenciesField",[]);
            this.setLocalOptions();
            if (this.get("executeInit")) {
                this.reviewRemoteVariable();    
            }
            this.setDefaultValue ();
        },
        setDefaultValue: function () {
            var options = this.get("options"),
            defaultValue = this.get("defaultValue");
            
            if ($.inArray(defaultValue.trim(), ["", null, undefined]) > 0) {
                this.set("defaultValue", options[0].value);
                this.set("value", options[0].value);
            }

            return this;
        },
        setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
        setDependencies: function(newDependencie) {
            var arrayDep, i, result, newArray = [];
            arrayDep = this.get("dependenciesField");
            if(arrayDep.indexOf(newDependencie) === -1){
            	arrayDep.push(newDependencie);
            }
            this.set("dependenciesField",[]);
            this.set("dependenciesField",arrayDep);
        },
        isValid: function(){
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
        },
        validate: function (attrs) {
        	
    		var valueFixed = attrs.value.trim();
            //this.set("value", valueFixed);
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("value", valueFixed);
            
            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
        	this.isValid();
            return this.get("valid");
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
            value: ""
		},
		initialize: function() {
			this.set("label", this.checkHTMLtags(this.get("label")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.on("change:options", this.onChangeOptions, this);
			this.set("validator", new PMDynaform.model.Validator({
				domain: true
			}));
			this.set("dependenciesField",[]);
			this.verifyControl();
			this.initControl();
			this.setLocalOptions();
			this.reviewRemoteVariable();
            
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
				if (!opts[i].label) {
					throw new Error ("The label parameter is necessary for the field");
				}
				if (!opts[i].value) {
					opts[i].value = opts[i].label;
				}
				if(opts[i].selected) {
					itemsSelected.push(opts[i].value.toString());
				}
				newOpts.push({
                    label: this.checkHTMLtags(opts[i].label),
                    value: this.checkHTMLtags(opts[i].value),
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
				if (!opts[i].label) {
					throw new Error ("The label parameter is necessary for the field");
				}
				if (!opts[i].value) {
					opts[i].value = opts[i].label;
				} else {
					opts[i].value = opts[i].value.toString();
				}
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
			return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
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
            rows: 3,
            group: "form",
            dataType: "string",
            hint: "",
            disabled: false,
            maxLength: null,
            mode: "edit",
            required: false,
            validator: null,
            valid: true
        },
        getData: function() {
            return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
        },
        initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));

            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            
            this.set("validator", new PMDynaform.model.Validator());
            this.initControl();
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
            var valueFixed = attrs.value.trim();
            this.set("value", valueFixed);
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("value", valueFixed);
            this.get("validator").set("maxLength", attrs.maxLength);
            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.TextArea", TextAreaModel);
}());
(function(){

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
            function: null,
            tooltipLabel: "",
            value: "",
            group: "form",
            defaultValue: "",
            dataType: "string",
            hint: "",
            mask: null,
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
            var_name: null
        },
        initialize: function() {
            //this.parseLabel();
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.set("validator", new PMDynaform.model.Validator());
            this.initControl();
        },
        initControl: function() {
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
        addFormulaTokenAssociated: function(formulator) {
            if (formulator instanceof PMDynaform.core.Formula) {
                //formulator.addField("field", this.get("name"));
                formulator.addTokenValue(this.get("name"), this.get("value"));
            }
            return this;
        },
        setDependencies: function(parentThis){
            
            return this;
        },
        addFormulaFieldName: function(otherField) {
            this.get("formulator").addField("field", otherField);
            return this;
        },
        updateFormulaValueAssociated: function(field) {
            var resultField = field.model.get("formulator").evaluate();

            field.model.set("value", resultField);
            return this;
        },
        isValid: function() {
            this.set("valid", this.get("validator").get("valid"));

            return this.get("valid");
        },
        validate: function (attrs) {
            var valueFixed = attrs.value.trim();

            this.set("value", valueFixed);
            this.get("validator").set("type", attrs.type);
            this.get("validator").set("required", attrs.required);
            this.get("validator").set("value", valueFixed);
            this.get("validator").set("maxLength", attrs.maxLength);
            this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            
            return this.get("valid");
        },
        getData: function() {
            return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Text", TextModel);
}());
(function(){
	var File =  PMDynaform.model.Field.extend({
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
            preview: false,
            required: false,
            size: 1, //1 MB
            type: "file",
            proxy: [],
            valid: true,
            validator: null,
            value: ""
        },
        initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:label", this.onChangeLabel, this);
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
        isValid: function() {
            this.set("valid", this.get("validator").get("valid"));
            
            return this.get("valid");
        },
        uploadSuccess: function (a, b, c) {
            console.log("SUCCESS",a, b, c);

            return this;
        },
        uploadFailure: function (a, b, c) {
            console.log("FAILURE",a, b, c);

            return this;
        },

        uploadFile: function (indexItem) {
            var file = this.get("items")[indexItem].file,
            rand = Math.floor((Math.random()*100000)+3),
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
                        console.log('progress', percentage);
                        if (percentage === 100) {
                          console.log('DONE!');
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
        validate: function (attrs) {
            
        }
	});

	PMDynaform.extendNamespace("PMDynaform.model.File", File);
}());
(function(){
	var CheckboxModel =  PMDynaform.model.Field.extend({
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
            name: PMDynaform.core.Utils.generateName("checkbox"),
			label: "",
            localOptions: [],
            maxLengthLabel: 15,
            mode: "edit",
            options: [
                {
                    label: "empty",
                    value: "empty"
                }
            ],
            readonly: false,
            required: false,
            remoteOptions: [],
            selected: [],
            type: "checkbox",
            tooltipLabel: "",
            validator: null,
            valid: true,
            var_name: null,
            var_uid: null,
            value: "",
            variableInfo: {}
		},
		initialize: function (attrs) {
            //this.parseLabel();
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:options", this.onChangeOptions, this);
            this.on("change:value", this.updateItemSelected, this);
			this.set("validator", new PMDynaform.model.Validator({
                type: attrs.type,
                required: attrs.required,
                dataType: attrs. dataType
            }));
			this.initControl();
			this.set("dependenciesField",[]);
            
            this.setLocalOptions();
            this.reviewRemoteVariable();
            
		},
		initControl: function() {
			var opts = this.get("options"), 
            i,
            newOpts = [],
			itemsSelected = [];

			for (i=0; i<opts.length; i+=1) {
				if (!opts[i].label) {
					throw new Error ("The label parameter is necessary for the field");
				}
				if (!opts[i].value) {
					opts[i].value = opts[i].label;
				}
				if(opts[i].selected) {
					itemsSelected.push(opts[i].value.toString());
				}
                newOpts.push({
                    label: this.checkHTMLtags(opts[i].label),
                    value: this.checkHTMLtags(opts[i].value),
                    selected: opts[i].selected? opts[i]: false
                });
			}
            this.set("options", newOpts);
			this.set("selected", itemsSelected);
		},
		setLocalOptions: function () {
            this.set("localOptions", this.get("options"));
            return this;
        },
        reviewRemoteVariable: function () {
            var prj = this.get("project"),
            url,
            restClient,
            that = this,
            endpoint;

            endpoint = this.getEndpointVariable({
                type: "variableInfo",
                keys: {
                    "{var_uid}": this.get("variable_uid") || ""
                }
            });
            url = prj.getFullURL(endpoint);
            restClient = new PMDynaform.core.Proxy ({
                url: url,
                method: 'GET',
                data: {},
                keys: prj.token,
                successCallback: function (xhr, response) {
                    that.set("variableInfo", response);
                    that.remoteProxyData();
                }
            });
            this.set("proxy", restClient);
            return this;
        },
		getData: function() {
			return {
                name: this.get("variable").var_name,
                value: this.get("selected").toString()
            };
		},	
		validate: function(attrs) {
			
            //this.get("validator").set("type", attrs.type);
            this.get("validator").set("value", attrs.selected.length);
            //this.get("validator").set("required", attrs.required);
            //this.get("validator").set("dataType", attrs.dataType);
            this.get("validator").verifyValue();
            this.isValid();
            return this.get("valid");
		},
		isValid: function(){
            this.set("valid", this.get("validator").get("valid"));
        	return this.get("valid");
        },
		setItemChecked: function(itemUpdated) {
			var opts = this.get("options"),
				selected = [],
				i;
			if (opts) {
				for(i=0; i<opts.length; i+=1) {
					if(opts[i].value.toString() === itemUpdated.value.toString()) {
						opts[i].selected = itemUpdated.checked;
					}
				}
                this.set("options", opts);

                for(i=0; i<opts.length; i+=1) {
                    if(opts[i].selected) {
                        selected.push(opts[i].value);
                    }
                }
                if (selected.length) {
                    this.attributes.value = selected[selected.length-1];
                }
                this.set("selected", selected);
                this.changeValuesFieldsRelated();
			}

            return this;
		},
        updateItemSelected: function () {
            var i,
            selected = this.get("selected") ;

            if (typeof this.attributes.value === "string"
                && this.attributes.value.length > 0) {
                selected = this.attributes.value.split(/,/g);    
            }
            this.setItemChecked({
                value: this.attributes.value,
                checked: true
            })
            for (i=0; i<selected.length; i+=1) {
                this.setItemChecked({
                    value: selected[i].trim(),
                    checked: true
                });
            }
            if (!this.attributes.disabled) {
                this.get("validator").set({
                    valueDomain: this.get("value"),
                    options: this.get("options")
                    //domain: this.attributes.value !== ""? true: false
                });
                this.get("validator").verifyValue();
            }
            

            return this;
        }
	});

	PMDynaform.extendNamespace("PMDynaform.model.Checkbox",CheckboxModel);
}());
(function(){
	var DatetimeModel =  PMDynaform.model.Field.extend({
		defaults: {
			colSpan: 12,
            colSpanLabel: 3,
            colSpanControl: 9,
            namespace: "pmdynaform",
            dataType: "date",
            disabled: false,
            format: null,
            formats: {
                datetime: "dd/MM/yyyy hh:mm:ss",
                date: "dd/MM/yyyy",
                time: "hh:mm:ss"
            },
            group: "form",
            hint: "",
            id: PMDynaform.core.Utils.generateID(),
            name: PMDynaform.core.Utils.generateName("datetime"),
			label: "Untitled label",
            placeholder: "",
            pickType: "datetime",
            mask: "YYYY-mm-dd",
            mode: "edit",
            required: false,
            type: "datetime",
            defaultValue: "",
            valid: true,
            validator: null,
            value: ""
        },
        getData: function() {
            return {
                name: this.get("variable").var_name,
                value: this.get("value")
            };
        },
        initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.initControl();
            this.set("validator", new PMDynaform.model.Validator());
        },
        initControl: function() {
            var maskObj, mask, defaultFormat;
            if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
            }
            if(this.get("pickType")) {
                maskObj = this.get("formats");
                if (maskObj[this.get("pickType")]) {
                    defaultFormat = maskObj[this.get("pickType")];
                }
                if (!this.get("format")) {
                    mask = defaultFormat;
                } else {
                    mask = this.get("format");
                }
                //mask = (maskObj[this.get("pickType")]) ? maskObj[this.get("pickType")] : 'dd/MM/yyyy hh:mm:ss' ;
                
                this.set("mask", mask);
            }


            return this;
        },
        onChangeValue: function (attrs, options) {
            this.attributes.value = this.checkHTMLtags(attrs.attributes.value);
            this.reviewValueMask();

            return this;
        },
        reviewValueMask: function () {
            var val = this.attributes.value,
            mask = this.attributes.mask;

            /**
             * There are different masks for the datetime field
             * Datetime 'dd/MM/yyyy hh:mm:ss', date 'dd/MM/yyyy' and time 'hh:mm:ss'
             */

            //maskValue = new RegExp("\\d+\\:\\d+\:\\d+","g");
            //"01/10/2014 10:19:31".match(/\d+\:\d+\:\d+/g) 


            return this;
        },
        isValid: function(){
            this.set("valid", this.get("validator").get("valid"));
            return this.get("valid");
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
            dependenciesField: []
        },
        initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
            this.set("validator", new PMDynaform.model.Validator());
            this.initControl();
            this.setLocalOptions();
            if (this.get("executeInit")) {
                this.reviewRemoteVariable();
            }
            
            //this.remoteProxyData();
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
            return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
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
            value: ""
        },
        initialize: function() {
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:label", this.onChangeLabel, this);
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
            return {
                name: this.get("variable").var_name || this.get("name"),
                value: this.get("value")
            };
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
            options: [""],
            required: false,
            type: "label"
        },
        getData: function() {
            return null;
        },
        initialize: function() {
            var i,
            newOptions = [],
            options = this.get("options");

            this.set("label", this.checkHTMLtags(this.get("label")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:options", this.onChangeOptions, this);
            
            for (i=0; i<options.length; i+=1) {
                newOptions.push(this.checkHTMLtags(options[i]));
            }
            this.set("options", newOptions);
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
            this.set("label", this.checkHTMLtags(this.get("label")));
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
(function(){
	var HiddenModel = Backbone.Model.extend({
		defaults: {
			colSpan: 12,
			dataType: "string",
			namespace: "pmdynaform",
			defaultValue: "",
			id: PMDynaform.core.Utils.generateID(),
 			name: PMDynaform.core.Utils.generateName("hidden"),
			type: "hidden",
			valid: true,
			value: ""
		},
		initialize: function () {
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:value", this.onChangeValue, this);
			this.initControl();
		},
		initControl: function () {
			if (this.get("defaultValue")) {
                this.set("value", this.get("defaultValue"));
			}
		},
		checkHTMLtags: function (value) {
            var i,
            newValue = value;
            if (typeof value === "string") {
            	if (value.match(/([\<])([^\>]{1,})*([\>])/i) !== null) {
	                value = value.replace(/</g, "&lt;");
	                newValue = value.replace(/>/g, "&gt;");
	            }
	            if (/\"|\'/g.test(value)) {
	                newValue = newValue.replace(/"/g, "&quot;");
	                newValue = newValue.replace(/'/g, "&#39;");
	            }
            }

            return newValue;
        }
	});
	
	PMDynaform.extendNamespace("PMDynaform.model.Hidden", HiddenModel);
}());
(function(){
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
			type: "image"
		},
		initialize: function (options) {
			var defaults;

			this.set("label", this.checkHTMLtags(this.get("label")));
            this.set("defaultValue", this.checkHTMLtags(this.get("defaultValue")));
            this.on("change:label", this.onChangeLabel, this);
            this.on("change:value", this.onChangeValue, this);
			if(options.project) {
                this.project = options.project;
            }
            this.setShapeType();
		},
		setShapeType: function () {
			var shape = this.get("shape"),
			types = this.get("shapeTypes"),
			selected;

			selected =types[shape] ? types[shape] : types["thumbnail"];
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
 			name: PMDynaform.core.Utils.generateName("subform"),
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
            latitude : null,
            longitude: null,
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
            this.set("label", this.checkHTMLtags(this.get("label")));
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
            this.set("label", this.checkHTMLtags(this.get("label")));
            this.on("change:label", this.onChangeLabel, this);
        }
    });
    PMDynaform.extendNamespace("PMDynaform.model.Annotation", Annotation);
}());
