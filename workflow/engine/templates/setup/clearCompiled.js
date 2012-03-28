Ext.onReady(function() {
  cacheFields = new Ext.form.FieldSet({

    title : _('ID_CLEAR_CACHE'),
    items : [
      {
        xtype      : 'checkbox',
        name       : 'javascriptCache',
        fieldLabel : 'Terms of Use',
        hideLabel  : true,
        id         : 'javascriptCache',
        style      : 'margin-top:15px',
        boxLabel   : _('ID_JAVASCRIPT_CACHE')

      },
      {
        xtype      : 'checkbox',
        name       : 'metadataCache',
        fieldLabel : 'Terms of Use',
        hideLabel  : true,
        id         : 'metadataCache',
        style      : 'margin-top:15px',
        boxLabel   : _('ID_FORMS_METADATA_CACHE')

      },
      {
        xtype      : 'checkbox',
        name       : 'htmlCache',
        fieldLabel : 'Terms of Use',
        hideLabel  : true,
        id         : 'htmlCache',
        style      : 'margin-top:15px',
        boxLabel   : _('ID_FORMS_HTML_CACHE')
        
      }

    ],
    buttons   : [{
      text    : _('ID_CLEAR'),
      handler : clearCache   
    }]
  });

  var frm = new Ext.FormPanel( {
    title         : '&nbsp',
    id            : 'frmCache',
    width         : 400,
    labelWidth    : 150,
    labelAlign    : 'right',
    autoScroll    : true,
    bodyStyle     : 'padding:2px',
    waitMsgTarget : true,
    frame         : true,
    defaults : {
      allowBlank : false,
      resizable  : true,
      msgTarget  : 'side',
      align      : 'center'
    },
    items : [ cacheFields ]

  });
  //render to process-panel
  // frm.render('processes-panel');
  frm.render(document.body);
});

function clearCache () {

  Ext.getCmp('frmCache').getForm().submit( { 
    url     : 'clearCompiledAjax',
    waitMsg : _('ID_SAVING_PROCESS'),
    timeout : 36000,
    success : function(obj, resp) {
      response = Ext.decode(resp.response.responseText);
      if (response.javascript) {
        var message1 = _('ID_JAVASCRIPT_CACHE') + '<br />';
      }
      else {
        var message1 = '';
      }
      if (response.xmlform) {
        var message2 = _('ID_FORMS_METADATA_CACHE') + '<br />';
      }
      else {
        var message2 = '';
      }
      if (response.smarty) {
        var message3 = _('ID_FORMS_HTML_CACHE') + '<br />';
      }
      else {
        var message3 = '';
      }

      parent.PMExt.notify(_('ID_CLEAR_CACHE'), message1 + message2 + message3 + _('ID_HAS_BEEN_DELETED'));
      
    },
    failure : function(obj, resp) {
      Ext.Msg.alert( _('ID_ERROR'), _('ID_SELECT_ONE_OPTION'));
    }
  });
} 

