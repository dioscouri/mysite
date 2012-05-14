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

class MysiteViewMenus extends MysiteViewBase 
{
	/**
	 * 
	 * @param $tpl
	 * @return unknown_type
	 */
	function getLayoutVars($tpl=null) 
	{
		$layout = $this->getLayout();
		switch(strtolower($layout))
		{
			case "view":
				$this->_form($tpl);
			  break;
			case "form":
				JRequest::setVar('hidemainmenu', '1');
				$this->_form($tpl);
			  break;
			case "default":
			default:
				$this->_default($tpl);
			  break;
		}
	}
	
	function _defaultToolbar()
	{
		JToolBarHelper::custom('generateItems', "refresh", "refresh", JText::_( 'Sync Sitemap Items' ), true);
		JToolBarHelper::deleteList( JText::_( 'VALIDDELETEITEMS' ) );
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
        	$menus = MysiteHelperTools::getPlugins();        	
				// if necessary, do things to each menu
				for ($i=0; $i < count($menus); $i++) {
					$menu = &$menus[$i];
					// Check if they have an event
					if (MySiteHelperTools::hasEvent( $menu, 'onEditMenuSliders' )) {
						// add menu to filtered array
						$filtered_sliders[] = $menu;
					}
					
				}
			$menus_sliders = $filtered_sliders;
	        $this->assign( 'menus_sliders', $menus_sliders );
	        
		// Add pane
			$sliders = JPane::getInstance( 'sliders' );		
			$this->assign('sliders', $sliders);
			
			$this->assignRef('row', $row);
								
    }
}
