Ext.onReady(function() {

  Ext.QuickTips.init();

  // turn on validation errors beside the field globally
  Ext.form.Field.prototype.msgTarget = 'side';

  var bd = Ext.getBody();

  // bd.createChild({tag: 'h2', html: 'Form 2 - Adding fieldsets'});


  // Store
  var store = new Ext.data.Store( {
    proxy: new Ext.data.HttpProxy({
      url: 'appCacheViewAjax',
      method: 'POST'
    }),    
    baseParams : { request : 'info'},
    reader : new Ext.data.JsonReader( {
      root : 'info',
      fields : [ {name : 'name'}, {name : 'value'} ]
    })
  });

  // create the Grid
  var infoGrid = new Ext.grid.GridPanel( {
    store : store,
    columns : [{
        id : 'name',
        header : '',
        width : 210,
        sortable : false,
        dataIndex : 'name'
      }, 
      {
        header : '',
        width : 190,
        sortable : false,
        dataIndex : 'value'
      }
      ],
      stripeRows : true,
      autoHeight : true,
      width : 400,
      title : 'Workflow Applications Cache Info',
      // config options for stateful behavior
      stateful : true,
      stateId : 'grid',
      enableColumnHide: false,
      enableColumnResize: false,
      enableHdMenu: false
    });

    // render the grid to the specified div in the page
    infoGrid.render('info-panel');

    
    var fsf = new Ext.FormPanel( {
      labelWidth : 105, // label settings here cascade unless overridden
      url : '',
      frame : true,
      title : ' ',
      bodyStyle : 'padding:5px 5px 0',
      width : 400,
      items : [ ]
    });
    
    var fieldset;
    
    var cmbLanguages = new Ext.form.ComboBox({
      fieldLabel : 'Language',
      hiddenName : 'lang',
      store : new Ext.data.Store( {
        proxy : new Ext.data.HttpProxy( {
          url : 'appCacheViewAjax',
          method : 'POST'
        }),
        baseParams : {request : 'getLangList'},
        reader : new Ext.data.JsonReader( {
          root : 'rows',
          fields : [ {name : 'LAN_ID'}, {name : 'LAN_NAME'} ]
        })
      }),
      valueField     : 'LAN_ID',
      displayField   : 'LAN_NAME',
      //triggerAction  : 'all',
      emptyText      : 'Select',
      selectOnFocus  : true,
      editable       : false,
      allowBlank     : false
    })
    
    var txtUser = {
      id   : 'txtUser',
      xtype: 'textfield',
      fieldLabel: 'User',
      disabled: false,
      name: 'user',
      value: ''
    };

    var txtHost = {
      id   : 'txtHost',
      xtype: 'textfield',
      fieldLabel: 'Host',
      disabled: false,
      name: 'host',
      value: ''
    };
    
    var txtPasswd = {
      id   : 'txtPasswd',
      inputType: 'password',
      xtype:'textfield',
      fieldLabel: 'Password',
      disabled: false,
      hidden: false,
      value: ''
    }
    
    fieldset = {
      xtype : 'fieldset',
      title : 'Rebuild Workflow Application Cache',
      collapsible : false,
      autoHeight  : true,
      defaults    : { width : 170 },
      defaultType : 'textfield',
      items   : [cmbLanguages],
      buttons : [{
        text : 'Build Cache',
        handler : function() {
          Ext.Msg.show ({ msg : TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:400} });
          Ext.Ajax.request({
            url: 'appCacheViewAjax',
            success: function(response) {
              store.reload();
              Ext.MessageBox.hide();  
              res = Ext.decode ( response.responseText );            
              Ext.Msg.alert ( '', res.msg );
                
            },
            failure : function(response) {
              Ext.Msg.hide();              
              Ext.Msg.alert ( 'Error', response.responseText );
            },
            params: {request: 'build', lang: 'en' },
            waitMsg : 'Building Workflow Application Cache...',
            timeout : 1000*60*30 //30 mins
          });
        }
      }]      
    } 
    
    
    fieldsetRoot = {
      xtype : 'fieldset',
      title : 'Setup MySql Root Password',
      collapsible : false,
      autoHeight  : true,
      defaults    : { width : 170 },
      defaultType : 'textfield',
      items   : [txtHost, txtUser, txtPasswd ],
      buttons : [{
        text : 'Setup Password',
        handler : function() {
          Ext.Msg.show ({ msg : TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:400} });
          Ext.Ajax.request({
            url: 'appCacheViewAjax',
            success: function(response) {
              store.reload();
              Ext.MessageBox.hide();              
              Ext.Msg.alert ( '', response.responseText );
            },
            failure : function(response) {
              Ext.Msg.hide();              
              Ext.Msg.alert ( 'Error', response.responseText );
            },
            params: { request: 'recreate-root', lang: 'en', host: Ext.getCmp('txtHost').getValue(), user: Ext.getCmp('txtUser').getValue(), password: Ext.getCmp('txtPasswd').getValue() },
            // timeout : 1000
            // 30 mins
            timeout : 1000*60*30 //30 mins
          });
        }
      }]      
    } 
    fsf.add(fieldset);
    fsf.add(fieldsetRoot);
    fsf.render(document.getElementById('main-panel'));

    //set the current language
    cmbLanguages.store.on('load',function(){ cmbLanguages.setValue ( currentLang ) });
    cmbLanguages.store.load();

    //store.load(); instead call standard proxy we are calling ajax request, because we need to catch any error 
    Ext.Ajax.request({
      url: 'appCacheViewAjax',
      success: function(response) {
        myData = Ext.decode ( response.responseText );
        store.loadData(myData);  
        if ( myData.error ) {
          Warning( 'error', myData.errorMsg );
      	}
      },
      failure : function(response) {
        Ext.Msg.alert ( 'Error', response.responseText );
      },
      params: {request: 'info' }
    });
  
  });  //ExtReady

var Warning = function( msgTitle, msgError ) {
  tplEl = Ext.get ('errorMsg');

  tplText = '<div style="font-size:12px; border: 1px solid #FF0000; background-color:#FFAAAA; display:block; padding:10px; color:#404000;"><b>' + msgTitle + ': </b>' + msgError + '</div>';
  tplEl.update ( tplText );

}
