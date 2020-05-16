<?php

class db{


    /*
    一个类只能建立一个连接
    $db = new db('srdb');
    $sql = 'select * from md_user';
    $user = $db->query($sql);
    */
    public static $conn = null;
    public static $dbconfig = null;
    



    public function __construct($dbconfig ){
        self::loadconfig($dbconfig);
    } 

    protected  static function loadconfig($config,$is_public = true){

        if(!isset($dbconfig)){
            self::$dbconfig = autoloader::loadConfig($config,$is_public);

            self::getconnection(self::$dbconfig);

        }
        
    }

    protected static function getconnection($config) { 
        

        if(self::$conn==null){
            
            $dsn = $config['driver'].':dbname='.$config['dbname'] . ';host=' . $config['host'] . ';port=' .$config['port']. ';charset=' .$config['charset']; 
            
            
            self::$conn = new pdo($dsn, $config['username'], $config['password']); 

            self::$conn->setattribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
            
        }
        
       
        
    } 
    

    public function query($sql, $parameters = null) { 
         
        
        $stmt = self::$conn->prepare($sql); 
        $stmt->execute($parameters); 
        $rs = $stmt->fetchall(); 
        
        $stmt = null; 
        self::$conn = null; 
        return $rs; 
    } 
    
    public  function getconfig(){
        return self::$dbconfig;
    }
    public function getconn(){
        return self::$conn;
        
    }

}