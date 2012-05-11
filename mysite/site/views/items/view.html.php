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

JLoader::import( 'com_mysite.views._base', JPATH_SITE.DS.'components' );
JLoader::import( 'com_mysite.helpers.item', JPATH_ADMINISTRATOR.DS.'components' );

class MysiteViewItems extends MysiteViewBase 
{
	function _default($tpl='')
	{
		
		$model = $this->getModel();
		$model->setState( 'filter_parent', 0);
		$model->setState( 'filter_enabled', '1' );
		
		// set the model state
			$this->assign( 'state', $model->getState() );
			
		// page-navigation
			$this->assign( 'pagination', $model->getPagination() );
		
		// list of items
			$this->assign('items', $model->getList());

		// form
			$validate = JUtility::getToken();
			$form = array();
			$view = strtolower( JRequest::getVar('view') );
			$form['action'] = "index.php?option=com_mysite&controller={$view}&view={$view}";
			$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
			$this->assign( 'form', $form );
	}

}