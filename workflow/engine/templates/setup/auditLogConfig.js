Ext.onReady(function() {
  auditLogFields = new Ext.form.FieldSet({
   
    title : _('ID_AUDITLOG_DISPLAY'),
    items : [
      {
        xtype      : 'checkbox',
        checked    : auditLogChecked,
        name       : 'acceptAL',
        fieldLabel : _('ID_TERMS_USE'),
        hideLabel  : true,
        id         : 'ch_ii',
        style      : 'margin-top:15px',
        boxLabel   : '<b>' + _('ID_ENABLE_AUDIT_LOG') + '</b>',
        listeners : {
          check : function(){
            Ext.getCmp('btn_save').enable();
          }
        }
      },
      {
        xtype  : 'box',
        autoEl : { tag  : 'div', 
                   html : '<br />' + _('ID_AUDIT_LOG_DETAILS_1') 
                          + '<br>' + _('ID_AUDIT_LOG_DETAILS_2')
                 },
        style : 'margin-left:20px'        
      }
    ],    
    buttons : [{
      id      : 'btn_save',
      text    : _('ID_SAVE_SETTINGS'),
      disabled: true,
      handler : saveOption    
    }]
  });
  
  
  var frm = new Ext.FormPanel( {
    title         : '&nbsp',
    id            : 'frmAuditLog',
    labelWidth    : 150,
    width         : 600,
    labelAlign    : 'right',
    autoScroll    : true,
    bodyStyle     : 'padding:2px',
    waitMsgTarget : true,
    frame         : true,
    
    defaults: {
      allowBlank : false,
      msgTarget  : 'side',
      align      : 'center'
    },
    items : [ auditLogFields ]
   
  });
  //render to process-panel
  frm.render(document.body);
});

function saveOption()
{
  Ext.getCmp('btn_save').disable();
  Ext.getCmp('frmAuditLog').getForm().submit( {  
    url     : 'auditLogConfigAjax?action=saveOption',
    waitMsg : _('ID_SAVING_PROCESS'),
    waitTitle : "&nbsp;",
    timeout : 36000,
    success : function(obj, resp) {
      //nothing to do
      response = Ext.decode(resp.response.responseText);
      if (response.enable) {
        parent.PMExt.notify(_('ID_AUDITLOG_DISPLAY'), _('ID_AUDIT_LOG_ENABLED'));        
      }
      else {
        parent.PMExt.notify(_('ID_AUDITLOG_DISPLAY'), _('ID_AUDIT_LOG_DISABLED'));
      }
    },
    failure : function(obj, resp) {
      Ext.Msg.alert( _('ID_ERROR'), resp.result.msg);
    }
  });
} 


