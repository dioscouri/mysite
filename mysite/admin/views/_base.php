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

class MysiteViewBase extends DSCViewAdmin
{
	/**
	 * Displays a layout file 
	 * 
	 * @param unknown_type $tpl
	 * @return unknown_type
	 */
	function display($tpl=null)
	{
        Mysite::load( 'MysiteUrl', 'library.url' );
        Mysite::load( 'MysiteSelect', 'library.select' );
        Mysite::load( 'DSCGrid', 'library.grid' );
        Mysite::load( 'MysiteMenu', 'library.menu' );
        
		parent::display($tpl);
	}

	
}