/*
 * object.watch polyfill
 *
 * 2012-04-03
 *
 * By Eli Grey, http://eligrey.com
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

// object.watch
if (!Object.prototype.watch) {
	Object.defineProperty(Object.prototype, "watch", {
		  enumerable: false
		, configurable: true
		, writable: false
		, value: function (prop, handler) {
			var
			  oldval = this[prop]
			, newval = oldval
			, getter = function () {
				return newval;
			}
			, setter = function (val) {
				oldval = newval;
				return newval = handler.call(this, prop, oldval, val);
			}
			;
			
			if (delete this[prop]) { // can't watch constants
				Object.defineProperty(this, prop, {
					  get: getter
					, set: setter
					, enumerable: true
					, configurable: true
				});
			}
		}
	});
}

// object.unwatch
if (!Object.prototype.unwatch) {
	Object.defineProperty(Object.prototype, "unwatch", {
		  enumerable: false
		, configurable: true
		, writable: false
		, value: function (prop) {
			var val = this[prop];
			delete this[prop]; // remove accessors
			this[prop] = val;
		}
	});
}




/* This code is to define enviromentVariables function and 
	to prevent its redefinition by PM 
	the default implementation has a bug when used in iFrame
	see: http://bugs.processmaker.com/view.php?id=18974 */
	
enviromentVariablesNew = function (variable) {
    if (window.location) {
        var url1 = window.location.pathname;
        var variables = url1.split('/');
        var WORKSPACE = variables[1];
        WORKSPACE = WORKSPACE.substring(3);
        var LANG = variables[2];
        var SKIN = variables[3];

        if (variable == 'WORKSPACE') {
            return WORKSPACE;
        } else if (variable == 'LANG') {
            return LANG;
        } else if (variable == 'SKIN') {
            return SKIN;
        } else {
            return null;
        }
    }
}

enviromentVariables = enviromentVariablesNew ;

function enviromentVariablesWatch(id, old, cur){
	return old; // to prevent redefinition
}

window.watch( "enviromentVariables", enviromentVariablesWatch ) ;



