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
	if (document.getElementById('authors').value == ""){
		alert( "<?php echo JText::_( 'Please specify the authors for this resource', true ); ?>" );
	} else if (document.getElementById('title').value == ''){
		alert( "<?php echo JText::_( 'You must agree with the licencing terms to submit the OER ', true ); ?>" );
	} else if (document.getElementById('oertype').value =="") {
		alert( "<?php echo JText::_( 'OER item must have a type.', true ); ?>" );
	} else if (document.getElementById('language').value == ""){
		alert( "<?php echo JText::_( 'Please specify the language of the OER', true ); ?>" );
	} else if (document.getElementById('keywords').value == ""){
		alert( "<?php echo JText::_( 'You must have at least one keyword ', true ); ?>" );
	} else if (document.getElementById('description').value == ""){
		alert( "<?php echo JText::_( 'You must enter a description for your OER ', true ); ?>" );
	} else if (document.getElementById('licence').value == 1){
		alert( "<?php echo JText::_( 'Please select the licencing terms applicable for the OER ', true ); ?>" );
	} else if (document.getElementById('agree0').checked){
		alert( "<?php echo JText::_( 'Please agree to the licencing terms to submit your OER to the repository ', true ); ?>" );
	
		
	} else {
		submitform( pressbutton )
	}
}

window.addEvent("domready",function(){
	
	$("licence").addEvent("change",function(){
		
	var url="index.php?option=com_oer&controller=oer&format=raw&task=getlicence&licence_id="+this.getValue();	
	var a=new Ajax(url,{
	method:"get",
	update:$("licence_div")
	}).request();
	});
});

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate" >
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>

<div id="page_error" name="page_error" class="warning">
</div>

<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td width="20%">
		<label for="projecttag">
			<?php echo JText::_( 'OER_PROJECT_TAG' ); ?>:
		</label>
	</td>
	<td width="80%">
		<input class="inputbox" type="text" id="projecttag" name="projecttag" size="50" maxlength="250" value="<?php echo $this->escape($this->data->projecttag);?>" />
	</td>
</tr>
<tr>
	<td width="20%">
		<label for="programtag">
			<?php echo JText::_( 'OER_PROGRAM_TAG' ); ?>:
		</label>
	</td>
	<td width="80%">
		<input class="inputbox" type="text" id="programtag" name="programtag" size="50" maxlength="250" value="<?php echo $this->escape($this->data->programtag);?>" />
	</td>
</tr>
<tr>
	<td width="20%">
		<label for="title">
			<?php echo JText::_( 'OER_TITLE' ); ?>:
		</label>
	</td>
	<td width="80%">
		<input class="inputbox required" type="text" id="title" name="title" size="50" maxlength="250" value="<?php echo $this->escape($this->data->title);?>" />
	</td>
</tr>
<tr>
	<td width="20%">
		<label for="authors">
			<?php echo JText::_( 'OER_AUTHORS' ); ?>:
		</label>
	</td>
	<td width="80%">
		<input class="inputbox required" type="text" id="authors" name="authors" size="50" maxlength="250" value="<?php echo $this->escape($this->data->authors);?>" />
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="type">
			<?php echo JText::_( 'OER_TYPE' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required" type="text" id="oertype" name="oertype" size="50" maxlength="250" value="<?php echo $this->escape($this->data->oertype);?>" />
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="language">
			<?php echo JText::_( 'OER_LANGUAGE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $this->lists['language']; ?> 
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="filedata">
			<?php echo JText::_( 'OER_FILE' ); ?>: 
		</label>
	</td>
	<td>
    
    	<?php echo $this->lists['file']; ?> 
		 
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="keywords">
			<?php echo JText::_( 'OER_KEYWORDS' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required" type="text" id="keywords" name="keywords" size="50" maxlength="250" value="<?php echo $this->escape($this->data->keywords);?>" /> 
	</td>
</tr>

<tr>
	<td valign="top">
		<label for="description">
			<?php echo JText::_( 'OER_DESCRIPTION' ); ?>:
		</label>
	</td>
	<td>
		<textarea class="inputbox required" cols="30" rows="6" id="description" name="description" style="width:300px"><?php echo $this->escape( $this->data->description);?></textarea>
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="licence">
			<?php echo JText::_( 'OER_LICENCE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $this->lists['licence']; ?> 
        <div id="licence_div"></div>
	</td>
      	
  
</tr>
<tr>
	<td valign="top">
		<label for="agree">
			<?php echo JText::_( 'OER_AGREE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $this->lists['agree']; ?>
	</td>
</tr>
</table>

<div>
	<button type="button" onclick="submitbutton('save')" class="button validate">
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

