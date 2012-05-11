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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class MysiteControllerMenus extends MysiteController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'menus');
        $this->registerTask( 'enabled.enable', 'boolean' );
        $this->registerTask( 'enabled.disable', 'boolean' );

	}
		
    function _setModelState()
    {
    	   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();
        $state = parent::_setModelState();
        
      	$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
      	$state['filter_levellimit'] = $app->getUserStateFromRequest($ns.'levellimit', 'filter_levellimit', '', '');
     
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
  		return $state;
    }

	/**
	 * save a record
	 * @return void
	 */
	function save() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
		
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		$row->bind( $_POST );
		
		if ( $row->save() ) 
		{
			$model->setId( $row->menu_id );
			$this->messagetype 	= 'message';
			$this->message  	= JText::_( 'Saved' );
			
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );

			
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_( 'Save Failed' )." - ".$row->getError();
		}
		
    	$redirect = "index.php?option=com_mysite";
    	$task = JRequest::getVar('task');
    	switch ($task)
    	{
    		case "savenew":
    			$redirect .= '&view='.$this->get('suffix').'&layout=form';
    		  break;
    		case "apply":
    			$redirect .= '&view='.$this->get('suffix').'&layout=form&id='.$model->getId();
    		  break;
    		case "save":
    		default:
    			$redirect .= "&view=".$this->get('suffix');
    		  break;
    	}

    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	function boolean()
	{
	    $do = parent::boolean();
	    
	    $cids = JRequest::getVar('cid', array (0), 'post', 'array');
        $task = JRequest::getVar( 'task' );
        $vals = explode('.', $task);

        $field = $vals['0'];
        $action = $vals['1'];

        switch (strtolower($action))
        {
            case "switch":
                $switch = '1';
              break;
            case "disable":
                $enable = '0';
                $switch = '0';
              break;
            case "enable":
                $enable = '1';
                $switch = '0';
              break;
            default:
                $this->messagetype  = 'notice';
                $this->message      = JText::_( "Invalid Task" );
                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                return;
              break;
        }

        $db =& JFactory::getDBO();

	    $config = Mysite::getInstance();
        $limit = $config->get('tree_depth', '0');
        if (!empty($limit))
        {
    		if(version_compare(JVERSION,'1.6.0','ge')) {
                // Joomla! 1.6+ code here
                $limit_query = "AND menu.level <= '$limit'";
            } else {
                // Joomla! 1.5 code here
                $limit_query = "AND menu.sublevel <= '$limit'";
            }
        }

        // parent was changed to parent_id in 1.6
        // name was changed to title in 1.6
    	if(version_compare(JVERSION,'1.6.0','ge')) {
            // Joomla! 1.6+ code here
            $parent_key = 'parent_id';
            $title_key = 'title';
        } else {
            // Joomla! 1.5 code here
             $parent_key = 'parent_id';
            $title_key = 'title';
        }
        
        // if a menu is enabled after being disabled, first regenerate items for it
        foreach (@$cids as $cid)
        {
			$db->setQuery( "SELECT * FROM #__mysite_menus WHERE `menu_id` = '$cid'" );
			$thismenu = $db->loadObject();
			
            if (($thismenu->enabled == 1) && $enable)
            {
				$db->setQuery( "SELECT * FROM #__mysite_menus WHERE `menu_id` = '$cid'" );
				$thismenu = $db->loadObject();
				$menutype = $thismenu->menutype;
				
				//delete all the items created from a this menu
				$db->setQuery( "SELECT item_id FROM #__mysite_items WHERE menutype = '$menutype'" );

				$items = $db->loadObjectList();
				$countItems = count($items);
				
				if ($countItems)
				{
					//update the menu
					$db->setQuery( "
					    SELECT menu.* 
		                FROM #__menu AS menu 
		                LEFT JOIN #__mysite_menus AS menutypes ON menu.menutype = menutypes.menutype 
		                WHERE menutypes.enabled = '1' 
		                AND menu.menutype = '$menutype' 
					    AND menu.published >= '0'
					    $limit_query 
		                ORDER BY menu.$parent_key ASC, menu.ordering DESC 
		                " );

			    	$menus = $db->loadObjectList();

					foreach ($menus as $menu) 
					{
						$db->setQuery( "UPDATE #__mysite_items SET url='".$menu->link."', enabled='".$menu->published."' WHERE itemid=".$menu->id );
						$db->query();						
					}		
					
					$updated++;
				}
				else
				{
					//rebuild the menu
					
					// first create the parent sitemap item for the menu
					unset($parent);
				    $parent = JTable::getInstance( 'Items', 'MysiteTable' );
		            $parent->menutype = $thismenu->menutype;
		            $parent->title = $thismenu->title;
		            $parent->url = "";
		            $parent->itemid = "";
		            $parent->parent = 0;
		            $parent->save();

					$db->setQuery( "
					    SELECT menu.* 
		                FROM #__menu AS menu 
		                LEFT JOIN #__mysite_menus AS menutypes ON menu.menutype = menutypes.menutype 
		                WHERE menutypes.enabled = '1' 
		                AND menu.menutype = '$menutype'
		                AND menu.published >= '0' 
					    $limit_query 
		                ORDER BY menu.$parent_key ASC, menu.ordering DESC 
		                " );

			    	$menus = $db->loadObjectList();

					$parentMap = array();
			        $parentMap[$parent->item_id] = array();

			        $fix = array();
					foreach ($menus as $menu) 
					{
					    $parent_id = (!empty($menu->$parent_key)) ? $parentMap[$parent->item_id][$menu->$parent_key] : $parent->item_id;
					    if (empty($parentMap[$parent->item_id][$menu->$parent_key]))
					    {
					        // the mysite item for the parent hasn't been created yet, 
					        // so we need to fix this one after all others are finished
					        $fix[$menu->id] = $menu->$parent_key;
					    }
					    
					    
						$table = JTable::getInstance( 'Items', 'MysiteTable' );
						$table->menutype    = $menu->menutype;
						$table->title       = $menu->$title_key;
						$table->url         = $menu->link;
						$table->itemid      = $menu->id;
						$table->enabled     = $menu->published;
						$table->parent      = $parent_id;
						$table->save();	

						$parentMap[$parent->item_id][$menu->id] = $table->id;
					}
                    
					// select all mysite_items where parent = 0
					// foreach one, set the parent if it exists
					// to correct for ones created before their parent existed
					$model = JModel::getInstance('Items', 'MysiteModel');
					$model->setState('filter_parent', '0');
					if ($list = $model->getList())
					{
					    foreach ($list as $item)
					    {
					        $parent_itemid = $fix[$item->itemid];
					        if (!empty($parentMap[$parent->item_id][$parent_itemid]))
					        {
					            $table = JTable::getInstance( 'Items', 'MysiteTable' );
					            $table->load( array('itemid'=>$item->itemid) );
					            $table->$parent_key = $parentMap[$parent->item_id][$parent_itemid];
					            $table->store();
					        }
					    } 
					}
					
					JPluginHelper::importPlugin('mysite');
					$dispatcher =& JDispatcher::getInstance();
					$data = array($menutype);
					$dispatcher->trigger('onGenerateItems', $data);
				}				
            }
        }
        
        // if a menu is disabled after being enabled, disable all of its menu items in the sitemap
        foreach (@$cids as $cid)
        {
            $db->setQuery( "SELECT menutype FROM #__mysite_menus WHERE menu_id = $cid" );
            if ($menutype = $db->loadResult())
            {
                $db->setQuery( "UPDATE #__mysite_items SET enabled = '{$enable}' WHERE menutype = '$menutype'" );
                $db->query();
            }
        }
	}
	
    /**
     * Deletes record(s) and redirects to default layout
     */
    function delete()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
        if (!isset($this->redirect)) {
            $this->redirect = JRequest::getVar( 'return' )
                ? base64_decode( JRequest::getVar( 'return' ) )
                : 'index.php?option=com_mysite&view='.$this->get('suffix');
            $this->redirect = JRoute::_( $this->redirect, false );
        }

        $model = $this->getModel($this->get('suffix'));
        $row = $model->getTable();

        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        foreach (@$cids as $cid)
        {
            $row->load($cid);
            
            if (!$row->delete($cid))
            {
                $this->message .= $row->getError();
                $this->messagetype = 'notice';
                $error = true;
            }
                else
            {
                $db =& JFactory::getDBO();
                if ($menutype = $row->menutype)
                {
                    $db->setQuery( "DELETE FROM #__mysite_items WHERE `menutype` = '$menutype'" );
                    if (!$db->query())
                    {
                        $error = true;
                        $this->message .= $db->getErrorMsg();
                        $this->messagetype = 'notice';
                    }
                }
            }
        }

        if ($error)
        {
            $this->message = JText::_('Error') . " - " . $this->message;
        }
            else
        {
            $this->message = JText::_('Items Deleted');
        }

        $this->setRedirect( $this->redirect, $this->message, $this->messagetype );
    }
    
    /**
     * Generates the sitemap items for selected menus
     * 
     * @return unknown_type
     */
    function generateItems()
    {
        JLoader::import( 'com_mysite.tables.items', JPATH_ADMINISTRATOR.DS.'components' );
        $db =& JFactory::getDBO();
        
        $updated = 0;
        
        $cids = JRequest::getVar('cid', array (0), 'request', 'array');
        
        $config = Mysite::getInstance();
        $limit = $config->get('tree_depth', '0');
        if (!empty($limit))
        {
        	
			if(version_compare(JVERSION,'1.6.0','ge')) {
                // Joomla! 1.6+ code here
                $limit_query = "AND menu.level <= '$limit'";
            } else {
                // Joomla! 1.5 code here
                 $limit_query = "AND menu.sublevel <= '$limit'";
            }
           
        }
        
        foreach (@$cids as $cid)
        {
            if ($cid!=0)
            {
                $db->setQuery( "SELECT * FROM #__mysite_menus WHERE `menu_id` = '$cid'" );
                $thismenu = $db->loadObject();
                $menutype = $thismenu->menutype;
                
                // collect all the itemids created from this menu
                $items_array = array();
                $db->setQuery( "SELECT itemid FROM #__mysite_items WHERE menutype = '$menutype'" );
                if ($items = $db->loadObjectList())
                {
                    foreach ($items as $item)
                    {
                        $items_array[] = $item->itemid;
                    }
                }
                
                // if any items exist for this menutype, do intelligent sync
                if (!empty($items_array))
                {
                    //jimport('joomla.utilities.arrayhelper');
                    //$items_array = JArrayHelper::fromObject($items);
                    
                    // get all the menu items for this menu type
                    $db->setQuery( "
                        SELECT menu.* 
                        FROM #__menu AS menu 
                        LEFT JOIN #__mysite_menus AS menutypes ON menu.menutype = menutypes.menutype 
                        WHERE menutypes.enabled = '1' 
                        AND menu.menutype = '$menutype'
                        AND menu.published >= '0'
                        $limit_query
                        ORDER BY menu.parent ASC, menu.ordering ASC 
                        " );
                    $menus = $db->loadObjectList();
                    
                    //update the menu
                    $did_update = false;
                    $do_reorder = false;
                    foreach ($menus as $menu) 
                    {
                        if (in_array($menu->id, $items_array))
                        {
                            // this menu item has already been synced, so just update some of the key parts
                            
                            // TODO Add config options for:
                               // do update at all?
                               // update title?
                               // update published state?
                               // update menutype? 
                               
                            $db->setQuery( 
                              "UPDATE #__mysite_items 
                              SET title = '".$menu->name."' ,  
                              url = '".$menu->link."' 
                              WHERE itemid = '".$menu->id."'" 
                            );
                            
                            if ($db->query())
                            {
                                // TODO Track somethin?
                                $did_update = true;
                            }
                        }
                           else
                        {
                            // create a new record
                            $table = JTable::getInstance( 'Items', 'MysiteTable' );
                            $parent_table = JTable::getInstance( 'Items', 'MysiteTable' );
                            $parent_table->load( array('itemid'=>$menu->parent) );
                            
                            $table->menutype    = $menu->menutype;
                            $table->title       = $menu->name;
                            $table->url         = $menu->link;
                            $table->itemid      = $menu->id;
                            $table->enabled     = $menu->published;
                            $table->parent      = (!empty($parent_table->item_id)) ? $parent_table->item_id : 0;
                            if ($table->store())
                            {
                                // TODO Track something?
                                $did_update = true;
                                $do_reorder = true;
                            }
                        }
                    }
                    
                    if ($did_update)
                    {
                        if ($do_reorder)
                        {
                            $table = JTable::getInstance( 'Items', 'MysiteTable' );
                            $table->reorder();                          
                        }
                        $updated++;                     
                    }

                }
                    else
                {
                    // no Mysite items exist for this menutype, so
                    // rebuild the menu
                    
                    // first create the parent sitemap item for the menu
                    unset($parent);
                    $parent = JTable::getInstance( 'Items', 'MysiteTable' );
                    $parent->menutype = $thismenu->menutype;
                    $parent->title = $thismenu->title;
                    $parent->url = "";
                    $parent->itemid = "";
                    $parent->parent = 0;
                    $parent->store();

                    $db->setQuery( "
                        SELECT menu.* 
                        FROM #__menu AS menu 
                        LEFT JOIN #__mysite_menus AS menutypes ON menu.menutype = menutypes.menutype 
                        WHERE menutypes.enabled = '1' 
                        AND menu.menutype = '$menutype'
                        AND menu.published >= '0' 
                        $limit_query 
                        ORDER BY menu.parent ASC, menu.ordering ASC 
                        " );

                    $menus = $db->loadObjectList();

                    $parentMap = array();
                    $parentMap[$parent->item_id] = array();

                    foreach ($menus as $menu) 
                    {
                        $table = JTable::getInstance( 'Items', 'MysiteTable' );
                        $table->menutype    = $menu->menutype;
                        $table->title       = $menu->name;
                        $table->url         = $menu->link;
                        $table->itemid      = $menu->id;
                        $table->enabled     = $menu->published;
                        $table->parent      = ($menu->parent ? $parentMap[$parent->item_id][$menu->parent] : $parent->item_id);
                        $table->store();    

                        $parentMap[$parent->item_id][$menu->id] = $table->id;
                    }
                    $table->reorder();

                    JPluginHelper::importPlugin('mysite');
                    $dispatcher =& JDispatcher::getInstance();
                    $data = array($menutype);
                    $dispatcher->trigger('onGenerateItems', $data);

                    $updated++;
                }               
            }
        }
        
        $message = '';
        $type = '';
        
        if ($updated)
        {
            if ($updated == 1)
            {
                $message = JText::_('Menu updated');            
            }
                else
            {
                $message = JText::_('Menus updated');
            }
            $type = 'message';
        }
            else
        {
            $message = JText::_('No Sync Necessary');
            $type = 'notice';
            
        }
        JFactory::getApplication()->redirect( 'index.php?option=com_mysite&view=menus', $message, $type );
    }
}

?>