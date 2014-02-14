<?php
// $Id: admission.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_admission
//=========================================================
class webphoto_admin_admission extends webphoto_base_this
{
	var $_notification_class;
	var $_delete_class;

// GET param
	var $_get_pos;
	var $_get_txt;

	var $_ADIMISSION_PHP;

	var $_PERPAGE = 20;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_admission( $dirname , $trust_dirname )
{
// use delete_photo
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_notification_class =& webphoto_notification_event::getInstance( $dirname , $trust_dirname );
	$this->_delete_class  =& webphoto_photo_delete::getInstance( $dirname );

	$this->_ADIMISSION_PHP = $this->_MODULE_URL .'/admin/index.php?fct=admission';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_admission( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_get_pos = $this->_post_class->get_get_int(  'pos' );
	$this->_get_txt = $this->_post_class->get_get_text( 'txt' );
	$get_mes        = $this->_post_class->get_get_text( 'mes' );

	switch ( $this->_get_action() )
	{
		case 'admission':
			$this->_admission();
			exit();

		case 'delete':
			$this->_delete();
			exit();
	
		default:
			break;
	}

	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'ADMISSION' );

	echo "<p><font color='blue'>". $this->sanitize($get_mes) ."</font></p>";
	$this->_print_form();

	xoops_cp_footer();
}

function _get_action()
{
	$post_action = $this->_post_class->get_post_text( 'action' );
	$post_ids    = $this->_post_class->get_post('ids');

	if ( ($post_action == 'admit') && is_array($post_ids) && count($post_ids) ) {
		return 'admission';
	} elseif ( ($post_action == 'delete') && is_array($post_ids) && count($post_ids) ) {
		return 'delete';
	}
	return '';
}

function _admission()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADIMISSION_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_ids = $this->_post_class->get_post('ids');

	$this->_item_handler->update_status_by_id_array( $post_ids );

	$item_rows = $this->_item_handler->get_rows_by_id_array( $post_ids );
	foreach ( $item_rows as $item_row )
	{
		$this->_notification_class->notify_new_photo( 
			$item_row['item_id'], $item_row['item_cat_id'], $item_row['item_title'] );
	}

	redirect_header( $this->_ADIMISSION_PHP , 1 , _AM_WEBPHOTO_ADMITTING ) ;
	exit() ;
}

function _delete()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADIMISSION_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_ids = $this->_post_class->get_post('ids');
	foreach( $post_ids as $id ) {
		$this->_delete_class->delete_photo( $id ) ;
	}

	redirect_header( $this->_ADIMISSION_PHP, 1 , _WEBPHOTO_DELETED ) ;
	exit ;
}

function _print_form()
{
	$keyword_array = $this->str_to_array( $this->_get_txt, ' ' );
	$where         = $this->_item_handler->build_where_waiting_by_keyword_array( $keyword_array );
	$photo_count   = $this->_item_handler->get_count_by_where( $where );
	$photo_rows    = $this->_item_handler->get_rows_by_where(  $where, $this->_PERPAGE, $this->_get_pos );


// Page Navigation
	$pagenavi_class =& webphoto_lib_pagenavi::getInstance();
	$extra = "fct=admission&txt=". urlencode( $this->_get_txt );
	$pagenavi_class->XoopsPageNav( $photo_count, $this->_PERPAGE , $this->_get_pos , 'pos' , $extra ) ;
	$navi = $pagenavi_class->renderNav( 10 ) ;

	$form_class =& webphoto_admin_admission_form::getInstance(
		$this->_DIRNAME, $this->_TRUST_DIRNAME  );

	echo '<div style="margin:3px; text-align:right">';
	$form_class->print_form_admission_search( $this->_get_txt );
	echo "</div>\n";

	if ( $navi ) {
		echo '<div style="margin:3px; text-align:right">';
		echo $navi;
		echo "</div>\n";
	}

	$form_class->print_list_admission( $photo_rows );

}

// --- class end ---
}

?>