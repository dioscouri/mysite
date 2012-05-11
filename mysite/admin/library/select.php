<?php
/**
* @package		Mysite
* @copyright	Copyright (C) 2009 DT Design Inc. All rights reserved.
* @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.dioscouri.com
*/


class MysiteSelect extends DSCSelect
{
	


 	/**
 	 * 
 	 * @param $selected
 	 * @param $name
 	 * @param $attribs
 	 * @param $idtag
 	 * @param $allowAny
 	 * @param $allowNone
 	 * @param $title
 	 * @param $title_none
 	 * @return unknown_type
 	 */
	public static function levellimit($selected, $name = 'filter_levellimit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'No Limit', $title_none = 'No Limit' )
 	{
	    $list = array();
		$list[] = JHTML::_('select.option',  '1000', '- No limit -' );
		$list[] = JHTML::_('select.option',  '1', 1 );
		$list[] = JHTML::_('select.option',  '2', 2 );
		$list[] = JHTML::_('select.option',  '3', 3 );
		$list[] = JHTML::_('select.option',  '4', 4 );
		$list[] = JHTML::_('select.option',  '5', 5 );
		$list[] = JHTML::_('select.option',  '6', 6 );
		$list[] = JHTML::_('select.option',  '7', 7 );
		$list[] = JHTML::_('select.option',  '8', 8 );
		$list[] = JHTML::_('select.option',  '9', 9 );
		$list[] = JHTML::_('select.option',  '10', 10 );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
 	}
 	
 	/**
 	 * 
 	 * @param $selected
 	 * @param $name
 	 * @param $attribs
 	 * @param $idtag
 	 * @param $allowAny
 	 * @param $allowNone
 	 * @param $title
 	 * @param $title_none
 	 * @return unknown_type
 	 */
	public static function changefrequency($selected, $name = 'filter_levellimit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Frequency', $title_none = 'never' )
 	{
	    $list = array();
		$list[] = JHTML::_('select.option',  'always',  'always');
		$list[] = JHTML::_('select.option',  'hourly', 'hourly' );
		$list[] = JHTML::_('select.option',  'daily', 'daily' );
		$list[] = JHTML::_('select.option',  'weekly', 'weekly' );
		$list[] = JHTML::_('select.option',  'monthly', 'monthly' );
		$list[] = JHTML::_('select.option',  'yearly', 'yearly' );
		$list[] = JHTML::_('select.option',  'never', 'never' );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
 	}
 	

	public static function changefrequencyWithDefault($selected, $name = 'filter_levellimit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Frequency', $title_none = 'never' )
 	{
	    $list = array();
		$list[] = JHTML::_('select.option',  '',  'default');
		$list[] = JHTML::_('select.option',  'always',  'always');
		$list[] = JHTML::_('select.option',  'hourly', 'hourly' );
		$list[] = JHTML::_('select.option',  'daily', 'daily' );
		$list[] = JHTML::_('select.option',  'weekly', 'weekly' );
		$list[] = JHTML::_('select.option',  'monthly', 'monthly' );
		$list[] = JHTML::_('select.option',  'yearly', 'yearly' );
		$list[] = JHTML::_('select.option',  'never', 'never' );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
 	}
 	
 	/**
 	 * 
 	 * @param $selected
 	 * @param $name
 	 * @param $attribs
 	 * @param $idtag
 	 * @param $allowAny
 	 * @param $allowNone
 	 * @param $title
 	 * @param $title_none
 	 * @return unknown_type
 	 */
	public static function priorities($selected, $name = 'filter_levellimit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = '0.0', $title_none = '0.0' )
 	{
	    $list = array();
		$list[] = JHTML::_('select.option',  '0.0', 0.0 );
		$list[] = JHTML::_('select.option',  '0.1', 0.1 );
		$list[] = JHTML::_('select.option',  '0.2', 0.2 );
		$list[] = JHTML::_('select.option',  '0.3', 0.3 );
		$list[] = JHTML::_('select.option',  '0.4', 0.4 );
		$list[] = JHTML::_('select.option',  '0.5', 0.5 );
		$list[] = JHTML::_('select.option',  '0.6', 0.6 );
		$list[] = JHTML::_('select.option',  '0.7', 0.7 );
		$list[] = JHTML::_('select.option',  '0.8', 0.8 );
		$list[] = JHTML::_('select.option',  '0.9', 0.9 );
		$list[] = JHTML::_('select.option',  '1.0', 1.0 );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
 	}

	public static function prioritiesWithDefault($selected, $name = 'filter_levellimit', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = '0.0', $title_none = '0.0' )
 	{
	    $list = array();
		$list[] = JHTML::_('select.option',  '',  'default');
	 	$list[] = JHTML::_('select.option',  '0.1', 0.1 );
		$list[] = JHTML::_('select.option',  '0.2', 0.2 );
		$list[] = JHTML::_('select.option',  '0.3', 0.3 );
		$list[] = JHTML::_('select.option',  '0.4', 0.4 );
		$list[] = JHTML::_('select.option',  '0.5', 0.5 );
		$list[] = JHTML::_('select.option',  '0.6', 0.6 );
		$list[] = JHTML::_('select.option',  '0.7', 0.7 );
		$list[] = JHTML::_('select.option',  '0.8', 0.8 );
		$list[] = JHTML::_('select.option',  '0.9', 0.9 );
		$list[] = JHTML::_('select.option',  '1.0', 1.0 );

		return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
 	}
 	
 	/**
 	 * 
 	 * @param unknown_type $selected
 	 * @param unknown_type $name
 	 * @param unknown_type $attribs
 	 * @param unknown_type $idtag
 	 * @param unknown_type $allowAny
 	 * @param unknown_type $allowNone
 	 * @param unknown_type $title
 	 * @param unknown_type $title_none
 	 * @param unknown_type $enabled
 	 */
 	public static function menutype($selected, $name = 'filter_menutype', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Menu', $title_none = 'No Menu', $enabled = null )
 	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'models' );
		$model = JModel::getInstance( 'Menus', 'MysiteModel' );
		$model->setState('enabled', '1');
		$model->setState('order', 'tbl.ordering');
		
		$items = $model->getList();

		$list[] =  self::option( '', JText::_('- Choose a menu -'), 'menu_id', 'title' );
		
        foreach (@$items as $item)
        {
        	$list[] =  self::option( $item->menutype, JText::_($item->title), 'menu_id', 'title' );
        }
		return self::genericlist($list, $name, $attribs, 'menu_id', 'title', $selected, $idtag );
 	}
 	
 	/**
 	 * 
 	 * @param unknown_type $selected
 	 * @param unknown_type $name
 	 * @param unknown_type $attribs
 	 * @param unknown_type $idtag
 	 * @param unknown_type $allowAny
 	 * @param unknown_type $allowNone
 	 * @param unknown_type $title
 	 * @param unknown_type $title_none
 	 * @param unknown_type $enabled
 	 */
    public static function item($selected, $name = 'filter_parentid', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $allowNone = false, $title = 'Select Parent', $title_none = 'No Parent', $enabled = null )
    {
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mysite'.DS.'models' );
        $model = JModel::getInstance( 'Items', 'MysiteModel' );
        $model->setState('order', 'tbl.ordering');
        $model->setState('direction', 'ASC');
        
        // establish the hierarchy of the menu
        $children = array();
        
        // get all rows from DB
        $rows = $model->getAll();
        
        // get children and create new list
        foreach ($rows as $v )
        {
            $parent = $v->parent;
            $v->parent_id = $v->parent;
            $items = @$children[$parent] ? $children[$parent] : array();
            array_push( $items, $v );
            $children[$parent] = $items;
        }
        
        // set the max level
        $level = strlen($model->getState('filter_levellimit')) ? $model->getState('filter_levellimit') - 1 : 9999;
        
        // indent the list
        Mysite::load( 'MysiteMenu', 'library.menu' );
        $items = MysiteMenu::treerecurse (0, '', array(), $children, $level, 0, 0, '- ', '--' );
        
        if ($allowAny) 
        {
            $list[] =  self::option('', "- ".JText::_( $title )." -", 'item_id', 'title' );
        }
    
        if ($allowNone) 
        {
            $list[] =  self::option('0', "- ".JText::_( $title_none )." -", 'item_id', 'title' );
        }
        
        foreach (@$items as $item)
        {
            $list[] =  self::option( $item->item_id, $item->treename, 'item_id', 'title' );
        }
        return self::genericlist($list, $name, $attribs, 'item_id', 'title', $selected, $idtag );
    }
 	
}
