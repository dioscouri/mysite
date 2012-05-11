<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$url = "http://dioscouri.com/joomla-extensions/non-commercial-extensions/mysite";

?>

<p align="center" <?php echo @$this->style; ?> >
    <?php echo JText::_( 'Powered by' )." <a href='{$url}' target='_blank'>".JText::_( 'MySite' )."</a>"; ?>
</p>

