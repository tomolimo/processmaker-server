<?php
session_start();
if (!isset($_SESSION['USER_LOGGED'])) {
	die( '<script type="text/javascript">
		try
		{
				var dataToSend = {
					"action": "credentials",
					"operation": "refreshPmSession",
					"type": "processCall",
					"funParams": [
						"",
						""
					],
					"expectReturn": false
				};
				var x = parent.postMessage(JSON.stringify(dataToSend), "*");
		}catch (err)
		{
			parent.location = parent.location;
		}
	</script>');
}
if(key_exists('form', $_GET) && $_GET['form']){
	header( 'location:' . $_SESSION['server'] . $_SESSION['PMCase'] );
}else if(key_exists('processmap', $_GET) && $_GET['processmap']){
	header( 'location:' . $_SESSION['server'] . $_SESSION['PMProcessmap'] );
}else if(key_exists('uploaded', $_GET) && $_GET['uploaded']){
	header( 'location:' . $_SESSION['server'] . $_SESSION['PMUploadedDocuments'] );
} else if(key_exists('generated', $_GET) && $_GET['generated']){
	header( 'location:' . $_SESSION['server'] . $_SESSION['PMGeneratedDocuments'] );
}
