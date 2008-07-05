<?php
// $Id: admission_form.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// bug fix
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_admission_form
//=========================================================
class webphoto_admin_admission_form extends webphoto_form_this
{
	var $_ADMISSION_SEARCH_SIZE = 20;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_admission_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_admission_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// admission
//---------------------------------------------------------
function print_form_admission_search( $txt )
{
	echo $this->build_form_tag( '', $this->_THIS_URL, 'get' );
	echo $this->build_input_hidden( 'fct', 'admission' );
	echo $this->build_input_text( 'txt', $this->sanitize( $txt ), $this->_ADMISSION_SEARCH_SIZE );
	echo $this->build_input_submit( 'submit', _AM_WEBPHOTO_BUTTON_EXTRACT );
	echo $this->build_form_end();

}

function print_list_admission( $photo_rows )
{
	$onclick_all = ' onclick="with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type==\'checkbox\'){elements[i].checked=this.checked;}}}" ';
	$onclick_admin  = ' onclick="document.MainForm.action.value=\'admit\'; submit();" ';
	$onclick_delete = ' onclick="if(confirm(\''. _AM_WEBPHOTO_JS_REMOVECONFIRM .'\')){document.MainForm.action.value=\'delete\'; submit();}" ';

	$url_category = $this->_MODULE_URL.'/index.php?fct=category&amp;p=';
	$url_edit     = $this->_MODULE_URL.'/index.php?fct=edit&amp;photo_id=';

	$img_edit     = $this->build_img_edit();
	$img_deadlink = $this->build_img_deadlink();

	echo $this->build_form_tag( 'MainForm' );
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'action', '' );

	echo "<table width='100%' class='outer' cellpadding='4' cellspacing='1'>";
	echo "<tr valign='middle'>";
	echo "<th width='5'>";
	echo '<input type="checkbox" name="dummy" '. $onclick_all .' />';
	echo "</th>";
	echo "<th></th>";
	echo "<th>"._WEBPHOTO_SUBMITTER."</th>";
	echo "<th>"._WEBPHOTO_PHOTO_TITLE."</th>";
	echo "<th>"._WEBPHOTO_PHOTO_DESCRIPTION."</th>";
	echo "<th>"._WEBPHOTO_CATEGORY."</th>";
	echo "</tr>\n";

// Listing
	foreach ( $photo_rows as $row )
	{
		$oddeven = $this->get_alternate_class();

		$id          = $row['photo_id'] ;
		$title_s     = $this->sanitize( $row['photo_title'] ) ;
		$desc_disp   = $this->_photo_handler->build_show_description_disp( $row ) ;
		$submitter_s = $this->get_xoops_user_name( $row['photo_uid'] );
		$cat_path    = $this->_cat_handler->get_nice_path_from_id(
			$row['photo_cat_id'] , 'cat_title', $url_category, true ) ;
		$link        = $row['photo_cont_url'];

		$editbutton  = '<a href="'. $url_edit . $id .'" target="_blank">';
		$editbutton .= $img_edit;
		$editbutton .= "</a>  ";

		$deadlinkbutton = '';
		if ( !$this->exists_photo( $row ) ) {
			$deadlinkbutton = $img_deadlink;
		}

		echo "<tr>";
		echo "<td class='". $oddeven ."'>";
		echo "<input type='checkbox' name='ids[]' value='". $id ."' />";
		echo "</td>";
		echo "<td class='". $oddeven ."'>". $editbutton .' '. $deadlinkbutton ."</td>";
		echo "<td class='". $oddeven ."'>". $submitter_s ."</td>";
		echo "<td class='". $oddeven ."'>";
		echo "<a href='". $link ."' target='_blank'>". $title_s. "</a>";
		echo "</td>";
		echo "<td class='". $oddeven ."'>". $desc_disp. "</td>";
		echo "<td class='". $oddeven ."'>". $cat_path ."</td>";
		echo "</tr>\n" ;

	}

	echo "<tr>";
	echo "<td colspan='8' align='left'>";
	echo _AM_WEBPHOTO_LABEL_ADMIT;
	echo ' <input type="button" value="'. _AM_WEBPHOTO_BUTTON_ADMIT .'" '. $onclick_admin .' />';
	echo "</td>";
	echo "</tr>\n";
	
	echo "<tr>";
	echo "<td colspan='8' align='left'>";
	echo _AM_WEBPHOTO_LABEL_REMOVE ;
	echo ' <input type="button" value="'. _DELETE .'" '. $onclick_delete .' />';
	echo "</td>";
	echo "</tr>\n";

	echo "</table>\n";

	echo $this->build_form_end();

}

// --- class end ---
}

?>