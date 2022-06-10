<?php 
/** 
 * Response Helpers
 * 
 * Updated 11 Juni 2020, 09:40
 *
 * @author Yudi Setiadi Permana 
 *
 */
namespace App\Helpers;

class ResponseHelper{

	/**
	 * Normal response
	 *
	 * @param
	 * - msg 	: message for response api
	 * - result : data resutl for response api
	 * 
	 * @return json
	 *
	 */
	public static function setResponse($msg = '', $result = null){

		$response = array('status' => 'OK', 'message' => 'Success', 'result' => null, 'time' => date('Y-m-d H:i:s'));

		if ($msg != '') {
			$response['message'] = $msg;
		}

		if (!empty($result)) {
			$response['result'] = $result;
		}
		
		self::_returnJson($response);

	}

	/**
	 * Error response
	 *
	 * @param
	 * - msg 	: message for response api
	 * 
	 * @return json
	 *
	 */
	public static function setErrorResponse($msg = '', $type = '')
	{
		$result = $type == 'list' ? [] : null;
		$response = array('status' => 'ERROR', 'message' => 'Failed to connect', 'result' => $result, 'time' => date('Y-m-d H:i:s'));

		if ($msg != '') {
			$response['message'] = $msg;
		}
		
		self::_returnJson($response);

	}

	/**
	 * Show json response
	 *
	 * @param
	 * - data : array of data will be encode
	 * 
	 * @return json
	 *
	 */	
	private static function _returnJson($data){

		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
		exit;

	}

}

?>