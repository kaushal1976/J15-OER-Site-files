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

// load tooltip behavior
JHtml::_('behavior.tooltip');

?>

<h1> <?php echo JText::_('LIST_HEADING'); ?> </h1>
<p> <?php echo JText::_('OER_LIST_DESCRIPTON'); ?> </p>

<form action="<?php echo JRoute::_('index.php?option=com_oer'); ?>" method="post" name="adminForm">
<table width="100%">
<tr>
	<td colspan="4">
		<?php echo JText::_( 'OER_SEARCH' ); ?>:
		<input type="text" size="50" maxlength="250" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="inputbox" onchange="document.adminForm.submit();" />
		<button class="button" onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button class="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</td>
</tr>
</table>

<table width="100%" class="list">
 <tr>
    <th><?php echo JText::_('OER_ID'); ?> </td>
    <th><?php echo JText::_('OER_TITLE'); ?></td>
    <th><?php echo JText::_('OER_SUBMITTED_BY'); ?></td>
    <th><?php echo JText::_('OER_FILE'); ?></td>
  </tr>
			<?php 
			$class='row0';	
			$oers=$this->data;
			$numofoers=count($oers);
			if ($numofoers==0){
			?>
			<tr>
            <td colspan="4" ><?php echo JText::_('NO_OERS_SUBMITTED'); ?> </td>	
            </tr>
            <?php 
			}else{
				foreach ( $oers as $oer ) {
					$submitted_by =  JFactory::getUser($oer->userid);
					if ($class=='row1'){
					$class='row0';
					}else{
					$class='row1';
					}
					$formname=$oer->id;
					?>
 					<tr class="<?php echo $class;?>">
      				<td><?php if ($oer->id > 0) {
						echo $oer->id; 
                        }else{
                        echo JText::_('INVALID_RESOURCE');
						}
						?>
                        
                         </td>
   	  				<td><?php echo $oer->title; ?></td>
   					<td><?php echo $submitted_by->name; ?>
				   	</td>
                    <td>
					<?php
                    echo $oer->filedata; ?>
	                </td>
   					</tr>
				<?php 
				}
			}
				?>
	
				</table>
 

<?php echo $this->pagination->getListFooter(); ?>
</form>

