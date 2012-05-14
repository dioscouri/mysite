<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'mysite.js', 'media/com_mysite/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
    <table>
        <tr>
            <td align="left" width="100%">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "document.getElementById('task').value='moveto'; document.adminForm.submit();"); ?>
                <?php echo MysiteSelect::item( '', 'moveto_target', $attribs, 'moveto_target', true, true, 'Move to New Parent' ); ?>
            </td>
            <td nowrap="nowrap" style="text-align: right;">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?php echo JText::_('Search'); ?>
                <input id="filter" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                <button onclick="mysiteResetFormFilters(this.form);"><?php echo JText::_('Reset'); ?></button>
                <?php echo MysiteSelect::levellimit( @$state->filter_levellimit, 'filter_levellimit', $attribs, 'levellimit', true, 'Level Limit' ); ?>
            </td>
        </tr>
    </table>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_("Num"); ?>
                </th>
                <th style="width: 20px;">
                	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'ID', "tbl.item_id", @$state->direction, @$state->order ); ?>
                </th>                
                <th style="text-align: left;width: 50%">
                	<?php echo DSCGrid::sort( 'Sitemap Item', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                	<?php echo DSCGrid::sort( 'Menu Type', "tbl.menutype", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo DSCGrid::sort( 'Menu Itemid', "tbl.itemid", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Enabled', "tbl.enabled", @$state->direction, @$state->order ); ?>
                </th>
            </tr>
            <tr class="filterline">
                <th colspan="3">
                    <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span> <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span> <input id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input" />
                        </div>
                    </div>
                </th>
                <th style="text-align: left;">
                    <?php echo MysiteSelect::item( @$state->filter_parent, 'filter_parent', $attribs, 'parent', true, true ); ?>
                </th>
                <th>
                    <?php echo MysiteSelect::menutype( @$state->filter_menutype, 'filter_menutype', $attribs, 'menutype', true, 'Menu type' ); ?>
                </th>
                <th>
                    <input id="filter_itemid" name="filter_itemid" value="<?php echo @$state->filter_itemid; ?>" size="15"/>
                </th>
                <th>
                
                </th>
                <th>
                    <?php echo MysiteSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true, 'Enabled State' ); ?>
                </th>
            </tr>
            <tr>
                <th colspan="20" style="font-weight: normal;">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
                    <div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="20">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
                    <?php echo @$this->pagination->getPagesLinks(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::checkedout( $item, $i, 'item_id' ); ?>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->item_id; ?>
					</a>
				</td>	
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php echo JText::_($item->treename); ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo $item->menutype; ?>
				</td>
                <td style="text-align: center;">
                    <?php echo $item->itemid; ?>
                </td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order($item->item_id); ?>
					<?php echo DSCGrid::ordering($item->item_id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->enabled, $i, 'enabled.'); ?>
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('No items found'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>