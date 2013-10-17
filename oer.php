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
//add style sheets
$document = &JFactory::getDocument(); 
$document->addStyleSheet('components'.DS.'com_oer'.DS.'assets'.DS.'css'.DS.'css.css'); 
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
// Require specific controller if requested

if($controller = JRequest::getCmd('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) { 
        require_once $path;
    } else {
        $controller = '';
    }
}

// Create the controller
$classname	= 'oerController'.ucfirst($controller); 
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getCmd('task', null, 'default', 'cmd'));
$controller->redirect();
?>