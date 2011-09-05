var plugin;

/**
 * function showMessage 
 * @author gustavo cruz gustavo-at-colosa-dot-com
 * @param  message    the message to show
 * @param  pluginUid  pluginUid or plugin name
 * @desc   function that invoques a msgBox call with the removePlugin function
 *         as argument.
 **/

function showMessage(message, pluginUid){
    plugin = pluginUid;
    msgBox(message, "confirm", removePlugin);
}

/**
 * function removePlugin
 * @author gustavo cruz gustavo-at-colosa-dot-com
 * @desc   function that executes a rpc and takes the server response into
 *         another message.
 **/

function removePlugin(){
    var callServer = new leimnud.module.rpc.xmlhttp({
  		url     : 'pluginsRemove',
  		async   : false,
  		method  : 'POST',
  		args    : 'pluginUid=' + plugin
    });
    callServer.make();
    var response = callServer.xmlhttp.responseText;
    msgBox(response, 'alert', refresh);
}

/**
 * function refresh
 * @author gustavo cruz gustavo-at-colosa-dot-com
 * @desc   a trivial but necesary function that reload a page, since the msgBox
 *         can only take functions with no arguments attached.
 **/

function refresh(){
    location.href = "pluginsList";
}