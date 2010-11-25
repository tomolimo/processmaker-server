var importOption;

Ext.onReady(function(){

  Ext.QuickTips.init();

  // turn on validation errors beside the field globally
  Ext.form.Field.prototype.msgTarget = 'side';

  var bd = Ext.getBody();
    
  importOption = new Ext.Action({
    text: _('ID_LOAD_FROM_FILE'),
    iconCls: 'silk-add',
    icon: '/images/import.gif',
    handler: function(){
      var w = new Ext.Window({
        title: '',
        width: 420,
        height: 140,
        modal: true,
        autoScroll: false,
        maximizable: false,
        resizable: false,
        items: [
          new Ext.FormPanel({
            /*renderTo: 'form-panel',*/
            id:'uploader',
            fileUpload: true,
            width: 400,
            frame: true,
            title: _('ID_OUT_PUT_DOC_UPLOAD_TITLE'),
            autoHeight: false,
            bodyStyle: 'padding: 10px 10px 0 10px;',
            labelWidth: 50,
            defaults: {
                anchor: '90%',
                allowBlank: false,
                msgTarget: 'side'
            },
            items: [{
                xtype: 'fileuploadfield',
                id: 'form-file',
                emptyText: _('ID_SELECT_TEMPLATE_FILE'),
                fieldLabel: _('ID_FILE'),
                name: 'templateFile',
                buttonText: '',
                buttonCfg: {
                    iconCls: 'upload-icon'
                }
            }],
            buttons: [{
                text: _('ID_UPLOAD'),
                handler: function(){
                  uploader = Ext.getCmp('uploader');
                  if(uploader.getForm().isValid()){
                    uploader.getForm().submit({
                      url: 'outputdocs_Ajax?action=setTemplateFile',
                      waitMsg: _('ID_UPLOADING_FILE'),
                      success: function(o, resp){
                        w.close();
                        
                        Ext.Ajax.request({
                          url: 'outputdocs_Ajax?action=getTemplateFile&r='+Math.random(),
                          success: function(response){
                            Ext.getCmp('OUT_DOC_TEMPLATE').setValue(response.responseText);
                            if(Ext.getCmp('OUT_DOC_TEMPLATE').getValue(response.responseText)=='')
                              Ext.Msg.alert(_('ID_INVALID_FILE'));
                          },
                          failure: function(){},
                          params: {request: 'getRows'}
                        });

                      },
                      failure: function(o, resp){
                        w.close();
                        //alert('ERROR "'+resp.result.msg+'"');
                        Ext.MessageBox.show({title: '', msg: resp.result.msg, buttons:
                        Ext.MessageBox.OK, animEl: 'mb9', fn: function(){}, icon:
                        Ext.MessageBox.ERROR});
                        //setTimeout(function(){Ext.MessageBox.hide(); }, 2000);
                      }
                    });
                  }
                }
            },{
              text: _('ID_CANCEL'),
              handler: function(){ w.close(); }
            }]
          })
        ]
      });
      w.show();
    }
  });
  

    var top = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
        title: '',
        bodyStyle:'padding:5px 5px 0',
        width: 790,
        tbar:[importOption],
        items: [
        {
            xtype:'htmleditor',
            id:'OUT_DOC_TEMPLATE',
            fieldLabel:'Output Document Template',
            height:300,
            anchor:'98%'
        }],

        buttons: [{
          text: _('ID_SAVE'),
          handler: function(){
            Ext.Ajax.request({
              url: 'outputdocs_Save',
              success: function(response){
                Ext.Msg.show({
                  title: '',
                  msg: 'Saved Successfully',
                  fn: function(){},
                  animEl: 'elId',
                  icon: Ext.MessageBox.INFO,
                  buttons: Ext.MessageBox.OK
                });
              },
              failure: function(){},
              params: {
                'form[OUT_DOC_UID]': OUT_DOC_UID,
                'form[OUT_DOC_TEMPLATE]':Ext.getCmp('OUT_DOC_TEMPLATE').getValue()
              }
            });
          }  
        },{
          text: 'Cancel',
          handler: function(){
            parent.outputdocsEditor.remove();
          }
        }]
    });

    top.render(document.body);
    
    Ext.Ajax.request({
      url: 'outputdocs_Ajax?action=loadTemplateContent&r='+Math.random(),
      success: function(response){
        Ext.getCmp('OUT_DOC_TEMPLATE').setValue(response.responseText);                            
      },
      failure: function(){},
      params: {OUT_DOC_UID: OUT_DOC_UID}
    });

});

function _(ID){
  return TRANSLATIONS[ID];
}