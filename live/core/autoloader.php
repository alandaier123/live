<?php
/*
1、目录名称变动需更改多处。

*/
class autoloader{

	protected static $loadedfiles = array(__FILE__ => true);

	//目录名
	const lib 			= 'lib';
	const controller 	= 'controller';
	const model 		= 'model';
	const view 			= 'view';
	const config 		= 'config';

	//const core_dir 		= 'core';

	//类名规范
	//1、类名格式：lib_+[<目录名>_[<目录名>_]]+<类名>
	//2、类名必须和文件名对应
	//	如系统类示例：	lib_user_info  		对应的文件路径  LIVE_PATH.'lib/user/info.php'
	//          		lib_user_userinfo	对应的文件路径	LIVE_PATH.'lib/user/userinfo.php' 
	//  应用类示例 		user_info_lib  		对应的文件路径  LIVE_PATH.'lib/user/info.php'
	//          		user_userinfo_lib	对应的文件路径	LIVE_PATH.'lib/user/userinfo.php'

	//确定加载内容
	public static function loader($class){

		switch (strtolower($class)) {
			case 'config':
			case 'db':
			case 'debug':
			case 'router':
			case 'controller':
			case 'model':
			case 'response':
			case 'view':
			
				return self::loadfile($class.EXT, LIVE_PATH.DS.CORE_DIRNAME);
				break;
		
			default:
			
				return self::loadClassFile($class);
				break;
		}

	}

	/**
	 * 过滤异常路径
	 * @param  string $path 要过滤的路径
	 * @return string       过滤后的路径
	 */
	protected static function clean_path($path ) {
		return str_replace('..', '', $path);
	}

	public static function viewload($path, $public_view = false){
		if($public_view) {
			$file = LIVE_PATH.DS.self::view.DS.self::clean_path($path).EXT;
		} else {
			$file = APP_PATH.DS.self::view.DS.self::clean_path($path).EXT;
		}
			
		$file_path = $file;
		while (!empty(self::$loadedfiles[$file_path])) {
			$file_path = $file.'.'. ++$i;
		}
		if(file_exists($file)){
			self::$loadedfiles[$file_path] = array(memory_get_usage(),memory_get_usage(true), microtime(true));
			return $file;
		} else {
			self::$loadedfiles[$file_path] = false;
			return false;
		}
	}
	/**
	 * 加载核心配置config,可通过getPathByInfo()支持多层目录
	 * @param  string $path 文件路径
	 * @return [type]       [description]
	 */
	public static function loadConfig($config, $public_config = true){
		//var_dump($config);die;
		if($public_config) {
			return self::loadfile($config .EXT, LIVE_PATH.DS.self::config);

		} else {
			return self::loadfile($config .EXT, APP_PATH.DS.self::config);
		}
	}
	/**
	 * 加载文件
	 * @param  string  $file        文件名
	 * @param  string  $path 		路径
	 * @param  boolean $load_once   是否仅加载一次
	 * @return bool                 是否加载成功
	 */
	protected static function loadfile($file, $path = '', $load_once = true){
		//var_dump($path);die;
		if($path) {
			$file_path = realpath($path) .DS. strtolower($file);
		} else {
			$file_path = $file;
		}
		//var_dump($file_path);die;
		if(isset(self::$loadedfiles[$file_path]) && $load_once) {
			return true;
		} elseif(is_file($file_path)){

			self::$loadedfiles[$file_path] = array(memory_get_usage(),memory_get_usage(true), microtime(true));

			if($load_once) {
				return require_once $file_path;
			} else {
				return include  $file_path;
			}
			
		} else {
			
				//self::trigger_error(Error::EC_FS_FILE_NF, $file_path . ' NOT found.');
			self::$loadedfiles[$file_path] = false;
			
			return false;
		}
	}

	/**
	 * 加载类声明文件
	 * @param  string $class 类名
	 * @return bool        加载是否成功
	 */
	protected static function loadClassFile($class){
		$class_info = explode('_', $class);
		switch (strtolower($class_info[0])) {
			
			//load library
			case 'lib':
				return static::loadfile(self::getPathByInfo(LIVE_PATH.DS.self::lib, array_slice($class_info, 1)));
				break;
			default:
				switch (strtolower(end($class_info))) {
					
					case 'lib':					
						self::preloader(APP_PATH,self::lib,$class_info);
						break;
					case 'controller':	
						self::preloader(APP_PATH,self::controller,$class_info);						
						break;
					case 'model':
						self::preloader(APP_PATH,self::model,$class_info);								
						break;
					
					default:
						
						break;
				}
				break;
		}
	}

	protected static function preloader($path,$libname,$class_info){

		return self::loadfile(self::getPathByInfo($path.DS.$libname, array_slice($class_info, 0, -1)));

	}


	public static function getPathByInfo($path_prefix, $path_info, $ext = EXT, $implode_origin = '_'){
			
		foreach ($path_info as $key => $path) {
			if (!preg_match('/^[\w]+$/', $path)) {
				return false;
			}
			if(is_file($path_prefix.DS.strtolower($path).$ext)) {
				return $path_prefix.DS.strtolower($path).$ext;
			}elseif (is_dir($path_prefix.DS.strtolower($path))) {
				//兼容多层目录读取
				$path_prefix .= DS . strtolower($path);
			} else{
				// 兼容文件名 形如user_info 
				return $path_prefix.DS .strtolower(implode($implode_origin, array_slice($path_info, $key))).$ext;
			}
			
		}
	}
	
	public static function getLoadedfiles(){
		
		return self::$loadedfiles;
	}










}