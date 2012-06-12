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

class MysiteModelDashboard extends MysiteModelBase 
{
	public function getTable($name='', $prefix='MysiteTable', $options = array())
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'tables' );
		$table = JTable::getInstance( 'Config', 'MysiteTable' );
		return $table;
	}
}