
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
			
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'oers' );
			JRequest::setVar('layout', 'listpage' );
		}
			
		parent::display();

    }
	
}
?>