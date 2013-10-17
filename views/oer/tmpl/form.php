<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (document.getElementById('title').value == ""){
		alert( "<?php echo JText::_( 'OER item must have a title', true ); ?>" );
	} else if (document.getElementById('oertype').value =="") {
		alert( "<?php echo JText::_( 'OER item must have a type.', true ); ?>" );
	} else if (document.getElementById('keywords').value == ""){
		alert( "<?php echo JText::_( 'You must have at least one keyword ', true ); ?>" );
	} else if (document.getElementById('description').value == ""){
		alert( "<?php echo JText::_( 'You must enter a description for your OER ', true ); ?>" );
	} else {
		submitform( pressbutton )
	}
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td width="10%">
		<label for="title">
			<?php echo JText::_( 'OER_TITLE' ); ?>:
		</label>
	</td>
	<td width="80%">
		<input class="inputbox" type="text" id="title" name="title" size="50" maxlength="250" value="<?php echo $this->escape($this->data->title);?>" />
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="type">
			<?php echo JText::_( 'OER_TYPE' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox" type="text" id="oertype" name="oertype" size="50" maxlength="250" value="<?php echo $this->escape($this->data->oertype);?>" />
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="filedata">
			<?php echo JText::_( 'OER_FILE' ); ?>: 
		</label>
	</td>
	<td>
		<input class="inputbox" type="file" id="filedata" name="filedata" />
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="keywords">
			<?php echo JText::_( 'OER_KEYWORDS' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox" type="text" id="keywords" name="keywords" size="50" maxlength="250" value="<?php echo $this->escape($this->data->keywords);?>" /> 
	</td>
</tr>

<tr>
	<td valign="top">
		<label for="description">
			<?php echo JText::_( 'OER_DESCRIPTION' ); ?>:
		</label>
	</td>
	<td>
		<textarea class="inputbox" cols="30" rows="6" id="description" name="description" style="width:300px"><?php echo $this->escape( $this->data->description);?></textarea>
	</td>
</tr>
</table>

<div>
	<button type="button" onclick="submitbutton('save')" class="button">
		<?php echo JText::_('Save') ?>
	</button>
	<button type="button" onclick="submitbutton('cancel')" class="button">
		<?php echo JText::_('Cancel') ?>
	</button>
</div>

	<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
	<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
	<input type="hidden" name="option" value="com_oer" />
	<input type="hidden" name="controller" value="oer" />
	<input type="hidden" name="task" value="" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>

