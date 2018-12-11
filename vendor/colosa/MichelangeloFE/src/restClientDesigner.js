var PMRestClient = function (options) {
    this.endpoint = null;
    this.typeRequest = null;
    this.data = null;
    this.functionSuccess = null;
    this.functionFailure = null;
    this.functionComplete = null;
    this.apiVersion = null;
    this.restProxy = null;
    this.pmProcess = null;
    this.messageError = null;
    this.messageSuccess = null;
    this.flashContainer = null;
    this.flashDuration = null;
    this.flashSeverity = null;
    this.multipart = false;
    this.needStringify = null;
    this.hostName = HTTP_SERVER_HOSTNAME;

    PMRestClient.prototype.init.call(this, options);
};

PMRestClient.prototype.type = "PMRestClient";

PMRestClient.prototype.init = function (options) {
    var defaults = {
        endpoint: '',
        typeRequest: 'get',
        data: null,
        functionSuccess: function (resp, data) {
        },
        functionFailure: function (resp, data) {
        },
        functionComplete: function (resp, data) {
        },
        apiVersion: '1.0',
        restProxy: '',
        processID: '',
        messageError: false,
        messageSuccess: false,
        flashContainer: document.body,
        flashDuration: 3000,
        flashSeverity: 'success',
        multipart: false,
        needStringify: true

    }

    jQuery.extend(true, defaults, options);

    this.setProcessID()
        .setApiVersion(defaults.apiVersion)
        .setEndpoint(defaults.endpoint)
        .setTypeRequest(defaults.typeRequest)
        .setData(defaults.data)
        .setFunctionSuccess(defaults.functionSuccess)
        .setFunctionFailure(defaults.functionFailure)
        .setFunctionComplete(defaults.functionComplete)
        .setMessageSuccess(defaults.messageSuccess)
        .setMessageError(defaults.messageError)
        .setFlashContainer(defaults.flashContainer)
        .setFlashDuration(defaults.flashDuration)
        .setFlashSeverity(defaults.flashSeverity)
        .setMultipart(defaults.multipart)
        .setNeedStringify(defaults.needStringify);
};

PMRestClient.prototype.setProcessID = function () {
    this.processID = PMDesigner.project.id;
    return this;
};

PMRestClient.prototype.setEndpoint = function (endpoint) {
    this.endpoint = "/api/" + this.apiVersion + "/" + WORKSPACE + "/project/" + this.processID + "/" + endpoint;
    return this;
};

PMRestClient.prototype.setBaseEndPoint = function (endpoint) {
    this.endpoint = "/api/" + this.apiVersion + "/" + WORKSPACE + "/" + endpoint;
    return this;
};

PMRestClient.prototype.setTypeRequest = function (typeRequest) {
    this.typeRequest = typeRequest;
    return this;
};

PMRestClient.prototype.setData = function (data) {
    this.data = data;
    return this;
};

PMRestClient.prototype.setFunctionSuccess = function (functionSuccess) {
    this.functionSuccess = functionSuccess;
    return this;
};

PMRestClient.prototype.setFunctionComplete = function (functionComplete) {
    this.functionComplete = functionComplete;
    return this;
};

PMRestClient.prototype.setFunctionFailure = function (functionFailure) {
    this.functionFailure = functionFailure;
    return this;
};

PMRestClient.prototype.setMessageSuccess = function (messageSuccess) {
    this.messageSuccess = (messageSuccess) ? messageSuccess : false;
    return this;
};

PMRestClient.prototype.setMessageError = function (messageError) {
    this.messageError = (messageError) ? messageError : false;
    return this;
};

PMRestClient.prototype.setApiVersion = function (apiVersion) {
    this.apiVersion = apiVersion;
    return this;
};

PMRestClient.prototype.setRestProxy = function () {
    this.restProxy = new PMUI.proxy.RestProxy();
    return this;
};

