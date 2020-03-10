<?php

//框架入口所在路径
defined('LIVE_PATH') OR define('LIVE_PATH',dirname(__FILE__));

/* 应用程序根目录 这个应在外部定义 */
defined('APP_PATH')  OR define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] );

//是否脚本 
defined('IS_SCRIPT') OR define('IS_SCRIPT', false);

//调试模式，默认false,
defined('IS_DEBUG')  OR define('IS_DEBUG', false);

defined('DS')        OR define('DS', '/');
defined('EXT')       OR define('EXT', '.php');    
//脚本模式 暂无处理
if(IS_SCRIPT){
    
}




//引入自动加载类
require_once LIVE_PATH.'/core/autoloader.php';

spl_autoload_register(array('autoloader', 'loader'));

//调试模式下 
if(IS_DEBUG){
    error_reporting(E_ALL);

}



// test_v_lib::asd();
//  debug::p($_SERVER['REQUEST_URI']);
//  var_dump(autoloader::getLoadedfiles());   
//终止处理函数
register_shutdown_function(function() {
   
    if (IS_DEBUG ) {
        //var_dump(error_get_last());

    }else{
       // 跳转默认终止页面 
    }

});

//1、自动加载还差cfg、view

//3、数据库文件加载
//4、404 页面
//1、自动加载测试

// test_v_lib::asd();
// v_lib::asd();
// v_controller::asd();
// test_v_controller::asd();
// v_model::asd();
// test_v_model::asd();
// lib_activity_dfsgd::asdf();
//2、配置文件加载测试
 //config::load('qwe');
//  config::load('qwe',false);
 var_dump(config::getall());
var_dump(autoloader::getLoadedfiles());

//路由测试
router::auto();
