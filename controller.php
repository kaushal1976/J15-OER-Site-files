
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

jimport('joomla.application.component.controller');

class oerController extends JController
{

	function __construct() 
	{
		parent::__construct();  
	}
	
    function display()
	{

		$user =& JFactory::getUser();
		
		if (!($user->guest)) {
			if ( ! (JRequest::getCmd( 'view' ) )) {
				JRequest::setVar('view', 'oers');
				}
			
		} else {
			
			$link = JRoute::_('index.php?option=com_user&view=login', false);
			$msg = JText::_('NEEDS_LOGIN');
			$this->setredirect($link,$msg); 
		}
        parent::display();

    }
	
}
?>