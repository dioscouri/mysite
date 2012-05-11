<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

	<?php echo DSCGrid::checkoutnotice( @$row ); ?>
	
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
										<?php echo JText::_( 'Title' ); ?>:
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
									<?php echo JText::_( 'Ordering' ); ?>:
								</td>
								<td>
									<?php echo $this->lists['ordering']; ?>
								</td>
							</tr>							
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
		
		<p>* <?php echo JText::_( "or" )  . " " . $this->required->image . " " . JText::_( 'Required Field' ); ?></p>

			<input type="hidden" name="cid[]" value="<?php echo @$row->menugroup_id; ?>" />
			<input type="hidden" name="boxchecked" value="" />
	        
			<input type="hidden" name="id" value="<?php echo @$row->menugroup_id; ?>" />
			<input type="hidden" name="task" id="task" value="" />
	</fieldset>
</form>