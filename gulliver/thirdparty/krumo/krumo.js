/** JavaScript routines for Krumo
* @version $Id: krumo.js 22 2007-12-02 07:38:18Z Mrasnika $
* @link http://sourceforge.net/projects/krumo
*/

function krumo() {}
krumo.reclass = function(el, className) {
	if (el.className.indexOf(className) < 0) {
		el.className += (' ' + className);
	}
}
krumo.unclass = function(el, className) {
	if (el.className.indexOf(className) > -1) {
		el.className = el.className.replace(className, '');
	}
}
krumo.toggle = function(el) {
	var ul = el.parentNode.getElementsByTagName('ul');
	for (var i=0; i<ul.length; i++) {
		if (ul[i].parentNode.parentNode == el.parentNode) {
			ul[i].parentNode.style.display = (ul[i].parentNode.style.display == 'none')
				? 'block'
				: 'none';
		}
}
if (ul[0].parentNode.style.display == 'block') {
		krumo.reclass(el, 'krumo-opened');
		} else {
		krumo.unclass(el, 'krumo-opened');
		}
}
krumo.over = function(el) {
	krumo.reclass(el, 'krumo-hover');
}
krumo.out = function(el) {
	krumo.unclass(el, 'krumo-hover');
}
