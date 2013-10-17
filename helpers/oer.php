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

// Component Helper

jimport('joomla.application.component.helper');

class confmgtHelper

//filters
{

	function listfilter($query, $active=NULL, $name,$js='') 
	{
		$database = & JFactory::getDBO();
		$database->setQuery( $query );
		$out = $database->loadObjectList() ;
		$output = JHTML::_('select.genericlist', $out, $name, 'class="inputbox"'.$js, 'value', 'text', $active);
		return $output;
	}
	
//papers

	function numofpapers($userid)
	{
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_main ".
		"WHERE user=".$userid;
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	return $num_rows;
	}
	
	function theme($themeid)
	{
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_themes ".
		"WHERE id = ".$themeid.
		" LIMIT 1";
		$db->setQuery( $query );
		$data = $db->loadObject();
 	   	return $data;
	}
	
	function listofpapers($userid)
	{
		if ($userid > 0) {
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_main ".
		"WHERE user=".$userid;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
 	   	return $rows;
		}else{ 
		return false;
		}
	}
	
	
	function fullauthordetails($paperid)
	{
		$paperdetails = $this->paperdetails($paperid,0);
		if ($paperdetails->linkid >0) {
			$paperlinkid = $paperdetails->linkid;
		}else{
			$paperlinkid = $paperid;
		}
		
		
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_fullauthors ".
		"WHERE paperid=".$paperlinkid." ORDER BY id ASC";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
 	   	return $rows;

	}
	
	
	function listofpapers_coordinator($userid)
	{
		if ($userid > 0) {
		$db =& JFactory::getDBO();
 		$query = "SELECT a.*, b.id as bid, b.main_coordinator ".
		"FROM #__confmgt_main AS a, ".
		"#__confmgt_themes AS b ".
		"WHERE a.themes=b.id AND b.main_coordinator=".$userid; 
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
 	   	return $rows;
		}else{ 
		return false;
		}
	}
	
	function paperdetails($paperid, $userid=0)
	{
		if ($paperid > 0) {
		$db =& JFactory::getDBO();
 		/*
		$query1 = "SELECT u.id AS uid, u.name AS uname, m.* ".
		"FROM #__users AS u, #__confmgt_main AS m ".
		"WHERE m.id=".$paperid." AND m.user=u.id";
		$query2=" AND m.user=".$userid;
		*/
		$query1 = "SELECT u.id AS uid, u.name AS uname, m.*, c.description as cdes, d.description as ddes ".
		"FROM #__users AS u, #__confmgt_main AS m ".
		"LEFT JOIN #__confmgt_outcomes as c ".
		"ON m.abreviewoutcome = c.id ".
		"LEFT JOIN #__confmgt_outcomes as d ".
		"ON m.fullpaperreviewoutcome = d.id ".
		"WHERE m.id=".$paperid." AND m.user=u.id";
		$query2=" AND m.user=".$userid;
	
		if ($userid==0){
			$query=$query1;
		}else{
			$query=$query1;
		}
		$db->setQuery( $query );
		$db->query();
		$row = $db->loadObject();
		return $row;
		}else{ 
		return false;
		}
	}
	
///

	function regstatus($userid)
	{
		$db =& JFactory::getDBO();
 		/*
		$query1 = "SELECT u.id AS uid, u.name AS uname, m.* ".
		"FROM #__users AS u, #__confmgt_main AS m ".
		"WHERE m.id=".$paperid." AND m.user=u.id";
		$query2=" AND m.user=".$userid;
		*/
		$query1 = "SELECT u.order_user_id AS uid, u.order_status as status, u.order_number as unumber, a.order_product_name as pname, b.user_id as huid, b.user_cms_id as cuid ".
		"FROM #__hikashop_order AS u, #__hikashop_order_product AS a, #__hikashop_user AS b  ".
		"WHERE b.user_cms_id=".$userid." AND a.order_product_code='full_registration' AND u.order_user_id=b.user_id  ";
		//echo $query1;
		$db->setQuery( $query1 );
		$rows = $db->loadObjectList();
		return $rows;
	}
	
///	
	
	
	
	
	
//revs
	
	function numofrevsforpaper($paperid) // Number of reviewers for a paper 
	{
		$db =& JFactory::getDBO();
 		$query = "SELECT a.id AS fa_id, a.revid, a.paperid, a.date, b.*  ".
		"FROM #__confmgt_reviewers_papers AS a, #__confmgt_reviewers AS b ".
		"WHERE a.revid=b.id ".
		"AND a.paperid=".$paperid;
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	return $num_rows;
	}
	
// buttons
	
	function homeButton($paperid=0, $type=1)
	{
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='home'>Home</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='home' value='Home'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."home_16.png' name='home' alt='Home' title='Home'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='form1".
		$paperid."' name='form1".
		$paperid."' method='post' action='".JRoute::_('index.php')."'>".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
		
	
	function rollBtn($type = 1)
	{
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='home'>Change roll</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='Change roll'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."home_16.png' name='roll' alt='Change roll' title='Change roll'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='rollBtn' name='rollBtn' method='post' action='".JRoute::_('index.php')."'>".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='confmgt' type='hidden'/>". 
		"<input name='view' value='confmgt' type='hidden'/>".
		"<input name='layout' value='status' type='hidden'/>".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
		return $html;
	}
	
	function cancelButton($name, $type=1) 
	{
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='button'  name=\"formbtn\" class='genBtn' onclick='history.go(-1)'>".
			"<span class='cancel'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='button' class='button' name='roll' value='".$name."' onclick='history.go(-1)'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"home_16.png' name='roll' alt='".$name."' title='".$name."' onclick='history.go(-1)'/>"; 
		}
			
		$html="<div class='btnFloatLeft'>".
		$button.
		"</div>";
		return $html; 
	}
	
	function listButtonConfmgt($name,$layout='statuslist', $type=1)
	{
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='back'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"back_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
		
	$html="<div class='btnFloatLeft'><form id='listbuttonconfmgt' name='listbuttonconfmgt' method='post' action='".
	JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout='.$layout)."'> ".
	$button.
	JHTML::_( 'form.token' ).	
	"</form></div>";
	return $html;
	} 
	
	
	function listButtonRevs($name,$layout='papersforrevs', $type=1)
	{
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='user_list'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"users_two_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
		
	$html="<div class='btnFloatLeft'><form id='listbutton' name='listbutton' method='post'".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout='.$layout)."' > ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	} 
	
	
	function saveBtn($name='Save', $action='save', $type=1)
	{
		//$action = "'".$action."'";
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button="<button type=\"button\"  name=\"submitbtn\" class=\"genBtn\" onclick=\"submitbutton('".$action."')\">".
			"<span class=\"new\">".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='button' class='button' name='roll' value='".$name."' onclick=submitbutton('".$action."')/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"add_16.png' name='roll' alt='".$name."' title='".$name."' onclick=submitbutton('".$action."')/>"; 
		}
		
	$html="<div class='btnFloatLeft'>".
	$button.
	"</div>";
	return $html;
	}
	
	
	function cancelBtn($name='Cancel', $action='cancel', $type=1)
	{
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='button'  name='cancelbtn' class='genBtn' onclick='history.go(-1)'>".
			"<span class='cancel'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='button' class='button' name='cancelbtn' value='".$name."' onclick='history.go(-1)'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"cancel_16.png' name='cancelbtn' alt='".$name."' title='".$name."' onclick='history.go(-1)';/>"; 
		}
		
	$html="<div class='btnFloatLeft'>".
	$button.
	"</div>";
	return $html;
	}
	
