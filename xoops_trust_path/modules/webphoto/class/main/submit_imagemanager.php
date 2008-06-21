<?php
// $Id: submit_imagemanager.php,v 1.1 2008/06/21 12:22:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit_imagemanager
//=========================================================
class webphoto_main_submit_imagemanager extends webphoto_main_submit
{
	var $_TIME_SUCCESS  = 3;
	var $_TIME_FAIL     = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit_imagemanager( $dirname , $trust_dirname )
{
	$this->webphoto_main_submit( $dirname , $trust_dirname );

	$this->_REDIRECT_URL = $this->_MODULE_URL .'/index.php?fct=close';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance))  {
		$instance = new webphoto_main_submit_imagemanager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->check_submit();

	$this->_print_header();
	$this->_print_form_imagemanager();
	$this->_print_footer();
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _exec_submit()
{
	$photo_tmp_name = null;

	$ret = $this->_check_submit();
	if ( $ret < 0 ) {
		return $ret;
	}

// init uploader if photo file uploaded
	$this->upload_init();

	$ret11 = $this->upload_fetch( $this->_PHOTO_FIELD_NAME );
	if ( $ret11 < 0 ) { 
		return $ret11;	// failed
	} elseif ( $ret11 == 1 ) {
		$photo_tmp_name = $this->upload_tmp_name();
		$this->overwrite_photo_title_by_media_name_if_empty();
	}

	if ( empty($photo_tmp_name) ) {
		return _C_WEBPHOTO_ERR_NO_IMAGE;
	}

	return $this->_add_to_handler( $photo_tmp_name, null );
}

//---------------------------------------------------------
// overwrite by submit_imagemanager
//---------------------------------------------------------
function submit_success()
{
	redirect_header( $this->_REDIRECT_URL, $this->_TIME_SUCCESS , $this->get_constant('SUBMIT_RECEIVED') ) ;
}

function check_xoops_upload_file_submit()
{
	return $this->check_xoops_upload_file( false );
}

//---------------------------------------------------------
// print_header
//---------------------------------------------------------
function _print_header()
{
	echo "<html><head>\n";
	echo "<title>". $this->get_constant('TITLE_PHOTOUPLOAD') ."</title>\n";
	echo '<link rel="stylesheet" type="text/css" media="all" href="'. XOOPS_URL .'/xoops.css" />'."\n";
	echo '<link rel="stylesheet" type="text/css" media="all" href="'. XOOPS_URL .'/modules/system/style.css" />'."\n";
	echo '<link rel="stylesheet" type="text/css" media="all" href="'. $this->_MODULE_URL .'/libs/default.css" />'."\n";
	echo '<meta http-equiv="content-type" content="text/html; charset='. _CHARSET .'" />'."\n";
	echo '<meta http-equiv="content-language" content="'. _LANGCODE .'" />'."\n";
	echo "</head>\n" ;
	echo "<html>\n" ;
	echo '<div class="webphoto_imagemanager">'."\n";
}

function _print_footer()
{
	echo '<div class="webphoto_close">';
	echo '<input value="'. _CLOSE .'" type="button" onclick="javascript:window.close();" />';
	echo "</div>\n";

	echo "</div>\n";
	echo "</body></html>" ;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form_imagemanager()
{
	$form_class =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$row = $this->_get_photo_default();

	$form_class->print_form_imagemanager( $row, null );
}

// --- class end ---
}

?>