<?php
/**
* @package		Mysite
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Mysite extends DSC
{
    protected $_name = 'mysite';
    static $_version 		= '1.0';
    static $_build          = 'r100';
    static $_versiontype    = 'community';
    static $_copyrightyear 	= '2012';
    static $_min_php		= '5.2';
    
	var $show_linkback						= '1';
	var $page_tooltip_dashboard_disabled	= '0';
	var $page_tooltip_config_disabled		= '0';
	var $page_tooltip_tools_disabled		= '0';
    var $tree_depth                         = '0';
    var $change_frequency                   = '0';
    var $priority                           = '0';
    
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    public function _buildQuery()
    {
        $query = "SELECT * FROM #__mysite_config";
        return $query;
    }
    
    /**
     * Get component config
     *
     * @acces	public
     * @return	object
     */
    public static function getInstance()
    {
        static $instance;
    
        if (!is_object($instance)) {
            $instance = new Mysite();
        }
    
        return $instance;
    }
    
    /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = Mysite::getClass( 'MysiteHelperCarts', 'helpers.carts' );
     * Usage: $suffix = Mysite::getClass( 'MysiteHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = Mysite::getClass( 'MysiteSelect', 'select' )->category( $selected );
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return object of requested class (if possible), else a new JObject
     */
    public static function getClass( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_mysite' )  )
    {
        return parent::getClass( $classname, $filepath, $options  );
    }
    
    /**
     * Method to intelligently load class files in the framework
     *
     * @param string $classname   The class name
     * @param string $filepath    The filepath ( dot notation )
     * @param array  $options
     * @return boolean
     */
    public static function load( $classname, $filepath='controller', $options=array( 'site'=>'admin', 'type'=>'components', 'ext'=>'com_mysite' ) )
    {
        return parent::load( $classname, $filepath, $options  );
    }
}
?>