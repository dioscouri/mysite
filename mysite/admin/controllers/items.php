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

class MysiteControllerItems extends MysiteController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'items');
        $this->registerTask( 'enabled.enable', 'boolean' );
        $this->registerTask( 'enabled.disable', 'boolean' );
		

	}
		
    function _setModelState()
    {
    	$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();

    	$state['filter_parent']   = $app->getUserStateFromRequest($ns.'parent', 'filter_parent', '', '');
      	$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
      	$state['filter_menutype'] = $app->getUserStateFromRequest($ns.'menutype', 'filter_menutype', '', '');
      	$state['filter_levellimit'] = $app->getUserStateFromRequest($ns.'levellimit', 'filter_levellimit', '', '');
      	$state['filter_itemid'] = $app->getUserStateFromRequest($ns.'filter_itemid', 'filter_itemid', '', '');
      	
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
                $redirect .= '&view='.$this->get('suffix').'&task=add';
              break;
            case "apply":
                $redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$model->getId();
    		  break;
    		case "save":
    		default:
    			$redirect .= "&view=".$this->get('suffix');
    		  break;
    	}

    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
    /**
     * 
     * @return unknown_type
     */
    function moveto()
    {
        $error = false;
        $this->messagetype  = '';
        $this->message      = '';
        $redirect = 'index.php?option=com_mysite&view='.$this->get('suffix');
        $redirect = JRoute::_( $redirect, false );

        $model = $this->getModel($this->get('suffix'));

        $value = JRequest::getVar( 'moveto_target', '0', 'post', 'int' );
        $cids = JRequest::getVar('cid', array (0), 'post', 'array');
        foreach (@$cids as $cid)
        {
            unset($row);
            $row = $model->getTable();
            $row->load( $cid );
            $row->parent = $value;

            if ( !$row->save() )
            {
                $this->message .= $row->getError();
                $this->messagetype = 'notice';
                $error = true;
            }
        }

        if ($error)
        {
            $this->message = JText::_('Error') . ": " . $this->message;
        }
            else
        {
            $this->message = JText::_('Parent Changed');
        }

        $this->setRedirect( $redirect, $this->message, $this->messagetype );
        
        return;
    }

    /**
     * Generates the XML Sitemap
     * from the list of items
     */
    public function generateXMLSitemap()
    {
        header('Content-type: application/xml');
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
        ';
        $sitemap .= '<?xml-stylesheet type="text/xsl" href="administrator/components/com_mysite/views/xmlsitemap/tmpl/sitemap.xsl"?>
        ';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        ';
        
        
        // get sitemap items
        $sitemap .= $this->getSitemapData();

        $sitemap .= '</urlset>
        ';

        $url = 'index.php?option=com_mysite&view=items';
        
        $uri =& JFactory::getURI();
        $path = substr($uri->getPath(), 0, -23);
        $path = 'http://'.$_SERVER['SERVER_NAME'].$path.'sitemap.xml';
        
        $message = JText::_( 'Sitemap saved to' ). " <a href='$path' target='_blank'>$path</a>";
        $message_type = 'message';
        if (!$this->saveData($sitemap))
        {
            $message = JText::_( 'Sitemap not saved to' ). " <a href='$path' target='_blank'>$path</a>";
            $message_type = 'notice';
        }
        
        JFactory::getApplication()->redirect( $url, $message, $message_type );
    }
    
    /**
     * Gets the sitemap data from MysiteHelperItem
     * @return the sitemap data
     */
    function getSitemapData()
    {
        $model = JModel::getInstance( 'Items', 'MySiteModel' );
        $model->setState( 'filter_parent', 0);
        $model->setState( 'filter_enabled', '1' );
        $model->setState( 'order', '');
        
        $items = $model->getList();
        $sitemap = MysiteHelperItem::print_recoursiveXML($items);
                    
        return $sitemap;
    }
    
    
    /**
     * Saves the sitemap to the website root directory
     * @param $data the sitemap data
     * @return null
     */
    function saveData($sitemap)
    {
    	jimport('joomla.filesystem.file');
    	
    	if (JFile::exists(JPATH_SITE.DS."sitemap.xml"))
    	{
    	    if (!JFile::delete(JPATH_SITE.DS."sitemap.xml"))
    	    {
    	        JFactory::getApplication()->enqueueMessage( JText::_( "Could not delete existing sitemap file from disk.  Will attempt an overwrite." ), 'notice' );
    	    }
    	}
    	
        if (!JFile::write( JPATH_SITE.DS."sitemap.xml", $sitemap) )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( "Could not write sitemap file to disk" ), 'notice' );
            return false;
        }
        
        return true;
    }
}

?>