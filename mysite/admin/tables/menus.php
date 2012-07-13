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


Mysite::load('MysiteTable','tables.base');
class MysiteTableMenus extends MysiteTable 
{
	function MysiteTableMenus( &$db ) 
	{
		$tbl_key 	= 'menu_id';
		$tbl_suffix = 'menus';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= "mysite";
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
    function reorder( $where='' )
    {
        $reorder = parent::reorder($where);
        
        // now, load the parent item for this menutype, and reorder it
        $table = JTable::getInstance('Items', 'MysiteTable');
        $table->load( array('menutype'=>$this->menutype, 'parent'=>0) );
        
        if (!empty($table->item_id))
        {
            // reorder
            $table->ordering = $this->ordering;
            $table->save();
        }
        
        return $reorder;
    }
    
    function move( $change, $where='' )
    {
        $move = parent::move( $change, $where );
        
        // now, load the parent item for this menutype, and move it
        $table = JTable::getInstance('Items', 'MysiteTable');
        $table->load( array('menutype'=>$this->menutype, 'parent'=>0) );
        if (!empty($table->item_id))
        {
            // move
            $table->ordering = $this->ordering;
            $table->save();
        }
        
        return $move;
    }
}