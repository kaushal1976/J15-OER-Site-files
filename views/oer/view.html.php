<?php
/**
 * @version		1.5.0 OER $
 * @package		OER
 * @copyright	Copyright © 2013 - All rights reserved.
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
		}else{
			$mainframe = JFactory::getApplication();
			$document =& JFactory::getDocument();
			$params   = &$mainframe->getParams();
			$model		=& $this->getModel();
			
			//get the OER
			$data	= new stdClass();
			$data	=& $this->get('data');
			$licence_description = $model->getDataLicence($data->licence)->decs;
			$licence_url = $model->getDataLicence($data->licence)->url;
			$data ->licence_desc = $licence_description. '<br />'.'<a href="'.$licence_url.'">'.$licence_url.'</a>';
			$lists['file'] = '<a href="components/com_oer/download.php?file='.$data->filedata.'">'.$data->filedata.'</a>';
			$this->assignRef('params' ,	 $params);
			$this->assignRef('lists' ,	 $lists);
			$this->assignRef('data' , $data);
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
		

		//get the OER
		$data	= new stdClass();
		$data	=& $this->get('data');
		$isNew	= ($data->id < 1);
		
		// Make sure you are logged in and have the necessary access rights
		
		
		if (!$isNew)
		{
			$author = $model->getAuthor($data->id);
	
			if (($author <> $user->id) || $user->guest)			
			{		
			JResponse::setHeader('HTTP/1.0 403',true);
            JError::raiseWarning( 403, JText::_('ALERTNOTAUTH') );
			return;
			}
		}


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

		JFilterOutput::objectHTMLSafe( $data, ENT_QUOTES, 'description' );
		
		//building the languages list
		$languages = $model->getdataLanguages();
		
		//build the licence list
		
		$licences = $model->getdataLicences();
			
		if ($data->id > 0)
		{
			$selectedlicence =  $data->licence; 
			$selectedaccept = $data->agree;
			$selectedlanguage = $data->language;
			
		}else{
			
			$selectedlicence = null;
			$selectedaccept = null;
			$selectedlanguage = null;
		}
		
		$attributes = 'class = "inputbox"';
		
		JHTML::_('behavior.formvalidation');
			
		$lists['licence'] = JHTML::_('select.genericlist', $licences, 'licence', $attributes, 'value', 'text', $selectedlicence);
		$lists['language'] = JHTML::_('select.genericlist', $languages, 'language', $attributes, 'text', 'text', $selectedlanguage);
		$lists['agree'] =  JHTML::_('select.booleanlist', 'agree', 'class = "inputbox"', $selectedaccept);
		
		if ($isNew) {
			$lists['file'] = '<input type="file" id="filedata" name="filedata" />';
		}else{
			//$lists['file'] = '<a href="index.php?option=com_oer&controller=file&task=download&file='.$data->filedata.'">'.$data->filedata.'</a>';
			$lists['file'] = '<a href="components/com_oer/download.php?file='.$data->filedata.'">'.$data->filedata.'</a>';
		}
		
		$this->assignRef('user' , $user);
		$this->assignRef('data' , $data);
		$this->assignRef('params' ,	 $params);
		$this->assignRef('lists' , $lists);
		parent::display($tpl);
    }

}
?>