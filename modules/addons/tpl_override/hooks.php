<?php


/*-- Security Protocols --*/
if (!defined("WHMCS")) die("This file cannot be accessed directly");
/*-- Security Protocols --*/



add_hook( 'ClientAreaPage', 1, function( $vars )
{
	global $smarty;
	$version	=	$GLOBALS['CONFIG']['Version'];
	
	// Register an output filter to replace templates
	if ( version_compare( $version, '5.3', 'ge' ) ) {
		$smarty->register_prefilter( 'tpl_override_output_filter' );
	}
	else {
		$smarty->register_outputfilter( 'tpl_override_output_filter' );
	}
	
});


function tpl_override_output_filter( $tpl_output, &$smarty ) {
	// Grab our original resource first
	$templatefile	=	$smarty->template_resource;
	
	// Find the filename of it
	$tparts			=	explode( '/', $templatefile );
	$templatefile	=	array_pop( $tparts );
	
	// Check for override directory
	$tparts[]		=	'custom';
	array_unshift( $tparts, 'templates' );
	$newpath		=	ROOTDIR . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $tparts );
	
	// If no override directory exists send back
	if (! is_dir( $newpath ) ) return $tpl_output;
	
	// Check for a custom file in place
	$newfilepath	=	$newpath . DIRECTORY_SEPARATOR . $templatefile;
	if (! file_exists( $newfilepath ) ) return $tpl_output;
	
	// Final checks
	if (! @is_readable( $newfilepath ) ) return $tpl_output;
	
	// Grab and go
	$tpl_output = $smarty->fetch( 'file:' . $newfilepath );
	
	return $tpl_output;
}

?>