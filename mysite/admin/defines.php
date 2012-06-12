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
	protected $_name           = 'mysite';	
    static $_version 		= '0.6.0';
    static $_copyrightyear 	= '2012';
    static $_min_php        = '5.2';
    
    
    

     /**
     * Intelligently loads instances of classes in framework
     *
     * Usage: $object = BIllets::getClass( 'BIlletsHelperCarts', 'helpers.carts' );
     * Usage: $suffix = BIllets::getClass( 'BIlletsHelperCarts', 'helpers.carts' )->getSuffix();
     * Usage: $categories = BIllets::getClass( 'BIlletsSelect', 'select' )->category( $selected );
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
	 * Get component config
	 *
	 * @acces	public
	 * @return	object
	 */
	 public static function getInstance()
	{
		static $instance;

		if (!is_object($instance))
		{
			$instance = new Mysite();
		}

		return $instance;
		
	}
}
?>