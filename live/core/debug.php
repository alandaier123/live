<?php

class debug{


	//调试输出  
	public static function p() { 

        $args = func_get_args();
        if( count( $args )<1){
          echo ( "<font color='red'> Debug" );
          return;
        }
        
        echo '<div style="width:100%;text-align:left"><pre>';

        foreach( $args as $arg){

                if( is_array( $arg)){  
                    print_r( $arg );
                    echo '<br>';
                }else if( is_string( $arg)){
                    echo $arg.'<br>';
                }else{ 
                    var_dump($arg);
                    echo '<br>';
                }
        }

        echo '</pre></div>';
	} 

}
