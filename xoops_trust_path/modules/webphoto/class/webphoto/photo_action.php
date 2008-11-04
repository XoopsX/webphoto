<?php
// $Id: photo_action.php,v 1.4 2008/11/04 14:08:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-04 K.OHWADA
// BUG: undefined property _REDIRECT_TIME_FAILED
// set values in preview
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_action
//=========================================================
class webphoto_photo_action extends webphoto_photo_edit
{
	var $_redirect_class;
	var $_embed_class ;

	var $_row_create  = null ;
	var $_row_current = null;
	var $_row_update  = null ;

	var $_is_none_type = false;

// for submit_imagemanager
	var $_FLAG_FETCH_ALLOW_ALL = true ;
	var $_FLAG_FETCH_THUMB = true ;
	var $_FLAG_ALLOW_NONE  = false ;
	var $_FLAG_POST_COUNT  = true ;
	var $_FLAG_NOTIFY      = true ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_action( $dirname , $trust_dirname )
{
	$this->webphoto_photo_edit( $dirname , $trust_dirname );

	$this->_redirect_class =& webphoto_photo_redirect::getInstance( $dirname, $trust_dirname );
	$this->_embed_class    =& webphoto_embed::getInstance( $dirname, $trust_dirname );

	$this->_FLAG_ALLOW_NONE = $this->_cfg_allownoimage ;
}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_action( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_fetch_allow_all( $val )
{
	$this->_FLAG_FETCH_ALLOW_ALL = (bool)$val ;
}

function set_flag_fetch_thumb( $val )
{
	$this->_FLAG_FETCH_THUMB = (bool)$val ;
}

function set_flag_allow_none( $val )
{
	$this->_FLAG_ALLOW_NONE = (bool)$val ;
}

function set_flag_post_count( $val )
{
	$this->_FLAG_POST_COUNT  = (bool)$val ;
}

function set_flag_notify( $val )
{
	$this->_FLAG_NOTIFY      = (bool)$val ;
}

//---------------------------------------------------------
// submit check 
//---------------------------------------------------------
function submit_check()
{
	$ret = $this->submit_check_exec() ;
	if ( $ret < 0 ) {
		$this->submit_check_redirect( $ret );
		return false;
	}

	return true;
}

function submit_check_redirect( $ret )
{
	$url = null ;
	$msg = null ;

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			$url = XOOPS_URL .'/user.php';
			$msg = $this->get_constant('ERR_MUSTREGFIRST') ;
			break;

		case _C_WEBPHOTO_ERR_CHECK_DIR:
			$url = $this->_INDEX_PHP ;
			$msg = 'Directory Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			break;

		case _C_WEBPHOTO_ERR_NO_CAT_RECORD :
			$url = $this->_INDEX_PHP ;
			$msg = $this->get_constant('ERR_MUSTADDCATFIRST') ;
			break;

		default;
			break;
	}

	$this->_redirect_url  = $url ;
	$this->_redirect_msg  = $msg ;

// BUG: undefined property _REDIRECT_TIME_FAILED
	$this->_redirect_time = $this->_TIME_FAILED ;
}

function submit_check_exec()
{
	if ( ! $this->_has_insertable )   {
		return _C_WEBPHOTO_ERR_NO_PERM ; 
	}

	if ( ! $this->exists_cat_record() ) { 
		return _C_WEBPHOTO_ERR_NO_CAT_RECORD ; 
	}

	$ret1 = $this->check_dir( $this->_PHOTOS_DIR );
	if ( $ret1 < 0 ) {
		return $ret1; 
	}

	$ret2 = $this->check_dir( $this->_THUMBS_DIR );
	if ( $ret2 < 0 ) {
		return $ret2; 
	}

	$ret3 = $this->check_dir( $this->_TMP_DIR );
	if ( $ret3 < 0 ) {
		return $ret3; 
	}

	return 0;
}

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
function build_submit_default_row()
{
	$this->get_post_cat_id();

	$this->_post_type           = $this->_post_class->get_post_get_text( 'type' );
	$this->_item_kind           = $this->_post_class->get_post_text( 'item_kind' );
	$this->_item_displaytype    = $this->_post_class->get_post_text( 'item_displaytype' );
	$this->_item_external_url   = $this->_post_class->get_post_text( 'item_external_url' );
	$this->_item_embed_type     = $this->_post_class->get_post_text( 'item_embed_type' );
	$this->_item_playlist_type  = $this->_post_class->get_post_text( 'item_playlist_type' );

// set checked
	$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_NO );

// new row
	$item_row = $this->_item_handler->create( true );
	$item_row['item_cat_id']         = $this->_post_item_cat_id;
	$item_row['item_kind']           = $this->_item_kind ;
	$item_row['item_displaytype']    = $this->_item_displaytype;
	$item_row['item_embed_type']     = $this->_item_embed_type;
	$item_row['item_playlist_type']  = $this->_item_playlist_type;
	$item_row['item_datetime']       = $this->get_mysql_date_today();

