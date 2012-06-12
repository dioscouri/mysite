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

JLoader::import( 'com_mysite.models._base', JPATH_ADMINISTRATOR.DS.'components' );

class MysiteModelMenus extends MysiteModelBase 
{
	protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
    	$filter_enabled    = $this->getState('filter_enabled');

        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.menu_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.title) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
    	if (strlen($filter_enabled))
    	{
    		$query->where('tbl.enabled = '.$filter_enabled);
    	}
    }
	
	public function getList($refresh = false)
	{
		$db = JFactory::getDBO();
    	$db->setQuery( 'SELECT * FROM #__menu_types ORDER BY id' );
    	$menutypes = $db->loadObjectList();
		
		foreach ($menutypes as $menutype) 
		{
			$table = JTable::getInstance( 'Menus', 'MysiteTable' );
			$table->load( array( 'menutype'=>$menutype->menutype ) );
			if ( empty($table->menu_id) ) 
			{
				$table->menutype = $menutype->menutype;
				$table->title = $menutype->title;
				$table->description = $menutype->description;
				$table->enabled = 0;
				$table->save();	
			}
		}

		$list = parent::getList();
		
		if (empty($list))
		{
		    return array();
		}
		
		foreach ($list as $item)
		{
		    $db->setQuery( "SELECT COUNT(item_id) FROM #__mysite_items WHERE menutype = '$item->menutype'" );
		    $item->count = $db->loadResult();    
		}
		
		return $list;
	}

}