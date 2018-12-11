/**
 * @class RCBase64
 * This class Encode and Decode Base64
 * @singleton
 */
var RCBase64 = {
    /**
     * @private
     * @type {String} Valid Characters for Base64
     */
    keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +
        "abcdefghijklmnopqrstuvwxyz0123456789+/=",

    //
    /**
     * Public method for encoding
     * @param input
     * @return {String}
     */
    encode : function (input) {
        var output = "",
            chr1,
            chr2,
            chr3,
            enc1,
            enc2,
            enc3,
            enc4,
            i = 0;

        input = this.utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
                this.keyStr.charAt(enc1) + this.keyStr.charAt(enc2) +
                this.keyStr.charAt(enc3) + this.keyStr.charAt(enc4);

        }

        return output;
    },

    /**
     * Public method for decoding
     * @param input
     * @return {String}
     */
    decode : function (input) {
        var output = "",
            chr1,
            chr2,
            chr3,
            enc1,
            enc2,
            enc3,
            enc4,
            i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this.keyStr.indexOf(input.charAt(i++));
            enc2 = this.keyStr.indexOf(input.charAt(i++));
            enc3 = this.keyStr.indexOf(input.charAt(i++));
            enc4 = this.keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 !== 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 !== 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = this.utf8_decode(output);

        return output;

    },

    /**
     * private method for UTF-8 encoding
     * @param string
     * @return {String}
     * @private
     */
    utf8_encode : function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "", n, c;

        for (n = 0; n < string.length; n++) {

            c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    /**
     * private method for UTF-8 decoding
     * @param utftext
     * @return {String}
     * @private
     */
    utf8_decode : function (utftext) {
        var string = "",
            i = 0,
            c = 0,
            c2 = 0,
            c3 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) |
                    ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }
};
/**
 * @class RestClient
 * Little REST Client written in JS
 *
 * Usage:
 *
 *      var rc,
 *          success;
 *
 *      //Instantiate an new RestClient Object
 *      rc= new RestClient();
 *      //Set the authorization mode to OAuth 2.0
 *      rc.setAuthorizationType('oauth2');
 *      //Set the client Credentials
 *      rc.setClient('client_id', 'client_secret');
 *      //Set the rest authorization endpoint
 *      rc.setAuthorizationServer('http://rest.example.com/authorize');
 *      //Set the OAuth 2.0 Grant Type
 *      rc.setGrantType('client');
 *
 *      //Make the call
 *      success = rc.authorize({
 *          success: function (xhr, response) {
 *              //Process Access Token
 *          },
 *          failure: function(xhr, response){
 *              //Process Failure
 *          }
 *      });
 *
 *  Other Example:
 *
 *      var rc,
 *          success;
 *
 *      //Instantiate an new RestClient Object
 *      rc= new RestClient();
 *      //Set the authorization mode to none
 *      rc.setAuthorizationType('none');
 *
 *      //Make the REST call
 *      success = rc.consume({
 *          url: 'http://rest.example.com/resource',
 *          operation: 'read',
 *          success: function (xhr, response) {
 *              //Process REST Response
 *          },
 *          failure: function(xhr, response){
 *              //Process Failure
 *          }
 *      });
 *
 *
 * @constructor
 * Create the RestClient namespace and object
 *
 *      var rc = new RestClient();
 *
 * @return {RestClient}
 */
