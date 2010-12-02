Ext.onReady(function(){
  location.href = uriReq;
  
  var hideMask = function () {
      Ext.get('loading').remove();
      Ext.fly('loading-mask').fadeOut({
          remove:true,
          callback : ''
      });
  }

  hideMask.defer(250);

}); 
