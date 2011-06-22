/*
 * @author krlos P.C <carlos@colosa.com>
 * Jan 15th, 2011
 */

Ext.onReady(function(){

  var fieldNameWS=new Ext.form.TextField({
  id: 'NW_TITLE',				
  fieldLabel: _('ID_NAME'), 
  xtype:'textfield',
  value:'sample',
  width: 200,
  autoCreate: {tag: 'input', type: 'text', size: '20', autocomplete: 'off', maxlength: '13'},
  allowBlank: false,
  listeners: {
              'render': function(c) {
                c.getEl().on('keyup', function() {
                  namews=formNewSite.getForm().findField('NW_TITLE').getValue();
                  formNewSite.getForm().findField('AO_DB_WF').setValue('wf_'+namews);
                  formNewSite.getForm().findField('AO_DB_RB').setValue('rb_'+namews);
                  formNewSite.getForm().findField('AO_DB_RP').setValue('rp_'+namews);
                }, c);
              }
            }
  });
  nameWS = new Ext.form.FieldSet({
    title: 'New Workspace',  
    items: [
			   fieldNameWS
    ]    
  });
  dbOptionsWS = new Ext.form.FieldSet({
    title: 'Database Options',  
    items: [			
      {
        id: 'AO_DB_WF',				
        fieldLabel: 'Workflow Database', 
        xtype:'textfield',
        value:'ws_sample',
        width: 200,
        autoCreate: {tag: 'input', type: 'text', size: '20', autocomplete: 'off', maxlength: '13'},
        allowBlank: false
      },      
      {
        id: 'AO_DB_RB',
        fieldLabel: 'Rbac Database',
        xtype:'textfield',
        value:'rb_sample',
        width: 200,
        autoCreate: {tag: 'input', type: 'text', size: '20', autocomplete: 'off', maxlength: '13'},
        allowBlank: false
      },
      {
        id: 'AO_DB_RP',
        fieldLabel: 'Report Database',			
        xtype:'textfield',
        value:'rp_sample',
        width: 200,
        autoCreate: {tag: 'input', type: 'text', size: '20', autocomplete: 'off', maxlength: '13'},
        allowBlank: false
      },
      {  
            xtype: 'checkbox', 
            fieldLabel: 'Drop database if exists',
            name: 'AO_DB_DROP', 
            id: 'id-active'
       }  
     ]
  });
  
  wspaceAdmWS = new Ext.form.FieldSet({
    title: 'Workspace Administrator',  
    items: [			
      {
        id: 'NW_USERNAME',				
        fieldLabel: 'Username', 
        xtype:'textfield',
        value:'admin',
        width: 200,
        allowBlank: false
      },      
      {
        id: 'NW_PASSWORD',
        fieldLabel: 'Password (admin)(Max. length 20):',
        xtype:'textfield',
        inputType:'password',
        value:'admin',
        width: 200,
        allowBlank: false
      },
      {
        id: 'NW_PASSWORD2',
        fieldLabel: 'Re-type Password',			
        xtype:'textfield',
        inputType:'password',
        value:'admin',
        width: 200,
        allowBlank: false
      }
      ]
  });
  
  
  formNewSite = new Ext.FormPanel({
    id:'formNewSite',       
    labelWidth: 250,
    labelAlign:'right',
    autoScroll: true,
    fileUpload: true,
    width:800,
    bodyStyle:'padding:10px',
    waitMsgTarget : true,
    frame: true,
    defaults: {
      anchor: '100%',
      allowBlank: false,
      resizable: true,
      msgTarget: 'side',
      align:'center'      
    },
    items:[
    nameWS,
    dbOptionsWS,
    wspaceAdmWS
      ],
    buttons: [
      {
        text: 'reset',
        handler: resetfields
      
      },
      {     
        text: 'Test',
        handler: TestSite
      }
    ]
    
  });

    formNewSite.render(document.body);
    
 });
 function resetfields(){
    formNewSite.getForm().reset();
 }
 function TestSite(){
 formNewSite.getForm().submit({
                            url: '../newSiteProxy/testingNW',
                            params: {
                            action : 'test'
                            },
                            waitMsg : 'new site testing...',
                            timeout : 3600,
                            success: function(f,a){
                             nwTitle    =formNewSite.getForm().findField('NW_TITLE').getValue();
                             aoDbWf     =formNewSite.getForm().findField('AO_DB_WF').getValue();
                             aoDbRb     =formNewSite.getForm().findField('AO_DB_RB').getValue();
                             aoDbRp     =formNewSite.getForm().findField('AO_DB_RP').getValue();
                             nwUsername =formNewSite.getForm().findField('NW_USERNAME').getValue();
                             nwPassword =formNewSite.getForm().findField('NW_PASSWORD').getValue();
                             nwPassword2=formNewSite.getForm().findField('NW_PASSWORD2').getValue();
                             aoDbDrop=formNewSite.getForm().findField('AO_DB_DROP').getValue();
                             //Ext.getCmp('NW_TITLE').disable()=true;
                             //Ext.getCmp('NW_TITLE').readOnly = true;
                             createNW(nwTitle, aoDbWf, aoDbRb, aoDbRp, nwUsername, nwPassword, nwPassword2);
                            },
                            failure: function(f,a){
                                if (a.failureType === Ext.form.Action.CONNECT_FAILURE){
                                    Ext.Msg.alert('Failure', 'Server reported:'+a.response.status+' '+a.response.statusText);
                                }
                                if (a.failureType === Ext.form.Action.SERVER_INVALID){
                                    Ext.Msg.alert('Warning', _('NEW_SITE_NOT_AVAILABLE'));
                                }
                            }
                        });
 } 
 
  function createNW(nwTitle, aoDbWf, aoDbRb, aoDbRp, nwUsername, nwPassword, nwPassword2){
    PMExt.confirm(_('ID_CONFIRM'), _('NEW_SITE_CONFIRM_TO_CREATE'), function(){
    var loadMask = new Ext.LoadMask(document.body, {msg:'site creating..'});
    loadMask.show();
     Ext.Ajax.request({
      url: '../newSiteProxy/testingNW',
      params: {
      action : 'create',
      NW_TITLE : nwTitle,
      AO_DB_WF : aoDbWf,
      AO_DB_RB : aoDbRb,
      AO_DB_RP : aoDbRp,
      NW_USERNAME : nwUsername,
      NW_PASSWORD : nwPassword,
      NW_PASSWORD2 : nwPassword2,
      AO_DB_DROP : aoDbDrop
      },
      method: 'POST',
      success: function ( result, request ) {
      loadMask.hide();
      var data = Ext.util.JSON.decode(result.responseText);
      if( data.success ) {
        PMExt.confirm(_('ID_CONFIRM'), _('NEW_SITE_SUCCESS') +" "+nwTitle+"<br/>"+ _('NEW_SITE_SUCCESS_CONFIRM')+"<br/>"+ _('NEW_SITE_SUCCESS_CONFIRMNOTE'), function(){
         nwTitle    =formNewSite.getForm().findField('NW_TITLE').getValue();
         parent.parent.window.location="/sys"+nwTitle+"/en/green/login/login";
       });
      } else {
       PMExt.error(_('ID_ERROR'), data.msg);
      }
      },
      failure: function ( result, request) {
       Ext.MessageBox.alert('Failed', result.responseText);
      }
     });
    });
   }
  
 
 
 




