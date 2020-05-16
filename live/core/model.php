<?php


class model {

    public static $counter = array();


    public function __construct($argv = null){
        self::$counter[get_class($this)] ++;
    }

    public function __destruct(){

    }
}
