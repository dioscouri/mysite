<?php
/**
 * @package Mysite
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

// Check the registry to see if our Mysite class has been overridden
if ( !class_exists('Mysite') ) 
    JLoader::register( "Mysite", JPATH_ADMINISTRATOR.DS."components".DS."com_mysite".DS."defines.php" );

// before executing any tasks, check the integrity of the installation
Mysite::getClass( 'MysiteHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Mysite::load( 'MysiteController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Mysite::load( 'MysiteController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
	$default_controller = new MysiteController();
	$redirect = "index.php?option=com_mysite&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_mysite = {};\n";
$js.= "com_mysite.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_mysite/helpers';
DSCLoader::discover('MysiteHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_mysite/library';
DSCLoader::discover('Mysite', $parentPath, true);

// load the plugins
JPluginHelper::importPlugin( 'mysite' );

// Create the controller
$classname = 'MysiteController'.$controller;
$controller = Mysite::getClass( $classname );
    
// ensure a valid task exists
$task = JRequest::getVar('task');
if (empty($task))
{
    $task = 'display';  
}
JRequest::setVar( 'task', $task );

// Perform the requested task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
?>