<?php
/**
 * @version	1.5
 * @package	MySite
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

JLoader::import( 'com_mysite.views._base', JPATH_ADMINISTRATOR.DS.'components' );

class MysiteViewItems extends MysiteViewBase 
{
	
	
	function _defaultToolbar()
	{
		JToolBarHelper::custom('generateXMLSitemap', "export", "export", JText::_( 'Generate XML Sitemap' ), false);
		JToolBarHelper::divider();
        JToolBarHelper::publishList( 'enabled.enable' );
        JToolBarHelper::unpublishList( 'enabled.disable' );
		JToolBarHelper::editList();
		JToolBarHelper::deleteList( JText::_( 'VALIDDELETEITEMS' ) );
		JToolBarHelper::addnew();
	}
	
	function _default($tpl = null)
	{
		parent::_default($tpl);

		$model = $this->getModel();
		
		// establish the hierarchy of the menu
		$children = array();
		
		// get all rows from DB
		$rows = $model->getAll();

		// get children and create new list
		foreach ($rows as $v )
		{
			$parent = $v->parent;
			$v->parent_id = $v->parent;
			$list = @$children[$parent] ? $children[$parent] : array();
			array_push( $list, $v );
			$children[$parent] = $list;
		}

        // only display the items matching the search filter
        if (strlen($model->getState('filter')) || strlen($model->getState('filter_itemid'))) 
        {
            $list1 = array();

            foreach ($rows as $row)
            {
                foreach ($children as $items)
                {
                    foreach ($items as $item)
                    {
                        if ($item->item_id == $row->item_id) {
                            $list1[] = $item;
                        }
                    }
                }
            }
            // replace full list with found items
            $children = array();
            $children[] = $list1;
            
            // set the max level
            $level = strlen($model->getState('filter_levellimit')) ? $model->getState('filter_levellimit') - 1 : 9999;
            
            // indent the list
            $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, $level );
        }
	       elseif (strlen($filter_parent = $model->getState('filter_parent')))
        {
            // set the max level
            $level = strlen($model->getState('filter_levellimit')) ? $model->getState('filter_levellimit') - 1 : 9999;
            
            // indent the list
            $list = JHTML::_('menu.treerecurse', $filter_parent, '', array(), $children, $level );            
        }
            else
        {
            // set the max level
            $level = strlen($model->getState('filter_levellimit')) ? $model->getState('filter_levellimit') - 1 : 9999;
            
            // indent the list
            $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, $level );            
        }

		// recreate pagination
        $total = count( $list );
        jimport('joomla.html.pagination');
        $this->pagination = new JPagination( $total, $model->getState('limitstart'), $model->getState('limit') );
        
        // slice out elements based on the pagination limits
        $list = array_slice( $list, $this->pagination->limitstart, $this->pagination->limit );
		
        // override the items list
		$this->items = $list;
	}
	
	/**
	 * 
	 * @return void
	 **/
	function _form($tpl = null) 
	{
		parent::_form($tpl);
        jimport('joomla.html.pane');
		$model 		= $this->getModel();
        		
		// get the data
			$row = $model->getTable();
			$row->load( (int) $model->getId() );

		// lists
			$query = 'SELECT ordering AS value, menugroup_title AS text'
				. ' FROM #__fingertips_menugroups'
				. ' ORDER BY ordering';
	
			$lists['enabled'] = JHTML::_('select.booleanlist',  'enabled', 'class="inputbox"', $row->enabled );

			$access_types[] = JHTML::_('select.option', 23, 'Manager');
			$access_types[] = JHTML::_('select.option', 24, 'Administrator');
			$access_types[] = JHTML::_('select.option', 25, 'Super Administrator');

			$lists['access'] = JHTML::_('select.genericlist', $access_types, 'access', '', 'value','text', $row->access );	


			$this->assign('lists', $lists );

        // Get plugins
        	JLoader::import( 'com_mysite.library.tools', JPATH_ADMINISTRATOR.DS.'components' );
        	$filtered_sliders = array();
        	$items = MysiteHelperTools::getPlugins();        	
				// if necessary, do things to each item
				for ($i=0; $i < count($items); $i++) {
					$item = &$items[$i];
					// Check if they have an event
					if (MySiteHelperTools::hasEvent( $item, 'onEditMenuSliders' )) {
						// add item to filtered array
						$filtered_sliders[] = $item;
					}
					
				}
			$items_sliders = $filtered_sliders;
	        $this->assign( 'items_sliders', $items_sliders );
	        
		// Add pane
			$sliders = JPane::getInstance( 'sliders' );		
			$this->assign('sliders', $sliders);
			
			$this->assignRef('row', $row);
								
    }
}