var RestClient;
RestClient = function () {
    /**
     * Library's Version
     * @type {String}
     */
    this.VERSION = 'RESTCLIENT_VERSION';
    /**
     * Stores the authorization variables
     * @cfg {Object}
     */
    this.authorization = {};
    /**
     * Stores the server variables
     * @cfg {Object}
     */
    this.server = {};
    /**
     * Stores the response variables
     * @cfg {Object}
     */
    this.response = {};
    /**
     * Stores the header variables
     * @cfg {Object}
     */
    this.headers = {};
    /**
     * Stores the Oauth2.0 access token
     * @cfg {Object}
     */
    this.accessToken = {};
    /**
     * Set if the RestClient send automatically the refresh token during a request
     * @type {Boolean}
     */
    this.autoUseRefreshToken = true;

    /**
     * Set if the RestClient stores automatically a valid access token received
     * @type {Boolean}
     */
    this.autoStoreAccessToken = true;
    /**
     * Stores the authorization type. Values Accepted: none, basic, oauth2
     * @type {String}
     */
    this.authorizationType = 'none';

    /**
     * Stores the request content-type
     * @type {String}
     */
    this.contentType = 'application/json';

    /**
     * Stores if the RestClient will send and Bearer Authorization Header
     * @type {Boolean}
     */
    this.sendOAuthBearerAuthorization = false;

    /**
     * Stores the information about the way to process the response
     *
     * Valid values are: 'json', 'plain','form', 'html'
     * @type {String}
     */

    this.dataType = 'json';

    /**
     * Stores if OAuth 2.0 Authorization need set Authorization Header
     * @type {Boolean}
     */
    this.oauth2NeedsAuthorization = true;

    /**
     * Stores the message to compare when an access token is expired
     * @type {String}
     */
    this.expiredAccessTokenMessage = '';

    /**
     * Sets if the restclient will apply REST or AJAX to connect the server
     * @type {Boolean}
     */
    this.restfulBehavior = true;

    /**
     * Sets the backup URL when the restful is not available
     * @type {String}
     */
    this.backupAJAXURL = null;
    /**
     * The property is used to specify certain media types which are aceptable for the response.
     * @type {String}
     */
    this.acceptType = null;
    /**
     * Stores the REST method/verbs accepted
     * @type {Object}
     * @private
     */
    this.RESTMethods = {
        'create' : 'POST',
        'read' : 'GET',
        'update' : 'PUT',
        'delete' : 'DELETE'
    };
    /**
     * Setting the OAUTH2 Grant Types
     * @type {Object}
     * @private
     */
    this.OAUTH2GrantTypes = {
        'code' : 'authorization_code',
        'implicit' : 'token',
        'user' : 'password',
        'client' : 'client_credentials',
        'refresh' : 'refresh_token'
    };

    RestClient.prototype.initObject.call(this);
};

/**
 * Http Success Constant
 * @type {Number}
 */
RestClient.prototype.HTTP_SUCCESS = ["200","201","202","204","207"];
/**
 * Http Bad Request Constant
 * @type {Number}
 */
RestClient.prototype.HTTP_BAD_REQUEST = 400;
/**
 * Http Unauthorized Constant
 * @type {Number}
 */
RestClient.prototype.HTTP_UNAUTHORIZED = 401;

/**
 * OAuth 2.0 Invalid Grant Error Message
 * @type {String}
 */
RestClient.prototype.OAUTH2_INVALID_GRANT = "invalid_grant";


/**
 * Resets the RestClient
 */
RestClient.prototype.initObject = function () {
    this.authorization = {};
    this.server = {};
    this.response = {};
    this.headers = {};
    this.accessToken = {};
    this.autoUseRefreshToken = true;
    this.autoStoreAccessToken = true;
    this.authorizationType = 'none';
    this.contentType = 'application/json';
    this.acceptType = 'application/json';
    this.sendOAuthBearerAuthorization = false;
    this.oauth2NeedsAuthorization = true;
    this.dataType = 'json';
    this.expiredAccessTokenMessage = 'The access token provided has expired.';
};

/**
 * Set the value for use refresh token automatically
 * @param value
 * @return {*}
 */
RestClient.prototype.setUseRefreshTokenAutomatically = function (value) {
    if (_.isBoolean(value)) {
        this.autoUseRefreshToken = value;
    }
    return this;
};

/**
 * Set if the access token should be stored automatically
 * @param value
 * @return {*}
 */
