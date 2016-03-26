<?php
namespace src\traits;

trait Singleton
{
    // Hold an instance of the class
    private static $instances = array();
   
    // A private constructor; prevents direct creation of object
    final private function __construct()
    {
    }

    // Prevent users to clone the instance
    final public function __clone()
    {
        trigger_error( 'Clone is not allowed.', E_USER_ERROR );
    }
   
    // The singleton method
    final public static function inst()
    {
        $c = get_called_class();
       
        if( ! isset( self::$instances[$c] ) )
        {
            self::$instances[$c] = new $c;
        }
       
        return self::$instances[$c];
    }
}
?>