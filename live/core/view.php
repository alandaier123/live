<?php

class view{


    protected static $last_load = null;


    protected static $views = array();

	    /**
     * 生成View内容并返回  
     * @param  $view_file   View路径名
     * @param  $data 参数 
     * @param  $public_view 是否公共view 默认否
     * @return string          返回View最终内容
     */
    public static function make($view_file, $data = array(),  $public_view = false) {
        self::$last_load = $view_file;
        
        $view_path = autoloader::viewload($view_file, $public_view);
        //var_dump($view_path);die;
        if ($view_path) {
            self::$views[$view_path] = file_get_contents($view_path);
        } else {
            return '';
        }
            
        
        if (is_array($data)) {
            extract($data);
        } elseif (is_object($data)) {
            extract(get_object_vars($data));
        }
        ob_start();
        
        
         include $view_path;
       
        $content = ob_get_clean();
       
       
        return $content;
    }

}