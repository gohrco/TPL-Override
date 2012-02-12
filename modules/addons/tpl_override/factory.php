<?php

/*-- Security Protocols --*/
if (!defined("WHMCS")) die("This file cannot be accessed directly");
/*-- Security Protocols --*/

/*-- File Inclusions --*/
require_once( "defines.php" );
require_once( "core/object.php" );
require_once( "core/uri.php" );
/*-- File Inclusions --*/

/**
 * Factory Class
 * @version		1.0.0
 * 
 * @since		1.0.0
 * @author		Steven
 */
class TplFactory
{
	/**
	 * Retrieves a singular instance of the database object
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		instance of IntDatabase object
	 * @since		1.0.0
	 */
	public function &getDbo()
	{
		static $instance;
		
		if (! is_object( $instance ) ) {
			require_once( "core/database.php" );
			$instance = new TplDatabase();
		}
		
		return $instance;
	}
	
	
	/**
	 * Retrieves a singular instance of the hook class object
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		instance of IntHook object
	 * @since		1.0.0
	 */
	public function &getHook()
	{
		static $instance;
		
		if (! is_object( $instance ) ) {
			require_once( "classes/hook.php" );
			$instance	= new TplHook();
		}
		
		return $instance;
	}
}