<?php
/**
 *
 *
 */

class controller{

	const controller_ext = '_controller';


	protected static $last_controller;
	protected static $last_action;


	protected static $called_action = array();




	public function __construct($argv = null){
		
	}

	public function __destruct(){
		

	}

/*	public static function get_controller(){
		return self::$called_action;
		
	}*/

	public static function call($controller, $action, $params = array() ){
			$controller_name = $controller . self::controller_ext;
			$action_name  = $action ;
				

				if(method_exists($controller_name, $action_name)) {
					$ctl = new $controller_name;
					self::$called_action[] = $controller .'.'. $action ;
					self::$last_controller = $controller;
					self::$last_action = $action;
					call_user_func_array(array($ctl, $action_name), (array) $params);
				} else {					
					echo '页面不存在！';
					//response::error(404);
				}
			 
	}


}