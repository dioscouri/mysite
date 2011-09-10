<?php
/**
 * @version	1.5
 * @package	Fingertips
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::import( 'com_mysite.tables._base', JPATH_ADMINISTRATOR.DS.'components' );
JLoader::import( 'com_mysite.helpers.item', JPATH_ADMINISTRATOR.DS.'components' );

class MysiteTableItems extends MysiteTable 
{
	function MysiteTableItems( &$db ) 
	{
		$tbl_key 	= 'item_id';
		$tbl_suffix = 'items';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "mysite";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
    /**
     * 
     * @param unknown_type $updateNulls
     * @return unknown_type
     */
    function store( $updateNulls=false )
    {
        $dispatcher = JDispatcher::getInstance();
        $before = $dispatcher->trigger( 'onBeforeStore'.$this->get('_suffix'), array( $this ) );
        if (in_array(false, $before, true))
        {
            return false;
        }

        if ( $return = parent::store( $updateNulls ))
        {
            $this->sublevel = MysiteHelperItem::getDepth( $this->item_id );
            parent::store( $updateNulls );
            
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger( 'onAfterStore'.$this->get('_suffix'), array( $this ) );
        }
        return $return;
    }
	
    function reorder( $where='' )
    {
        parent::reorder('parent = '.$this->_db->Quote($this->parent));
    }
    
    function move( $change, $where='' )
    {
        $where = 'parent = '.$this->_db->Quote($this->parent);
        return parent::move( $change, $where );
    }
}