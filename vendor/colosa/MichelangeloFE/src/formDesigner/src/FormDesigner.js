var FormDesigner = {};
FormDesigner.version = '0.1';
FormDesigner.extendNamespace = function (path, newClass) {
    var current,
        pathArray,
        extension,
        i;

    if (arguments.length !== 2) {
        throw new Error("FormDesigner.extendNamespace(): method needs 2 arguments");
    }

    pathArray = path.split('.');
    if (pathArray[0] === 'FormDesigner') {
        pathArray = pathArray.slice(1);
    }
    current = FormDesigner;

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
FormDesigner.generateUniqueId = function () {
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
    }, uniqid = function (prefix, more_entropy) {
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

        var retId,
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
        if (!this.php_js) {
            this.php_js = {};
        }
        // END REDUNDANT
        if (!this.php_js.uniqidSeed) { // init seed with big random int
            this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
        }
        this.php_js.uniqidSeed += 1;

        retId = prefix; // start with prefix, add current milliseconds hex string
        retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
        retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
        if (more_entropy) {
            // for more entropy we add a float lower to 10
            retId += (Math.random() * 10).toFixed(8).toString();
        }

        return retId;
    }, sUID;
    do {
        sUID = uniqid(rand(0, 999999999), true);
        sUID = sUID.replace('.', '0');
    } while (sUID.length !== 32);
    return sUID;
};
FormDesigner.getNextNumber = function (string, type, field, max) {
    var a, 
        i;
    if (max === undefined) {
        max = 0;
    }
    for (i in string) {
        if (typeof string[i] === "object")
            max = FormDesigner.getNextNumber(string[i], type, field, max);
        if (typeof string[i] === "string" && string[i] === type && string[field]) {
            a = parseInt(string[field].replace(/^\D+/g, ''));
            if (a >= max)
                max = a;
        }
    }
    return max;
};
FormDesigner.getNextNumberVar = function (jsonData, properties, callback) {
    PMDesigner.restApi.execute({
        method: "read",
        url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + "/project/" + PMDesigner.project.id + "/process-variables",
        success: function (data, textStatus, xhr) {
            var i,
                num,
                max,
                nextVar = FormDesigner.getNextNumber(jsonData, properties.type.value, "variable");
            max = nextVar;
            for (i = 0; i < data.length; i += 1) {
                if (data[i].var_name.indexOf(properties.type.value) >= 0 && data[i].var_name) {
                    num = parseInt(data[i].var_name.replace(/^\D+/g, ''));
                    if (num >= max) {
                        max = num;
                    }
                }
            }
            nextVar = max + 1;
            nextVar = nextVar.toString();
            nextVar = FormDesigner.leftPad(nextVar, 3, "0");
            nextVar = properties.type.value + "Var" + nextVar;
            callback(nextVar);
        },
        error: function (xhr, textStatus, errorThrown) {
        }
    });
};
FormDesigner.leftPad = function (string, length, fill) {
    if (string && typeof string === "string") {
        while (string.length < length) {
            string = fill + string;
        }
    }
    return string;
};
