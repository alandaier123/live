<?php
/**
 * 控制器基类
 *
 * @package		KIS
 * @author		塑料做的铁钉<ring.msn@gmail.com>
 * @since		2014-06-23
 * @example
 *
 */

class controller{

	const controller_ext = '_controller';


	protected static $last_controller;
	protected static $last_action;

	/**
	 * 所有被调用的action
	 * @var array
	 */
	protected static $called_action = array();

	protected static $debug_on = false;

	/**
	 * 构造一下
	 * @param null
	 */
	public function __construct($argv = null){
		//parent::__construct();
	}

	public function __destruct(){
		
/*		if(method_exists('hlp_stat', 'PageView')){
			$pv_name = self::$last_controller . '__' .self::$last_action;
			hlp_stat::PageView($pv_name,'','',NULL,FALSE);
		}*/
	}

	/**
	 * 调用某个控制器下的某
	 * @param  string $controller 控制器名称
	 * @param  string $action     action名称
	 * @param  array  $params     传入action的参数
	 * @return null
	 */
	public static function call($controller, $action, $params = array() ){
			$controller_name = $controller . self::controller_ext;
			$action_name  = $action ;
			if(class_exists($controller_name)) {
				if(method_exists($controller_name, $action_name)) {
					$ctl = new $controller_name;
					self::$called_action[] = $controller .'.'. $action ;
					self::$last_controller = $controller;
					self::$last_action = $action;
					call_user_func_array(array($ctl, $action_name), (array) $params);
				} else {

					return('方法不存在！');
					//Response::error(404, "action not found:{$controller_name}::{$action_name}");
					
				}
			} else {
				return('不存在的类！');
				
			}
	}


}