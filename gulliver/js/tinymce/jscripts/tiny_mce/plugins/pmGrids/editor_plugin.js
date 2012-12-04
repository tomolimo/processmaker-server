/**
* Name: editor_plugin_src.js (for pmGrids tinyMCE plugin)
**/

(function(){
    var strPluginURL;
    tinymce.create('tinymce.plugins.pmGridsPlugin', {
    	init: function(ed, url) 
        {   
            strPluginURL = url;                                         // store the URL for future use..
            ed.addCommand('mcepmGrids', function() {
                pmGrids();              
            });
            ed.addButton('pmGrids', {
                title: 'pmGrids',
                label : '  @#',
                cmd: 'mcepmGrids',
                image: url + '/img/grids.png'
            }); 
        },
        createControl: function(n, cm) {
            return null;
        },
        getPluginURL: function() {
            return strPluginURL;
        }
    });
    tinymce.PluginManager.add('pmGrids', tinymce.plugins.pmGridsPlugin);
})();

// this function can get called from the plugin inint (above) or from the callback on advlink/advimg plugins..
// in the latter case, win and type will be set.. In the rist case, we will just update the main editor window
// with the path of the uploaded file
function pmGrids(field_name, url, type, win) {    
    //tinyMCE.activeEditor.anyVariable='path/to/ProcessMaker' 
    var strPluginPath  = tinyMCE.activeEditor.plugins.pmGrids.getPluginURL();                               // get the path to the uploader plugin    
    var strScriptURL   = strPluginPath + "/pmGrids.html";             

tinyMCE.activeEditor.windowManager.open({                                                                       // open the plugin popup
        file            : strScriptURL,
        title           : 'ProcessMaker Grid Wizard',
        width           : '600px',
        height          : '230px',
        resizable       : "yes",
        scrollbars      : "no",
        overflow        : false,
        inline          : 1,        // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous  : "no"
    }, {
        window : win,
        input : field_name
    });

    return false;
}
// This function will get called when the uploader is done uploading the file and ready to update
// calling dialog and close the upload popup
// strReturnURL should be the string with the path to the uploaded file
function closePluginPopup(){    
    tinyMCEPopup.close();	                                                                    // close popup window
}

function updateEditorContent(serializedHTML){
    tinyMCE.activeEditor.execCommand('mceInsertContent', false, serializedHTML);
}