RestClient.prototype.setStoreAccessTokenAutomatically = function (value) {
    if (_.isBoolean(value)) {
        this.autoStoreAccessToken = value;
    }
    return this;
};

/**
 * Sets the authorization type
 * @param {String} type Valid Authorization Type
 * @return {*}
 */
RestClient.prototype.setAuthorizationType = function (type) {
    var acceptedTypes = {none: 1, basic: 1, oauth2: 1};
    if (acceptedTypes[type]) {
        this.authorizationType = type;
    }
    return this;
};

/**
 * Set the request content-type
 * @param value
 * @return {*}
 */
RestClient.prototype.setContentType = function (value) {
    this.contentType = value;
    return this;
};
/**
 * Sets the request accept type
 * @param {String} type Represent the accept type
 */
RestClient.prototype.setAcceptType = function (type) {
    var availableAcceptTypes = {
        plain: "text/plain",
        xhtml_xml: "application/xhtml+xml",
        json: "application/json",
        xml: "application/xml",
        all: "*/*"
    };
    
    if (availableAcceptTypes[type]) {
        this.acceptType = availableAcceptTypes[type];
    }
    return this;
};
/**
 * Sets if into OAuth 2.0 mode should be sent the bearer authorization header
 * @param value
 * @return {*}
 */
RestClient.prototype.setSendBearerAuthorization = function (value) {
    if (_.isBoolean(value)) {
        this.sendOAuthBearerAuthorization = value;
    }
    return this;
};

/**
 * Set if OAuth 2.0 Authorization Request sends Authorization Header
 * @param value
 * @return {*}
 */
RestClient.prototype.setOAuth2NeedsAuthorization = function (value) {
    if (_.isBoolean(value)) {
        this.oauth2NeedsAuthorization = value;
    }
    return this;
};
/**
 * Set the DataType used to request data
 * @param type
 * @return {*}
 */
RestClient.prototype.setDataType = function (type) {
    var acceptedDataTypes = {
        json: 'application/json',
        plain: 'text/plain',
        form: 'application/x-www-form-urlencoded',
        html: 'text/html'
    };
    if (acceptedDataTypes[type]) {
        this.dataType = type;
        this.contentType = acceptedDataTypes[type];
    }
    return this;
};

/**
 * Set the message to compare when and access token is expired
 * @param {String} msg
 * @return {*}
 */
RestClient.prototype.setAccessTokenExpiredMessage = function (msg) {
    this.expiredAccessTokenMessage = msg;
    return this;
};

/**
 * Returns the library version
 * @return {String} RestClient Version
 */
RestClient.prototype.getVersion = function () {
    return this.VERSION;
};

/**
 * Setting the client authorization credentials
 * @param {String} client_id Client Identifier
 * @param {String} client_secret Client Secret or Password
 * @param {String} client_url Authorization URL
 * @return {RestClient}
 */
RestClient.prototype.setClient = function (client_id, client_secret,
                                           client_url) {
    this.authorization.client_id = client_id;
    this.authorization.client_secret = client_secret;
    this.authorization.client_url = (client_url !== 'undefined') ? client_url
        : null;
    return this;
};

/**
 * Setting the OAuth2 Grant Type and Data
 * @param {String} type Grant Type
 * @param {Object} data
 * @return {RestClient}
 */
RestClient.prototype.setGrantType = function (type, data) {
    this.authorization.grant_type = (this.OAUTH2GrantTypes[type] !==
        'undefined') ? this.OAUTH2GrantTypes[type]
        : null;
    this.authorization = _.extend(this.authorization, data);
    return this;
};

/**
 * Setting the Server URL to consume REST
 * @param {String} url Authorization URL
 * @return {Boolean}
 */
RestClient.prototype.setAuthorizationServer = function (url) {
    var result = true,
        reg_url;
    if (typeof url === 'undefined' || url === null) {
        result = false;
    } else {
        reg_url = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        if (url.match(reg_url)) {
            this.server.rest_auth_uri = url;
        } else {
            result = false;
        }
    }
    return result;
};

