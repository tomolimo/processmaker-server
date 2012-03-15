Ext.onReady(function(){
  var box = new Ext.form.Checkbox({
    boxLabel: _('ID_ENABLE_EMAIL_NOTIFICATION'),//'Enable Email Notifications',
    name: 'EnableEmailNotifications',
    id: 'EnableEmailNotifications',
    checked:false,
    listeners: {
      check: function(EnableEmailNotifications, checked) {
        if(checked) {
          loadfields();
          combo.setVisible(true);
          combo.getEl().up('.x-form-item').setDisplayed(true); // show label 
          Ext.getCmp('Server').setVisible(true);
          Ext.getCmp('Server').getEl().up('.x-form-item').setDisplayed(true); // show label            
          Ext.getCmp('Port').setVisible(true);
          Ext.getCmp('Port').getEl().up('.x-form-item').setDisplayed(true);  
          Ext.getCmp('RequireAuthentication').setVisible(true);
          Ext.getCmp('RequireAuthentication').getEl().up('.x-form-item').setDisplayed(true); 
          Ext.getCmp('AccountFrom').setVisible(true);
          Ext.getCmp('AccountFrom').getEl().up('.x-form-item').setDisplayed(true); 
          Ext.getCmp('Password').setVisible(false);
          Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false); 
          Ext.getCmp('SendaTestMail').setVisible(true);   
          Ext.getCmp('SendaTestMail').getEl().up('.x-form-item').setDisplayed(true);
	  
          if(Ext.getCmp('SendaTestMail').checked) {     
            Ext.getCmp('eMailto').setVisible(true); 
            Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(true);                       
          }
          else {         
            Ext.getCmp('eMailto').setVisible(false); 
            Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(false);
            Ext.getCmp('eMailto').setValue(' ');
          }
	  
          if(!Ext.getCmp('UseSecureConnection').getValue()) {
            Ext.getCmp('UseSecureConnection').setValue('No');          
          }
	  
          Ext.getCmp('UseSecureConnection').setVisible(true); 
          Ext.getCmp('UseSecureConnection').getEl().up('.x-form-item').setDisplayed(true);
          Ext.getCmp('Test').setDisabled(false);
        } 
        else {
          combo.setVisible(false);
          combo.getEl().up('.x-form-item').setDisplayed(false); // hide label   
          Ext.getCmp('Server').setVisible(false);
          Ext.getCmp('Server').getEl().up('.x-form-item').setDisplayed(false); // hide label   
          Ext.getCmp('Port').setVisible(false);
          Ext.getCmp('Port').getEl().up('.x-form-item').setDisplayed(false);  
          Ext.getCmp('RequireAuthentication').setVisible(false);
          Ext.getCmp('RequireAuthentication').getEl().up('.x-form-item').setDisplayed(false);
          Ext.getCmp('AccountFrom').setVisible(false);
          Ext.getCmp('AccountFrom').getEl().up('.x-form-item').setDisplayed(false);              
          Ext.getCmp('Password').setVisible(false);
          Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false);
          Ext.getCmp('SendaTestMail').setVisible(false);  
          Ext.getCmp('SendaTestMail').getEl().up('.x-form-item').setDisplayed(false);
          
	  if(Ext.getCmp('SendaTestMail').getValue().checked) { 
            Ext.getCmp('eMailto').setVisible(true); 
            Ext.getCmp('eMailto').setVisible(true);               
            Ext.getCmp('eMailto').setValue('');
          } 
          else {  
            Ext.getCmp('eMailto').setVisible(false); 
            Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(false);
            Ext.getCmp('eMailto').setValue(' '); 
          }
          
	  Ext.getCmp('UseSecureConnection').setVisible(false);  
          Ext.getCmp('UseSecureConnection').getEl().up('.x-form-item').setDisplayed(false);
          Ext.getCmp('Test').setDisabled(true)
        }
      }
    }
  });

  var EmailEngine  = new Ext.data.SimpleStore({  
    fields: ['id', 'EmailEngine'],        
    data : [
      ['PHPMAILER','SMTP (PHPMailer)'],
      ['MAIL','Mail (PHP)']          
    ] 
  }); 

  var combo = new Ext.form.ComboBox({
    id:'EmailEngine',
    name:'EmailEngine',
    xtype: 'combo',         
    fieldLabel: _('EMAIL_ENGINE'),//'Email Engine',
    blankText: '',
    lazyRender: true,
    allowBlank: false,
    selectOnFocus: true,  
    forceSelection: true, 
    store:EmailEngine,    
    displayField:'EmailEngine', 
    mode: 'local',      
    triggerAction: 'all',
    listeners: {
      select: function(combo, value) {
        if (Ext.getCmp('EmailEngine').getValue()== 'Mail (PHP)') {
	  Ext.getCmp('Server').setVisible(false);
	  Ext.getCmp('Server').getEl().up('.x-form-item').setDisplayed(false); // hide label   
	  Ext.getCmp('Port').setVisible(false);
	  Ext.getCmp('Port').getEl().up('.x-form-item').setDisplayed(false);  
	  Ext.getCmp('RequireAuthentication').setVisible(false); 
	  Ext.getCmp('RequireAuthentication').getEl().up('.x-form-item').setDisplayed(false);
	  Ext.getCmp('AccountFrom').setVisible(false);
	  Ext.getCmp('AccountFrom').getEl().up('.x-form-item').setDisplayed(false);              
	  Ext.getCmp('Password').setVisible(false);
	  Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false);             
        }
	else {
	  Ext.getCmp('Server').setVisible(true);
	  Ext.getCmp('Server').getEl().up('.x-form-item').setDisplayed(true); // hide label   
	  Ext.getCmp('Port').setVisible(true);
	  Ext.getCmp('Port').getEl().up('.x-form-item').setDisplayed(true);  
	  Ext.getCmp('RequireAuthentication').setVisible(true); 
	  Ext.getCmp('RequireAuthentication').getEl().up('.x-form-item').setDisplayed(true);
	  Ext.getCmp('AccountFrom').setVisible(true);
	  Ext.getCmp('AccountFrom').getEl().up('.x-form-item').setDisplayed(true);              
	  Ext.getCmp('Password').setVisible(false);
	  Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false);             
        }         	  
      }
    } 
  });

  var EMailFields = new Ext.form.FieldSet({
    title: _('ID_CONFIGURATION'),
    items : [
      box,    
      combo,  
      {
        xtype: 'textfield',  
        hideLabel : false,
        fieldLabel: _('ID_SERVER'),//'Server',     
        id:'Server',     
        //blankText: 'Server',
        width: 200,
        allowBlank: false,
	listeners : {
	  'change': {
            fn:function() {
              Ext.getCmp('PasswordHide').setValue('');
	      Ext.getCmp('Password').setValue('');
            }
          }	 
        }
      },
      {
        xtype: 'numberfield',   
        fieldLabel: _('PORT_DEFAULT'),//'Port (default 25)',
        id:'Port',  
        name:'Port',
        emptyText : null,
        width: 40,
        maxLength: 3,  
        allowBlank: false
      },    
      {
        xtype: 'checkbox',
        boxLabel: _('REQUIRE_AUTHENTICATION'),//'Require authentication',
        id:'RequireAuthentication',
        name:'RequireAuthentication',
        validateMessage: 'You really should do it.',
        validateField: true,
        handler: function() {
          if (this.checked) {
	    Ext.getCmp('Password').setVisible(true);    
            Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(true); 
	  }
	  else {
	    Ext.getCmp('Password').setVisible(false);    
            Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false);
	    Ext.getCmp('Password').setValue('');	    
	  }
        } 
      },
      {
        xtype: 'textfield',    
        fieldLabel: _('ACCOUNT_FROM'),//'Account From',
        id:'AccountFrom',
        name:'AccountFrom',
        vtype:'email',
        width: 200,
        allowBlank: false
      },
      {
        xtype: 'textfield',   
        fieldLabel: _('ID_PASSWORD'),//'PasswordHidden',
        id:'PasswordHide',
        name:'PasswordHide',
        inputType: 'PasswordHide',      
        width: 200,
	hidden: true,
	hideLabel: true,
        allowBlank: true
      },
      {
        xtype: 'textfield',  
        fieldLabel: _('ID_PASSWORD'),//'Password',
        id:'Password',
        name:'Password',
        inputType: 'password',      
        width: 200,
        allowBlank: true,
	listeners : {          
	  'change' : function() {
	   if (Ext.getCmp('Password').getValue() != '') {
              Ext.getCmp('PasswordHide').setValue('');
	    }
	  }
        } 
      },
      {
        xtype: 'checkbox',
        boxLabel: _('SEND_TEST_MAIL'),//'Send a test mail' ,
        id:'SendaTestMail',
        name:'SendaTestMail',
        listeners: {
          check: function(EnableEmailNotifications, checked) {
            if(checked) {           
              Ext.getCmp('eMailto').setVisible(true);    
              Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(true); 
              Ext.getCmp('eMailto').setValue(''); 
            } 
            else{           
              Ext.getCmp('eMailto').setVisible(false);
              Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(false); 
              Ext.getCmp('eMailto').setValue('  ');                
            }
          }
        }
      },
      {
        xtype: 'textfield',   
        fieldLabel: _('MAIL_TO'),//'Mail to',
        id:'eMailto', 
        name:'eMailto',      
        width: 200,          
        allowBlank: false
      },
      {
        id:'UseSecureConnection',
        name:'UseSecureConnection', 
        xtype: 'radiogroup',  
        fieldLabel: _('USE_SECURE_CONNECTION'),//'Use Secure Connection',
        columns: 3,       
        width: 200,
        vertical: true,     
        items: [
          {boxLabel: 'No',inputValue: 'No',name: 'UseSecureConnection',checked:true},
          {boxLabel: 'TLS', inputValue: 'tls',name: 'UseSecureConnection'},
          {boxLabel: 'SSL', inputValue: 'ssl',name: 'UseSecureConnection'}
        ]
      }   
    ]  
  });


  loadfields = function(){
    Ext.Ajax.request({
      url: '../adminProxy/loadFields',
      params: { CFG_UID: 'Emails' },
      success: function(r,o) {          
        var res = Ext.decode(r.responseText);
        
	if (res.success) { 
          Ext.getCmp('EnableEmailNotifications').setValue(res.data.MESS_ENABLED);
          Ext.getCmp('EmailEngine').setValue(res.data.MESS_ENGINE);
          Ext.getCmp('Server').setValue(res.data.MESS_SERVER);
          Ext.getCmp('Port').setValue(res.data.MESS_PORT);
          Ext.getCmp('RequireAuthentication').setValue(res.data.MESS_RAUTH);
          Ext.getCmp('AccountFrom').setValue(res.data.MESS_ACCOUNT);
          Ext.getCmp('Password').setValue(res.data.MESS_PASSWORD);
	  Ext.getCmp('PasswordHide').setValue(Ext.getCmp('Password').getValue());
	  Ext.getCmp('Password').setValue('');
          Ext.getCmp('SendaTestMail').setValue(res.data.MESS_TRY_SEND_INMEDIATLY);          
          
	  if(!res.data.MAIL_TO) {
            Ext.getCmp('eMailto').setValue(' ');
          }
          else {
            Ext.getCmp('eMailto').setValue(res.data.MAIL_TO);
          }
	  
          if (res.data.SMTPSecure == 'none') {
            Ext.getCmp('UseSecureConnection').setValue('No'); 
          }
          else {
            Ext.getCmp('UseSecureConnection').setValue(res.data.SMTPSecure); 
          }        
        }	
      }
    });
  }

  loadfields();

  var frm = new Ext.FormPanel({
    title: '&nbsp',
    id:'EMailFields',
    labelWidth: 150,
    width:600,
    labelAlign:'right',
    autoScroll: true,
    bodyStyle:'padding:2px',
    waitMsgTarget : true,
    frame: true,    
    defaults: {
      allowBlank: false,     
      msgTarget: 'side',
      align:'center'
    },
    items: [ EMailFields ],
    buttons : [{
      text : _('ID_TEST'),
      id:'Test',
      handler: testMethod         
    },
    {
      text : _('ID_SAVE_CHANGES'),
      id:'SaveChanges',
      disabled : true,
      handler: saveMethod             
    }]
  });
  //render to process-panel
  frm.render(document.body);
  combo.setVisible(false);
  combo.getEl().up('.x-form-item').setDisplayed(false); // hide label    
  Ext.getCmp('Server').setVisible(false);
  Ext.getCmp('Server').getEl().up('.x-form-item').setDisplayed(false); 
  Ext.getCmp('Port').setVisible(false);
  Ext.getCmp('Port').getEl().up('.x-form-item').setDisplayed(false);  
  Ext.getCmp('RequireAuthentication').setVisible(false); 
  Ext.getCmp('RequireAuthentication').getEl().up('.x-form-item').setDisplayed(false);
  Ext.getCmp('AccountFrom').setVisible(false);
  Ext.getCmp('AccountFrom').getEl().up('.x-form-item').setDisplayed(false);              
  Ext.getCmp('Password').setVisible(false);
  Ext.getCmp('Password').getEl().up('.x-form-item').setDisplayed(false);
  Ext.getCmp('SendaTestMail').setVisible(false);  
  Ext.getCmp('SendaTestMail').getEl().up('.x-form-item').setDisplayed(false); 
  Ext.getCmp('eMailto').setVisible(false);
  Ext.getCmp('eMailto').getEl().up('.x-form-item').setDisplayed(false);
  Ext.getCmp('UseSecureConnection').setVisible(false);  
  Ext.getCmp('UseSecureConnection').getEl().up('.x-form-item').setDisplayed(false);
  Ext.getCmp('Test').setDisabled(true);  
});

