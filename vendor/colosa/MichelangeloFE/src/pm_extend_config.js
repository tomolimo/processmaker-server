/**
 * configure and extend classes and prototypes similar to inheritance in
 * another languages. The method detects if we trying to extend a method of an object
 * or a function inserted into a prototype.
 * @param  {String|Object} classObj contains the value of the class that we want to take an method for modifying
 * @param  {Sting} method the name of the function that we will be modified or overwritten
 * @param  {function} additionalFunc contains a function with the new code or the additional code
 *                    for modified the original function.
 *
 * @return {[type]}    [returns the value of the function modified, with the new functionality in this context.]
 */

var PMExtend = function (classObj, method, additionalFunc) {
    var originalFunc;
    if (classObj.prototype !== undefined) {
        originalFunc = classObj.prototype[method];
        if (originalFunc !== undefined && typeof originalFunc === 'function') {
            return function () {
                var returnVal = originalFunc.apply(this, arguments);
                if (returnVal) {
                    returnVal = additionalFunc.apply(this, [returnVal].concat(arguments));
                } else {
                    additionalFunc.apply(this, arguments);
                }
                return returnVal;
            };

        } else {
            //You need to implement a catch on a higher level or at the plugin
            throw new Error("Cannot extend method " + method + " in Class " + classObj.name);
        }
    } else {
        originalFunc = classObj[method];
        if (originalFunc !== undefined && typeof originalFunc === 'function') {
            return function () {
                var res;
                res = originalFunc.apply(this, arguments);
                res = additionalFunc.apply(this, [res].concat(arguments));
                return res;
            };
        } else {
            //You need to implement a catch on a higher level or at the plugin
            throw new Error("Cannot extend method " + method + " in Class " + classObj.name);
        }
    }
};