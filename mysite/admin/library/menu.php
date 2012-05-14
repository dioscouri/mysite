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

jimport('joomla.html.toolbar');
jimport( 'joomla.utilities.simplexml' );
require_once( JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php' );

class MysiteMenu extends JObject
{
    private $_name = array();
    private $_menu;
    
    function __construct($name = 'submenu')
    {
        $this->_name = $name;
		
        $this->_menu =& JToolBar::getInstance( $name );
        
        // Try to load initial values for the menu with a config file
        $initialpath = 'media' . DS . 'com_mysite' . DS . 'menus';
		
        $admin = JFactory::getApplication()->isAdmin();
	    if ($admin) {
	        $path = '..' . DS . $initialpath . DS . 'admin';
	    } else {
	        $path = $initialpath . DS . 'site';
	    }
		    
	    $xmlfile = $path . DS . "$name.xml";

	    // Does the file exist?
        if (file_exists($xmlfile)) 
        {
            $xml = new JSimpleXML;
            
            // Parse the file
            if ($xml->loadFile($xmlfile)) 
            {
                $items = array();
                foreach ($xml->document->children() as $child) 
                {
                    $name = $url = NULL;
                    
                    // $child will be a single link with name and url sub elements
                    foreach ($child->children() as $element) 
                    {
                        switch ($element->_name) {
                            case 'name':
                                $name = JText::_($element->_data);
                                break;
                            case 'url':
                                $url = $element->_data;
                                break;
                        }
                    }
                    
                    // If we have both a URL and name, add a new link
                    if (!empty($name) && !empty($url)) 
                    {
                        $object = new JObject();
                        $object->name = $name;
                        $object->url = ($admin) ? $url : JRoute::_($url);
                        $object->url_raw = $url;
                        $object->active = false;
                        $items[] = $object; 
                    }
                }
                
                // find an exact URL match
                $uri = JURI::getInstance();
                $uri_string = "index.php" . $uri->toString(array('query'));
                $exact_match = false;
                foreach ($items as $item)
                {
                    if ($item->url_raw == $uri_string)
                    {
                        $exact_match = $item->url_raw;
                    }
                }

                // if no exact match, then match on view
                foreach ($items as $item)
                {
                    parse_str($item->url_raw, $urlvars);
                    $active = (strtolower( JRequest::getVar('view') ) == strtolower($urlvars['view']));
                    if ($exact_match == $item->url_raw || (empty($exact_match) && $active))
                    {
                        $item->active = true;
                    }
                    $this->_menu->appendButton($item->name, $item->url, $item->active);
                }
            }
        }
    }
    
    /**
     * 
     * @param string $name
     * @return mixed
     * 
     * Returns a reference to a MysiteMenu object or false if submenus have been disabled by an admin
     */
    function & getInstance($name = 'submenu')
    {
        // Check the config to see if the admin has disabled submenus
		if (!Mysite::getInstance()->get('display_submenu', '1')) {
		    return false;
		}
		
        static $instances;
        
        if (!isset($instances)) {
            $instances = array();
        }
        
        if (empty ($instances[$name])) {
            $instances[$name] = new MysiteMenu($name);
        }
        
        return $instances[$name];
    }
    
	/**
	 * 
	 * @param $name
	 * @param $link
	 * @param $active
	 * @return unknown_type
	 */
	function addEntry($title, $link = '', $active=false)
	{
		$this->_menu->appendButton($title, $link, $active);
	}
	
	/**
	 * Displays the menu according to view.
	 * 
	 * @return unknown_type
	 */
	function display($layout='submenu', $hidemainmenu='')
	{
	    jimport( 'joomla.application.component.view' );
	    
	    // TODO This should be passed as an argument
		$hide = JRequest::getInt('hidemainmenu');
        		
		// Load the named template, if there are links to display.				
		if (!empty($this->_menu->_bar)) 
		{
		    $view = new JView(array('name'=>'dashboard'));
		    $view->set('items', $this->_menu->_bar);
		    $view->set('name', $this->_name);
		    $view->set('hide', $hide);
    		$view->setLayout($layout);
    		$view->display();		    
		}
	}
	
	public static function treerecurse($id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1, $pre=null, $spacer=null)
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;

				if ($type==1) {
					if (is_null($pre)) { $pre	= '<sup>|_</sup>&#160;'; }
					if (is_null($spacer)) { $spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;'; }
				} else {
					if (is_null($pre)) { $pre	= '- '; }
					if (is_null($spacer)) { $spacer = '&#160;&#160;'; }
				}

				if ($v->parent_id == 0) {
					$txt	= $v->title;
				} else {
					$txt	= $pre . $v->title;
				}
				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count(@$children[$id]);
				$list = MysiteMenu::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type, $pre, $spacer);
			}
		}
		return $list;
	}
}