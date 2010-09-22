/*
 * @author: Erik A. Ortiz
 * Aug 20th, 2010 
 */

new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
    fn: function(keycode, e) {
    	if (! e.ctrlKey) {
        if (Ext.isIE) {
            // IE6 doesn't allow cancellation of the F5 key, so trick it into
            // thinking some other key was pressed (backspace in this case)
            e.browserEvent.keyCode = 8;
        }
        e.stopEvent();
        document.location = document.location;
      }
      else
    Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
  }
});

Ext.onReady(function(){

 

  //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
  Ext.QuickTips.init();

  var infoGrid = new Ext.grid.GridPanel( {
    /*renderTo: 'list-panel',*/
    region: 'center',
    layout: 'fit',
    id: 'infoGrid',
    stripeRows : true,
    autoHeight : true,
    width : 800,
    title : '',
    stateful : true,
    stateId : 'grid',
    enableColumnHide: false,
    enableColumnResize: true,
    enableHdMenu: false,
    collapsible: true,
    animCollapse: false,


    view: new Ext.grid.GroupingView({
        forceFit:true,
        //groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        groupTextTpl: '{text}'
    }),


    columns : [
      {
        dataIndex : 'LAN_FLAG',
        id : 'LAN_FLAG',
        header : '',
        width : 30,
        sortable : false,
        renderer: function (value, p, r){
          //return String.format("<span style='{1}'>{0}</span>", value, 'color:green;' );
          return String.format("<img src='/images/flags/{0}.gif' />", value);
        }
      }, {
        dataIndex : 'LAN_ID',
        id : 'LAN_ID',
        header : '',
        width : 35,
        sortable : false,
        hidden: true
      }, {
        dataIndex : 'LOCALE',
        id : 'LOCALE',
        header : 'Locale',
        width : 60,
        sortable : false
      }, {
        dataIndex : 'LAN_NAME',
        header : TRANSLATIONS.ID_LAN_LANGUAGE,
        width : 120,
        sortable : false,
        renderer: function (value, p, r){
          if( r.data.DEFAULT == '1' )
            return String.format(
              "{0} <font color=green style='font-size:9px'>({1})</font>",
              value,
              TRANSLATIONS.ID_LANG_PREDETERMINED
            );
          else
            return value;
        }
      }, {
        dataIndex : 'COUNTRY_NAME',
        header : TRANSLATIONS.ID_LAN_COUNTRY,
        width : 120,
        sortable : false
      }, {
        dataIndex : 'DATE',
        header : TRANSLATIONS.ID_LAN_UPDATE_DATE,
        width : 120,
        sortable : false
      }, {
        dataIndex : 'REV_DATE',
        header : TRANSLATIONS.ID_LAN_REV_DATE,
        width : 140,
        sortable : false
      }, {
        dataIndex : 'VERSION',
        header : TRANSLATIONS.ID_LAN_VERSION,
        width : 150,
        sortable : false
      }, {
        dataIndex : 'TRANSLATOR',
        header : 'Translator',
        width : 150,
        sortable : false,
        hidden: false
      }, {
        dataIndex : 'NUM_RECORDS',
        header : '# Records',
        width : 60,
        sortable : false
      }
    ],

    store: new Ext.data.GroupingStore( {
      proxy : new Ext.data.HttpProxy({
        url: 'language_Ajax'
      }),

      reader : new Ext.data.JsonReader( {
        root : 'data',
        fields : [
          {name : 'LAN_FLAG'},
          {name : 'LAN_ID'},
          {name : 'LOCALE'},
          {name : 'LAN_NAME'},
          {name : 'COUNTRY_NAME'},
          {name : 'DATE'},
          {name : 'REV_DATE'},
          {name : 'VERSION'},
          {name : 'TRANSLATOR'},
          {name : 'NUM_RECORDS'}
        ]
      }),
      groupField:'LAN_NAME'

    }),

    tbar:[
      {
        text: TRANSLATIONS.ID_LANG_INSTALL_UPDATE,
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
                title: TRANSLATIONS.ID_LAN_UPLOAD_TITLE,
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
                    emptyText: TRANSLATIONS.ID_LAN_FILE_WATER_LABEL,
                    fieldLabel: TRANSLATIONS.ID_LAN_FILE,
                    name: 'form[LANGUAGE_FILENAME]',
                    buttonText: '',
                    icon: '/images/delete-16x16.gif',
                    buttonCfg: {
                        iconCls: 'upload-icon'
                    }
                }],
                buttons: [{
                    text: TRANSLATIONS.ID_UPLOAD,
                    handler: function(){
                      uploader = Ext.getCmp('uploader');

                      if(uploader.getForm().isValid()){
                        uploader.getForm().submit({
                          url: 'languages_Import',
                          waitMsg: 'Uploading the translation file...',
                          success: function(o, resp){
                            w.close();
                            infoGrid.store.reload();

                            Ext.MessageBox.show({
                              title: '',
                              width: 500,
                              height: 500,
                              msg: "<pre style='font-size:10px'>"+resp.result.msg+"</pre>",
                              buttons: Ext.MessageBox.OK,
                              animEl: 'mb9',
                              fn: function(){},
                              icon: Ext.MessageBox.INFO
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
                }/*,{
                    text: 'Reset',
                    handler: function(){
                      uploader = Ext.getCmp('uploader');
                      uploader.getForm().reset();
                    }
                }*/,{
                    text: TRANSLATIONS.ID_CANCEL,
                    handler: function(){
                      w.close();
                    }
                }]
              })
            ]/*,
            listeners:{
              show:function() {
                this.loadMask = new Ext.LoadMask(this.body, {
                  msg:'Loading. Please wait...'
                });
              }
            }*/
          });
          w.show();
        }
      },{
        text: TRANSLATIONS.ID_EXPORT,
        iconCls: 'silk-add',
        icon: '/images/export.png',
        handler: function(){
          iGrid = Ext.getCmp('infoGrid');
          var rowSelected = iGrid.getSelectionModel().getSelected();
          if( rowSelected ) {
            location.href = 'languages_Export?LOCALE='+rowSelected.data.LOCALE+'&rand='+Math.random()
          } else {
             Ext.Msg.show({
              title:'',
              msg: 'first select a language from the list please.',
              buttons: Ext.Msg.INFO,
              fn: function(){},
              animEl: 'elId',
              icon: Ext.MessageBox.INFO,
              buttons: Ext.MessageBox.OK
            });
          }
        },
        scope: this
      },/*{
        text: 'Configuration',
        iconCls: 'silk-add',
        icon: '/images/options.png',
        handler: function(){
          var w = new Ext.Window({
            title: '',
            width: 500,
            height: 420,
            modal: true,
            autoScroll: false,
            maximizable: false,
            resizable: false,
            items: []
          });
          w.show();
        },
        scope: this
      },
      {
        text: 'Set as predetermined',
        iconCls: 'silk-add',
        icon: '/images/language-selected.png',
        handler: function(){
          iGrid = Ext.getCmp('infoGrid');
          var rowSelected = iGrid.getSelectionModel().getSelected();
          if( rowSelected ) {
            langId      = rowSelected.data.LAN_ID;
            langName    = rowSelected.data.LAN_NAME;
            countryName = rowSelected.data.COUNTRY_NAME;
            locale      = rowSelected.data.LOCALE;

            Ext.Msg.show({
              title:'Save Changes?',
              msg: 'Do you want apply to the "'+langName + ' ('+capitalize(countryName)+') as default for '+langName+' language?',
              buttons: Ext.Msg.YESNO,
              fn: function(btn){
                if( btn == 'yes' ) {
                  Ext.Ajax.request({
                    url: 'language_Ajax',
                    success: function(response){
                      infoGrid.store.reload();

                      Ext.Msg.show({
                        title:'',
                        msg: response.responseText,
                        buttons: Ext.Msg.INFO,
                        fn: function(){},
                        animEl: 'elId',
                        icon: Ext.MessageBox.INFO,
                        buttons: Ext.MessageBox.OK
                      });
                    },
                    failure: function(){},
                    params: {
                      'function': 'savePredetermined',
                      'LOCALE': locale,
                      'LAN_ID': langId
                    }
                  });
                }
              },
              animEl: 'elId',
              icon: Ext.MessageBox.QUESTION
            });
          } else {
            Ext.Msg.show({
              title:'',
              msg: 'first select a language from the list please.',
              buttons: Ext.Msg.INFO,
              fn: function(){},
              animEl: 'elId',
              icon: Ext.MessageBox.INFO,
              buttons: Ext.MessageBox.OK
            });
          }
        },
        scope: this
      },*/{
        xtype: 'tbfill'
      },
      {
        text: 'Delete',
        iconCls: 'silk-add',
        icon: '/images/delete-16x16.gif',
        handler: this.onAdd,
        scope: this
      }
    ]

  });

  infoGrid.store.load({params: {"function":"languagesList"}});

  //////////////////////store.load({params: {"function":"xml"}});

  //infoGrid.render('list-panel');
  //infoGrid.render(document.body);
  //fp.render('form-panel');


  var viewport = new Ext.Viewport({
    layout: 'border',
    autoScroll: true,
    items: [
      infoGrid
    ]
  });
});


capitalize = function(s){
  s = s.toLowerCase();
  return s.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};