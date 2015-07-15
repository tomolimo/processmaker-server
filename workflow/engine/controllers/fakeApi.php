<?php

class FakeApi extends HttpProxyController
{

	public function save() {
		$json = new stdClass();
		$json->success = true;
		echo json_encode($json);
		exit;
	} 

}