/**
 * Add HTML header information to send though XHR
 * @param {String} name Name Field
 * @param {String} value Value Field
 * @return {Boolean}
 */
RestClient.prototype.setHeader = function (name, value) {
    var response = true,
        addObj;
    if (name && value) {
        addObj = JSON.parse('{"' + name + '" :  "' + value + '"}');
        this.headers = _.extend(this.headers, addObj);
    } else {
        response = false;
    }
    return response;
};
/**
 * Parses the response text from the server
 * @param {String} response The string to parse as JSON.
 * @return {Object} Returns the Object corresponding to the given JSON text.
 */
RestClient.prototype.JSONParse = function (response) {
    var r;
    try {
        if (!response){
            r = "";
        } else if (response === ""){
            r = "";
        } else {
            r = JSON.parse(response);
        }
    } catch (e) {
        r = "ERROR_PARSE";
    }
    return r;
};

/**
 * Set the user and password for the basic authentication method
 * @param {String} username
 * @param {String} password
 * @return {RestClient}
 */
RestClient.prototype.setBasicCredentials = function (username, password) {
    this.authorization.basic_user = username;
    this.authorization.basic_password = password;
    return this;
};
/**
 * Set manually with an access token
 * @param {Object} obj
 * @return {*}
 */
RestClient.prototype.setAccessToken = function (obj) {
    if (typeof obj === 'object') {
        this.accessToken = obj;
    }
    return this;
};

/**
 * Sets the restful behavior for this object
 * @param {Boolean} value
 * @return {*}
 */
RestClient.prototype.setRestfulBehavior = function (value) {
    if (_.isBoolean(value)) {
        this.restfulBehavior = value;
    }
    return this;
};

/**
 * Sets the backup Ajax URL
 * @param {String} url
 * @return {*}
 */
RestClient.prototype.setBackupAjaxUrl = function (url) {
    this.backupAJAXURL = url;
    return this;
};

/**
 * Convert an Object to Key/Value string
 * @param {Object} obj Input Object
 * @return {String}
 * @private
 */
RestClient.prototype.toParams = function (obj) {
    var keys = _.keys(obj),
        out = [];
    _.each(keys, function (key) {
        out.push(key + '=' + obj[key]);
    });
    return out.join('&');
};

/**
 * Prepares the data to send through XHR depending of the content-type defined
 * @param {*} data
 * @return {String}
 */
RestClient.prototype.prepareBody = function (data) {
    var out = '';
    if (this.dataType === 'json' || this.dataType === 'jsonp') {
        if (typeof data === 'object') {
            out = JSON.stringify(data);
        }
    } else {
        out = this.toParams(data);
    }
    return out;
};

/**
 * Create an object XmlHttpRequest or returns false if fails
 * @return {*}
 */
RestClient.prototype.createXHR = function () {
    var httpRequest;
    if (window.XMLHttpRequest) {
        httpRequest = new XMLHttpRequest();
    } else {
        try {
            httpRequest = new ActiveXObject("MSXML2.XMLHTTP");
        } catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (ex) {
            }
        }
    }
    if (!httpRequest) {
        return false;
    }
    return httpRequest;
};

/**
 * Request an OAuth 2.0 Access Token
 * @param {Object} [options] Authorization Options
 *
 * Options Example:
 *
 *     {
 *         //fires when the authorize method is success
 *         success: function (xhr, response) {
 *             //Process Success
 *         },
 *         //fires when the authorize method is failure
 *         failure: function (xhr, response) {
 *             //Process Failure
 *         },
 *         //fires when the XmlHttpRequest is ready
 *         ready: function (xhr) {
 *             //Show States
 *         },
 *         //fires when the restclient obtain a new access token successfully
 *         autorefresh: function (accessToken) {
 *            //Update Token
 *         },
 *         //fires if the restclient cannot create a XmlHttpRequest Object
 *         xhrfailure: function (error, data) {
 *            //Notify Client Browser Not Supported
 *         },
 *         //fires when the request finishes
 *         complete: function(xhr, response) {
 *             //Process Complete
 *         }
 *     }
 *
 * @return {Boolean}
 */
