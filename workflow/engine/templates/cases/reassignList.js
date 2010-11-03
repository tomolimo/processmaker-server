  Ext.util.Format.comboRenderer = function(combo){
    return function(value){
      var record = combo.findRecord(combo.valueField, value);
      //var record = array();
      return record ? record.get(combo.displayField) : combo.valueNotFoundText;
    }
  }
  
  // create the combo instance
  var combo = new Ext.form.ComboBox({
    typeAhead     : true,
    triggerAction : 'all',
    lazyRender    : true,
    mode  : 'local',
    store : new Ext.data.JsonStore({
        id     : Ext.id(),
        fields : [
            'userId',
            'userFullname'
        ]
        //data   : 
    }),
    valueField: 'userId',
    displayField: 'userFullname'
  });

//alert (this.fields.);