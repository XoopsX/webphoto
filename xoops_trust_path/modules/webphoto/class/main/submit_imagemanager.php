<?php
// $Id: submit_imagemanager.php,v 1.4 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// webphoto_photo_action
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used upload_fetch_photo()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit_imagemanager
//=========================================================
class webphoto_main_submit_imagemanager extends webphoto_photo_action
{
	var $_THIS_CLOSE_FCT  = 'close';
	var $_THIS_CLOSE_URL ;

	var $_TIME_SUCCESS = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit_imagemanager( $dirname , $trust_dirname )
{
	$this->webphoto_photo_action( $dirname , $trust_dirname );

	$this->_THIS_CLOSE_URL  = $this->_MODULE_URL .'/index.php?fct='. $this->_THIS_CLOSE_FCT ;
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
	$this->get_post_param();

	$ret = $this->submit_check();
	if ( !$ret ) {
		redirect_header( 
			$this->get_redirect_url() , 
			$this->get_redirect_time() ,
			$this->get_redirect_msg()
		) ;
		exit();
	}

	$op = $this->_post_class->get_post_text( 'op' );
	switch ( $op ) 
	{
		case 'submit':
			$this->_submit();
			break;
	}

	$this->_print_header();
	$this->_print_form_imagemanager();
	$this->_print_footer();
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$is_failed = false;

// exit if error
	$this->check_token_and_redirect( $url, $this->_TIME_FAILED );

	$this->set_flag_fetch_allow_all( false );
	$this->set_flag_fetch_thumb( false );
	$this->set_flag_allow_none( false );
	$this->set_flag_post_count( false );
	$this->set_flag_notify( false );

	$ret = $this->submit();
	switch ( $ret )
	{

// success
		case _C_WEBPHOTO_RET_SUCCESS :
			break;

// error
		case _C_WEBPHOTO_RET_ERROR :
			$is_failed = true;
			break;
	}

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( $is_failed ) );

	redirect_header( $url, $time, $msg );
	exit();
}

function _build_redirect_param( $is_failed )
{
	$param = array(
		'is_failed'   => $is_failed ,
		'url_success' => $this->_THIS_CLOSE_URL ,
		'url_failed'  => $this->_THIS_CLOSE_URL , 
		'msg_success' => $this->get_constant('SUBMIT_RECEIVED') ,
	);
	return $param ;
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
	$row = $this->build_submit_default_row() ;

	$param = array(
		'has_resize'    => $this->_has_image_resize,
		'allowed_exts'  => $this->get_normal_exts() ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_imagemanager( $row, $param );
}

// --- class end ---
}

?>