PMRestClient.prototype.setToken = function () {
    var keys;
    if (this.pmProcess === '' || this.pmProcess === null) {
        this.setProcessID();
    }
    if (this.restProxy === '' || this.restProxy === null) {
        this.setRestProxy();
    }
    keys = PMDesigner.project.getKeysClient();
    this.restProxy.setDataType("json");
    this.restProxy.setAuthorizationType('oauth2', PMDesigner.project.tokens);
    if (this.multipart) {
        this.enableMultipart();
    }
    return this;
};

/**
 * Enables Multipart header
 * @returns {PMRestClient}
 */
PMRestClient.prototype.enableMultipart = function () {
    this.restProxy.rc.setHeader('X-Requested-With', 'MULTIPART');
    return this;
};

PMRestClient.prototype.setFlashContainer = function (flashContainer) {
    this.flashContainer = flashContainer;
    return this;
};

PMRestClient.prototype.setFlashDuration = function (flashDuration) {
    this.flashDuration = flashDuration;
    return this;
};

PMRestClient.prototype.setFlashSeverity = function (flashSeverity) {
    this.flashSeverity = flashSeverity;
    return this;
};

PMRestClient.prototype.setMultipart = function (multipart) {
    this.multipart = multipart;
    return this;
};

PMRestClient.prototype.setNeedStringify = function (value) {
    this.needStringify = value;
    return this;
};

PMRestClient.prototype.setHeader = function (name, value) {
    if (this.restProxy === '' || this.restProxy === null) {
        this.setRestProxy();
    }
    this.restProxy.rc.setHeader(name, value);
    return this;
};

/**
 * Validates Multipart responses
 * @param response
 */
PMRestClient.prototype.validateMesssage = function (response) {
    var HTTP_SUCCESS = ["200", "201", "202", "204", "207"],
        showMessage = false,
        messageMultipart = [],
        i,
        that = this;
    for (i = 0; i < response.length; i += 1) {
        if (HTTP_SUCCESS.indexOf(String(response[i].status)) !== -1) {
            if (that.messageSuccess[i] !== null) {
                showMessage = true;
                messageMultipart.push(that.messageSuccess[i]);
            }
        } else {
            if (that.messageError[i] !== null) {
                showMessage = true;
                messageMultipart.push(that.messageError[i]);
                that.setFlashSeverity('error');
            }
        }
    }
};

/**
 * Executes extended Rest Client
 *
 */
PMRestClient.prototype.executeRestClient = function () {
    if (this.restProxy === '' || this.restProxy === null) {
        this.setRestProxy();
    }
    this.setToken();
    this.restProxy.rc.setHeader("Accept-Language", LANG);
    switch (this.typeRequest) {
        case 'get':
            this.get();
            break;
        case 'post':
            this.post();
            break;
        case 'put':
            this.put();
            break;
        case 'update':
            this.update();
            break;
        case 'remove':
            this.remove();
            break;
    }
};

/**
 * Get method
 */
PMRestClient.prototype.get = function () {
    var that = this;
    this.restProxy.get({
        url: this.hostName + this.endpoint,
        authorizationOAuth: true,
        data: this.data,
        success: function (xhr, response) {
            if (that.multipart) {
                that.validateMesssage(response);
            }
            if (that.messageSuccess) {
                PMDesigner.msgFlash(
                    that.messageSuccess,
                    that.flashContainer,
                    that.flashSeverity,
                    that.flashDuration
                );
            }
            that.functionSuccess(xhr, response);
        },
        failure: function (xhr, response) {
            that.failureResponse(that, xhr, response);
        },
        complete: function (xhr, response) {
            that.functionComplete(xhr, response);
        }
    });
};
/**
 * Post method
 */
PMRestClient.prototype.post = function () {
    var that = this;
    this.restProxy.post({
        url: this.hostName + this.endpoint,
        authorizationOAuth: true,
        data: this.data,
        success: function (xhr, response) {
            if (that.multipart) {
                that.validateMesssage(response);
            }
            if (that.messageSuccess) {
                PMDesigner.msgFlash(
                    that.messageSuccess,
                    that.flashContainer,
                    that.flashSeverity,
                    that.flashDuration
                );
            }
            that.functionSuccess(xhr, response);
        },
        failure: function (xhr, response) {
            that.failureResponse(that, xhr, response);
        },
        complete: function (xhr, response) {
            that.functionComplete(xhr, response);
        }
    });
};
/**
 * Put Method
 */