var testConnForm = new Ext.FormPanel({
  collapsible: false,
  maximizable: true,
  width:445,    
  autoHeight:true,
  frame:true,
  autoDestroy : true,
  monitorValid : true,
  plain: true,
  waitMsgTarget: true,    
  items:[{
    xtype  : 'fieldset',
    layout : 'form',
    id:'testConnField',
    title: _('TESTING_EMAIL_CONF'),//'Testing email configuration',
    labelWidth:20,
    items : [
      {
        xtype: 'label', fieldLabel: ' ', 
        id:'step1', width: 300, 
        labelSeparator:''
      },
      {
        xtype: 'label', fieldLabel: '     ', 
        id:'result1', 
        width: 300, 
        labelSeparator:'',
        style : 'font-size: 11px;'
      },
      {
        xtype: 'label', 
        fieldLabel: ' ', 
        id:'step2', 
        width: 300, 
        labelSeparator:''
      },
      {
        xtype: 'label', fieldLabel: '     ', 
        id:'result2', 
        width: 300, 
        labelSeparator:'',
        style : 'font-size: 11px;'
      },
      {
        xtype: 'label', 
        fieldLabel: ' ', 
        id:'step3', 
        width: 300, 
        labelSeparator:''
      },
      {
        xtype: 'label', fieldLabel: '     ', 
        id:'result3', 
        width: 300, 
        labelSeparator:'',
        style : 'font-size: 11px;'
      },
      {
        xtype: 'label', 
        fieldLabel: ' ', 
        id:'step4', 
        width: 300, 
        labelSeparator:''
      },
      {
        xtype: 'label', 
        fieldLabel: '     ', 
        id:'result4', 
        width: 300, 
        labelSeparator:'',
        style : 'font-size: 11px;'
      },
      {
        xtype: 'label', 
        fieldLabel: ' ', 
        id:'step5', 
        width: 300, 
        labelSeparator:''
      },
      {
        xtype: 'label', 
        fieldLabel: '     ', 
        id:'result5', 
        width: 300, 
        labelSeparator:'',
        style : 'font-size: 11px;'
      }
    ]
  }],
  buttons: [    
    {
      text:_('ID_DONE'),
      id: 'done',
      handler: function(){
        testEmailWindow.hide();	      
      }
    }
  ]
});
  
