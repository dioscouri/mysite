<?php
/**
* @package		Mysite
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Mysite extends JObject
{
    static $_version 		= '0.6.0';
    static $_copyrightyear 	= '2010';
    static $_name 			= 'mysite';
    static $_min_php        = '5.2';
    
    /**
     * Get the version
     */
    public static function getVersion()
    {
        return self::$_version;
    }

    /**
     * Get the copyright year
     */
    public static function getCopyrightYear()
    {
        return self::$_copyrightyear;
    }

    /**
     * Get the Name
     */
    public static function getName()
    {
        return self::$_name;
    }

	/**
     * Get the URL to the folder containing all media assets
     *
     * @param string	$type	The type of URL to return, default 'media'
     * @return 	string	URL
     */
    public static function getURL($type = 'media')
    {
    	$url = '';

    	switch($type)
    	{
    		case 'media' :
    			$url = JURI::root(true).'/media/com_mysite/';
    			break;
    		case 'css' :
    			$url = JURI::root(true).'/media/com_mysite/css/';
    			break;
    		case 'images' :
    			$url = JURI::root(true).'/media/com_mysite/images/';
    			break;
    		case 'js' :
    			$url = JURI::root(true).'/media/com_mysite/js/';
    			break;
    	}

    	return $url;
    }

	/**
     * Get the path to the folder containing all media assets
     *
     * @param 	string	$type	The type of path to return, default 'media'
     * @return 	string	Path
     */
    public static function getPath($type = 'media')
    {
    	$path = '';

    	switch($type)
    	{
    		case 'media' :
    			$path = JPATH_SITE.DS.'media'.DS.'com_mysite';
    			break;
    		case 'css' :
    			$path = JPATH_SITE.DS.'media'.DS.'com_mysite'.DS.'css';
    			break;
    		case 'images' :
    			$path = JPATH_SITE.DS.'media'.DS.'com_mysite'.DS.'images';
    			break;
    		case 'js' :
    			$path = JPATH_SITE.DS.'media'.DS.'com_mysite'.DS.'js';
    			break;
    	}

    	return $path;
    }

    /**
     * Method to intelligently load class files in the Mysite framework
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return boolean
     */
    public static function load( $classname, $filepath, $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_mysite' ), $force = false ) 
    {
        $classname = strtolower( $classname );
    	if(version_compare(JVERSION,'1.6.0','ge')) {
            // Joomla! 1.6+ code here
            $classes = JLoader::getClassList();
        } else {
            // Joomla! 1.5 code here
            $classes = JLoader::register();
        }            

        if ( ( class_exists($classname) || array_key_exists( $classname, $classes ) ) && !$force )  
        {
            return true;
        }
        
        static $paths;

        if (empty($paths)) 
        {
            $paths = array();
        }
        
        if (empty($paths[$classname]) || !is_file($paths[$classname]))
        {
            // find the file and set the path
            if (!empty($options['base']))
            {
                $base = $options['base'];
            }
                else
            {
                // recreate base from $options array
                switch ($options['site'])
                {
                    case "site":
                        $base = JPATH_SITE.DS;
                        break;
                    default:
                        $base = JPATH_ADMINISTRATOR.DS;
                        break;
                }
                
                $base .= (!empty($options['type'])) ? $options['type'].DS : '';
                $base .= (!empty($options['ext'])) ? $options['ext'].DS : '';
            }
            
            $paths[$classname] = $base.str_replace( '.', DS, $filepath ).'.php';
        }
        
        // if invalid path, return false
        if (!is_file($paths[$classname]))
        {
            return false;
        }
        
        // if not registered, register it
        if ( !array_key_exists( $classname, $classes ) || $force ) 
        {
            JLoader::register( $classname, $paths[$classname] );
            return true;
        }
        return false;
    }
    
    /**
     * Intelligently loads instances of classes in Mysite framework
     * 
     * Usage: $object = Mysite::getClass( 'MysiteHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Mysite::getClass( 'MysiteHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Mysite::getClass( 'MysiteSelect', 'library.select' )->category( $selected );
     * 
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_mysite' )  )
    {
        if (Mysite::load( $classname, $filepath, $options ))
        {
            $instance = new $classname();
            return $instance;
        }
        
        $instance = new JObject();
        return $instance;
    }
    
	/**
	 * Method to dump the structure of a variable for debugging purposes
	 *
	 * @param	mixed	A variable
	 * @param	boolean	True to ensure all characters are htmlsafe
	 * @return	string
	 * @since	1.5
	 * @static
	 */
	public static function dump( &$var, $htmlSafe = true ) {
		$result = print_r( $var, true );
		return '<pre>'.( $htmlSafe ? htmlspecialchars( $result ) : $result).'</pre>';
	}

	var $show_linkback						= '1';
	var $page_tooltip_dashboard_disabled	= '0';
	var $page_tooltip_config_disabled		= '0';
	var $page_tooltip_tools_disabled		= '0';
    var $tree_depth                         = '0';
    var $change_frequency                   = '0';
    var $priority                           = '0';
    


	/**
	 * constructor
	 * @return void
	 */
	function __construct() {
		parent::__construct();

		$this->setVariables();
	}

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = "SELECT * FROM #__mysite_config";
		return $query;
	}

	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// load the data if it doesn't already exist
		if (empty( $this->_data )) {
			$database = &JFactory::getDBO();
			$query = $this->_buildQuery();
			$database->setQuery( $query );
			$this->_data = $database->loadObjectList();
		}

		return $this->_data;
	}

	/**
	 * Set Variables
	 *
	 * @acces	public
	 * @return	object
	 */
	function setVariables() {
		$success = false;

		if ( $data = $this->getData() )
		{
			for ($i=0; $i<count($data); $i++)
			{
				$title = $data[$i]->config_name;
				$value = $data[$i]->value;
				if (isset($title)) {
					$this->$title = $value;
				}
			}

			$success = true;
		}

		return $success;
	}

	/**
	 * Get component config
	 *
	 * @acces	public
	 * @return	object
	 */
	function &getInstance() {
		static $instance;

		if (!is_object($instance)) {
			$instance = new Mysite();
		}

		return $instance;
	}
}
?>