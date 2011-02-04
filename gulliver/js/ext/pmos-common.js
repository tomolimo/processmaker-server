PMExtJSCommon = function() {
  this.version = '1.8';
   
  this.confirm = function(title, msg, fnYes, fnNo)
  {
    Ext.MessageBox.confirm(title, msg, function(btn, text){
      if ( btn == 'yes' )
        setTimeout(fnYes, 0)
      else if( fnNo != undefined )
        setTimeout(fnNo, 0)
    });      
  }
  
  this.info = function(title, msg, fn) {
    Ext.MessageBox.show({
      title: title,
      msg: msg,
      buttons: Ext.MessageBox.OK,
      animEl: 'mb9',
      fn: fn != undefined ? fn: function(){},
      icon: Ext.MessageBox.INFO
    });
  }

  this.question = function(title, msg, fn) {
    Ext.MessageBox.show({
      title: title,
      msg: msg,
      buttons: Ext.MessageBox.OK,
      animEl: 'mb9',
      fn: fn != undefined ? fn: function(){},
      icon: Ext.MessageBox.QUESTION
    });
  }

  this.warning = function(title, msg, fn) {
    Ext.MessageBox.show({
      title: title,
      msg: msg,
      buttons: Ext.MessageBox.OK,
      animEl: 'mb9',
      fn: fn != undefined ? fn: function(){},
      icon: Ext.MessageBox.WARNING
    });
  }

  this.error = function(title, msg, fn) {
    Ext.MessageBox.show({
      title: title,
      msg: msg,
      buttons: Ext.MessageBox.OK,
      animEl: 'mb9',
      fn: fn != undefined ? fn: function(){},
      icon: Ext.MessageBox.ERROR
    });
  }
  
  this.notify = function(title, msg)
  {
    Ext.msgBoxSlider.msg(title, msg);
  }
} 
var PMExt = new PMExtJSCommon();


/**
 * Common Ext Functions
 */

Ext.msgBoxSlider = function(){
  var msgCt;

  function createBox(t, s){
    return [
      '<div class="msg">',
      '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
      '<div class="x-box-ml"><div class="x-box-mr"><div id="x-box-mc-inner" class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
      '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
      '</div>'
    ].join('');
  }
  return {
    msg : function(title, format) {
      if( ! msgCt ) {
          msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
      }
      msgCt.alignTo(document, 't-t');
      var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
      var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
      m.setWidth(400 );
      m.position(null, 5000 );
      m.alignTo(document, 't-t');
      Ext.get('x-box-mc-inner' ).setStyle('background-image', 'url("<?php echo _EXT_URL ?>/images/_accept.png")');
      Ext.get('x-box-mc-inner' ).setStyle('background-position', '5px 10px');
      Ext.get('x-box-mc-inner' ).setStyle('background-repeat', 'no-repeat');
      Ext.get('x-box-mc-inner' ).setStyle('padding-left', '35px');
      m.slideIn('t').pause(3).ghost("t", {remove:true});
    }
  };
}();

/** 
 * Translator function for internationalization
 */
function _(ID_LABEL)
{
  if( typeof(TRANSLATIONS) != 'undefined' ) {
    if( typeof(TRANSLATIONS[ID_LABEL]) != 'undefined' ) {
      trn = TRANSLATIONS[ID_LABEL];
    } else {
      trn = '**' + ID_LABEL + '**';
    }
  } else {
    PMExt.error('Processmaker JS Core Error', 'The TRANSLATIONS global object is not loaded!');
    trn = '';
  }
  return trn;
}
