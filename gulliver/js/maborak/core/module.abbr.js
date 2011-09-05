var $=function(el)
{
	var d =  (typeof el=="string")?document.getElementById(el):el;
	return new leimnud.module.dom.methods(d);
};
var button	= leimnud.module.dom.button;
var input	= leimnud.module.dom.input;
var DOM		= leimnud.module.dom.create;
var panel	= leimnud.module.panel;
var select	= leimnud.module.dom.select;