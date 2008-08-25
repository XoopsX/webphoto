<?php
// $Id: i.php,v 1.3 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_i
//=========================================================
class webphoto_main_i extends webphoto_show_photo
{
	var $_agent_class;
	var $_retrieve_class;
	var $_pagenavi_class;
	var $_multibyte_class;
	var $_image_create_class;

	var $_xoops_sitename = null;

	var $_TEMPLATE = null;

	var $_CHARSET        = _CHARSET ;
	var $_CHARSET_OUTPUT = _CHARSET ;

	var $_LATEST_LIMIT   = 1;
	var $_RANDOM_LIMIT   = 1;
	var $_RAMDOM_ORDERBY = 'rand()';
	var $_LIST_LIMIT     = 10;
	var $_LIST_ORDERBY   = 'item_time_update DESC, item_id DESC';
	var $_NAVI_WINDOWS   = 4;

	var $_MAX_MOBILE_WIDTH  = 480;
	var $_MAX_MOBILE_HEIGHT = 480;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_i( $dirname , $trust_dirname )
{
	$this->webphoto_show_photo( $dirname , $trust_dirname );

	$this->_agent_class     =& webphoto_lib_user_agent::getInstance();
	$this->_retrieve_class  =& webphoto_mail_retrieve::getInstance( $dirname , $trust_dirname );
	$this->_pagenavi_class  =& webphoto_lib_pagenavi::getInstance();
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );

	$this->_set_charset_output();
	$this->_set_mobile_array();

	$this->_TEMPLATE = 'db:'. $dirname .'_main_i.html';

	$this->_xoops_sitename = $this->_xoops_class->get_config_by_name( 'sitename' ) ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_i( $dirname , $trust_dirname );
	}
	return $instance;
}

function _set_charset_output()
{
	if ( defined("_WEBPHOTO_CHARSET_MOBILE") ) { 
		if ( constant("_WEBPHOTO_CHARSET_MOBILE") ) {
			$this->_CHARSET_OUTPUT = _WEBPHOTO_CHARSET_MOBILE;
		}
	}
}

function _set_mobile_array()
{
	if ( function_exists('webphoto_mobile_array') ) { 
		$arr = webphoto_mobile_array();
		if ( isset($arr) ) {
			$this->_agent_class->set_mobile_array( $arr );
		}
	}
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->http_output('pass');
	header( 'Content-Type:text/html; charset='.$this->_CHARSET_OUTPUT );

	$op = $this->_post_class->get_get_text('op');
	switch ( $op )
	{
		case 'post':
			$this->_post();
			break;

		default:
			$this->_show() ;
			break;
	}

}

//---------------------------------------------------------
// post
//---------------------------------------------------------
function _post()
{
	$title = $this->_MODULE_NAME .' - '. $this->_xoops_sitename ;

	$text  = $this->build_html_head( $this->sanitize($title), $this->_CHARSET_OUTPUT );
	$text .= $this->build_html_body_begin();
	$text .= $this->_post_exec();
	$text .= $this->_build_goto();
	$text .= $this->build_html_body_end();

	echo $this->conv( $text );
}

function _post_exec()
{
	$text = '';

	if ( ! $this->_check_perm() ) {
		$text .= _NOPERM ;
		return $text ;
	}

	$text .= $this->get_constant('TITLE_MAIL_POST')."<br>\n";
	ob_start();

	if ( $this->_is_module_admin ) {
		$level = _C_WEBPHOTO_MSG_LEVEL_ADMIN ;
	} else {
		$level = _C_WEBPHOTO_MSG_LEVEL_NON ;
	}

	$this->_retrieve_class->set_msg_level( $level );
	$this->_retrieve_class->set_flag_force_db( true );

	$ret   = $this->_retrieve_class->retrieve();
	$count = $this->_retrieve_class->get_mail_count();
	switch ( $ret )
	{
		case _C_WEBPHOTO_RETRIEVE_CODE_ACCESS_TIME :
			$text .= $this->_build_retry() ;
			break;

		case _C_WEBPHOTO_RETRIEVE_CODE_NOT_RETRIEVE :
		case _C_WEBPHOTO_RETRIEVE_CODE_NO_NEW :
			$text .= $this->get_constant('TEXT_MAIL_NO_NEW') ;
			break;

		default:
			$text .= sprintf( $this->get_constant('TEXT_MAIL_RETRIEVED_FMT'), $count );
			break;
	}

	if ( $this->_is_module_admin ) {
		$text .= "<br /><br />\n";
		$text .= "--- <br />\n";
		$text .= ob_get_contents();
		$text .= "<br />\n";
		$text .= "--- <br />\n";
	}

	ob_end_clean();

	return $text;
}

function _check_perm()
{
	if (  $this->_retrieve_class->is_set_mail() &&
	    ( $this->_retrieve_class->has_mail() || 
	      $this->_agent_class->parse_mobile() )) {
		return true ;
	}

	return false;
}

function _build_retry()
{
	$url = $this->_MODULE_URL . '/i.php?op=post';
	$text  = $this->get_constant('TEXT_MAIL_ACCESS_TIME') ;
	$text .= "<br>\n";
	$text .= $this->get_constant('TEXT_MAIL_RETRY') ;
	$text .= "<br>\n";
	$text .= '<a href="'. $url .'">';
	$text .= $this->get_constant('TITLE_MAIL_POST') ;
	$text .= "</a><br>\n";
	return $text;
}

