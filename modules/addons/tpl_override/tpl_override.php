<?php

/*-- Security Protocols --*/
if (!defined("WHMCS")) die("This file cannot be accessed directly");
/*-- Security Protocols --*/


/**
 * Configuration function called by WHMCS
 * 
 * @return An array of configuration variables
 * @since  3.0.0
 */
function tpl_override_config()
{
	$configarray = array(
		"name"			=> "Template Override",
		"version"		=> "Not Released",
		"author"		=> "<div style='text-align: center; width: 100%; '>Go Higher<br/>Information Services</div>",
		"description"	=> "This addon permits you to override your templates in WHMCS by simply putting your custom files in a separate subdirectory.",
		"language"		=> "english",
    );
    return $configarray;
}


/**
 * Activation function called by WHMCS
 * 
 * @since  3.0.0
 */
function tpl_override_activate()
{
	
}


/**
 * Deactivation function called by WHMCS
 * 
 * @since  3.0.0
 */
function tpl_override_deactivate()
{
	
}


/**
 * Upgrade function called by WHMCS
 * @param  array		Contains the variables set in the configuration function
 * 
 * @since  3.0.0
 */
function tpl_override_upgrade($vars)
{
	
}


/**
 * Output function called by WHMCS
 * @param  array		Contains the variables set in the configuration function
 * 
 * @since 3.0.0
 */
function tpl_override_output($vars)
{	
	
}


/**
 * Function to generate sidebar menu called by WHMCS
 * @param  array		Contains the variables set in the configuration function
 * 
 * @since  3.0.0
 */
function tpl_override_sidebar($vars)
{
	
}
?>