RestClient.prototype.authorize = function (options) {
    var self = this,
        success = false,
        operation = 'create',
        method = this.RESTMethods[operation],
        basicHash,
        xhr,
        body,
        response;

    basicHash = RCBase64.encode(this.authorization.client_id + ':' +
        this.authorization.client_secret);

    xhr = this.createXHR();
    try {
        xhr.open(method, this.server.rest_auth_uri, false);
    } catch (e) {
        if (options.xhrfailure) {
            options.xhrfailure(e, {});
        } else {
            this.XHRFailure(e, {});
        }
        return success;
    }

    xhr.onreadystatechange = function () {
        if (options.ready) {
            options.ready(xhr);
        } else {
            self.AuthorizeReady(xhr);
        }
        if (xhr.readyState === 4) {
            response = self.JSONParse(xhr.responseText);
            if ((self.HTTP_SUCCESS).indexOf(String(xhr.status)) != -1 && response !== "ERROR_PARSE") {

                    if (self.autoStoreAccessToken) {
                        self.accessToken = response.token || {};
                    }
                    success = true;
                    if (options.success) {
                        try {
                            options.success(xhr, response);
                        } catch (e) {
                            throw new Error (e.message);
                        }
                    } else {
                        self.AuthorizeSuccess(xhr, response);
                    }
   
                
            } else {

                if (response === "ERROR_PARSE"){
                    response = {
                        'success': false,
                        'error' : {
                            'error' : self.HTTP_BAD_REQUEST,
                            'error_description' : 'Response is not a valid JSON'
                        }
                    };
                }
                
                if (options.failure) {
                    try {
                        options.failure(xhr, response);
                    } catch (e) {
                        throw new Error (e.message);
                    }
                } else {
                    self.AuthorizeFailure(xhr, response);
                }
            }
            if(typeof options.complete === 'function') {
                options.complete(xhr, response);
            }
        }
    };

    body = {};

    if (this.authorization.grant_type) {
        body.grant_type = this.authorization.grant_type;
        switch (this.authorization.grant_type) {
        case 'authorization_code':
            body.code = this.authorization.code;
            break;
        case 'token':
            body.token = this.authorization.token;
            break;
        case 'password':
            body.username = this.authorization.username;
            body.password = this.authorization.password;
            break;
        case 'client_credentials':
            body.client_id = this.authorization.client_id;
            body.client_id = this.authorization.client_secret;
            break;
        case 'refresh_token':
            body.refresh_token = this.authorization.refresh_token;
            break;
        }
    }

    //SEND HEADERS
    if (this.oauth2NeedsAuthorization) {
        xhr.setRequestHeader("Authorization", "Basic " + basicHash);
    }
    xhr.setRequestHeader("Accept", this.acceptType);
    xhr.setRequestHeader("Content-Type", this.contentType);

    //Insert Headers
    _.each(this.headers, function (value, key) {
        xhr.setRequestHeader(key, value);
    });

    xhr.send(this.prepareBody(body));

    return success;
};

/**
 * Prepare the error object when exists required fields not found
 * @param {Array} fields Required Fields Array
 * @return {Object} Error Object
 * @private
 */
RestClient.prototype.prepareReqFields = function (fields) {
    var response;
    response = {
        success: false,
        error: {
            error: this.HTTP_BAD_REQUEST,
            error_description : 'Required fields not found'
        },
        fields: fields
    };
    return response;
};

/**
 * Prepares the consume URL using configurations
 * @param {String} operation
 * @param {String} url
 * @param {String} id
 * @param {Object} data
 * @return {Object}
 */
