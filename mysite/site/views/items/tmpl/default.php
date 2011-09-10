<?php
defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();
$document->addStyleSheet('media/com_mysite/css/mysite.css');
$items = $this->items ? $this->items : array(); 
?>

<div id="mysite">
    <?php 
        $output = '';
        
        foreach ($items as $item) 
        {
            $output .= '<p><a href="'.JRoute::_($item->url_itemid).'">'.$item->title.'</a></p>';
            
            $model = JModel::getInstance( 'Items', 'MySiteModel' );
            $model->setState( 'filter_parent', $item->item_id);
            $model->setState( 'filter_enabled', '1' );
            $model->setState( 'order', 'tbl.ordering' );
            $model->setState( 'direction', 'ASC' );
            $subitems = $model->getList();

            if (count($subitems))
            {
                $output .= MysiteHelperItem::print_recoursive($subitems);
            }
        }
        
        echo $output;
    ?>
</div>