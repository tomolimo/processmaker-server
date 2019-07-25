/**
 * RestApi cliento to consume designer end points
 * V 0.0.1
 * @type {{}|RestApi}
 */
//create the RestApi variable if one does not exist already

var RestApi = RestApi || {};

RestApi = (function () {
    var _api;
    var _methodsMap = {
        "read": "GET",
        "update": "PUT",
        "create": "POST",
        "delete": "DELETE"
    };
    var _methods = ["read", "update", "create", "delete"];

    function RestClient(params) {
        var keys = params.keys,
            defaults,
            serverUrl = (params && params.serverUrl) || "/rest/v10";
        //console.log('init RestClient instance');
        //TODO  restClient
        return {
            execute: function (params) {
                defaults = {
                    url: HTTP_SERVER_HOSTNAME + "/rest/v10",
                    method: "GET",
                    contentType: "application/json",
                    data: '',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + keys.access_token);
                    },
                    success: function () {
                    },
                    error: function () {
                    }
                };
                _.extend(defaults, params);
                defaults.url = defaults.url;
                defaults.method = _methodsMap[defaults.method];
                defaults.data = defaults.data;
                defaults.success = defaults.success;
                defaults.error = defaults.error;

                $.ajax(defaults);
            }
        };
    };
    return {
        /**
         * Gets an instance of rest api
         * @param params
         * @returns {PMDesigner.RestApi}
         */
        getRestApi: function (params) {
            return _api || this.createRestApi(params);
        },
        /**
         * creates a rest api instance
         * @param params
         * @returns {*}
         */
        createRestApi: function (params) {
            _api = new RestClient(params);
            return _api;
        }
    };
})();

