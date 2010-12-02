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
		var bodyWidth = Math.max(document.body.clientWidth,document.documentElement.clientWidth) - 20;

		if(!pmtooltip){
			pmtooltip = document.createElement('DIV');
			pmtooltip.id = 'pmtooltip';
			pmtooltipShadow = document.createElement('DIV');
			pmtooltipShadow.id = 'pmtooltipShadow';

			document.body.appendChild(pmtooltip);
			document.body.appendChild(pmtooltipShadow);

			if(tooltip_is_msie){
				pmiframe = document.createElement('IFRAME');
				pmiframe.frameborder='5';
				pmiframe.style.backgroundColor='#FFFFFF';
				pmiframe.src = '#';
				pmiframe.style.zIndex = 100;
				pmiframe.style.position = 'absolute';
				document.body.appendChild(pmiframe);
			}

		}

		pmtooltip.style.display='block';
		pmtooltipShadow.style.display='block';
		if(tooltip_is_msie)pmiframe.style.display='block';

		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0;
		var leftPos = e.clientX + 10;

		pmtooltip.style.width = null;	// Reset style width if it's set
		pmtooltip.innerHTML = tooltipTxt;
		pmtooltip.style.left = leftPos + 5 + 'px';
		pmtooltip.style.top = e.clientY + st + 'px';

		pmtooltipShadow.style.left =  leftPos + pmshadowSize + 'px';
		pmtooltipShadow.style.top = e.clientY + st + pmshadowSize + 'px';

		if(pmtooltip.offsetWidth>pmtooltipMaxWidth){	/* Exceeding max width of tooltip ? */
			pmtooltip.style.width = pmtooltipMaxWidth + 'px';
		}

		var tooltipWidth = pmtooltip.offsetWidth;
		if(tooltipWidth<pmtooltipMinWidth)tooltipWidth = pmtooltipMinWidth;


		pmtooltip.style.width = tooltipWidth + 'px';
		pmtooltipShadow.style.width = pmtooltip.offsetWidth + 'px';
		pmtooltipShadow.style.height = pmtooltip.offsetHeight + 'px';

		if((leftPos + tooltipWidth)>bodyWidth){
			pmtooltip.style.left = (pmtooltipShadow.style.left.replace('px','') - ((leftPos + tooltipWidth)-bodyWidth)) + 'px';
			pmtooltipShadow.style.left = (pmtooltipShadow.style.left.replace('px','') - ((leftPos + tooltipWidth)-bodyWidth) + pmshadowSize) + 'px';
		}

		if(tooltip_is_msie){
			pmiframe.style.left = pmtooltip.style.left;
			pmiframe.style.top = pmtooltip.style.top;
			pmiframe.style.width = pmtooltip.offsetWidth + 'px';
			pmiframe.style.height = pmtooltip.offsetHeight + 'px';

		}

	}

	function hideTooltip(){
		pmtooltip.style.display='none';
		pmtooltipShadow.style.display='none';
		if(tooltip_is_msie)pmiframe.style.display='none';
	}
	
	