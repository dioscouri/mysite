<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

$thisextension = strtolower( "com_mysite" );
$thisextensionname = substr ( $thisextension, 4 );

include JPATH_SITE . '/libraries/dioscouri/component/uninstall.php';
?>
