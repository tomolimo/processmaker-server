

function getCSSRules(selector, sheet) {
	var results = new Array() ;
    var sheets = typeof sheet !== 'undefined' ? [sheet] : document.styleSheets;
    for (var i = 0, l = sheets.length; i < l; i++) {
        var sheet = sheets[i];
        if( !sheet.cssRules ) { continue; }
        for (var j = 0, k = sheet.cssRules.length; j < k; j++) {
            var rule = sheet.cssRules[j];
            if (rule.selectorText && rule.selectorText.split(',').indexOf(selector) !== -1) {
                results.push( rule );
            }
        }
    }
    return results;
}

(function () {
//debugger ;
	var rules = getCSSRules( '.rotateText' ) ;
	for (var i = 0, l = rules.length; i < l; i++) {
		rules[i].style['filter']="";
	}
}());