var testEmailWindow = new Ext.Window({ 
  width: 470,    
  closable:false,
  autoHeight: true,
  layout: 'fit',
  plain: true,
  y: 82,
  items: testConnForm
});

var params;
var count = 0;
var testMethod = function()
{
  if((Ext.getCmp('Port').getValue()==null)||(Ext.getCmp('Port').getValue()=='')) {
    Ext.getCmp('Port').setValue('25');
  }
  
  if (Ext.getCmp('RequireAuthentication').checked){
    if (Ext.getCmp('Password').getValue()=='') {  
      if (Ext.getCmp('PasswordHide').getValue()=='') {
        PMExt.warning(_('ID_WARNING'),_('ID_PASSWD_REQUIRED'));
        return false;
      }    
    }
  }  
  var x                   = Ext.getCmp('UseSecureConnection').getValue();
  var UseSecureConnection = x.getGroupValue();
  var count=0;
  var create=true;
  
  params = {		    
    server         : Ext.getCmp('Server').getValue(),         
    user           : Ext.getCmp('AccountFrom').getValue(),
    passwd         : Ext.getCmp('Password').getValue(),
    passwdHide     : Ext.getCmp('PasswordHide').getValue(),
    port           : Ext.getCmp('Port').getValue(),
    req_auth       : Ext.getCmp('RequireAuthentication').getValue(),
    UseSecureCon   : UseSecureConnection,
    SendaTestMail  : Ext.getCmp('SendaTestMail').getValue() ,
    eMailto        : Ext.getCmp('eMailto').getValue(),
    login          : Ext.getCmp('AccountFrom').getValue()    
  };  
    
  Ext.getCmp('step1').setText('<span id="rstep1"></span>  '+_('RESOLVING_NAME')+' <b>'+params.server+'</b>', false);
  Ext.getCmp('step2').setText('<span id="rstep2"></span>  '+_('ID_CHECK_PORT')+' <b>'+params.port+'</b>',false);
  Ext.getCmp('step3').setText('<span id="rstep3"></span>  '+_('ESTABLISHING_CON_HOST')+' <b>'+params.server+':'+params.port+'</b>',false);
  Ext.getCmp('step4').setText('<span id="rstep4"></span>  '+_('LOGIN_AS')+' [<b>'+params.login+'</b>] '+_('ID_ON')+' '+params.server+' '+_('SMTP_SERVER')+' <b>',false);  
  Ext.getCmp('step5').setText('<span id="rstep5"></span>  '+_('SENDING_TEST_EMAIL')+' [<b>'+params.eMailto +'</b>]...<b>', false);
  
  Ext.getCmp('step1').setVisible(false);
  Ext.getCmp('step2').setVisible(false);
  Ext.getCmp('step3').setVisible(false);
  Ext.getCmp('step4').setVisible(false);
  Ext.getCmp('step5').setVisible(false);
  Ext.getCmp('result1').setVisible(false);
  Ext.getCmp('result2').setVisible(false);
  Ext.getCmp('result3').setVisible(false);
  Ext.getCmp('result4').setVisible(false);
  Ext.getCmp('result5').setVisible(false);
  
  Ext.getCmp('done').enable();    
  Ext.getCmp('SaveChanges').disable();
  
  testEmailWindow.show();
  
  execTest(1);  
  
  return true;
}  
  
