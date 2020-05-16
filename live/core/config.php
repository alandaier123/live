<?php
//配置文件加载

class config{

	public static $config_files = array();

	//默认加载核心目录配置，key键标识
	//const  path = 'core:';


	public static function getall(){
		return self::$config_files;
	}

	public static function load($config,$is_public = true){
		$filename = explode('.', $config);
		
		$file_prefix = $is_public? CORE_DIRNAME:'';

		if(empty(static::$config_files[$file_prefix.$filename[0]])) {
			// autoloader::loadConfig()加载失败返回 false
			static::$config_files[$file_prefix.$filename[0]] = autoloader::loadConfig($filename[0], $is_public);
			
		}
		return static::$config_files[$file_prefix.$filename[0]]; 
	}

	
}