RestClient.prototype.prepareConsumeUrl = function (operation, url, id, data) {
    var auxUrl,
        auxBody,
        auxAccessType = this.acceptType,
        auxContentType = this.contentType,
        usedQuestionMark = false;
    if (this.restfulBehavior) {
        switch (operation) {
        case 'read':
            auxUrl = url;
            if (id) {
                auxUrl += id;
            }
            if (this.authorizationType === 'oauth2' && !this.sendOAuthBearerAuthorization) {
                usedQuestionMark = true;
                auxUrl += '?access_token=' + this.accessToken.access_token;
            }
            if (data && data !== {}) {
                if (usedQuestionMark) {
                    auxUrl += "&";
                } else {
                    auxUrl += "?";
                }
                auxUrl += this.toParams(data);
            }
            auxBody = null;
            if (auxContentType === null) {
                auxContentType = 'application/json';
            }
            
            break;
        case 'create':
            auxUrl = url;
            auxBody = data || {};
            if (this.authorizationType === 'oauth2' && !this.sendOAuthBearerAuthorization) {
                auxBody.access_token = this.accessToken.access_token;
            }
            auxBody = this.prepareBody(auxBody);
            break;
        case 'update':
            auxUrl = url;
            if (id) {
                auxUrl += id;
            }
            auxBody = data || {};
            if (this.authorizationType === 'oauth2' && !this.sendOAuthBearerAuthorization) {
                auxBody.access_token = this.accessToken.access_token;
            }
            auxBody = this.prepareBody(auxBody);
            break;
        case 'delete':
            auxUrl = url;
            if (id) {
                auxUrl += id;
            }
            auxBody = data || {};
            if (this.authorizationType === 'oauth2' && !this.sendOAuthBearerAuthorization) {
                auxBody.access_token = this.accessToken.access_token;
            }
            auxBody = this.prepareBody(auxBody);
            break;
        }
    } else {
        auxUrl = this.backupAJAXURL;
        auxBody = {
            operation: operation,
            url: url,
            id: id,
            data: data
        };
        auxBody = "data='" + encodeURIComponent(JSON.stringify(auxBody)) + "'";
        if (auxContentType === null) {
            auxContentType = 'application/json';    
        }
    }
    if (!auxAccessType){
        auxAccessType = "*/*";
    }
    return {
        url: auxUrl,
        body: auxBody,
        content_type: auxContentType,
        acceptType: auxAccessType
    };

};

/**
 * Consume REST through GET Method
 * @param {Object} config Configuration Object
 *
 * To view an example of this config object go to {@link #consume} method
 * @return {Boolean} REST Response Status
 */
RestClient.prototype.getCall = function (config) {
    config.operation = 'read';
    return this.consume(config);
};

/**
 * Consume REST through POST Method
 * @param {Object} config Configuration Object
 *
 * To view an example of this config object go to {@link #consume} method
 * @return {Boolean} REST Response Status
 */
RestClient.prototype.postCall = function (config) {
    config.operation = 'create';
    return this.consume(config);
};

/**
 * Consume REST through PUT Method
 * @param {Object} config Configuration Object
 *
 * To view an example of this config object go to {@link #consume} method
 * @return {Boolean} REST Response Status
 */
RestClient.prototype.putCall = function (config) {
    config.operation = 'update';
    return this.consume(config);
};

/**
 * Consume REST through DELETE Method
 * @param {Object} config Configuration Object
 *
 * To view an example of this config object go to {@link #consume} method
 * @return {Boolean} REST Response Status
 */
RestClient.prototype.deleteCall = function (config) {
    config.operation = 'delete';
    return this.consume(config);
};

