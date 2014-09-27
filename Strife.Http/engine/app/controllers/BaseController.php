<?php

class BaseController extends Controller {

	var $response = array();

	function __construct() 
	{
		$_RAW_POST_DATA = file_get_contents('php://input');
		$start_time = time();
		$this->response['meta-data'] = array(
				'RequestDate' => date('Y-m-d H:i:s'),
				'RequestUri' => $_SERVER['REQUEST_URI'],
				'ServerHostname' => $_SERVER['SERVER_ADDR'],
				'ServerName' => $_SERVER['SERVER_NAME'],
				'ClientIP' => $_SERVER['REMOTE_ADDR'],
				'ClientAgent' => $_SERVER['HTTP_USER_AGENT'],
				'RequestTime' => ($_SERVER['REQUEST_TIME_FLOAT']-$start_time)
			);
		$this->response['body'] = NULL;
		$this->response['error'] = array('error_no_request');
	}

	public function Response($status = 200)
	{
		$response = Response::view('response', array('data' => serialize($this->response)), $status);
		//Log::info($response);
		return $response;
	}
}
