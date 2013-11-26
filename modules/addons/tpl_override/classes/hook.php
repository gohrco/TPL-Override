<?php


/*-- Security Protocols --*/
defined( 'WHMCS' ) or die( 'Restricted access' );
/*-- Security Protocols --*/

/**
 * Hook Class
 * @version		1.0.0
 * 
 * @since		1.0.0
 * @author		Steven
 */
class TplHook extends TplObject
{
	/**
	 * Indicates if we should output debug info
	 * @access		private
	 * @version		1.0.0
	 * @var			boolean
	 */
	private $debug			= false;
	
	
	/**
	 * Constructor
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @since		1.0.0
	 */
	public function __construct()
	{
		
	}
	
	
	/**
	 * Destructor method
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @since		1.0.0
	 */
	public function __destruct()
	{
		
	}
	
	
	/**
	 * Adds the hooks used by the Integrator to the WHMCS framework
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @static
	 * @since		1.0.0
	 */
	public static function addHooks()
	{
		$hooks = array(
			'ClientAreaPage'		=>	'tpl_override_clientarea',
		);
		
		// Add the hooks to the WHMCS framework
		foreach ( $hooks as $point => $fnxn ) {
			add_hook( $point, 30, $fnxn );
		}
	}
	
	
	/**
	 * **********************************************************************
	 * METHODS BELOW ARE CALLED DIRECTLY BY HOOK POINT
	 * **********************************************************************
	 */
	
	
	/**
	 * Handles all the clientarea actions to perform
	 * @access		public
	 * @version		1.0.0
	 * @param		array		- $vars: contains variables passed by WHMCS hook point
	 * 
	 * @since		1.0.0
	 */
	public function clientarea( $vars )
	{
		global $smarty;
		
		// Register an output filter to replace templates
		$smarty->register_outputfilter( array( &$this, 'output_filter' ) );
	}
	
	
	/**
	 * **********************************************************************
	 * METHODS BELOW ARE VISUAL RENDERING RELATED
	 * **********************************************************************
	 */
	
	
	/**
	 * Filters the template output from the smarty object
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $tpl_output: contains the output from the rendered smarty tpl file
	 * @param		Smarty object
	 * 
	 * @return		string containing filtered output
	 * @since		1.0.0
	 */
	public function output_filter( $tpl_output, &$smarty )
	{
		if ( preg_match( '#<html[^>]*>#im', $tpl_output ) ) {
			return $tpl_output;
		}
		
		if ( preg_match( '#</html[^>]*>#im', $tpl_output ) ) {
			return $tpl_output;
		}
		
		if ( defined( 'TPL_OVERRIDE' ) ) return $tpl_output;
		define( 'TPL_OVERRIDE', true );
		
		$current = $GLOBALS['templatefile'];
		
		// Workaround for WHMCS 5.2 and PHP 5.3
		if( empty( $current ) && ! empty( $_SESSION['uid'] ) && is_object( $GLOBALS['ca'] ) && version_compare( PHP_VERSION, '5.3', 'ge' ) ) {
			$myObject			=	$GLOBALS['ca'];
			$reflectionObject	=	new ReflectionObject($myObject);
			$property			=	$reflectionObject->getProperty( 'templatefile' );
			
			$property->setAccessible(true);
			$current			=	$property->getValue( $myObject );
		}
		
		// Just in case we aren't able to get the template file name somehow
		if (! empty( $current ) ) {
			$filename	= dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . $current . '.tpl';
			if ( @is_readable( $filename ) ) {
				$tpl_output = $smarty->fetch( 'file:' . $filename );
			}
		}

		
		return $tpl_output;
	}
	
	
	/**
	 * **********************************************************************
	 * METHODS BELOW ARE PRIVATE FOR THIS OBJECT
	 * **********************************************************************
	 */
	
	

}