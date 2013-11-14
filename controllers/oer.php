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

class oerControllerOer extends oerController
{
function __construct()

	{
		parent::__construct();  
	}
	
function display()

	{
			$user =& JFactory::getUser(); 
		
			if ($user->guest) { // if user not logged in 
				$redirectUrl = 'index.php?option=com_oer';
				$redirectUrl = urlencode(base64_encode($redirectUrl));
				$this->setredirect(JRoute::_('index.php?option=com_user&view=login&return='.$redirectUrl),JText::_('NEEDS_LOGIN')); 
			}else if ( ! JRequest::getCmd( 'layout' ) ) {
			JRequest::setVar('view', 'oers' );
			JRequest::setVar('layout', 'listpage' );
			}
			
        parent::display();
    }
	
function loginrequired() // check if the user need to be logged in to complete an action  
	{
		$user =& JFactory::getUser();
		if ( $user->guest) { // if not logged in
		return true;
		}else{
		return false;
		}
     }

function getlicence() // check if the user need to be logged in to complete an action  
	{
	$model = $this->getModel('oer');
	$out = $model->getDataLicence();
	echo $out->decs.'<br />' .
	'<a href="'.$out->url.'">'.$out->url.'</a>';

	return; 
     }


function listpage()
	{
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('view', 'oers');
		JRequest::setVar('layout', 'listpage');
		parent::display();	
	}
	
	function viewpage()
	{
		
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('view', 'oer');
		JRequest::setVar('layout', 'viewpage');
		parent::display();
		
	}

	function edit()
	{
	
		if ($user->guest) { // if user not logged in 
				$redirectUrl = 'index.php?option=com_oer';
				$redirectUrl = urlencode(base64_encode($redirectUrl));
				$this->setredirect(JRoute::_('index.php?option=com_user&view=login&return='.$redirectUrl),JText::_('NEEDS_LOGIN')); 
			}else if ( ! JRequest::getCmd( 'layout' ) ) {
				JRequest::setVar('view', 'oer');
				JRequest::setVar('layout', 'form');
			}
			parent::display();
	}
	
	function addnew()
	{
		
		$user =& JFactory::getUser(); 
		
			if ($user->guest) { // if user not logged in 
				$redirectUrl = 'index.php?option=com_oer';
				$redirectUrl = urlencode(base64_encode($redirectUrl));
				$this->setredirect(JRoute::_('index.php?option=com_user&view=login&return='.$redirectUrl),JText::_('NEEDS_LOGIN')); 
			}else if ( ! JRequest::getCmd( 'layout' ) ) {
				JRequest::setVar('view', 'oer');
				JRequest::setVar('layout', 'form');
			}
		
        parent::display();
	}
	
	
	
	
	function cancel()
	{
		JRequest::setVar('view', 'oers');
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('layout', 'listpage');
		parent::display();
	}
	
	function fileUpload()
	
	{    
		jimport('joomla.filesystem.file');		
		$component = JComponentHelper::getComponent('com_oer');
		$user =& JFactory::getUser();
		$params = new JParameter( $component->params );
		$max = ini_get('upload_max_filesize');	    
		$filesAllowed =  $params->def( 'file_types', 'doc,ppt,docx,pdf');
		$file = JRequest::getVar('filedata', null, 'files', 'array'); 
		
		$msg = array();
		unset($msg);
		$msg['return'] = true;
		$msg['filename'] = null;
		$msg['error'] = null;
		       
        if(isset($file)){ 
                			
                $filename = JFile::makeSafe($file['name']);
				$uploadedFileExtension = JFile::getExt($filename);
				$fname = $user->name.'-'.time().'.'.$uploadedFileExtension;
                $filename = JFile::makeSafe($fname);
 
                if($file['size'] > $max*1024*1024) 
				{ 
					$msg ['error'] = JText::_('ONLY_FILES_UNDER').' '.$max;
					$msg ['return'] = false;
				
                }
				
				$validFileExts = explode(',', $filesAllowed);
				//assume the extension is false until we know its ok
				$extOk = false;
				//go through every ok extension, if the ok extension matches the file extension (case insensitive)
				//then the file extension is ok
				foreach($validFileExts as $key => $value)
				{
					$test= "/\b".$value."\b/i";
					
	    				if( preg_match($test, $uploadedFileExtension ) )
        				{
           					$extOk = true;
        				}
				}
				if ($extOk == false) 
				{
        			$msg ['error'] = $msg['error'].'<br />'. JText::_( 'INVALID EXTENSION' ).' '.$uploadedFileExtension;
					$msg ['return'] = false;
	
				}
				
				$fileError = $file['error'];
				if ($fileError > 0) 
				{
        			switch ($fileError) 
       			 	{
        				case 1:
						$msg ['error'] = $msg['error'].'<br />'.JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' );
						$msg ['return'] = false;
				 
						case 2:
						$msg ['error'] = $msg['error'].'<br />'.JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' );
						$msg ['return'] = false;
				 
						case 3:
						$msg ['error'] = $msg['error'].'<br />'.JText::_( 'ERROR PARTIAL UPLOAD' );
						$msg ['return'] = false;
				 
						case 4:
						$msg ['error'] = $msg['error'].'<br />'.JText::_( 'ERROR NO FILE' );
						$msg ['return'] = false;

					}
				}
				
				$src = $file['tmp_name'];
                $dest = JPATH_COMPONENT.DS.'upload'.DS.$filename;
				
				if ($msg['return'] == true) {
 
                
					if ( JFile::upload($src, $dest) ) {
	 
						//Redirect to a page of your choice
						$msg ['error'] = JText::_('FILE_SAVE_AS');
						$msg ['return'] = true;
						$msg ['filename'] = $filename;
					} else {
						//Redirect and throw an error message
						$msg ['error'] = $msg['error'].'<br />'.JText::_('ERROR_IN_UPLOAD');
						$msg ['return'] = false;
					}
				}
         
        }
        return $msg;
	}
	
	function save()
	{
		
		$upload = $this->fileUpload();
		$post = JRequest::get('post');
		$id = $post['id'];
		if ($upload['return'] == true )
		{
			$post['filedata'] = $upload ['filename'];
			$model = $this->getModel('oer');
			if ($model->store($post,'oer')) 
			{
				$msg = $upload['error'].'<br />'. JText::_( 'UPLOAD_SAVED');
			} else {
				$msg = $uplaod['error'].'<br />'.JText::_( 'ERROR_SAVING_UPLOAD_DB' );
				$msg .= ' ['.$model->getError().'] ';
			}
   			// success, exit with code 0 for Mac users, otherwise they receive an IO Error
   			$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oers'), $msg, 'information');
		}else{
			$mainframe =& JFactory::getApplication();
			$mainframe->setUserState( "oer.post", $post );
			$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&id='.$id), $upload['error'], 'error');
		}	
	
	}
	
	function download() 
	{
		oerHelper::download();
	}
}