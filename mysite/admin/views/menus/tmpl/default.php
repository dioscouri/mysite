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
                <?php echo JText::_('Search'); ?>
                <input id="search" name="filter" value="<?php echo @$state->filter; ?>" />
                <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                <button onclick="mysiteResetFormFilters(this.form);"><?php echo JText::_('Reset'); ?></button>
            </td>
            <td nowrap="nowrap">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?php echo MysiteSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true, 'Enabled State' ); ?>
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
                	<?php echo DSCGrid::sort( 'ID', "tbl.menu_id", @$state->direction, @$state->order ); ?>
                </th>                
                <th style="text-align: left;width: 20%">
                	<?php echo DSCGrid::sort( 'Title', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;width: 10%">
                	<?php echo DSCGrid::sort( 'Menutype', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;width: 45%">
                	<?php echo DSCGrid::sort( 'Description', "tbl.title", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
    	            <?php echo DSCGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
    	            <?php echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th>
    	            <?php echo DSCGrid::sort( 'Enabled', "tbl.enabled", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                    <?php echo JText::_( 'Sitemap Items' ); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::checkedout( $item, $i, 'menu_id' ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo $item->menu_id; ?>
				</td>	
				<td style="text-align: left;">
					<?php echo JText::_($item->title); ?>
				</td>
				<td style="text-align: left;">
						<?php echo JText::_($item->menutype); ?>
				</td>
				<td style="text-align: left;">
						<?php echo JText::_($item->description); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::order($item->menu_id); ?>
					<?php echo DSCGrid::ordering($item->menu_id, $item->ordering ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::enable($item->enabled, $i, 'enabled.'); ?>
				</td>
                <td style="text-align: center;">
                    <?php echo (int) $item->count; ?>
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
		<tfoot>
			<tr>
				<td colspan="20">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>