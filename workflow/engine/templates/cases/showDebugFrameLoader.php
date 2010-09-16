<script>
 try{
        if( !parent.PANEL_EAST_OPEN ){
            parent.PANEL_EAST_OPEN = true;
            parent.outerLayout.show('east', false);
            parent.outerLayout.toggle('east');
        }
    } catch(e){}

	var oRPC = new leimnud.module.rpc.xmlhttp({
	  	url : 'cases_Ajax',
	  	args: 'action=showdebug'
	});
    parent.document.getElementById('paneEastNorthContent').innerHTML  = '<center><img src="/images/ajax-loader.gif" border="0"></center>';
    parent.document.getElementById('paneEastCenterContent').innerHTML = '<center><img src="/images/ajax-loader.gif" border="0"></center>';;
    parent.document.getElementById('paneEastSouthContent').innerHTML  = '<center><img src="/images/ajax-loader.gif" border="0"></center>';;
	oRPC.callback = function(rpc){
	  	var scs=rpc.xmlhttp.responseText.extractScript();
        htmlResp = rpc.xmlhttp.responseText;
        Sections = htmlResp.split('<!---->');

        parent.document.getElementById('paneEastNorthContent').innerHTML = Sections[0];
        parent.document.getElementById('paneEastCenterContent').innerHTML = Sections[1];
        parent.document.getElementById('paneEastSouthContent').innerHTML = Sections[2];
	  	scs.evalScript();
	}.extend(this);
	oRPC.make();
</script>