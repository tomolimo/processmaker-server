/*
 * PM tooltip
 * 
 * @Adapted by Erik Amaru Ortiz <erik@colosa.com>
 * 
 * */

    var pmtooltip = false;
	var pmtooltipShadow = false;
	var pmshadowSize = 4;
	var pmtooltipMaxWidth = 400;
	var pmtooltipMinWidth = 100;
	var pmiframe = false;
	var tooltip_is_msie = (navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('opera')==-1 && document.all)?true:false;
	
	function showTooltip(e,tooltipTxt){
            if (!pmtooltip) {
                pmtooltip = document.createElement('DIV');
                pmtooltip.id = 'pmtooltip';
                pmtooltipShadow = document.createElement('DIV');
                pmtooltipShadow.id = 'pmtooltipShadow';
                document.body.appendChild(pmtooltip);
                document.body.appendChild(pmtooltipShadow);
            }
            var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            var xOffset = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
            var yOffset = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
            var length = tooltipTxt.length * 4.5;
            if (length < 100)
                length = 100;
            if (length > 400)
                length = 400;
            var x = e.clientX + 10 + xOffset;
            var y = e.clientY + 5 + yOffset;
            if (x + length > w) {
                x = x - length - 20;
            }
            pmtooltip.style.display = 'block';
            pmtooltip.innerHTML = tooltipTxt;
            pmtooltip.style.left = x + 'px';
            pmtooltip.style.top = y + 'px';
            pmtooltip.style.width = length + 'px';
            pmtooltipShadow.style.display = 'block';
            pmtooltipShadow.style.left = (x + pmshadowSize) + 'px';
            pmtooltipShadow.style.top = (y + pmshadowSize) + 'px';
            pmtooltipShadow.style.width = pmtooltip.offsetWidth + 'px';
            pmtooltipShadow.style.height = pmtooltip.offsetHeight + 'px';
	}

	function hideTooltip(){
		pmtooltip.style.display='none';
		pmtooltipShadow.style.display='none';
	}
	
	