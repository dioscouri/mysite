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

// Require the base controller
Mysite::load( 'MysiteController', 'controller' );

// Require specific controller if requested
$controller = JRequest::getWord('controller', JRequest::getVar( 'view' ) );
if (!Mysite::load( 'MysiteController'.$controller, "controllers.$controller" ))
    $controller = '';

if (empty($controller))
{
    // redirect to default
    $redirect = "index.php?option=com_mysite&view=items";
    $redirect = JRoute::_( $redirect, false );
    JFactory::getApplication()->redirect( $redirect );
}
    
// load the plugins
JPluginHelper::importPlugin( 'mysite' );

// Create the controller
$classname = 'MysiteController'.$controller;
$controller = Mysite::getClass( $classname );
    
// Perform the requested task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
?>