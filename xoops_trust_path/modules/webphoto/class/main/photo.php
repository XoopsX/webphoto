<?php
// $Id: photo.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used build_uri_photo() build_photo_pagenavi()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_photo
//=========================================================
class webphoto_main_photo extends webphoto_show_main
{
	var $_d3_comment_view_class;

	var $_get_photo_id;
	var $_get_cat_id;
	var $_get_order;

	var $_row = null;
	var $_has_tagedit = false;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_photo( $dirname , $trust_dirname )
{
	$this->webphoto_show_main( $dirname , $trust_dirname );
	$this->set_flag_highlight( true );

	$this->_comment_view_class =& webphoto_d3_comment_view::getInstance();
	$this->_comment_view_class->init( $dirname );

	$this->_has_tagedit = $this->_perm_class->has_tagedit();

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_photo( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_edittag()
{
	$this->_check();

	if ( $this->_is_edittag() ) {
		$this->_edittag();
		exit();
	}

}

function _check()
{
	$this->_get_photo_id = $this->_uri_class->get_pathinfo_id( 'photo_id' ) ;
	$this->_get_cat_id   = $this->_pathinfo_class->get_int( 'cat_id' );
	$this->_get_order    = $this->_pathinfo_class->get( 'order' );

	$row = $this->_photo_handler->get_row_public_by_id( $this->_get_photo_id ) ;
	if( !is_array($row) ) {
		redirect_header( $this->_MODULE_URL.'/' , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit();
	}

// save row
	$this->_row = $row;
}

//---------------------------------------------------------
// edittag
//---------------------------------------------------------
function _is_edittag()
{
	if ( $this->_post_class->get_post('op') == 'tagedit' ) {
		return true;
	}
	return false;
}

function _edittag()
{
	$redirect_this_url = $this->build_uri_photo( $this->_get_photo_id );

	$ret = $this->_excute_edittag();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
			exit ;

		case _C_WEBPHOTO_ERR_TOKEN:
			$msg = 'Token Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_token_errors();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAIL , $msg );
			exit();

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAIL, $msg ) ;
			exit();

		case 0:
		default:
			break;
	}

	redirect_header( $redirect_this_url , $this->_TIME_SUCCESS , $this->get_constant('DBUPDATED') ) ;
	exit();
}

function _excute_edittag()
{
	if ( ! $this->_has_tagedit ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->check_token() ) { 
		return _C_WEBPHOTO_ERR_TOKEN;
	}

// load row
	$row = $this->_row;

	$photo_id  = $row['photo_id'];

	$post_tags  = $this->_post_class->get_post_text( 'tags' );
	$post_array = $this->_tag_class->str_to_tag_name_array( $post_tags );

	$ret = $this->_tag_class->update_tags( $photo_id, $this->_xoops_uid, $post_array );
	if ( !$ret ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
// load row
	$row = $this->_row;
	$photo_id  = $row['photo_id'];
	$photo_uid = $row['photo_uid'];

// for xoops comment & notification
	$_GET['photo_id'] = $photo_id;

	$this->set_keyword_array_by_get();

// countup hits
	$this->_photo_handler->update_hits( $photo_id );

	$total_all  = $this->_photo_handler->get_count_public();
	$photo      = $this->build_photo_show( $row );

	$gmap_param = $this->_build_gmap_param( $row );
	$show_gmap  = $gmap_param['show_gmap'];

	$tags_param = $this->_build_tags_param( $photo_id );

	$cat_id = $this->_get_catid_row_or_post( $row ) ;

	$this->assign_xoops_header( 'category', $cat_id, $show_gmap );

	$arr = array(
		'xoops_pagetitle' => $photo['title_s'],
		'photo'           => $photo,
		'sub_title'       => $this->build_cat_sub_title( $cat_id ),
		'photo_nav'       => $this->_build_navi( $photo_id, $cat_id ),
		'use_box_js'      => $this->_USE_BOX_JS ,
		'show_photo_desc' => true ,
		'show_photo_exif' => true ,
		'photo_total_all' => $total_all ,
		'lang_thereare'   => $this->build_lang_thereare( $total_all ) ,
		'notification_select' => $this->_build_notification_select() ,
	);

	$ret = array_merge( $arr, $gmap_param, $tags_param );
	return $this->add_box_list( $ret );
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _get_catid_row_or_post( $row )
{
	$cat_id = ( $row['photo_cat_id'] > 0 ) ? $row['photo_cat_id'] : $this->_get_cat_id ;
	return $cat_id;
}

function _build_gmap_param( $row )
{
	$show  = false;
	$icons = null;

	$photo = $this->_gmap_class->build_show( $row );
	if ( is_array($photo) ) {
		$show  = true;
		$icons = $this->_gmap_class->build_icon_list();
	}

	$arr = array(
		'show_gmap'       => $show,
		'gmap_photo'      => $photo,
		'gmap_icons'      => $icons ,
		'gmap_latitude'   => $row['photo_gmap_latitude'] ,
		'gmap_longitude'  => $row['photo_gmap_longitude'] ,
		'gmap_zoom'       => $row['photo_gmap_zoom'] ,
		'gmap_lang_not_compatible' => $this->get_constant('GMAP_NOT_COMPATIBLE') ,
	);
	return $arr;
}

function _build_navi( $photo_id, $cat_id )
{
	$script   = $this->_uri_class->build_photo_pagenavi() ;
	$orderby  = $this->_sort_class->sort_to_orderby( $this->_get_order );
	$id_array = $this->_photo_handler->get_id_array_public_by_catid_orderby( $cat_id, $orderby );

	return $this->_pagenavi_class->build_id_array( $script, $id_array, $photo_id );
}

function _build_notification_select()
{
// for core's notificationSubscribableCategoryInfo
	$_SERVER['PHP_SELF'] = $this->_notification_select_class->get_new_php_self();

	return $this->_notification_select_class->build();
}

function _build_tags_param( $photo_id )
{
	if ( ! $this->_has_tagedit ) {
		$arr = array(
			'show_tagedit' => false
		);
		return $arr;
	}

	$arr = array(
		'show_tagedit' => true ,
		'token_name'   => $this->get_token_name() ,
		'token_value'  => $this->get_token() ,
		'photo_id'     => $photo_id ,
		'tags'         => $this->_build_tags( $photo_id ) ,
	);
	return $arr;
}

function _build_tags( $photo_id )
{
	return $this->_tag_class->build_tags_for_photo( $photo_id, $this->_xoops_uid );
}

//---------------------------------------------------------
// xoops comment
//---------------------------------------------------------
function comment_view()
{
	$this->_comment_view_class->assgin_tmplate();
}

// --- class end ---
}

?>