	return $item_row;
}

function build_submit_preview_row()
{
	$item_row = $this->_item_handler->create( true );
	$item_row = $this->build_row_by_post( $item_row, false, false );

	$item_row['item_cat_id'] = $this->_post_item_cat_id;
	$item_row['item_uid']    = $this->_xoops_uid;

	if ( $item_row['item_datetime'] ) {
		$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_YES );
	} else {
		$item_row['item_datetime'] = $this->get_mysql_date_today();
	}

	return $item_row;
}

function is_show_extra_form()
{
	if ( $this->_post_item_cat_id > 0 ) {
		return false;
	}
	return $this->is_upload_type();
}

function is_upload_type()
{
	if ( $this->is_embed_type() ) {
		return false ;
	}
	if ( $this->is_external_type() ) {
		return false ;
	}
	if ( $this->is_admin_playlist_type() ) {
		return false ;
	}
	return true;
}

function build_form_param( $mode )
{
	list ( $types, $allowed_exts ) = $this->_mime_class->get_my_allowed_mimes();

	$param = array(
		'mode'            => $mode,
		'type'            => $this->_post_type ,
		'preview_name'    => $this->get_preview_name(),
		'tag_name_array'  => $this->get_tag_name_array(),
		'checkbox_array'  => $this->get_checkbox_array(),
		'has_resize'      => $this->_has_image_resize,
		'has_rotate'      => $this->_has_image_rotate,
		'allowed_exts'    => $allowed_exts ,
	);

	return $param;
}

//---------------------------------------------------------
// modify form
//---------------------------------------------------------
function build_modify_row_by_post( $item_row, $flag_default=false )
{
	$post_preview              = $this->_post_class->get_post_text('preview');
	$post_submit               = $this->_post_class->get_post_text('submit' );
	$post_time_update_checkbox = $this->_post_class->get_post_int( 'item_time_update_checkbox' );
	$post_time_update          = $this->_post_class->get_post_time('item_time_update' );

	if ( $flag_default ) {
		$this->set_checkbox_by_name( 'item_datetime_checkbox',    _C_WEBPHOTO_YES );	
		$this->set_checkbox_by_name( 'item_time_update_checkbox', _C_WEBPHOTO_YES );
		$this->set_checkbox_by_name( 'thumb_checkbox',            _C_WEBPHOTO_YES );
		$this->set_tag_name_array( $this->tag_handler_tag_name_array( $this->_post_photo_id ) );
	}

	if ( $post_preview || $post_submit ) {

		$item_row = $this->build_row_by_post( $item_row );

// admin
		if ( $this->_FLAG_ADMIN ) {
			if ( $post_time_update_checkbox ) {
				$item_row['item_time_update'] = $post_time_update;
			}

// user
		} else {
			$item_row['item_time_update'] = time();
		}

	}

	return $item_row;
}

//---------------------------------------------------------
// print form video thumb
//---------------------------------------------------------
function print_form_video_thumb( $mode, $item_row )
{
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$form_class =& webphoto_photo_edit_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $mode, $item_row );
}

