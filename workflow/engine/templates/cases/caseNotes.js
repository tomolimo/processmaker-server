function closeCaseNotesWindow(){
  if(Ext.get("caseNotesWindowPanel")){
    Ext.get("caseNotesWindowPanel").destroy();
  }
}

function openCaseNotesWindow(appUid,modalSw){
  if(!appUid) appUid="";

  var startRecord=0;
  var loadSize=10;

  var storeNotes = new Ext.data.JsonStore({
    url : '../caseProxy/getNotesList?appUid='+appUid,
    root: 'notes',
    totalProperty: 'totalCount',
    fields: ['USR_USERNAME','NOTE_DATE','NOTE_CONTENT'],
    baseParams:{
      start:startRecord,
      limit:startRecord+loadSize
    },
    listeners:{
      load:function(){
        if(storeNotes.getCount()<storeNotes.getTotalCount()){
          Ext.getCmp('CASES_MORE_BUTTON').show();
        }else{
          Ext.getCmp('CASES_MORE_BUTTON').hide();
        }
      }
    }
  });
  storeNotes.load();

  var tplNotes = new Ext.XTemplate(
    '<tpl for=".">',
    '<div class="thumb-wrap">',
    '<div class="thumb" >',
    '<span class="x-editable"><b>{USR_USERNAME}</b></span><br>',
    '<span class="x-editable">{NOTE_CONTENT}</span><br>',
    '<span class="x-editable"><small><i>{NOTE_DATE}</i></small><hr /></span>',
    '</div>',
    '</div>',
    '</tpl>',
    '<div class="x-clear"></div>'
    );

  
  var panelNotes = new Ext.Panel({
    id:'notesPanel',

    frame:true,
    autoWidth:true,
    autoHeight:true,
    collapsible:false,    
    items:[ new Ext.DataView({
      store: storeNotes,
      tpl: tplNotes,
      autoWidth:true,
      loadingtext:_('ID_CASE_NOTES_LOADING'),
      autoScroll:true,
      multiSelect: false,
      overClass:'x-view-over',
      itemSelector:'div.thumb-wrap',
      emptyText: _('ID_CASE_NOTES_EMPTY'),


      prepareData: function(data){
        //data.shortName = Ext.util.Format.ellipsis(data.name, 15);
        //data.sizeString = Ext.util.Format.fileSize(data.size);
        //data.dateString = data.lastmod.format("m/d/Y g:i a");
        //console.log(data);
        return data;
      },

      listeners: {
        selectionchange: {
          fn: function(dv,nodes){
            var l = nodes.length;
            var s = l != 1 ? 's' : '';
          //panelNotes.setTitle('Process ('+l+' item'+s+' selected)');
          }
        },
        click: {
          fn: function(dv,nodes,a){
          //console.info("Click");
          //console.log(dv);
          //console.log(a);
          }
        }
      }
    }),{
      xtype:'button',
      id:'CASES_MORE_BUTTON',
      hidden:true,
      text:_('ID_CASE_NOTES_MORE'),
      align:'center',
      handler:function() {        
        startRecord=startRecord+loadSize;
        limitRecord=startRecord+loadSize;
        storeNotes.load({
          params:{
            start:0,
            limit:startRecord+loadSize
          }
        });
    }
  }]
  });

var caseNotesWindow;
caseNotesWindow = new Ext.Window({
  title: _('ID_CASES_NOTES'), //Title of the Window
  id: 'caseNotesWindowPanel', //ID of the Window Panel
  width:300, //Width of the Window
  resizable: true, //Resize of the Window, if false - it cannot be resized
  closable: true, //Hide close button of the Window
  modal: modalSw, //When modal:true it make the window modal and mask everything behind it when displayed
  //iconCls: 'ICON_CASES_NOTES',
  autoCreate: true,
  height:400,
  shadow:true,
  minWidth:300,
  minHeight:200,
  proxyDrag: true,
  keys: {
    key: 27,
    fn  : function(){
      caseNotesWindow.hide();
    }
  },
  autoScroll:true,
  items:[panelNotes],
  tools:[
  {
    id:'refresh',
    handler:function() {
      storeNotes.load();
    }
  }
  ],
  bbar:[
  new Ext.ux.StatusBar({
    defaultText : _('ID_NOTES_READY'),
    id : 'notesStatusPanel',
    //defaultIconCls: 'ICON_CASES_NOTES',

    // values to set initially:
    text: _('ID_NOTES_READY'),
    //iconCls: 'ready-icon',
    statusAlign: 'left',

    // any standard Toolbar items:
    items: []
  })

  ],
  tbar:[{
    //xtype:'textfield',
    xtype:'textarea',
    id:'caseNoteText',
    name:'caseNoteText',
    hideLabel: true,
    blankText:_('ID_CASES_NOTES_POST'),
    anchor: '100% -53',
    width:200,
    grow:true,
    selectOnFocus:true,
    maxLenght:150,
    allowBlank:true
  },
  ' ',
  {
    cls: 'x-toolbar1',
    text: _('ID_SUBMIT_NOTE'),
    iconCls: 'x-pm-startcase-btn',
    scale: 'large',
    stype:'button',
    iconAlign: 'top',
    handler: function(){
      var noteText = Ext.getCmp('caseNoteText').getValue();
  
      if (noteText == "") {
        return false;
      }

      Ext.getCmp('caseNoteText').focus();
      Ext.getCmp('caseNoteText').reset();
      statusBarMessage( _('ID_CASES_NOTE_POSTING'), true);
      Ext.Ajax.request({
        url : '../caseProxy/postNote' ,
        params : {
          appUid:appUid,
          noteText:noteText
        },
        success: function ( result, request ) {
          var data = Ext.util.JSON.decode(result.responseText);
          if(data.success=="success"){
            statusBarMessage( _('ID_CASES_NOTE_POST_SUCCESS'), false,true);
            storeNotes.load();
          }else{
            statusBarMessage( _('ID_CASES_NOTE_POST_ERROR'), false,false);
            Ext.MessageBox.alert(_('ID_CASES_NOTE_POST_ERROR'), data.message);

          }
        },
        failure: function ( result, request) {
          statusBarMessage( _('ID_CASES_NOTE_POST_FAILED'), false,false);
          Ext.MessageBox.alert(_('ID_CASES_NOTE_POST_FAILED'), result.responseText);
        }
      });
    }
  }],
  listeners:{
    show:function() {
      this.loadMask = new Ext.LoadMask(this.body, {
        msg:_('ID_LOADING')
      });
    },
    close:function(){
      //console.log(Ext.get("caseNotes"));
      if(Ext.get("caseNotes")){
        Ext.getCmp("caseNotes").toggle(false);
      //Ext.getCmp('caseNotes').show();
      }
    }
  }
});
caseNotesWindow.show();
}

function statusBarMessage( msg, isLoading, success ) {
  // console.log("Status Bar needed");
  // console.log(msg);
  var statusBar = Ext.getCmp('notesStatusPanel');
  if( !statusBar ) return;
  // console.log("Status bar acceced: "+msg);
  if( isLoading ) {
    statusBar.showBusy(msg);
  }
  else {
    //statusBar.setStatus("Done.");
    statusBar.clearStatus();
    if( success ) {
      statusBar.setStatus({
        text: '' + msg,
        //iconCls: 'success',
        clear: true
      });
    } else {
      statusBar.setStatus({
        text: 'Error: ' + msg,
        //iconCls: 'error',
        clear: true
      });
    }
  }
}