PMRestClient.prototype.put = function () {
    var that = this;
    this.restProxy.put({
        url: this.hostName + this.endpoint,
        authorizationOAuth: true,
        data: this.data,
        success: function (xhr, response) {
            if (that.multipart) {
                that.validateMesssage(response);
            }
            if (that.messageSuccess) {
                PMDesigner.msgFlash(
                    that.messageSuccess,
                    that.flashContainer,
                    that.flashSeverity,
                    that.flashDuration
                );
            }
            that.functionSuccess(xhr, response);
        },
        failure: function (xhr, response) {
            that.failureResponse(that, xhr, response);
        },
        complete: function (xhr, response) {
            that.functionComplete(xhr, response);
        }
    });
};
/**
 * Delete method
 */
PMRestClient.prototype.remove = function () {
    var that = this;
    this.restProxy.remove({
        url: this.hostName + this.endpoint,
        authorizationOAuth: true,
        data: this.data,
        success: function (xhr, response) {
            if (that.multipart) {
                that.validateMesssage(response);
            }
            if (that.messageSuccess) {
                PMDesigner.msgFlash(
                    that.messageSuccess,
                    that.flashContainer,
                    that.flashSeverity,
                    that.flashDuration
                );
            }
            that.functionSuccess(xhr, response);
        },
        failure: function (xhr, response) {
            that.failureResponse(that, xhr, response);
        },
        complete: function (xhr, response) {
            that.functionComplete(xhr, response);
        }
    });
};
/**
 * Update Method
 */
PMRestClient.prototype.update = function () {
    var that = this;
    this.restProxy.update({
        url: this.hostName + this.endpoint,
        authorizationOAuth: true,
        data: this.data,
        success: function (xhr, response) {
            if (that.multipart) {
                that.validateMesssage(response);
            }
            if (that.messageSuccess) {
                PMDesigner.msgFlash(
                    that.messageSuccess,
                    that.flashContainer,
                    that.flashSeverity,
                    that.flashDuration
                );
            }
            that.functionSuccess(xhr, response);
        },
        failure: function (xhr, response) {
            that.failureResponse(that, xhr, response);
        },
        complete: function (xhr, response) {
            that.functionComplete(xhr, response);
        }
    });
};
/**
 * Failure response manager
 * @param root
 * @param xhr
 * @param response
 */
PMRestClient.prototype.failureResponse = function (root, xhr, response) {
    if (xhr.status === 401 && typeof(response.error) != "undefined") {
        root.refreshAccesToken(root);
        return;
    } else {
        if (xhr.status === 400) {
            root.functionFailure(xhr, response);

            return;
        }

        if (root.messageError) {
            PMDesigner.msgWinError(root.messageError);
        }
    }
    root.functionFailure(xhr, response);
};
/**
 * Gets new token using the refresh token key
 * @param root
 */
PMRestClient.prototype.refreshAccesToken = function (root) {
    var newRestClient = new PMUI.proxy.RestProxy();
    newRestClient.post({
        url: this.hostName + "/api/" + root.apiVersion + "/" + WORKSPACE + "/token",
        data: {
            grant_type: "refresh_token",
            client_id: PMDesigner.project.tokens.client_id,
            client_secret: PMDesigner.project.tokens.client_secret,
            refresh_token: PMDesigner.project.tokens.refresh_token
        },
        success: function (xhr, response) {
            PMDesigner.project.tokens.access_token = response.access_token;
            PMDesigner.project.tokens.expires_in = response.expires_in;
            PMDesigner.project.tokens.token_type = response.token_type;
            PMDesigner.project.tokens.scope = response.scope;
            PMDesigner.project.tokens.refresh_token = response.refresh_token;

            root.executeRestClient();
        },
        failure: function (xhr, response) {
            PMDesigner.msgWinError('An error occurred while retrieving the access token'.translate());
        }
    });
};