// forms

	function reAbstract($paperid, $name='Edit the Absrtact', $type=1) { //Abstract edit button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	$html="<div class='btnFloatLeft'><form id='reabstract' name='reabstract' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&task=abstract_edit')."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='mode' value='edit' />".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	
	function ab_Resubmit($paperid, $name='Resubmit the Absrtact', $type=1) { //Abstract resubmit button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	$html="<div class='btnFloatLeft'><form id='reabstract' name='reabstract' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&task=ab_resubmit')."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function ab_Resubmitform($paperid, $name='Re-submit the Absrtact', $type=1) { //Abstract resubmit button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	$html="<div class='btnFloatLeft'><form id='reabstract' name='reabstract' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=abresubmitform&id='.$paperid.'&mode=1')."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function full_Resubmitform($paperid, $name='Re-submit the full paper', $type=1) { //Abstract resubmit button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	$html="<div class='btnFloatLeft'><form id='reabstract' name='reabstract' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=full_reupload&id='.$paperid)."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function repractice($paperid) { //Abstract edit button
	$html="<div class='btnFloatLeft'><form id='reabstract' name='reabstract' method='post' ".
	"action='index.php?option=com_confmgt&controller=confmgt&view=confmgt&task=practice_edit'>" .
	"<div style='float:left'>".
	"<input type='submit' class='button' name=\"formbtn\" value='Edit' /> ".
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='mode' value='edit' />".
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function userFull($paperid, $name='Full paper submission', $from='full', $type=1) { //Full paper submission button
	
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	
	
	$html="<div class='btnFloatLeft'><form id='userfull' name='userfull' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=authlist')."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='from' value='".$from."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	

	function usercamera($paperid, $name='Camera ready paper submission', $type=1) { //Camera ready submission button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type == 1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	
	$html="<div  class='btnFloatLeft'><form id='usercamera' name='usercamera' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=upload')."' > " .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='mode' value='camera' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	
	}
	
	
	function pres($paperid, $name='Presentation submission', $type=1) { //Presentation submission button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	
	$html="<div  class='btnFloatLeft'><form id='pres' name='pres' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=upload')."'>" .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='mode' value='ppt' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function pd_co($paperid, $name, $type=3) { //paper details for coodinators
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else if ($type =3 ){
			$button= "<input type='submit' class='submitLink' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}
	
	$html="<div  class='btnFloatLeft'><form id='pd_co$paperid' name='pd_co$paperid' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout=pd_co')."'>" .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function revAssign($paperid, $name, $type=3) { //paper details for coodinators
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else if ($type == 3){
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		
		}else{
			$button= "<input type='submit' class='submitLink' name='roll' value='".$name."'/>";
		}
	
	$html="<div class='btnFloatLeft'><form id='revassign$paperid' name='revassign$paperid' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout=revallocationform')."'>" .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function co_revs($paperid, $name='Post', $mode='abstract', $type=3) { //paper details for coodinators
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else if ($type = 3){
			$button= "<input type='submit' class='submitLink' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}	
	
	$html="<div class='btnFloatLeft'><form id='corev$paperid' name='corev$paperid' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout=corevpost&paperid=$paperid')."'>" .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='postmode' value='$mode' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	return $html;
	}
	
	function revs($paperid, $name='Post', $mode='abstract', $type=5) { //paper details for coodinators
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else if ($type == 3){
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}else if ($type == 4) {
			$button= "<input type='submit' style='background:none;border:0;colour:black; text-decoration:underline; cursor:pointer; left-margin:1px;' name='roll' value='".$name."'/>";
		}
				
	
	$html="<div class='btnFloatLeft'><form id='rev$paperid' name='rev$paperid' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout=revpost')."'>" .
	"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
	"<input type='hidden' class='button' name='postmode' value='$mode' /> ".
	$button.
	JHTML::_( 'form.token' ).
	"</form></div>";
	
	$html1 = "<a href=".JRoute::_('index.php?option=com_confmgt&controller=revs&view=revs&layout=revpost&paperid='.$paperid.'&postmode='.$mode).">"."Post"."</a>";
	
	if ($type ==5) {
		return $html1;
	}else{
	
	return $html;
	}
	}
	
	function editRevs($revid, $mode='edit', $type=3, $name='Edit') { //paper details for coodinators
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='list_paper'>".$name."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='roll' value='".$name."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath.
			"paper_content_pencil_16.png' name='roll' alt='".$name."' title='".$name."'/>"; 
		}	
	
	
	$html="<div class='btnFloatLeft'><form id='rev$revid' name='rev$revid' method='post' ".
	"action='".JRoute::_('index.php?option=com_confmgt&amp;controller=revs&amp;view=revs&amp;layout=revform')."'>" .
	"<input type='hidden' class='button' name='revid' value='".$revid."' /> ".
	"<input type='hidden' class='button' name='mode' value='$mode' /> ".
	JHTML::_( 'form.token' ).
	$button.
	"</form></div>";
	return $html;
	}
	
// abstract PDF

	function abstractPDF($paperid) {
	$html="<a href='index.php?option=com_confmgt&amp;controller=".
	"confmgt&amp;view=confmgt&amp;layout=abview&amp;format=pdf&amp;paperid=" .
	$paperid.
	"' > ".
	JText::_('ABSTRACT').
	"</a>";
	return $html;
	} 
	
	function abstractrePDF($paperid) {
	$html="<a href='index.php?option=com_confmgt&amp;controller=".
	"confmgt&amp;view=confmgt&amp;layout=abreview&amp;format=pdf&amp;paperid=" .
	$paperid.
	"' target='_blank'> ".
	JText::_('FULL_ABSTRACT_REVIEW').
	"</a>";
	return $html;
	}
	
	function fullrePDF($paperid) {
	$html="<a href='index.php?option=com_confmgt&amp;controller=".
	"confmgt&amp;view=confmgt&amp;layout=fullreview&amp;format=pdf&amp;paperid=" .
	$paperid.
	"' target='_blank'> ".
	JText::_('FULL_PAPER_REVIEW').
	"</a>";
	return $html;
	}
	
	function downloadF($fileN, $linkN) {
	$html="<a href='index.php?option=com_confmgt&amp;controller=confmgt&amp;task=download&amp;file=".
	$fileN.
	"' target='_blank'> ".
	JText::_($linkN).
	"</a>";
	return $html;
	}
	
	function showimgext($img_folder, $ext) {
	$html="<img src= '"
	.$img_folder.DS;
		if ($ext=='.pdf') {
			$html=$html.'pdf.gif';
		}else if (($ext=='.doc') || ($ext=='.docx')) {
			$html=$html.'word.gif';
		}else if (($ext=='.ppt') || ($ext=='.pptx')) {
			$html=$html.'ppt.gif';
		}else{ 
			$html=$html.'other.gif';
		}
     $html=$html."' alt='".
	 $ext.
	 "' width='16' height='16'/>";
	 return $html;
	}
	
	function allrevsposted($paperid, $mode='abstract') // # All reviews posted for a paper
	{
		$db =& JFactory::getDBO();
 		$query = "SELECT a.*, b.* ". 
		"FROM #__confmgt_reviews AS a ".
		"LEFT JOIN #__confmgt_outcomes AS b ".
		"ON a.outcome=b.id ".
		"WHERE a.mode='".$mode."' AND a.paperid=".$paperid;  
		$db->setQuery( $query );
		$db->query();
		$rows = $db->LoadObjectList();
 	   	return $rows;
	}
	
		function fullAuthForPaper($paperid) // # All full authors for a paper
	{
		$db =& JFactory::getDBO();
 		$query = "SELECT * ". 
		"FROM #__confmgt_fullauthors ".
		"WHERE paperid=".$paperid;  
		$db->setQuery( $query );
		$db->query();
		$rows = $db->LoadObjectList();
 	   	return $rows;
	}
	
	function fullAuth ($authid, $mode, $paperid, $type=3) { //Full authors edit button
	
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ($mode == 'Add') {
			$img = 'add_16.png';
			$class = 'new';
			$txt = 'Add Author';
			$type=1;
		}else if ($mode == 'Edit'){
			$img = 'pencil_16.png';
			$class = 'edit';
			$txt = 'Edit Author';
		}
		if ( $type==1 ) {
			$button= "<button type='submit'  name='authEdit' class='genBtn'>".
			"<span class='".$class."'>".$txt."</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='authEdit' value='".$mode."'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."".$img."' name='edit' alt='".$txt."' title='".$txt."' '/>";
		}
	
		$html="<div class='btnFloatLeft'><form id='auth".$authid.$mode."' name='auth".$authid.$mode."' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=fullauth').">" .
		"<input type='hidden' name='mode' value='".$mode."' /> ".
		"<input type='hidden' name='authid' value='".$authid."' /> ".
		"<input type='hidden' name='paperid' value='".$paperid."' /> ".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
		return $html;
	}
	
	function authDel ($formname, $authid, $paperid, $type=3) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='authDel' class='genBtn' onclick='deleteconfirmation(".$formname.");'>".
			"<span class='delete'>Delete author</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='upload' value='Upload file' onclick='deleteconfirmation(".$formname.");'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."cancel_16.png' name='upload' alt='Delete Author' title='Delete Author' onclick='deleteconfirmation(".$formname.");'/>";
		}
		
		$html="<div class='btnFloatLeft'><form id='formdel".$formname."' name='formdel".$formname."' method='post'".
		"action=".JRoute::_('index.php' )." onsubmit='return false;'> ".
		"<input name='option' value='com_confmgt' type='hidden'/> ".
		"<input name='controller' value='confmgt' type='hidden'/> ".
		"<input name='task' value='authdel' type='hidden'/> ".
		$button.
		"<input type='hidden' name='authid' value=".$authid." /> ".
		"<input type='hidden' name='paperid' value='".$paperid."' /> ".
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
	
	function upload ($paperid, $mode, $type=1) {
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='upload' class='genBtn'>".
			"<span class='upload'>Upload file</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='upload' value='Upload file'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."box_upload_16.png' name='upload' alt='Upload file' title='Upload file'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='formupload".$paperid."' name='formupload".$paperid."' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=upload')."> ".
		"<input type='hidden' class='button' name='paperid' value='".$paperid."' /> ".
		"<input type='hidden' class='button' name='mode' value='".$mode."' /> ".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
		return $html;
		}
	
	function rememailBtn ($revuserid,$fromid,$revid=0, $type=3) {
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='button'  class='genBtn' name='remind' value='Remind email' onclick='deleteconfirmation(".$revid.");'>".
			"<span class='mail'>Remind email</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='button' class='button' name='remind' value='Remind email' onclick='deleteconfirmation(".$revid.");' /> ";
		}else{
			$button= "<input type='image' src='".$iconpath."mail_16.png' name='newRev' alt='Remind email' title='Remind Email' onclick='deleteconfirmation(".$revid.");'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='rememail".$revid."' name='rememail".$revid."' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&controller=revs&amp;task=rememail' )." onsubmit='return false;'> ".
		"<input type='hidden' class='button' name='revid' value='".$revid."' /> ".
		"<input type='hidden' class='button' name='revuserid' value='".$revuserid."' /> ".
		"<input type='hidden' class='button' name='fromid' value='".$fromid."' /> ".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
		return $html;
	}	
	
	function revListBtn ($name, $type=1) {
		
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='listRev' class='genBtn'>".
			"<span class='user_list'>Reviewers List</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='listrev' value='List'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."users_two_16.png' name='newRev' alt='Reviewer list' title='Reviewer list'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='addRev' name='addRev".
		"method='post' action=".JRoute::_('index.php').">".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='rev' type='hidden'/>".
		"<input name='view' value='revs' type='hidden'/>".
		"<input type='hidden' name='layout' value='revlist' />".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}	
	
	function addRevBtn ($name, $type=1) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='addRev' class='genBtn'>".
			"<span class='new'>New Reviewer</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='newRev' value='New'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."add_16.png' name='newRev' alt='New Reviewer' title='New Reviewer'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='addRev' name='addRev".
		"method='post' action=".JRoute::_('index.php').">".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='rev' type='hidden'/>".
		"<input name='view' value='revs' type='hidden'/>".
		"<input type='hidden' name='layout' value='revform' />".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
	
	
	function authBtn ($type) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		$params = new JParameter( $component->params );
		if ( $type==1 ) {
			$button= "<button type='submit'  name='auth' class='rollselect'>".
			"<img src = '".$iconpath."user_48.png'".
			"alt='Author'/> ".
			"Author". 
			"</button>";
		}else{
			$button= "<input type='submit' name='auth' value='Author' class='button' /> ";
		}
		$html="<div><form id='auth' name='auth' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&controller=confmgt&view=confmgt&layout=statuslist')."> ".
		"<div style='float:left' class='button_no_border'>".
		$button.
		JHTML::_( 'form.token' ).
		"</div>".
		"</form></div>";
	return $html;
	}
	
	function regBtn ($type=1) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		$params = new JParameter( $component->params );
		if ( $type==1 ) {
			$button= "<button type='submit'  name='regI' class='genBtn'>".
			"<img src = '".$iconpath."add_16.png'".
			"alt='Register - International'/> ".
			"Register - International". 
			"</button>";
		}else{
			$button= "<input type='submit' name='regI' value='Register - International' class='button' /> ";
		}
		$html="<div><form id='regI' name='regI' method='post' ".
		"action=".JRoute::_('index.php?option=com_hikashop&ctrl=product&task=listing&Itemid=33')."> ".
		"<div style='float:left' class='button_no_border'>".
		$button.
		JHTML::_( 'form.token' ).
		"</div>".
		"</form></div>";
	return $html;
	}
	
	function regLocalBtn ($type=1) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		$params = new JParameter( $component->params );
		if ( $type==1 ) {
			$button= "<button type='submit'  name='auth' class='genBtn'>".
			"<img src = '".$iconpath."add_16.png'".
			"alt='Register - Local'/> ".
			"Register - Local". 
			"</button>";
		}else{
			$button= "<input type='submit' name='regL' value='Register - Local' class='button' /> ";
		}
		$html="<div><form id='regl' name='regl' method='post' ".
		"action=".JRoute::_('index.php?option=com_content&view=article&id=26&Itemid=34')."> ".
		"<div style='float:left' class='button_no_border'>".
		$button.
		JHTML::_( 'form.token' ).
		"</div>".
		"</form></div>";
	return $html;
	}
	
	function revBtn ($type) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		$params = new JParameter( $component->params );
		if ( $type==1 ) {
			$button= "<button type='submit'  name='rev' class='rollselect'>".
			"<img src = '".$iconpath."paper_content_pencil_48.png'".
			"alt='Reviewer'/> ".
			"Reviewer".
			"</button>";		
			
		}else{
			$button= "<input type='submit' name='rev' value='Reviewer' class='button' /> ";
		}
		$html="<div><form id='rev' name='rev' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&controller=revs&amp;view=revs&amp;layout=papersforrevs').">".
		"<div style='float:left' class='button_no_border'> ".
		$button.
		JHTML::_( 'form.token' ).
		"</div>".
		"</form></div>";
	return $html;
	}
	
	function coordBtn ($type) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			
			$button= "<button type='submit'  name='coord' class='rollselect'>".
			"<img src = '".$iconpath."paper_content_chart_48.png'".
			"alt='Theme Leader'/> ".
			"Theme Leader".
			"</button>";
			
		}else{
			$button= "<input type='submit' name='coord' value='Theme leader' class='button' /> ";
		}
		$html="<div><form id='coord' name='coord' method='post' ".
		"action=".JRoute::_('index.php?option=com_confmgt&amp;controller=confmgt&amp;view=confmgt&amp;layout=coordinatorlist').">".
		"<div style='float:left' class='button_no_border'> ".
		$button.
		JHTML::_( 'form.token' ).
		"</div>".
		"</form></div>";
	return $html;
	}
	
	function detailsBtn ($paperid,$type=3) { //status list form details button
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='details'>Details</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name=\"formbtn\" value='View details' />";
		}else{
			$button= "<input type='image' src='".$iconpath."paper_content_pencil_16.png' name=\"formbtn\" alt='Details' title='Details' />";
		}
		$html="<div class='btnFloatLeft'><form id='formdetails' method='post'".
		$paperid."' name='formdetails".
		$paperid."' action=".JRoute::_('index.php').">".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='confmgt' type='hidden'/>".
		"<input name='view' value='confmgt' type='hidden'/>".
		"<input name='layout' value='status' type='hidden'/>".
		"<input type='hidden' name='paperid' value=".$paperid." />".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
	
	function paperDelBtn ($paperid,$type=3) { //status list form paper delete button
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='del' class='genBtn' onclick='deleteconfirmation(".$paperid.");'>".
			"<span class='delete'>Delete</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='del' value='Delete' onclick='deleteconfirmation(".$paperid.");' />";
		}else{
			$button= "<input type='image' src='".$iconpath."cancel_16.png' name='del' alt='Delete' title='Delete' onclick='deleteconfirmation(".$paperid.");' />";
		}
		$html="<div class='btnFloatLeft'><form id='formdel".
		$paperid."' name='formdel".
		$paperid."' method='post' action=".JRoute::_('index.php')." onsubmit='return false;'>".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='confmgt' type='hidden'/>".
		"<input name='task' value='paperremove' type='hidden'/>".
		"<input type='hidden' class='button' name='paperid' value=".$paperid." />".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
	
	function newAbsBtn ($paperid=0,$type=1) { //submit new abstract button
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name='addAbs' class='genBtn'>".
			"<span class='new'>New Abstract</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name='newAbs' value='New'/>";
		}else{
			$button= "<input type='image' src='".$iconpath."add_16.png' name='newAbs' alt='New Abstract' title='New Abstract'/>";
		}
			
		$html="<div class='btnFloatLeft'><form id='form1".
		$paperid."' name='form1".
		$paperid."' method='post' action=".JRoute::_('index.php').">".
		"<input name='option' value='com_confmgt' type='hidden'/>".
		"<input name='controller' value='confmgt' type='hidden'/>".
		"<input name='view' value='confmgt' type='hidden'/>".
		"<input type='hidden' name='layout' value='abform' />".
		$button.
		JHTML::_( 'form.token' ).
		"</form></div>";
	return $html;
	}
		
	
	function revsForPaper($paperid) // A list of reviewers for a paper 
	{
		$user =& JFactory::getUser();
		$userid=$userid= $user->get('id');
		$db =& JFactory::getDBO();
 		$query = "SELECT a.id AS fa_id, a.revid, a.paperid, a.date, b.*  ".
		"FROM #__confmgt_reviewers_papers AS a, #__confmgt_reviewers AS b ".
		"WHERE a.revid=b.id ".
		"AND a.paperid=".$paperid;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
 	   	return $rows;
	}
	
	// roll check
	
	function isGenRev()
	{
		$user =& JFactory::getUser();
		$userid= $user->get('id');
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_reviewers ".
		"WHERE userid=".$userid; 
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	if ($num_rows<>0) {
			return true;
		}else{
			return false;
		}
	}
	
	function isGenCoord()
	{
		$user =& JFactory::getUser();
		$userid= $user->get('id');
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_themes ".
		"WHERE main_coordinator=".$userid; 
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	if ($num_rows<>0) {
			return true;
		}else{
			return false;
		}
	}
	
	function isGenAuth()
	{
		$user =& JFactory::getUser();
		$userid= $user->get('id');
		$db =& JFactory::getDBO();
 		$query = "SELECT * ".
		"FROM #__confmgt_main ".
		"WHERE user=".$userid; 
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	if ($num_rows<>0) {
			return true;
		}else{
			return false;
		}
	}
	
	function isCoord($paperid) // revise
	{
		$user =& JFactory::getUser();
		$userid= $user->get('id');
		$db =& JFactory::getDBO();
 		$query = "SELECT a.id, a.themes, b.id, b.main_coordinator ".
		"FROM #__confmgt_main AS a, ".
		"#__confmgt_themes AS b ".
		"WHERE a.id=".$paperid." ".
		"AND a.themes=b.id AND b.main_coordinator=".$userid; 
		$db->setQuery( $query );
		$db->query();
		$num_rows = $db->getNumRows();
 	   	return $num_rows;
	}
	
	
	function Btn($type,$class,$text, $img) {
		$iconpath=JURI::base( true ).DS.'components'.DS.'com_confmgt'.DS.'assets'.DS.'images'.DS.'icons'.DS;
		if ( $type==1 ) {
			$button= "<button type='submit'  name=\"formbtn\" class='genBtn'>".
			"<span class='details'>Details</span>".
			"</button>";
		}else if ($type == 2){
			$button= "<input type='submit' class='button' name=\"formbtn\" value='View details' />";
		}else{
			$button= "<input type='image' src='".$iconpath."add_16.png' name=\"formbtn\" alt='Details' title='Details' />";
		}
		return $button;
	}

	
}
?>