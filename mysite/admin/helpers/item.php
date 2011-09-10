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
JLoader::import( 'com_mysite.models._base', JPATH_ADMINISTRATOR.DS.'components' );
jimport('joomla.utilities.date');

class MysiteHelperItem extends MysiteHelperBase
{
    function getDepth( $id )
    {
        $level = 0;
        
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'tables' );
        $item = JTable::getInstance( 'Items', 'MysiteTable' );
        $item->load($id);
        
        // while parent != 0
        while ($item->parent != 0)
        {
            $level ++;
            // load the parent if the item's parent != 0
            $item->load( $item->parent );            
        }
        
        return $level;         
    }
    
    /**
     * Builds an HTML recursive unordered list of items
     *
     * @param array items
     * @return string the HTML unordered list
     */
    public static function print_recoursive($items) {

       	$output = '<ul>';

		foreach ($items as $item) 
		{
			$output .= '<li><a href="'.JRoute::_($item->url_itemid).'">'.$item->title.'</a>';

			$model = JModel::getInstance( 'Items', 'MySiteModel' );
			$model->setState( 'filter_parent', $item->item_id);
			$model->setState( 'filter_enabled', '1' );
            $model->setState( 'order', 'tbl.ordering' );
            $model->setState( 'direction', 'ASC' );
            
			$subitems = $model->getList();

			if (count($subitems))
			{
			    $output .= self::print_recoursive($subitems);
			}

    	    $output .= '</li>';
		}

    	$output .= '</ul>';

		return $output;
	}

    public static function print_recoursiveXML($items) 
    {
    	$output = '';

    	$db =& JFactory::getDBO();
    	$query = "SELECT value FROM #__mysite_config WHERE config_name='priority'";
		$db->setQuery($query);
		$default_priority = $db->loadResult();

    	$query = "SELECT value FROM #__mysite_config WHERE config_name='change_frequency'";
    	$db->setQuery($query);
		$default_change_frequency = $db->loadResult();

		$app    = JApplication::getInstance('site');
        $router = &$app->getRouter();
		foreach ($items as $item) 
		{
			// Is the URL internal or external?
			//$external = preg_match('/^http:\/\//', JRoute::_($item->url_itemid));
			$internal = JURI::isInternal($item->url_itemid);

			if ($internal && !empty($item->url_itemid))
			{
				$output .= '<url>';
				
				//$uri =& JFactory::getURI();
				//$path = substr($uri->_path, 0, -23);
				//$output .= '<loc>'.JFilterOutput::ampReplace( JRoute::_( 'http://'.$_SERVER['SERVER_NAME'].$path.$item->url_itemid )).'</loc>';

                $uri = $router->build( JURI::root() . $item->url_itemid );
                $url = $uri->toString();
				$url = str_replace( '/administrator/', '/', $url);
				
				$output .= '<loc>'.JFilterOutput::ampReplace( $url ).'</loc>';

    			//set modified date
    			$dateModified = '';
    			if ($item->last_modified != '0000-00-00 00:00:00')
    			{
    				$dateModified = $item->last_modified;
    			}
    
    			//set change frequency
    			$changeFrequency = '';
    			if ($item->change_frequency != '')
    			{
    				$changeFrequency = $item->change_frequency;
    			}
    			else
    			{
    				$changeFrequency = $default_change_frequency;
    			}
    
    			//set priority
    			$priority = '';
    			if ($item->priority != '')
    			{
    				$priority = $item->priority;
    			}
    			else
    			{
    				$priority = $default_priority;
    			}
    
    			if ($dateModified)
    			{
    				$output .= '<lastmod>'.$dateModified.'</lastmod>
    				';
    			}
    			if ($changeFrequency)
    			{
    				$output .= '<changefreq>'.$changeFrequency.'</changefreq>
    				';
    			}
    			if ($priority)
    			{
    				$output .= '<priority>'.$priority.'</priority>
    				';
    			}
    
    			$output .= '</url>
    			';
			}
			$model = JModel::getInstance( 'Items', 'MySiteModel' );
			$model->setState( 'filter_parent', $item->item_id);
			$model->setState( 'filter_enabled', '1' );
            $model->setState( 'order', 'tbl.ordering' );
            $model->setState( 'direction', 'ASC' );
			
			$subitems = $model->getList();

			if (count($subitems)) $output .= self::print_recoursiveXML($subitems);
		}


		return $output;
	}

	private static function getComContentArticleModifiedDate($id) 
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT modified FROM #__content WHERE id='.$id;
		$db->setQuery( $query );
		return $db->loadResult();
	}

}