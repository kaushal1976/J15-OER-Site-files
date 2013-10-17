<?php
/**
 * @version		1.5.0 confmgt $
 * @package		confmgt
 * @copyright	Copyright © 2012 - All rights reserved.
 * @license		GNU/GPL
 * @author		Dr Kaushal Keraminiyage
 * @author mail	admin@confmgt.com
 * @website		www.confmgt.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );


class oerViewOer extends Jview
{
	function display($tpl = null)
	{
	
		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		
		// Get some objects from the JApplication
		$mainframe = JFactory::getApplication();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$params   = &$mainframe->getParams();
		$model		=& $this->getModel();
		$user		=& JFactory::getUser();
		$uri     	=& JFactory::getURI();
		

		// Make sure you are logged in and have the necessary access rights
		if ($user->get('gid') < 19) {
			  JResponse::setHeader('HTTP/1.0 403',true);
              JError::raiseWarning( 403, JText::_('ALERTNOTAUTH') );
			return;
		}

		//get the OER
		$data	= new stdClass();
		$data	=& $this->get('data');
		$isNew	= ($data->id < 1);

		// Edit or Create?
		if (!$isNew)
		{
	

			// Set page title
			$menus	= &JSite::getMenu();
			$menu	= $menus->getActive();

			// because the application sets a default page title, we need to get it
			// right from the menu item itself
			if (is_object( $menu )) {
				$menu_params = new JParameter( $menu->params );
				if (!$menu_params->get( 'page_title')) {
					$params->set('page_title',	JText::_( 'OER'.' - '.JText::_('Edit') ));
				}
			} else {
				$params->set('page_title',	JText::_( 'OER'.' - '.JText::_('Edit') ));
			}

			$document->setTitle( $params->get( 'page_title' ) );

		}
		else
		{
			/*
			 * The OER does not already exist so we are creating a new one.  Here
			 * we want to manipulate the pathway and pagetitle to indicate this.  Also,
			 * we need to initialize some values.
			 */
	
			// Set page title
			$menus	= &JSite::getMenu();
			$menu	= $menus->getActive();

			// because the application sets a default page title, we need to get it
			// right from the menu item itself
			if (is_object( $menu )) {
				$menu_params = new JParameter( $menu->params );
				if (!$menu_params->get( 'page_title')) {
					$params->set('page_title', JText::_('SUBMIT_AN_OER') );
				}
			} else {
				$params->set('page_title', JText::_('SUBMIT_AN_OER') );
			}

			$document->setTitle( $params->get( 'page_title' ) );

			// Add pathway item
			$pathway->addItem(JText::_('New'), '');
		}

		JFilterOutput::objectHTMLSafe( $weblink, ENT_QUOTES, 'description' );

		$this->assignRef('user' , $user);
		$this->assignRef('data' , $data);
		$this->assignRef('params' ,	 $params);
		parent::display($tpl);
    }

}
?>