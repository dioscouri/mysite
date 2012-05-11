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

class MysiteModelItems extends MysiteModelBase 
{
	protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
       	$filter_parent      = $this->getState('filter_parent');
    	$filter_menutype    = $this->getState('filter_menutype');
    	$filter_enabled     = $this->getState('filter_enabled');
    	$filter_levellimit     = $this->getState('filter_levellimit');
    	$filter_itemid     = $this->getState('filter_itemid');
    
        if ($filter) 
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.item_id) LIKE '.$key;
            $where[] = 'LOWER(tbl.title) LIKE '.$key;
            $where[] = 'LOWER(tbl.url) LIKE '.$key;
            $where[] = 'LOWER(tbl.itemid) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
        
    	if (strlen($filter_menutype))
    	{
    		$query->where("tbl.menutype = '".$filter_menutype."'");
    	}

        if (strlen($filter_enabled))
        {
            $query->where('tbl.enabled = '.$filter_enabled);
        }
    	
    	if (strlen($filter_parent))
    	{
    		
    	 $query->where('tbl.parent = '.$filter_parent);
		}
		
    	if (strlen($filter_itemid))
        {
            $key    = $this->_db->Quote('%'.$this->_db->getEscaped( trim( strtolower( $filter_itemid ) ) ).'%');
            $where = array();
            $where[] = 'LOWER(tbl.itemid) LIKE '.$key;
            $query->where('('.implode(' OR ', $where).')');
        }
          
		    	
       if (strlen($filter_levellimit))
        {
            $query->where('tbl.sublevel <= '.$filter_levellimit);
        }
	    
	   
    }
    
    function getTable()
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'tables' );
		$table = JTable::getInstance( 'Items', 'MysiteTable' );
		return $table;
	}
	
	public function getList()
	{
		
		
		$items = parent::getList(); 	
		foreach(@$items as $item)
		{
			$item->link = 'index.php?option=com_mysite&controller=items&view=items&task=edit&id='.$item->item_id;
			$item->id = $item->item_id; // JHTML::_('menu.treerecurse') needs $item->id to be set
			$item->name = $item->title;
			if (strpos($item->url, 'Itemid') !== false || empty($item->itemid) || !JURI::isInternal($item->url) || empty($item->url) )
			{
			    // is a menulink or an external URL
			    $item->url_itemid = $item->url;
			}
			    else
			{
			    $item->url_itemid = $item->url."&Itemid=".$item->itemid;
			}
		}
		
		return $items;
	}
	
    public function getAll()
    {
        $items = parent::getAll();     
        
        foreach(@$items as $item)
        {
            $item->link = 'index.php?option=com_mysite&controller=items&view=items&task=edit&id='.$item->item_id;
            $item->id = $item->item_id; // JHTML::_('menu.treerecurse') needs $item->id to be set
            $item->name = $item->title;
            $item->url_itemid = $item->itemid ? $item->url."&Itemid=".$item->itemid : $item->url;
        }
        
        return $items;
    }
}