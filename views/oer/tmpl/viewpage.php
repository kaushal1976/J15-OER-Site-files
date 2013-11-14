<?php
/**
 * @version		1.5.0 OER $
 * @package		oer
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license		GNU/GPL
 * @author		Dr Kaushal Keraminiyage
 * @author mail	admin@confmgt.com
 * @website		www.confmgt.com
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">

function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

}
</script>

<?php
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
?>
<?php

if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; 

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate" >
<div>
<h2><?php echo JText::_( 'OER_TITLE' ); ?>: </h2>
<p><?php echo $this->escape($this->data->title);?></p>
<h2><?php echo JText::_( 'OER_AUTHORS' ); ?>:</h2>
<p><?php echo $this->escape($this->data->authors);?></p>
<h2><?php echo JText::_( 'OER_TYPE' ); ?>:</h2>
<p><?php echo $this->escape($this->data->oertype);?></p>
<h2><?php echo JText::_( 'OER_DESCRIPTION' ); ?>:</h2>
<p><?php echo $this->escape( $this->data->description);?></p>
<h2><?php echo JText::_( 'OER_KEYWORDS' ); ?>:</h2>
<p><?php echo $this->escape( $this->data->keywords);?> </p>
<h2><?php echo JText::_( 'OER_DOWNLOAD' ); ?>:</h2>
<p><?php echo $this->lists['file'];?> </p>
<h2><?php echo JText::_( 'OER_LICENCE' ); ?>:</h2>
<p><?php echo $this->data->licence_desc;?> </p>
</div>

<div>
	<button type="button" onclick="submitbutton('cancel')" class="button">
		<?php echo JText::_('Cancel') ?>
	</button>
    <input type="hidden" name="option" value="com_oer" />
	<input type="hidden" name="task" value="" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</div>
</form>



