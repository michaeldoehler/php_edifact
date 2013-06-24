<?php
/**
 * Simple static methods for writing debug and log data
 *
 * @subpackage util
 * @author     Mark Foster <mark@myndtech.com>
 */

/**
 * Simple static methods for writing debug and log data
 *
 * @subpackage util
 * @author     Mark Foster <mark@myndtech.com>
 */
class L {
    private static $logpath;
    private static $debug = false;
    private static $stderr = false;

   /**
    * Set the path for the logfile directory
    * @static
    * @param string    path to log directory
    */
    static function setLogPath( $dir ) {
        //ini_set( "error_log", "$dir" );
        self::$logpath = "$dir".DIRECTORY_SEPARATOR."ediparse.log";
    }

   /**
    * Set this to true and output will be sent to STDERR
    *
    * @static
    * @param boolean    
    */
    static function setStderr( $bool ) {
        if ( $bool ) {
           self::$stderr = true; 
        } else {
           self::$stderr = false; 
        }
    }

   /**
    * Set this to true and debug messages will be logged/output
    *
    * @static
    * @param boolean    
    */
    static function setDebug( $bool ) {
        if ( $bool ) {
           self::$debug = true; 
        } else {
           self::$debug = false; 
        }
    }

   /**
    * output debug information (depending on whether {@link setDebug()} is set
    *
    * @static
    * @param string    message
    */
    static function debug( $msg ) {
    	echo "L::debug - " . $msg . PHP_EOL;
        if ( ! self::$debug ) {
            return;
        }
        self::log( ">> $msg");
    }

   /**
    * log a message
    *
    * By default this will simply call error_log. 
    * If {@link setStderr()} has been set, then the message will be output to STDERR
    * instead
    * If {@link setLogPath()} has been set, then this path will be honored.
    * @static
    * @param string    message
    */
    static function log( $msg ) {
        if ( self::$stderr ) {
            file_put_contents("php://stderr", "++ $msg\n" );
            return;
        }
 
        $stamped = "[".strftime("%a %b %e %H:%M:%S %Y %Z")."] $msg";
        if ( self::$logpath ) {
            error_log( $stamped, 3, self::$logpath );
        } else {
            error_log( $stamped );
        }
   }
}

?>