/**
 * Consume  REST method
 * @param {Object} options Consume Options
 *
 * Options Example:
 *
 *     {
 *         //ID related to the operation (optional)
 *         id : 12,
 *         //URL to consume the rest
 *         url: 'http://rest.colosa.com/resource',
 *         //operation to consume: 'create', 'read', 'update', 'delete'
 *         operation: 'create',
 *         //{Object} to be sent
 *         data: {},
 *         //fires when the authorize method is success
 *         success: function (xhr, response) {
 *             //Process Success
 *         },
 *         //fires when the authorize method is failure
 *         failure: function (xhr, response) {
 *             //Process Failure
 *         },
 *         //fires when the XmlHttpRequest is ready
 *         ready: function (xhr) {
 *             //Show States
 *         },
 *         //fires if the restclient cannot create a XmlHttpRequest Object
 *         xhrfailure: function (error, data) {
 *            //Notify Client Browser Not Supported
 *         },
 *         //fires when the request finishes
 *         complete: function(xhr, response) {
 *             //Process Complete
 *         }
 *     }
 * @return {Boolean}
 */
RestClient.prototype.consume = function (options) {
    var basicHash,
        xhr,
        operation,
        method,
        response = {},
        body,
        prepareUrl,
        self,
        error,
        success = true,
        url,
        requiredFields = [],
        data,
        id,
        prepare,
        bearerText,
        contentType,
        acceptType,
        accessTokenExpired = false;

    if (options.operation) {
        operation = options.operation;
    } else {
        success = false;
        requiredFields.push('operation');
    }

    if (options.url) {
        url = options.url;
    } else {
        success = false;
        requiredFields.push('url');
    }

    data = options.data || null;
    id = options.id || null;

    if (!success) {
        if (options.failure) {
            options.failure(null, this.prepareReqFields(requiredFields));
        } else {
            this.ConsumeFailure(null, this.prepareReqFields(requiredFields));
        }
        return success;
    }

    prepare = this.prepareConsumeUrl(operation, url, id, data);
    prepareUrl = prepare.url;
    body = prepare.body;
    contentType = prepare.content_type;
    acceptType = prepare.acceptType;

    xhr = this.createXHR();
    
    if (this.restfulBehavior) {
        method = this.RESTMethods[operation];
    } else {
        method = this.RESTMethods.create;
    }
    try {
        xhr.open(method, prepareUrl, false);
        switch (this.authorizationType) {
            case 'none':
                break;
            case 'basic':
                basicHash = RCBase64.encode(this.authorization.basic_user + ':' +
                    this.authorization.basic_password);
                xhr.setRequestHeader("Authorization", "Basic " + basicHash);
                break;
            case 'oauth2':
                if (!this.accessToken.access_token) {
                    success = false;
                    requiredFields.push('access_token');
                    error = {
                        success: false,
                        error : {
                            error : this.HTTP_BAD_REQUEST,
                            error_description: 'Access Token not defined'
                        }
                    };
                    if (options.failure) {
                        options.failure(null, this.prepareReqFields(requiredFields));
                    } else {
                        this.ConsumeFailure(null, this.prepareReqFields(requiredFields));
                    }
                    return success;
                } else {
                    if (this.sendOAuthBearerAuthorization) {
                        bearerText = "Bearer " + this.accessToken.access_token;
                        xhr.setRequestHeader("Authorization", bearerText);
                    }
                }
                break;
        }
    } catch (exc) {
        if (options.xhrfailure) {
            options.xhrfailure(exc, data);
        } else {
            this.XHRFailure(exc, data);
        }
        return false;
    }

    self = this;
    xhr.onreadystatechange = function () {
        if (options.ready) {
            options.ready(xhr);
        } else {
            self.ConsumeReady(xhr);
        }
        if (xhr.readyState === 4) {

            response = self.JSONParse(xhr.responseText);
            if ((self.HTTP_SUCCESS).indexOf(String(xhr.status)) != -1 && response !== "ERROR_PARSE") {
                if (self.autoStoreAccessToken) {
                    self.accessToken = response.token || {};
                }
                success = true;
                if (options.success) {
                    try {
                        options.success(xhr, response);
                    } catch (e) {
                        throw new Error (e.message);
                    }
                } else {
                    self.AuthorizeSuccess(xhr, response);
                }
            } else {
                success = false;
                if (response === "ERROR_PARSE"){
                    response = {
                        'success': false,
                        'error' : {
                            'error' : self.HTTP_BAD_REQUEST,
                            'error_description' : 'Response is not a valid JSON'
                        }
                    };
                } else {
                    if (response.error === self.OAUTH2_INVALID_GRANT &&
                        response.error_description === self.expiredAccessTokenMessage){
                        accessTokenExpired = true;
                    }
                    if (xhr.status === self.HTTP_UNAUTHORIZED && self.autoUseRefreshToken && accessTokenExpired) {

                        if (self.accessToken.refresh_token) {
                            self.setGrantType('refresh', {refresh_token: self.accessToken.refresh_token});
                            self.authorize({
                                success: function (x, data) {
                                    success = self.consume(options);
                                    if (success) {
                                        if (options.autorefresh) {
                                            options.autorefresh(self.accessToken);
                                        } else {
                                            self.AuthorizeAutoRefresh(self.accessToken);
                                        }
                                    }
                                },
                                failure: function (x, data) {
                                    success = false;
                                    if (options.failure) {
                                        options.failure(null, data);
                                    } else {
                                        self.ConsumeFailure(null, data);
                                    }
                                }
                            });
                        } else {
                            success = false;
                            response = {
                                success: false,
                                error: {
                                    error: self.HTTP_UNAUTHORIZED,
                                    error_description: 'Refresh token is not defined'
                                }
                            };
                        }

                    }

                }
                

                if (options.failure) {
                    try {
                        options.failure(xhr, response);
                    } catch (e) {
                        throw new Error (e.message);
                    }
                } else {
                    self.AuthorizeFailure(xhr, response);
                }
                
            }
            if(typeof options.complete === 'function') {
                options.complete(xhr, response);
            }
        }
    };
    xhr.setRequestHeader("Accept", acceptType);
    xhr.setRequestHeader("Content-Type", contentType);
    //Insert Custom Headers
    _.each(this.headers, function (value, key) {
        xhr.setRequestHeader(key, value);
    });

    xhr.send(body);

    return success;
};

