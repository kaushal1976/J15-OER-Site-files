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

 // import the Joomla modellist library
jimport('joomla.application.component.model');


//Extend the JmodileList class
class oerModelOers extends JModel
{
	
		/**
	 * OERs data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * OERs total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	 
	var $_pagination = null;
	 
	 /**
   	 * Pagination object
     * @var object
     */
 
	 

	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication();
 
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
		

	}

	/**
	 * Method to get OER item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{

        // if data hasn't already been obtained, load it
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));    
        }
        return $this->_data;

	}
	
	/**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.           
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('cc.*');
        // From the tablename
        $query->from('#__oer_oers AS cc');
        return $query;
    }


	/**
	 * Method to get the total number of OERs items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	/**
	 * Method to get the pagination
	 *
	 * @access public
	 * @return integer
	 */
	
	function getPagination()
  	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  	}

	function _buildQuery()
	
	{
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
				
		//Query to retrieve all OERs.
		$query = 'SELECT cc.*'
			.' FROM #__oer_oers AS cc'
			. $where
			. $orderby
			
			;
		return $query;
	}
	
	
	function _buildContentOrderBy()
	{
	 	$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

	}

	function _buildContentWhere()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		$db					=& JFactory::getDBO();
		$search				= $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$where = array();

	
		if ($search) {
			$where[] = 'LOWER(cc.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
	
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	
	
}
?>