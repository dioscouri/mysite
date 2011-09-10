<?php
/**
 * @version	1.5
 * @package	Mysite
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

JLoader::import( 'com_mysite.helpers._base', JPATH_ADMINISTRATOR.DS.'components' );

class MysiteHelperDiagnostics extends MysiteHelperBase 
{
    /**
     * Redirects with message
     * 
     * @param object $message [optional]    Message to display
     * @param object $type [optional]       Message type
     */
    function redirect($message = '', $type = '')
    {
        $mainframe = JFactory::getApplication();
        
        if ($message) 
        {
            $mainframe->enqueueMessage($message, $type);
        }
        
        JRequest::setVar('controller', 'dashboard');
        JRequest::setVar('view', 'dashboard');
        JRequest::setVar('task', '');
        return;
    }    
    
    /**
     * Inserts fields into a table
     * 
     * @param string $table
     * @param array $fields
     * @param array $definitions
     * @return boolean
     */
    function insertTableFields($table, $fields, $definitions)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if (!$rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` ADD `{$field}` {$definitions[$field]}; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
    /**
     * Changes fields in a table
     * 
     * @param string $table
     * @param array $fields
     * @param array $definitions
     * @param array $newnames
     * @return boolean
     */
    function changeTableFields($table, $fields, $definitions, $newnames)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if ($rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` CHANGE `{$field}` `{$newnames[$field]}` {$definitions[$field]}; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
    /**
     * Drops fields in a table
     * 
     * @param string $table
     * @param array $fields
     * @return boolean
     */
    function dropTableFields($table, $fields)
    {
        $database = JFactory::getDBO();
        $fields = (array) $fields;
        $errors = array();
        
        foreach ($fields as $field)
        {
            $query = " SHOW COLUMNS FROM {$table} LIKE '{$field}' ";
            $database->setQuery( $query );
            $rows = $database->loadObjectList();
            if ($rows && !$database->getErrorNum()) 
            {       
                $query = "ALTER TABLE `{$table}` DROP `{$field}` ; ";
                $database->setQuery( $query );
                if (!$database->query())
                {
                    $errors[] = $database->getErrorMsg();
                }
            }
        }
        
        if (!empty($errors))
        {
            $this->setError( implode('<br/>', $errors) );
            return false;
        }
        return true;
    }
    
	/**
	 * Performs basic checks on your Mysite installation to ensure it is configured OK
	 * @return unknown_type
	 */
	function checkInstallation() 
	{
	    if (!$this->checkItemsSublevel()) 
        {
            return $this->redirect( JText::_('DIAGNOSTIC CHECKITEMSSUBLEVEL FAILED') .' :: '. $this->getError(), 'error' );
        }
	}
	
    /**
     * Check if the sublevel field exists
     * @return boolean
     */
    function checkItemsSublevel() 
    {
        // if this has already been done, don't repeat
        if (Mysite::getInstance()->get('checkItemsSublevel', '0'))
        {
            return true;
        }
        
        $table = '#__mysite_items';
        $definitions = array();
        $fields = array();
        
        $fields[] = "sublevel";
            $definitions["sublevel"] = "int(11) DEFAULT '0'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'MysiteTable' );
            $config->load( array( 'config_name'=>'checkItemsSublevel') );
            $config->config_name = 'checkItemsSublevel';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;        
    }
    
    /**
     * Check if the itemid field exists
     * @return boolean
     */
    function checkItemsItemid() 
    {
        // if this has already been done, don't repeat
        if (Mysite::getInstance()->get('checkItemsItemid', '0'))
        {
            return true;
        }
        
        $table = '#__mysite_items';
        $definitions = array();
        $fields = array();
        
        $fields[] = "itemid";
            $definitions["itemid"] = "int(11) DEFAULT '0'";
            
        if ($this->insertTableFields( $table, $fields, $definitions ))
        {
            // Update config to say this has been done already
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'tables' );
            $config = JTable::getInstance( 'Config', 'MysiteTable' );
            $config->load( array( 'config_name'=>'checkItemsItemid') );
            $config->config_name = 'checkItemsItemid';
            $config->value = '1';
            $config->save();
            return true;
        }
        return false;        
    }

}