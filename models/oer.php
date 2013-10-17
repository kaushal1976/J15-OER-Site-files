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
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class oerModelOer extends Jmodel
{
		function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the OER identifier
	 *
	 * @access	public
	 * @param	int OER identifier
	 */
	function setId($id)
	{
		// Set OER id and wipe data
		$this->_id		= $id;
		
		if ($id ==0) 
		{
			$mainframe =& JFactory::getApplication();
			$data = $mainframe->getUserState( "oer.post" );
			if (empty($data)) 
			{
				$this->_data	= null;
			}else{
				$this->_data	= new stdClass();
				
				foreach ($data as $key => $value)
				{
    				$this->_data->$key = $value;
				}
				
				$this->_data->id= $id;
			}
			
		}else{
			$this->_data	= null;
		}
					
	}

	/**
	 * Method to get an OER
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the OER data
		if ($this->_loadData())
		{

		}
		else  $this->_initData();

		return $this->_data;
	}

	
	function store($data,$table='oer')
	{
		$row =& $this->getTable($table);

		// Bind the form fields to the OER table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}
	
	function store_lastid($data,$table='oer')
	{
		$row =& $this->getTable($table);

		// Bind the form fields to the OER table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $this->_db->insertid();;
	}
	
		/**
	 * Method to load content OER data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT cc.*' .
					' FROM #__oer_oers AS cc' .
					' WHERE cc.id = '. (int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the OER data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$oer = new stdClass();
			$oer->id					= 0;
			$oer->title					= null;
			$oer->description			= null;
			$oer->filedata				= null;
			$oer->keywords				= null;
			$oer->oertype				= null;
		
			$this->_data				= $oer;
			return (boolean) $this->_data;
		}else{
			$data = $this->_data;
			$oer = new stdClass();
			foreach ($data as $key => $value)
				{
    				$oer->$key = $value;
				}
				
			$oer->id= $this->_id;
			$this->_data				= $oer;
			return (boolean) $this->_data;
		}
		
		return true;
	}
	
	
	
}
?>