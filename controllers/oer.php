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
		
		if (!$user->guest) { // if user logged in showing the list of OERs	
				if (!JRequest::getCmd('view')) {
					JRequest::setVar('view', 'oers');
				}
		} else {
			$this->setredirect(JRoute::_('index.php?option=com_user&view=login'),JText::_('NEEDS_LOGIN')); 
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

function listing()
	{
		$check=$this->loginrequired();
		if (!$check){ //already logged in
		$user =JFactory::getUser(); // get the user object
		$userid= $user->get('id'); // get the logged in user identity 
		$model = $this->getModel('oers'); // set the model
	
        }else{ // if not logged in redirecting to the login page (using the user comp)
		$this->setredirect(JRoute::_('index.php?option=com_user&view=login'),JText::_('NEEDS_LOGIN'));
		}
		parent::display();
	}


	function edit()
	{
		
		JRequest::setVar('view', 'oer');
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('layout', 'form');
		JRequest::setVar('option', 'oer');
        parent::display();
	}
	
	
	
	function cancel()
	{
		JRequest::setVar('view', 'oers');
		JRequest::setVar('controller', 'oer');
		parent::display();
	}
	
	function fileUpload()
	
	{  
       
		jimport('joomla.filesystem.file');		
		$component = JComponentHelper::getComponent('com_oer');
		$params = new JParameter( $component->params );
		$max = ini_get('upload_max_filesize');	    
		$filesAllowed =  $params->def( 'file_types', 'doc,ppt,docx,pdf');
		$file = JRequest::getVar('filedata', null, 'files', 'array'); 
		
		$msg = array();
		unset($msg);
		$msg['return'] = true;
        
        if(isset($file)){ 
                
                $filename = JFile::makeSafe($file['name']);
				$uploadedFileExtension = JFile::getExt($filename);
 
                if($file['size'] > $max*1024*1024) 
				{ 
					$msg ['error'] = JText::_('ONLY_FILES_UNDER').' '.$max;
					$msg ['return'] = false;
				
                }
				
				$validFileExts = explode(',', $filesAllowed);
				print_r($validFileExts);
				//assume the extension is false until we know its ok
				$extOk = false;
				//go through every ok extension, if the ok extension matches the file extension (case insensitive)
				//then the file extension is ok
				foreach($validFileExts as $key => $value)
				{
					$test= "/\b".$value."\b/i";
					echo $test;
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
	$path=JPATH_COMPONENT.DS.'upload'.DS. //To DO set config
	$file=JRequest::getVar('file');
	$file=$path.$file;
	$download=1;
	set_time_limit(0);
	$fext = strtolower(substr(strrchr($file,"."),1));
	$mtype = $this->getmtype($fext);
		if ($download == 1) {	
			header("Pragma:no-cache");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: $mtype");
			header("Content-Transfer-Encoding: binary"); 
			header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
			//$remotesize = $this->remotefsize($file);
			//if (ini_get('allow_url_fopen')) {
		  	//	if (extension_loaded('curl')) {
		  	//		header("Content-Length:". $remotesize);
		  	//	}
			//}
			header("Accept-Ranges: bytes");
			@readfile("$file");
   		exit();
		}
		else {
		header("Location: " .$file);
		}
	}

	function getmtype($fext) 
	{
		$mine_types = array (
		'zip'		=> 'application/zip',
		'pdf'		=> 'application/pdf',
		'doc'		=> 'application/msword',
		'docx'		=> 'application/msword',
		'xls'		=> 'application/vnd.ms-excel',
		'ppt'		=> 'application/vnd.ms-powerpoint',
		'exe' 		=> 'application/octet-stream',
		'gif' 		=> 'image/gif',
		'png'		=> 'image/png',
		'jpg'		=> 'image/jpeg',
		'jpeg'		=> 'image/jpeg',
		'mp3'	=> 'audio/mpeg',
		'wav'		=> 'audio/x-wav',
		'mpeg'	=> 'video/mpeg',
		'mpg'	=> 'video/mpeg',
		'mpe'	=> 'video/mpeg',
		'mov'	=> 'video/quicktime',
		'avi'		=> 'video/x-msvideo'
		);
		if ($mine_types[$fext] == '') {
			$mtype = '';
			if (function_exists('mime_content_type')) {
		  	$mtype = mime_content_type($file);
			}
			else 
			{
		 	 $mtype = "application/force-download";
			}
		}
		else
		{
			$mtype = $mine_types[$fext];
		}
		return $mtype;
	}

		function remotefsize($url)
	{
		ob_start();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		$ok = curl_exec($ch);
		curl_close($ch);
		$head = ob_get_contents();
		ob_end_clean();
		$regex = '/Content-Length:\s([0-9].+?)\s/';
		$count = preg_match($regex, $head, $matches);
		return isset($matches[1]) ? $matches[1] : "unknown";
	}
}
?>