function _build_goto()
{
	$url = $this->_MODULE_URL . '/i.php?op=latest';
	$text  = "<br><br>\n";
	$text .= '<a href="'. $url .'">';
	$text .= $this->sanitize( $this->_MODULE_NAME ) ;
	$text .= "</a><br>\n";
	return $text;
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function _show()
{
	$tpl = new XoopsTpl();
	$tpl->assign( $this->_show_exec() ) ;
	$tpl->display( $this->_TEMPLATE );
}

function _show_exec()
{
	$id   = $this->_post_class->get_get_int('id');
	$size = $this->_post_class->get_get_int('s');
	$page = $this->_post_class->get_get_int('page', 1);
	$op   = $this->_post_class->get_get_text('op');

	$show_photo = false;
	$photo      = null;

// if noto specify page
	if ( $page <= 1 ) {
		$photo = $this->_get_photo( $op, $id );
	}

	$pagetitle = $this->_MODULE_NAME ;
	if ( is_array($photo) ) {
		$show_photo = true;
		$pagetitle  = $photo['title'];
	}

	$arr = array(
		'photo'         => $photo,
		'photo_list'    => $this->_get_photo_list( $page ),
		'navi'          => $this->_build_navi( $page ) ,
		'xoops_dirname' => $this->_DIRNAME ,
		'charset'       => $this->_CHARSET_OUTPUT,
		'size'          => $size,
		'show_photo'    => $show_photo ,
		'show_post'     => $this->_check_perm() ,
		'token'         => $this->get_token() ,
		'mobile_width'  => $this->_MAX_MOBILE_WIDTH ,

		'sitename_conv'    => $this->conv( $this->sanitize( $this->_xoops_sitename ) ) ,
		'pagetitle_conv'   => $this->conv( $this->sanitize( $pagetitle ) ) ,
		'modulename_conv'  => $this->conv( $this->sanitize( $this->_MODULE_NAME ) ) ,
		'lang_video_conv'  => $this->conv( $this->get_constant('ICON_VIDEO') ) ,
		'lang_second_conv' => $this->conv( $this->get_constant('SECOND') ) ,
		'lang_post_conv'   => $this->conv( $this->get_constant('TITLE_MAIL_POST') ) ,
	);

	return $arr;
}

function _get_photo( $op, $id )
{
	$item_row = null;
	$photo    = null;

// latest
	if ( $op == 'latest' ) {
		$item_rows = $this->_item_handler->get_rows_public_imode_by_orderby(
			$this->_LIST_ORDERBY, $this->_LATEST_LIMIT );
		if ( isset($item_rows[0]) ) {
			$item_row = $item_rows[0] ;
		}

// specified
	} elseif ( $id > 0 ) {
		$item_row = $this->_item_handler->get_row_by_id( $id );
	}

// random
	if ( !is_array($item_row) ) {
		$item_rows = $this->_item_handler->get_rows_public_imode_by_orderby( 
			$this->_RAMDOM_ORDERBY, $this->_RANDOM_LIMIT );
		if ( isset($item_rows[0]) ) {
			$item_row = $item_rows[0] ;
		}
	}

	if ( is_array($item_row) ) {
		$photo = $this->build_show_conv( $item_row );
	}

	return $photo;
}

function _get_photo_list( $page )
{
	$this->_pagenavi_class->set_page( $page );
	$start = $this->_pagenavi_class->calc_start( $page, $this->_LIST_LIMIT );
	$item_rows  = $this->_item_handler->get_rows_public_imode_by_orderby(
		$this->_LIST_ORDERBY, $this->_LIST_LIMIT, $start );
	return $this->build_show_conv_from_rows( $item_rows );
}

function _build_navi( $page )
{
	$url = $this->_MODULE_URL .'/i.php?';
	$total = $this->_item_handler->get_count_public_imode();
	return $this->_pagenavi_class->build( 
		$url, $page, $this->_LIST_LIMIT, $total, $this->_NAVI_WINDOWS );
}

//---------------------------------------------------------
// build show
//---------------------------------------------------------
function build_show_conv( $item_row )
{
	$photo = $this->build_photo_show( $item_row );

	$photo['title_conv']       = $this->conv( $photo['title_s'] ) ;
	$photo['place_conv']       = $this->conv( $photo['place_s'] ) ;
	$photo['equipment_conv']   = $this->conv( $photo['equipment_s'] ) ;
	$photo['uname_conv']       = $this->conv( $photo['uname_s'] ) ;
	$photo['description_conv'] = $this->conv( $photo['description_disp'] ) ;
	$photo['summary_conv']     = $this->conv( $photo['summary'] ) ;

	return $photo;
}

function build_show_conv_from_rows( $item_rows )
{
	$arr = array();
	foreach ( $item_rows as $item_row ) {
		$arr[] = $this->build_show_conv( $item_row ) ;
	}
	return $arr;
}


//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function http_output( $encoding )
{
	return $this->_multibyte_class->m_mb_http_output( $encoding );
}

function conv( $str )
{
	return $this->_multibyte_class->convert_encoding( $str, $this->_CHARSET_OUTPUT, $this->_CHARSET );
}

// --- class end ---
}

?>