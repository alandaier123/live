<?php

class router{

	/* URL结构示例：
	 * /controller/action/param[0]/param[1]/.../?xxx=v....
	 * /dir_name/controller/action/param[0]/param[1]/.../?xxx=v....
	*/

	protected static $path_info = array();

	//控制器、方法、参数信息
	protected static $controller = null;
	protected static $action = null;
	protected static $params = array();

	//默认控制器前目录层数
	const prefix_dir_num 		= 0;

	//404 页面
	const default_controller_404 = 'default404';
	const default_action_404 = 'index';

	const default_controller = 'index';
	const default_action 	 = 'index';

	public static function auto(){
		self::parse_uri();
		
		if(self::parse_controller() && self::$controller) {
			 

			controller::call(self::$controller, self::$action, self::$params);
		} else {
			if(self::default_controller_404) {
				//if(method_exists(DEFAULT_CONTROLLER_404, $action_name))
				if(self::default_action_404) {
					self::$controller = self::default_controller_404;
					self::parse_action(-1);
					return controller::call(self::default_controller_404, self::default_action_404, self::$params);
				} 
					self::parse_action(0);
					return controller::call(self::default_controller_404, self::$action, self::$params);
			}

			
			//Response::error('404', 'controller not found  :'. (self::$controller ?: self::$path_info[intval(CORE_PREFIX_PARAMS_NUM)]) );
		}

	}
	
	protected static function parse_controller(){
		$dir_num = self::prefix_dir_num;	
		
		if(empty(self::$path_info[$dir_num])) {
			
			self::$controller = self::default_controller_404;
			self::$action = self::default_action_404;
	
			return true;
		} else {
			$dir_name = APP_PATH.DS.autoloader::controller;
			
			//var_dump(self::$path_info);die;
			foreach (self::$path_info as $key => $path) {
				
				if (!preg_match('/^[\w]+$/', $path)) {
					return false;
				}
			
				if(file_exists($dir_name)){

					if(file_exists($dir_name.DS.strtolower($path).EXT )){

						self::$controller = implode('_', array_slice(self::$path_info, $dir_num, $key - $dir_num + 1));
						//var_dump(self::$controller);die;
						self::parse_action($key);
						return true;
					}
					$dir_name .= DS . strtolower($path);
				
				}
			}
			return false;
		}
	}
	/**
	 * 解析URL到self::$path_info
	 * @return null
	 */
	protected static function parse_uri(){
		
		$pathinfo = $_SERVER['REQUEST_URI'];
		if (false !== ($pos = strpos($pathinfo, '?'))) {
			$pathinfo = substr($pathinfo, 0, $pos);
		}
		
		$pathinfo = trim(str_replace('//', '/', $pathinfo), '/');

		self::$path_info = explode('/', $pathinfo);

	}




	protected static function parse_action($key){
		if(empty(self::$path_info[$key + 1])) {
			self::$action = self::default_action;
		} else {
			self::$action = self::$path_info[$key + 1];
			if(!method_exists(self::$controller.controller::controller_ext, self::$action)) {
				self::$action = self::default_action_404;
			}
			empty(self::$path_info[$key + 1]) || self::$params = array_slice(self::$path_info, $key + 2);
		}
	}




	public static function get_action(){
		return self::$action;
	}

	public static function get_controller(){
		return self::$controller;
	}



}