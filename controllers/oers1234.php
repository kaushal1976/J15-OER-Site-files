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

class oerControllerOers extends oerController
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
	
        }else{ // if not logged in redirecting to the login page (using the user extended comp)
		$this->setredirect(JRoute::_('index.php?option=com_user&view=login'),JText::_('NEEDS_LOGIN'));
		}
		parent::display();
	}


	function edit()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		JRequest::setVar('view', 'oer');
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('layout', 'form');
		JRequest::setVar('option', 'oer');
        parent::display();
	}
	
	
	}
	
	function cancel()
	{
		JRequest::setVar('view', 'oers');
		JRequest::setVar('controller', 'oer');
		JRequest::setVar('layout', 'listing');
		JRequest::setVar('option', 'oer');
        parent::display();
	}
	
	function save()
	{
	JRequest::checkToken() or jexit( 'Invalid Token' );	
	$component = JComponentHelper::getComponent('com_oer');
	$post = JRequest::get( 'post' );
	$objname=$post['objname'];
	$paperid=JRequest::getInt('id');
	$params = new JParameter( $component->params );
	if ($paperid=='' || $paperid==0 || $mode==''){
		jexit('You do not have authority to perform this task');
	}
	//import joomlas filesystem functions, we will do all the filewriting with joomlas functions,
	//so if the ftp layer is on, joomla will write with that, not the apache user, which might
	//not have the correct permissions
	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');
	//this is the name of the field in the html form, filedata is the default name for swfupload
	//so we will leave it as that
	$fieldName = $objname;
 	//any errors the server registered on uploading
	$fileError = $_FILES[$fieldName]['error'];
	if ($fileError > 0) 
	{
        switch ($fileError) 
        {
        case 1:
		$msg=JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' );
        $this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
		return;
 
        case 2:
        $msg=JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' );
		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
        return;
 
        case 3:
        $msg=JText::_( 'ERROR PARTIAL UPLOAD' );
		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
        return;
 
        case 4:
       	$msg=JText::_( 'ERROR NO FILE' );
		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
        return;
        }
	}
	//check for filesize
	$fileSize = $_FILES[$fieldName]['size'];
	if($fileSize > 10000000)
	{
   $msg= JText::_( 'FILE_BIGGER_THAN_10MB' );
   $this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error'); 
   return;
	}
	//check the file extension is ok
	$fileName = $_FILES[$fieldName]['name'];
	$uploadedFileNameParts = explode('.',$fileName);
	$uploadedFileExtension = array_pop($uploadedFileNameParts);
	$filesAllowed =  $params->def( 'file_types', 'doc, ppt');
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
        $msg= JText::_( 'INVALID EXTENSION' );
		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
        return;
	}
 	//the name of the file in PHP's temp directory that we are going to move to our folder
	$fileTemp = $_FILES[$fieldName]['tmp_name'];
	//for security purposes, we will also do a getimagesize on the temp file (before we have moved it 
	//to the folder) to check the MIME type of the file, and whether it has a width and height
	//lose any special characters in the filename
	
		$namefirstpart = $paperid;
		
	$fileName = $namefirstpart."_".time().".".$uploadedFileExtension;
	//always use constants when making file paths, to avoid the possibilty of remote file inclusion
	$uploadPath = JPATH_SITE.DS.'components'.DS.'com_oer'.DS.'upload'.DS.$fileName;
	if(!JFile::upload($fileTemp, $uploadPath)) 
	{
        $msg= JText::_( 'ERROR MOVING FILE' );
		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
        return;
	}else{
		$post[$fieldName]=$fileName;
		$model = $this->getModel('oer');
		if ($model->store($post,'oer')) {
			$msg = JText::_( 'UPLOAD_SAVED');
			} else {
			$msg = JText::_( 'ERROR_SAVING_UPLOAD_DB' );
			$msg .= ' ['.$model->getError().'] ';
		}
   // success, exit with code 0 for Mac users, otherwise they receive an IO Error
   		$this->setRedirect(JRoute::_('index.php?option=com_oer&controller=oer&view=oer&layout=form&paperid='.$paperid), $msg, 'error');
	}
	
	
	function paperremove()
	{	
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$post = JRequest::get( 'post' );
		$paperid=$post['paperid'];
		$model = $this->getModel('oer');
		$archieve=confmgtHelper::paperdetails($paperid);
		$paperrem=$model->paperremove($paperid);
		$msg=JText::_( 'PAPER_REMOVED');
		$archieve_post['oldpaperid']=$paperid;
		$archieve_post['uid'] = $archieve->uid;
		$archieve_post['linkid'] = $archieve->linkid;
		$archieve_post['user'] = $archieve->user;
		$archieve_post['abstract'] = $archieve->abstract;
		$archieve_post['date'] = $archieve->date;
		$archieve_post['fullpaper'] = $archieve->fullpaper;
		$archieve_post['uname'] = $archieve->uname;
		$archieve_post['register'] = $archieve->register;
		$archieve_post['abreview2'] = $archieve->abreview2;
		$archieve_post['title'] = $archieve->title;
		$archieve_post['authors'] = $archieve->authors;
		$archieve_post['payid '] = $archieve->payid;
		$archieve_post['invoice'] = $archieve->invoice;
		$archieve_post['rev1'] = $archieve->rev1;
		$archieve_post['rev2'] = $archieve->rev2;
		$archieve_post['rev_date'] = $archieve->rev_date;
		$archieve_post['cameraready'] = $archieve->cameraready;
		$archieve_post['executive'] = $archieve->executive;
		$archieve_post['themes'] = $archieve->themes;
		$archieve_post['keywords'] = $archieve->keywords;
		$archieve_post['presenter'] = $archieve->presenter;
		$archieve_post['biography'] = $archieve->biography;
		$archieve_post['fullpaperreviewoutcome'] = $archieve->fullpaperreviewoutcome;
		$archieve_post['abreviewoutcome'] = $archieve->abreviewoutcome;
		$archieve_post['ppt'] = $archieve->ppt;
		$archieve_post['student'] = $archieve->student;
		$archieve_post['recall'] = $archieve->recall;
		$archieve_post['payonarrival'] = $archieve->payonarrival;
		$archieve_post['ppt'] = $archieve->ppt;
		$archieve_post['id']='';
		$model->store($archieve_post,'archieve');
		JRequest::setVar('view', 'confmgt');
		JRequest::setVar('controller', 'confmgt');
		JRequest::setVar('layout', 'statuslist'); 
		parent::display();
	}
	
	
	function download() 
	{
	$path=JPATH_SITE.DS.'components'.DS.'com_confmgt'.DS.'upload'.DS; //To DO set config
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