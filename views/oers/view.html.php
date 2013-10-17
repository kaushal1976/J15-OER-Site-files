<?php
/**
 * @version		1.5.0 OER $
 * @package		oer
 * @copyright	Copyright © 2009 - All rights reserved.
 * @license		GNU/GPL
 * @author		Dr Kaushal Keraminiyage
 * @author mail	admin@confmgt.com
 * @website		www.confmgt.com
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport( 'joomla.application.component.view' );


class oerViewOers extends Jview
{
		function display($tpl = null)
    {	
		 $mainframe = &JFactory::getApplication();
		 $option = JRequest::getCmd('option');
		 $search = $mainframe->getUserStateFromRequest( $option.'search','search','','string' );
		if (strpos($search, '"') !== false) 
		{
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);
		$lists['search']= $search;
		 
		 
		 
		 
		 // Get data from the model
         $items = $this->get('Items');
         $pagination = $this->get('Pagination');
		 $data = $this->get('Data');
 
        // Check for errors.
         if (count($errors = $this->get('Errors'))) 
           {
             JError::raiseError(500, implode('<br />', $errors));
              return false;
           }
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;
		$this->data = $data;
		$this->assignRef('lists', $lists);
 
        // Display the template
  	
		parent::display($tpl);
	}
	
}
?>