function execTest(step) {
  
  if (step == 6) return false;
  
  if ((step == 5) && (params.SendaTestMail == false))
    return false;
  
  document.getElementById('rstep'+step).innerHTML = '<img width="13" height="13" border="0" src="/images/ajax-loader.gif">';
  Ext.getCmp('step'+step).setVisible(true); 
  
  params.step = step;
  
  Ext.Ajax.request({
    url: '../adminProxy/testConnection',
    method:'POST',
    params: params,
    waitMsg: _('ID_UPLOADING_PROCESS_FILE'),   
    success: function(r,o){      
      var resp = Ext.util.JSON.decode(r.responseText);
      
      if (resp.success) {                     	
	img = '/images/dialog-ok-apply.png';
	Ext.getCmp('SaveChanges').enable();
      }
      else {
	img = '/images/delete.png';
	Ext.getCmp('SaveChanges').disable();		
      }
      
      document.getElementById('rstep'+step).innerHTML = '<img width="13" height="13" border="0" src="'+img+'">';      

      if(resp.msg) {
	document.getElementById('result'+step).innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#FF0000;">'+resp.msg+'</span>';
        Ext.getCmp('result'+step).setVisible(true);		 
      }
      
      execTest(step+1);      
    }
  });  
} 
  
saveMethod=function() { 
  var x = Ext.getCmp('UseSecureConnection').getValue();
  var UseSecureConnection = x.getGroupValue(); 
  Ext.Ajax.request({
    url: '../adminProxy/saveConfiguration',
    method:'POST',
    params:{                     
      type:'type',
      server:Ext.getCmp('Server').getValue(),
      db_name:'db_name', 
      from:Ext.getCmp('AccountFrom').getValue(),
      passwd:Ext.getCmp('Password').getValue(),
      passwdHide:Ext.getCmp('PasswordHide').getValue(),
      port:Ext.getCmp('Port').getValue(),
      req_auth:Ext.getCmp('RequireAuthentication').getValue(),                   
      UseSecureCon:UseSecureConnection,
      SendaTestMail : Ext.getCmp('SendaTestMail').getValue() ,
      eMailto :  Ext.getCmp('eMailto').getValue(),
      EnableEmailNotifications : Ext.getCmp('EnableEmailNotifications').getValue(),
      EmailEngine : Ext.getCmp('EmailEngine').getValue(),
      background: 'true'
    },
    success: function(r){
      var i = Ext.decode(r.responseText); 
      PMExt.notify(_('ID_CHANGES_SAVED'),i.msg);
      Ext.getCmp('Password').setValue('');
    }
  });
}