//---------------------------------------------------------
// print form video thumb
//---------------------------------------------------------
function print_form_delete_confirm( $mode, $item_row )
{
	$img = $this->build_img_thumb( $item_row );

	echo '<h4>'. $this->get_constant('TITLE_PHOTODEL') ."</h4>\n";
	echo '<b>'. $this->sanitize( $item_row['item_title'] ) ."<b><br />\n";

	if ( $img ) {
		echo $img ;
	}

	echo "<br />\n";

	$form_class =& webphoto_photo_edit_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$form_class->print_form_delete_confirm( 'admin', $item_row['item_id'] );
}

function build_img_thumb( $item_row )
{
	$src = null;
	$str = null;

	$cont_url = $this->get_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;

	$thumb_url = $this->get_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ;

	if ( $thumb_url ) {
		$src = $thumb_url ;
	} elseif ( $cont_url && $this->is_image_kind( $item_row['item_kind'] ) ) {
		$src = $cont_url ;
	}

	if ( $src ) {
		$str  = '<img src="'. $this->sanitize($src) .'" border="0" />'."\n";
		$str .= "<br />\n";
	}

	return $str ;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function submit()
{
	$this->get_post_param();
	$ret1 = $this->submit_exec();

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function get_created_row()
{
	return $this->_row_create ;
}

function submit_exec()
{
	$this->clear_msg_array();

	$ret1 = $this->submit_exec_check();
	if ( $ret1 < 0 ) {
		return $ret1 ;
	}

	$ret2 = $this->submit_exec_fetch();
	if ( $ret2 < 0 ) {
		return $ret2 ;
	}

	$ret3 = $this->submit_exec_fetch_check();
	if ( $ret3 < 0 ) {
		return $ret3 ;
	}

	$ret4 = $this->submit_exec_save();
	if ( $ret4 < 0 ) {
		return $ret4 ;
	}

	$this->submit_exec_notify();
	return 0; 
}

function submit_exec_check()
{
// Check if cid is valid
	if ( empty( $this->_post_item_cat_id ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	if ( ! $this->check_valid_catid( $this->_post_item_cat_id ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

// Check if upload file name specified
	if ( $this->is_upload_type() && ! $this->check_xoops_upload_file( $this->_FLAG_FETCH_THUMB ) ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	return 0; 
}

function submit_exec_fetch()
{

	$this->upload_init( true ) ;

	if ( $this->is_external_type() ) {
		$this->set_ext_when_external() ;
		$this->set_title_when_external() ;

	} elseif ( $this->is_embed_type() ) {
		$this->set_title_when_embed() ;
		$this->set_thumb_when_embed() ;

	} elseif ( $this->is_admin_playlist_type() ) {
		$this->set_title_when_playlist() ;
		$this->set_player_when_playlist() ;

// fetch photo
	} else {
		$ret11 = $this->upload_fetch_photo( $this->_FLAG_FETCH_ALLOW_ALL );
		if ( $ret11 < 0 ) { 
			return $ret11;	// failed
		}
	}

// set values in preview
	if ( empty($this->_photo_tmp_name) && $this->is_readable_preview() ) {
		$this->_photo_tmp_name = $this->get_preview_name() ;
		$this->set_values_for_fetch_photo( $this->_photo_tmp_name );
	}

// fetch thumb
	if ( $this->_FLAG_FETCH_THUMB ) {
		$ret12 = $this->upload_fetch_thumb();
		if ( $ret12 < 0 ) { 
			return $ret12;	// failed
		}
	}

	if ( $this->is_item_undefined_kind() ) {
		$this->set_item_kind( $this->get_new_kind() );
	}

	$this->set_item_displaytype( $this->get_new_displaytype() );
	$this->set_item_onclick(     $this->get_new_onclick() );

	return 0; 
}

function submit_exec_fetch_check()
{
	$this->_is_none_type = false;

	if ( $this->_photo_tmp_name ) {
		return 0 ;
	}

// embed type
	if ( $this->is_embed_type() ) {
		if ( $this->is_fill_item_embed_src() ) {
			return 0;
		} else {
			return _C_WEBPHOTO_ERR_EMBED;
		}
	}

// playlist type
	if ( $this->is_admin_playlist_type() ) {
		if ( $this->is_fill_item_playlist_feed() ) {
			return 0;
		} elseif ( $this->is_fill_item_playlist_dir() ) {
			return 0;
		} else {
			return _C_WEBPHOTO_ERR_PLAYLIST;
		}
	}

// check title
	if ( !$this->is_fill_item_title() ) {
		return _C_WEBPHOTO_ERR_NO_TITLE;
	}

// check allow no image mode
	if ( $this->_FLAG_ALLOW_NONE ) {
		$this->set_item_kind( _C_WEBPHOTO_ITEM_KIND_NONE );
		return 0; 
	}

	return _C_WEBPHOTO_ERR_NO_IMAGE;
}

function submit_exec_save()
{
	$photo_tmp_name = $this->_photo_tmp_name;
	$thumb_tmp_name = $this->_thumb_tmp_name;

// --- insert item ---
	$item_row = $this->build_insert_item_row();
	$item_id = $this->_item_handler->insert( $item_row );
	if ( !$item_id ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id;

	$this->_row_create = $item_row;

	$ret14 = $this->create_thumb_for_submit($item_id, $photo_tmp_name, $thumb_tmp_name );
	if ( $ret14 < 0 ) {
		return $ret14;
	}

	$file_params = $this->get_file_params();

// --- update item ---
	$update_row = $this->build_update_item_row( $item_row, $file_params );
	$ret15 = $this->_item_handler->update( $update_row );
	if ( !$ret15 ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->_row_create = $update_row ;

	if ( $this->is_admin_playlist_type() ) {
		$ret = $this->_playlist_class->create_cache_by_item_row( $update_row );
		if ( !$ret ) {
			$this->set_msg_array( $this->_playlist_class->get_errors() );
		}
	}

// --- add tag ---
	$ret16 = $this->_tag_class->add_tags( 
		$item_id, $this->_xoops_uid, $this->get_tag_name_array() );
	if ( !$ret16 ) { 
		return _C_WEBPHOTO_ERR_DB; 
	}

	return 0;
}

function submit_exec_notify()
{
	if ( $this->_FLAG_POST_COUNT ) {
		$xoops_user_class =& webphoto_xoops_user::getInstance();
		$xoops_user_class->increment_post_by_num_own( $this->_cfg_addposts );
	}

// Trigger Notification when supper insert
	if ( $this->_FLAG_NOTIFY && $this->get_new_status() ) {
		$this->notify_new_photo( $this->_row_create );
	}

	return 0;
}

function build_insert_item_row()
{
	$item_row = $this->_item_handler->create( true );

	$item_row = $this->build_row_by_post( $item_row, true );

	$item_row['item_uid']    = $this->_xoops_uid;
	$item_row['item_status'] = $this->get_new_status();
	$item_row['item_search'] = $this->build_search_for_edit( $item_row, $this->get_tag_name_array() );

	return $item_row;
}

function build_update_item_row( $item_row, $file_params )
{
	$item_id  = $item_row['item_id'];

	$file_ids = $this->_photo_class->insert_files_from_params(
		$item_id,  $file_params );

	$update_row = $this->_photo_class->build_update_item_row(
		$item_row, $file_ids );

	if ( $this->is_admin_playlist_type() ) {
		$update_row['item_playlist_cache'] = 
			$this->_playlist_class->build_name( $item_id ) ;
	}

	return $update_row;
}

function get_new_kind()
{
	$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;

// external
	if ( $this->is_external_type() ) {
		if ( $this->is_image_ext( $this->_item_ext ) ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE ;
		} else {
			$kind = _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL ;
		}

// upload
	} elseif ( $this->_item_ext ) {
		$kind = $this->_mime_class->ext_to_kind( $this->_item_ext );

// embed
	} elseif ( $this->is_embed_type() ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_EMBED ;

// playlist
	} elseif ( $this->is_admin_playlist_type() ) {
		if ( $this->_item_playlist_feed ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ;

		} elseif( $this->_item_playlist_dir ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ;
		}
	}

	return $kind ;
}

function get_new_displaytype()
{
	$str = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	if ( $this->is_image_ext( $this->_item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;

	} elseif ( $this->is_swfobject_ext( $this->_item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;

	} elseif ( $this->is_mediaplayer_ext( $this->_item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

	} elseif ( $this->is_embed_type() ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_EMBED ;

	} elseif ( $this->is_admin_playlist_type() ) {
		if ( $this->is_item_playlist_type_general() ) {
			$str = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

		} elseif( $this->is_item_playlist_type_image() ) {
			$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR ;
		}
	}

	return $str ;
}

function get_new_onclick()
{
	return $this->_photo_class->get_onclick( $this->_item_ext );
}

function get_new_status()
{
	return intval( $this->_has_superinsert );
}

function set_ext_when_external()
{
	$ext = $this->parse_ext( $this->_item_external_url ) ;
	if ( $ext ) {
		$this->set_item_ext( $ext );
	}
}

function external_ext_to_kind( $ext )
{
	$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;
	if ( $ext ) {
		$kind = $this->_mime_class->ext_to_kind( $ext );
	}
	return $kind;
}

function set_title_when_external()
{
	$this->overwrite_item_title_if_empty( 
		$this->external_url_to_title() );
}

function external_url_to_title()
{
	return $this->strip_ext( 
		$this->_utility_class->parse_url_to_filename( $this->_item_external_url ) );
}

function set_title_when_embed()
{
	$this->overwrite_item_title_if_empty( 
		$this->embed_src_to_title() );
}

function set_thumb_when_embed()
{
	$this->overwrite_item_external_thumb_if_empty( 
		$this->build_embed_thumb() );
}

function embed_src_to_title()
{
	$title = null;

	if ( empty( $this->_item_embed_type ) ) {
		return $title ;	// null
	}

	if ( empty( $this->_item_embed_src ) ) {
		return $title ;	// null
	}

	$title  = $this->_item_embed_type ;
	$title .= ' : ';
	$title .= $this->_item_embed_src ;

	return $title ;
}

function build_embed_thumb()
{
	return $this->_embed_class->build_thumb(
		$this->_item_embed_type, $this->_item_embed_src );
}

function set_title_when_playlist()
{
	$this->overwrite_item_title_if_empty( 
		$this->playlist_to_title() );
}

function set_player_when_playlist()
{
	if ( $this->is_item_playlist_type_general() ) {
		$this->set_item_player_id( _C_WEBPHOTO_PLAYER_ID_PLAYLIST ) ;
	}
}

function playlist_to_title()
{
	if ( $this->_item_playlist_dir ) {
		$title = $this->_item_playlist_dir ;

	} elseif ( $this->_item_playlist_feed ) {
		$param = parse_url( $this->_item_playlist_feed );
		if ( isset($param['host']) ) {
			$title = $param['host'] ;
		} else {
			$title = date( "YmdHis" );
		}
	}

	if ( $title ) {
		$title = 'playlist: '.$title;
	}

	return $title ;
}

function create_thumb_for_submit( $newid, $photo_tmp_name, $thumb_tmp_name )
{
// already set
	if ( $this->is_fill_item_external_thumb() ) {
		return 0;	// no action
	}

	$flag_thumb = false;

	if ( empty($thumb_tmp_name) ) {
		if ( $this->is_external_type() ) {
			$this->create_thumb_from_external( $newid );
			$flag_thumb = true;

		} elseif ( $this->is_embed_type() ) {
			$this->create_thumb_from_embed( $newid );
			$flag_thumb = true;

		} elseif ( $this->is_admin_playlist_type() ) {
			$this->create_thumb_for_playlist( $newid );
			$flag_thumb = true;
		}
	}

	if ( !$flag_thumb ) {
		$ret = $this->create_photo_thumb( $newid, $photo_tmp_name, $thumb_tmp_name );
		if ( $ret < 0 ) {
			return $ret;
		}
	}

	return 0;
}

function notify_new_photo( $item_row )
{
	$notification_class =& webphoto_notification_event::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$notification_class->notify_new_photo( 
		$item_row['item_id'],  $item_row['item_cat_id'],  $item_row['item_title'] );
}

function is_item_playlist_type_general()
{
	return $this->is_playlist_type_general(
		$this->_item_playlist_type );
}

function is_item_playlist_type_image()
{
	return $this->is_playlist_type_image(
		$this->_item_playlist_type );
}

function is_playlist_type_general( $type )
{
	switch ( $type )
	{
		case _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_VIDEO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_FLASH :
			return true;
	}

	return false;
}

function is_playlist_type_image( $type )
{
	switch ( $type )
	{
		case _C_WEBPHOTO_PLAYLIST_TYPE_IMAGE :
			return true;
	}

	return false;
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function modify( $item_row )
{
	$this->get_post_param();
	$ret1 = $this->modify_exec( $item_row );

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function get_updated_row()
{
	return $this->_row_update ;
}

function modify_exec( $item_row )
{
// save
	$this->_row_update = $item_row ;

	$photo_tmp_name = null;
	$thumb_tmp_name = null;
	$image_info     = null;

	$cont_id   = 0 ;
	$thumb_id  = 0 ;
	$middle_id = 0 ;
	$flash_id  = 0 ;
	$docomo_id = 0 ;

	$this->clear_msg_array();

	$item_id  = $item_row['item_id'] ;

	$current_cont_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;

	$current_thumb_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ;

	$current_middle_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE ) ;

	$current_flash_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;

	$current_docomo_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO ) ;

// Check if upload file name specified
	if ( $this->is_upload_type() && !$this->check_xoops_upload_file() ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	$this->upload_init( true ) ;

	if ( $this->is_embed_type() ) {
		// dummy

	} elseif ( $this->is_admin_playlist_type() ) {
		// dummy

	} else {
		$ret11 = $this->upload_fetch_photo( true );
		if ( $ret11 < 0 ) { 
			return $ret11;	// failed
		}
	}

	$ret12 = $this->upload_fetch_thumb();
	if ( $ret12 < 0 ) { 
		return $ret12;	// failed
	}

	$photo_tmp_name = $this->_photo_tmp_name;
	$thumb_tmp_name = $this->_thumb_tmp_name;

// no upload
	if ( empty($photo_tmp_name) && empty($thumb_tmp_name) ) {
		return $this->update_photo_no_image( $item_row );
	}

// remove old photo & thumb file
	if ( $photo_tmp_name ) {
		$this->unlink_path( $current_cont_path );
		$this->unlink_path( $current_thumb_path );
		$this->unlink_path( $current_middle_path );
		$this->unlink_path( $current_flash_path );
		$this->unlink_path( $current_docomo_path );

// remove old thumb file
	} elseif ( empty($photo_tmp_name) && $thumb_tmp_name ) {
		$this->unlink_path( $current_thumb_path );
	}

	$ret12 = $this->create_photo_thumb(
		$item_id, $photo_tmp_name, $thumb_tmp_name );
	if ( $ret12 < 0 ) {
		return $ret12; 
	}

	$file_params = $this->get_file_params();

	if ( is_array($file_params) ) {
		$cont_param   = $file_params['cont'] ;
		$thumb_param  = $file_params['thumb'] ;
		$middle_param = $file_params['middle'] ;
		$flash_param  = $file_params['flash'] ;
		$docomo_param = $file_params['docomo'] ;

		if ( is_array($cont_param) ) {
			$cont_id = $this->_photo_class->insert_file( $item_id, $cont_param );
		}
		if ( is_array($thumb_param) ) {
			$thumb_id = $this->_photo_class->insert_file( $item_id, $thumb_param );
		}
		if ( is_array($middle_param) ) {
			$middle_id = $this->_photo_class->insert_file( $item_id, $middle_param );
		}
		if ( is_array($flash_param) ) {
			$flash_id = $this->_photo_class->insert_file( $item_id, $flash_param );
		}
		if ( is_array($docomo_param) ) {
			$docomo_id = $this->_photo_class->insert_file( $item_id, $docomo_param );
		}
	}

	if ( $cont_id == 0 ) {
		$this->update_all_file_duration( $item_row );
	}

	$row_update = $this->build_update_row_by_post( $item_row );

	if ( $cont_id > 0 ) {
		$row_update['item_file_id_1'] = $cont_id;
	}
	if ( $thumb_id > 0 ) {
		$row_update['item_file_id_2'] = $thumb_id;
	}
	if ( $middle_id > 0 ) {
		$row_update['item_file_id_3'] = $middle_id;
	}
	if ( $flash_id > 0 ) {
		$row_update['item_file_id_4'] = $flash_id;
	}
	if ( $docomo_id > 0 ) {
		$row_update['item_file_id_5'] = $docomo_id;
	}

// set if empty
	if ( $this->is_admin_playlist_type() && 
	     empty( $row_update['item_playlist_cache'] ) ) {

		$update_row['item_playlist_cache'] = 
			$this->_playlist_class->build_name( $item_id ) ;
	}

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $item_id, $this->get_tag_name_array() );

// when approve
	if ( $this->is_apporved_status( $row_update['item_status'] ) ) {
		$this->notify_new_photo( $row_update );
	}

// save
	$this->_row_update = $row_update;

	return 0;
}

function build_update_row_by_post( $item_row )
{
	$row_update = $this->build_modify_row_by_post( $item_row, false );

	$row_update['item_status'] = 
		$this->build_modify_status( $item_row['item_status'] );

	$row_update['item_search'] = $this->build_search_for_edit( 
		$row_update, $this->get_tag_name_array() );

	return $row_update;
}

function build_modify_status( $current_status )
{
	$post_valid = $this->_post_class->get_post_int('valid');
	$new_status = $current_status ;

	switch ( $current_status ) 
	{
		case _C_WEBPHOTO_STATUS_WAITING : 
			if ( $this->_FLAG_ADMIN && ( $post_valid == _C_WEBPHOTO_YES ) )  {
				$new_status = _C_WEBPHOTO_STATUS_APPROVED ;
			}
			break;

		case _C_WEBPHOTO_STATUS_APPROVED : 
			$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
			break;

		case _C_WEBPHOTO_STATUS_UPDATED :
		case _C_WEBPHOTO_STATUS_OFFLINE :
		case _C_WEBPHOTO_STATUS_EXPIRED :
		default:
			break;
	}

	return $new_status;
}

function is_apporved_status( $status )
{
	if ( $status == _C_WEBPHOTO_STATUS_APPROVED ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// update_photo_no_image
//---------------------------------------------------------
function update_photo_no_image( $item_row )
{
	$this->update_all_file_duration( $item_row );

	$row_update = $this->build_update_row_by_post( $item_row );

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $item_row['item_id'] , $this->get_tag_name_array() );

// when approve
	if ( $this->is_apporved_status( $row_update['item_status'] ) ) {
		$this->notify_new_photo( $row_update );
	}

// save
	$this->_row_update = $row_update;

	return 0;
}

function update_all_file_duration( $item_row )
{
	$duration      = $this->get_item_duration();
	$cont_duration = $this->get_file_cont_duration( $item_row ); 

	if ( $duration != $cont_duration ) {
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	}
}

function update_file_duration( $duration, $item_row, $kind )
{
	$file_row = $this->get_file_row_by_kind( $item_row, $kind );
	if ( !is_array($file_row ) ) {
		return true;
	}

	$file_row['file_duration'] = $duration ;

	$ret = $this->_file_handler->update( $file_row );
	if ( !$ret ) {
		$this->set_error( $this->_file_handler->get_errors() );
		return false;
	}
	return true;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete( $item_row )
{
	$err  = null;
	$ret1 = $this->delete_exec( $item_row );

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return false ;
	}

	return true ;
}

function delete_exec( $item_row )
{
	if ( ! $this->_has_deletable ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->check_edit_perm( $item_row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$delete_class =& webphoto_photo_delete::getInstance( $this->_DIRNAME );

	$ret = $delete_class->delete_photo_by_item_row( $item_row );
	if ( !$ret ) {
		$this->set_error( $delete_class->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

//---------------------------------------------------------
// video redo
//---------------------------------------------------------
function video_redo( $item_row )
{
	$flag_thumb = $this->_post_class->get_post_int('redo_thumb' );
	$flag_flash = $this->_post_class->get_post_int('redo_flash' );

	$ret1 = $this->video_redo_exec( $item_row, $flag_thumb, $flag_flash ) ;

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function video_redo_exec( $item_row, $flag_thumb, $flag_flash )
{
	$this->clear_msg_array();

	$this->_is_video_thumb_form = false;
	$flash_param = null;

	$item_id   = $item_row['item_id'];
	$item_ext  = $item_row['item_ext'];
	$item_kind = $item_row['item_kind'];

	$cont_file  = null ;
	$flash_file = null ;
	$param      = null ;

	$cont_row = $this->get_file_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;
	if ( is_array($cont_row) ) {
		$cont_path     = $cont_row['file_path'];
		$cont_width    = $cont_row['file_width'];
		$cont_height   = $cont_row['file_height'];
		$cont_duration = $cont_row['file_duration'];
		$cont_file     = XOOPS_ROOT_PATH . $cont_path ;

		$param                = array() ;
		$param['src_file']    = $cont_file ;
		$param['src_kind']    = $item_kind ;
		$param['video_param'] = array(
			'width'    => $cont_width ,
			'height'   => $cont_height ,
			'duration' => $cont_duration ,
		);
	}

	$flash_row = $this->get_file_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;
	if ( is_array($flash_row) ) {
		$flash_path = $flash_row['file_path'];
		$flash_file = XOOPS_ROOT_PATH . $flash_path ;
	}

	$flash_tmp_file = $this->_TMP_DIR .'/tmp_' . uniqid( $item_id.'_' ) ;

// create flash
	if ( $flag_flash && is_array($param) ) {
// save file
		$this->rename_file( $flash_file, $flash_tmp_file );

// BUG: undefined create_video_flash()
		$flash_param = $this->_photo_class->create_video_flash_param( $item_id, $param );
		if ( is_array($flash_param) ) {
// remove file if success
			$this->unlink_file( $flash_tmp_file );

		} else {
// recovery file if fail
			$this->rename_file( $flash_tmp_file, $flash_file );
		}
	}

// create video thumb
	if ( $flag_thumb && $this->_cfg_makethumb && $cont_file ) {

// BUG: undefined create_video_plural_thumbs()
		$this->_is_video_thumb_form 
			= $this->_photo_class->create_video_plural_thumbs(
				$item_id, $cont_file, $item_ext ) ;
	}

// update
	$row_update = $item_row ;

	if ( is_array($flash_param) ) {
		$flash_id = $this->_photo_class->insert_file( $item_id, $flash_param );
		if ( $flash_id > 0 ) {
			$row_update['item_file_id_4'] = $flash_id;

			$ret = $this->_item_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_item_handler->get_errors() );
				return _C_WEBPHOTO_ERR_DB;
			}
		}
	}

// save
	$this->_row_update = $row_update ;

	return 0;
}

//---------------------------------------------------------
// build_redirect
//---------------------------------------------------------
function build_failed_msg( $ret )
{
	$this->_redirect_class->set_error( $this->get_errors() );
	$ret = $this->_redirect_class->build_failed_msg( $ret );
	$this->clear_errors();
	$this->set_error( $this->_redirect_class->get_errors() );
	return $ret;
}

function build_redirect( $param )
{
	$this->_redirect_class->set_error( $this->get_errors() );
	return $this->_redirect_class->build_redirect( $param );
}

function get_redirect_url()
{
	return $this->_redirect_class->get_redirect_url();
}

function get_redirect_time()
{
	return $this->_redirect_class->get_redirect_time();
}

function get_redirect_msg()
{
	return $this->_redirect_class->get_redirect_msg();
}

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function build_preview_template( $row )
{
	$tpl = new XoopsTpl() ;
	$tpl->assign( 'xoops_dirname' , $this->_DIRNAME ) ;
	$tpl->assign( 'mydirname' ,     $this->_DIRNAME ) ;
	$tpl->assign( $this->get_photo_globals() ) ;
	$tpl->assign( 'photo' , $row ) ;

// BUG: not show description in preview
	$tpl->assign( 'show_photo_desc' , true ) ;

// BUG: not show img alt
	$tpl->assign( $this->get_lang_array() ) ;

	$template = 'db:'. $this->_DIRNAME .'_inc_photo_in_list.html';
	return $tpl->fetch( $template ) ;
}

// --- class end ---
}

?>