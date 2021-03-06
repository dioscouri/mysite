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

// set the options array
$options = array( 'site'=>'site', 'type'=>'components', 'ext'=>'com_mysite' );

// Require the base controller
Mysite::load( 'MysiteController', 'controller', $options );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Mysite::load( 'MysiteController'.$controller, "controllers.$controller", $options ))
    $controller = ''; // redirect to default?

if (empty($controller))
{
    // redirect to default
    $redirect = "index.php?option=com_mysite&view=dashboard";
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}
    
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