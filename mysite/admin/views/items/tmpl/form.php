<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

	<?php echo MysiteGrid::checkoutnotice( @$row ); ?>
	
	<fieldset>
		<legend><?php echo JText::_('Form'); ?></legend>
		<table class="invisible">
			<tbody>
                <tr>
					<td valign="top">

					<?php
					// display defaults
					$pane = '1';
					echo $this->sliders->startPane( "pane_$pane" );
					
					$legend = JText::_( "Default Information" );
					echo $this->sliders->startPanel( JText::_( $legend ), 'defaults' );
						?>
						<table class="adminlist" cellspacing="1">
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'ID' ); ?>:
								</td>
								<td>
									<strong>
										<?php echo $this->row->item_id; ?>
									</strong>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="title">
										<?php echo JText::_( 'Title' ); ?>*:
									</label>
								</td>
								<td>
									<input class="text_area" type="text" name="title" id="title" size="35" value="<?php echo $this->row->title; ?>" />
								</td>
							</tr>
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'URL' ); ?>:
								</td>
								<td>
									<input class="text_area" type="text" name="url" id="url" size="75" value="<?php echo $this->row->url; ?>" />
								</td>
							</tr>
                            <tr>
                                <td valign="top" class="key">
                                    <?php echo JText::_( 'Menu' ); ?>
                                </td>
                                <td>
                                    <?php // TODO Make this filter the Parent list below ?>
                                    <?php echo MysiteSelect::menutype( $this->row->get('menutype'), 'menutype' ); ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" class="key">
                                    <?php echo JText::_( 'Parent' ); ?>
                                </td>
                                <td>
                                    <?php echo MysiteSelect::item( $this->row->get('parent'), 'parent', '', '', true ); ?>
                                </td>
                            </tr>
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'Default change frequency value' ); ?>
								</td>
								<td>
									<?php echo MysiteSelect::changefrequencyWithDefault( $this->row->get('change_frequency', '0'), 'change_frequency', null, 'change_frequency', true, 'Change Frequency' ); ?>
								</td>
							</tr>
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'Default priority value' ); ?>
								</td>
								<td>
									<?php echo MysiteSelect::prioritiesWithDefault( $this->row->get('priority', '0'), 'priority', null, 'priority', false, 'Priority' ); ?>
								</td>
							</tr>
								
							<!--	
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'Ordering' ); ?>:
								</td>
								<td>
									<?php echo $this->lists['ordering']; ?>
								</td>
							</tr>							
							-->
							<tr>
								<td valign="top" class="key">
									<?php echo JText::_( 'Enabled' ); ?>:
								</td>
								<td>
									<?php echo $this->lists['enabled']; ?>
								</td>
							</tr>
						</table>
					<?php
					echo $this->sliders->endPanel();
					?>						
                </tr>
            </tbody>
		</table>
		
		<p>* <?php echo JText::_( "or" ) . JText::_( 'Required Field' ); ?></p>

			<input type="hidden" name="cid[]" value="<?php echo @$row->item_id; ?>" />
			<input type="hidden" name="boxchecked" value="" />
	        
			<input type="hidden" name="id" value="<?php echo @$row->item_id; ?>" />
			<input type="hidden" name="task" id="task" value="" />
	</fieldset>
</form>