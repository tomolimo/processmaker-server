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
    }),
  });
  store.load();

  // create the Grid
  var infoGrid = new Ext.grid.GridPanel( {
    store : store,
    columns : [ {
      id : 'name',
      header : '',
      width : 190,
      sortable : false,
          dataIndex : 'name'
        }, {
          header : '',
          width : 160,
          sortable : false,
          dataIndex : 'value'
        }
      ],
      stripeRows : true,
      autoHeight : true,
      width : 350,
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
      labelWidth : 75, // label settings here cascade unless overridden
      url : '',
      frame : true,
      title : ' ',
      bodyStyle : 'padding:5px 5px 0',
      width : 350,
      items : [ ],
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
      allowBlank     : false,
      //allowBlankText : 'You should to select a language from the list.'
    })
    
    var txtUser = { 
      xtype:'textfield',
      fieldLabel: 'User',
      disabled: false,
      name: 'user',
      value: ''
    };
    
    var txtPasswd = { 
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
              Ext.Msg.alert ( '', response.responseText );
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
      items   : [txtUser, txtPasswd ],
      buttons : [{
        text : 'Setup Password',
        handler : function() {
          Ext.Msg.show ({ msg : TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:400} });
          Ext.Ajax.request({
            url: 'appCacheViewAjaxx',
            success: function(response) {
              store.reload();
              Ext.MessageBox.hide();              
              Ext.Msg.alert ( '', response.responseText );
            },
            failure : function(response) {
              Ext.Msg.hide();              
              Ext.Msg.alert ( 'Error', response.responseText );
            },
            params: {request: 'build', lang: 'en' },
            waitMsg : 'Building Workflow Application Cache...',
            //timeout : 1000 //30 mins
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

    if (!appCacheViewEnabled) {
      Warning();
    }
  });

var newEl;
var Warning = function() {
  var tpl = new Ext.Template(
      '<div id="fb" style="font-size:12px; border: 1px solid #FF0000; background-color:#FFAAAA; display:none; padding:12px; color:#000000;">',
      '<b>Warning: </b>We detect that the Application Cache Data is not present in this Workspace environment, you need build it <a href="#" id="help1">Online Help</a></div>');
  newEl = tpl.insertFirst(document.body);

  /*
   * Ext.fly('hideWarning').on('click', function() {
   * Ext.fly(newEl).slideOut('t',{remove:true}); cp.set('hideFBWarning', true);
   * });
   */
  Ext.fly(newEl).slideIn();
}

// /

Ext.example = function() {
  var msgCt;

  function createBox(t, s) {
    return [
        '<div class="msg">',
        '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
        '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>',
        t,
        '</h3>',
        s,
        '</div></div></div>',
        '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
        '</div>' ].join('');
  }
  return {
    msg : function(title, format) {
      if (!msgCt) {
        msgCt = Ext.DomHelper.insertFirst(document.body, {
          id : 'msg-div'
        }, true);
      }
      msgCt.alignTo(document, 't-t');
      var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
      var m = Ext.DomHelper.append(msgCt, {
        html : createBox(title, s)
      }, true);
      m.slideIn('t').pause(1).ghost("t", {
        remove : true
      });
    },

    init : function() {
      var lb = Ext.get('lib-bar');
      if (lb) {
        lb.show();
      }
    }
  };
}();

Ext.onReady(Ext.example.init, Ext.example);
