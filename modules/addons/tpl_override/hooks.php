<?php


/*-- Security Protocols --*/
if (!defined("WHMCS")) die("This file cannot be accessed directly");
/*-- Security Protocols --*/

/*-- File Inclusions --*/
require_once( "factory.php" );
/*-- File Inclusions --*/

global $TPL_hook;

$TPL_hook	=	TplFactory::getHook();
TplHook::addHooks();


/**
 * Handles client area rendering
 * @version		1.0.0
 * @param		array		- $vars: contains data passed by WHMCS hook point
 * 
 * @since		1.0.0
 */
function tpl_override_clientarea( $vars )
{
	global $TPL_hook;
	$TPL_hook->clientarea( $vars );
}

?>