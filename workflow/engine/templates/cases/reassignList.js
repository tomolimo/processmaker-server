

  var proxyUsersToReassignList = new Ext.data.HttpProxy({
    api: {
      read :   'proxyReassignUsersList'
    }
  });

  var readerUsersToReassignList = new Ext.data.JsonReader({
    //totalProperty: 'totalCount',
    //successProperty: 'success',
    //idProperty: 'index',
    root: 'data',
    fields: [
        // map Record's 'firstname' field to data object's key of same name
        {name: 'userUid', mapping: 'userUid'},
        // map Record's 'job' field to data object's 'occupation' key
        {name: 'userFullname', mapping: 'userFullname'}
    ]

    //messageProperty: 'message'
    }
  );


  // The new DataWriter component.
  //currently we are not using this in casesList, but it is here just for complete definition
  var writerUsersToReassignList = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: true
  });

  
  var storeUsersToReassign = new Ext.data.Store({
    remoteSort: true,
    proxy : proxyUsersToReassignList,
    reader: readerUsersToReassignList,
    writer: writerUsersToReassignList,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: false // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
  });
  
  Ext.util.Format.comboRenderer = function(combo){
    return function(value){
      var record   = combo.findRecord(combo.valueField, value);
      //getting the parent gridpanel.
      /*var gp = combo.findParentBy (
        function (ct, cmt) {return (ct instanceof Ext.grid.GridPanel) ? true : false;}
      );*/

      //storeUsersToReassign.load();
      //alert(gp);
      // var record = array();
      return record ? record.get(combo.displayField) : combo.valueNotFoundText;
    }
  }

  // create the combo instance
  var combo = new Ext.form.ComboBox({
    typeAhead     : true,
    triggerAction : 'all',
    lazyRender    : true,
    //store         : new Ext.data.Store(),
    store         : storeUsersToReassign,
    /*listeners:{
      'select': function() {
        //storeUsersToReassign.load();
        //alert("extras");
        //getSelectionData();
      }
    },*/
    valueField    : 'userId',
    displayField  : 'userFullname'
  });

//alert (this.fields.);