/**
 * Captures when the RestClient cannot create an XHR object
 * @param error
 * @param data
 * @event
 */
RestClient.prototype.XHRFailure = function (error, data) {
};

/**
 * Captures when the Authorize method returns a success response
 * @param xhr
 * @param response
 * @event
 */
RestClient.prototype.AuthorizeSuccess = function (xhr, response) {
};

/**
 * Captures when the Authorize method returns a failure response
 * @param xhr
 * @param response
 * @event
 */
RestClient.prototype.AuthorizeFailure = function (xhr, response) {
};

/**
 * Captures when the Authorize method change the state (XHR)
 * @param xhr
 * @event
 */
RestClient.prototype.AuthorizeReady = function (xhr) {
};

/**
 * Captures when the Authorize method fires the refresh token authorization successfuly
 * @param accessToken
 * @event
 */
RestClient.prototype.AuthorizeAutoRefresh = function (accessToken) {
};

/**
 * Captures when the Consume method returns a success response
 * @param xhr
 * @param response
 * @event
 */
RestClient.prototype.ConsumeSuccess = function (xhr, response) {
};

/**
 * Captures when the Consume method returns a failure response
 * @param xhr
 * @param response
 * @event
 */
RestClient.prototype.ConsumeFailure = function (xhr, response) {
};

/**
 * Captures when the Consume method change the state (XHR)
 * @param xhr
 * @event
 */
RestClient.prototype.ConsumeReady = function (xhr) {
};

//Define Module to be used in server side (Node.js)
if (typeof exports !== 'undefined') {
    module.exports = {
        RestClient: RestClient,
        RCBase64: RCBase64
    };
    var _ = require('underscore');
}
