Ext.onReady(function() {

  Ext.QuickTips.init();

  // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';

    var bd = Ext.getBody();

    // bd.createChild({tag: 'h2', html: 'Form 2 - Adding fieldsets'});

 // proxy
    proxy = new Ext.data.HttpProxy( {
      url : 'appCacheViewAjax?request=info'
    });

    // reader
    var reader = new Ext.data.JsonReader( {
      root : 'info',
      fields : [ {
        name : 'name'
      }, {
        name : 'value'
      } ]
    })

    // Store
    var store = new Ext.data.Store( {
      proxy : proxy,
      reader : reader
    });
    store.load();

    // manually load local data
    //store.loadData(myData);

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
          width : 140,
          sortable : false,
          dataIndex : 'value'
        }
      ],
      stripeRows : true,
      autoHeight : true,
      width : 330,
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
      title : 'Build/Rebuild the Workflow Applications Cache',
      bodyStyle : 'padding:5px 5px 0',
      width : 300,

      items : [ ],
      buttons : [ 
        /*{ 
          text: 'Test Grants in database' 
        },*/ 
        {
          text : 'Build Cache',
          disabled : false,
          handler : function() {
            fsf.getForm().submit( {
              url : 'appCacheViewAjax?request=build&dbUserType='+dbUserType+'&r=' + Math.random(),
              waitMsg : 'Building Cache for Application Data...',
              timeout : 36000,
              success : function(res, req) {
                /*
                * Ext.MessageBox.show({ title: '', msg: req.result.msg, buttons:
                * Ext.MessageBox.OK, animEl: 'mb9', fn: function(){}, icon:
                * Ext.MessageBox.INFO }); setTimeout(function(){
                * Ext.MessageBox.hide(); }, 2000);
                */
                store.reload();
                Ext.example.msg('', req.result.msg);
                try {
                  Ext.fly(newEl).slideOut('t', {
                    remove : true
                  });
                } catch (e) {
                }
              }
            });
          }
        }
      ]
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
        baseParams : {
          request : 'getLangList'
        },
        reader : new Ext.data.JsonReader( {
          root : 'rows',
          fields : [ {
            name : 'LAN_ID'
          }, {
            name : 'LAN_NAME'
          } ]
        })
      }),
      valueField : 'LAN_ID',
      displayField : 'LAN_NAME',
      triggerAction : 'all',
      emptyText : 'Select',
      selectOnFocus : true,
      editable : false,
      allowBlank : false,
      allowBlankText : 'You should to select a language from the list.'
    })
    
    
    var txtUser = { 
          xtype:'textfield',
          fieldLabel: 'User',
          disabled: false,
          hidden: false,
          name: 'user',
          value: ''
        };
    
    var txtPasswd = { 
          inputType: 'password',
                xtype:'textfield',
          fieldLabel: 'Password',
          disabled: false,
          hidden: false,
          name: 'password',
          value: ''
    }
    
    if( true /*enoughGrants */){
      fieldset = {
          xtype : 'fieldset',
          title : 'Cache configuration',
          collapsible : false,
          autoHeight : true,
          defaults : {
            width : 170
          },
          defaultType : 'textfield',
          items : [cmbLanguages]
      }
    } 
    else {
      fieldset = {
          xtype : 'fieldset',
          title : 'Cache configuration',
          collapsible : false,
          autoHeight : true,
          defaults : {
            width : 170
          },
          defaultType : 'textfield',
          items : [cmbLanguages, txtUser, txtPasswd]
      }
    }
    
    fsf.add(fieldset);

    fsf.render(document.getElementById('main-panel'));

    
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
