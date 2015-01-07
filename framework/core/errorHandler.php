<?php
class errorHandler{
	/**
	 * 错误类型
	 * @param int $code 错语类型代码
	 * @return array 返回
	 */
	public static function errorType($code){
		$error = $log = null;
		switch($code){
			case E_PARSE:
			case E_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR: 
				$error = 'Fatal Error';
				$log = LOG_ERR;
				break;
			case E_WARNING:
			case E_USER_WARNING:
			case E_COMPILE_WARNING:
			case E_RECOVERABLE_ERROR:
				$error = 'Warning';
				$log = LOG_WARNING;
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = 'Notice';
				$log = LOG_NOTICE;
				break;
			case E_STRICT:
				$error = 'Strict';
				$log = LOG_NOTICE;
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				$error = 'deprecated';
				$log = LOG_NOTICE;
				break;
		}
		return array($error,$log);
	}
	
	public function handlerError($error,$msg,$file,$line){
	
	}
}