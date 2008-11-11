<?php
// $Id: photo_action.php,v 1.5 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// upload_fetch_middle()
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

//	$this->upload_init( true ) ;

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

// preview
		if ( empty($this->_photo_tmp_name) && $this->is_readable_preview() ) {
			$this->_photo_tmp_name = $this->get_preview_name() ;
		}

		$this->set_values_for_fetch_photo( $this->_photo_tmp_name );
	}

// fetch thumb middle
	if ( $this->_FLAG_FETCH_THUMB ) {
		$this->upload_fetch_thumb();
		$this->upload_fetch_middle();
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
	$photo_tmp_name  = $this->_photo_tmp_name;
	$thumb_tmp_name  = $this->_thumb_tmp_name;
	$middle_tmp_name = $this->_middle_tmp_name;

// --- insert item ---
	$item_row = $this->build_insert_item_row();
	$item_id = $this->_item_handler->insert( $item_row );
	if ( !$item_id ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id;

	$this->_row_create = $item_row;

	$ret14 = $this->create_photo_thumb(
		$item_row, $photo_tmp_name, $thumb_tmp_name, $middle_tmp_name, true );

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

function create_photo_thumb( $item_row, $photo_name, $thumb_name, $middle_name, $is_submit )
{
	$item_id = $item_row['item_id'] ;

	$photo_param  = $this->build_photo_param( $item_row, $photo_name, $thumb_name );
	$thumb_param  = null;
	$middle_param = null;

	if ( is_array($photo_param) ) {
		$ret = $this->create_photo_param_by_param( $photo_param );
		if ( $ret < 0 ) {
			return $ret;
		}
	}

	if ( $thumb_name ) {
		$thumb_param = $this->create_thumb_param_by_tmp( $item_id, $thumb_name );

	} elseif ( $is_submit && $this->is_external_type() ) {
			$this->create_thumb_from_external( $item_id );

	} elseif ( $is_submit && $this->is_embed_type() ) {
		$this->create_thumb_from_embed( $item_id );

	} elseif ( $is_submit && $this->is_admin_playlist_type() ) {
		$this->create_thumb_for_playlist( $item_id );

	} elseif ( is_array($photo_param) ) {
		$thumb_param = $this->create_thumb_param_by_param( $photo_param );
	}

	if ( $middle_name ) {
		$middle_param = $this->create_middle_param_by_tmp( $item_id, $middle_name );

	} elseif ( is_array($photo_param) ) {
		$middle_param = $this->create_middle_param_by_param( $photo_param );
	}

// unlink tmp file
	if ( $photo_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $photo_name );
	}
	if ( $thumb_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $thumb_name );
	}
	if ( $middle_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $middle_name );
	}

	$this->_file_params['thumb']  = $thumb_param ;
	$this->_file_params['middle'] = $middle_param ;

	return 0;
}

function build_photo_param( $item_row, $photo_name, $thumb_name )
{
	if ( empty($photo_name) ) {
		return null; 
	}

	$photo_param                     = array();
	$photo_param['item_id']          = $item_row['item_id'] ;
	$photo_param['src_ext']          = $item_row['item_ext'] ;
	$photo_param['src_kind']         = $item_row['item_kind'] ;
	$photo_param['src_file']         = $this->_TMP_DIR .'/'. $photo_name ;
	$photo_param['mime']             = $this->_photo_media_type ;
	$photo_param['video_param']      = $this->_video_param ;
	$photo_param['flag_video_thumb'] = $thumb_name ? false : true ;

	return $photo_param;
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

// Check if upload file name specified
	if ( $this->is_upload_type() && !$this->check_xoops_upload_file() ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

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

	$this->upload_fetch_thumb();
	$this->upload_fetch_middle();

	$photo_name  = $this->_photo_tmp_name;
	$thumb_name  = $this->_thumb_tmp_name;
	$middle_name = $this->_middle_tmp_name;

// no upload
	if ( empty($photo_name) && empty($thumb_name) && empty($middle_name) ) {
		return $this->update_photo_no_image( $item_row );
	}

	$ret12 = $this->create_photo_thumb(
		$item_row, $photo_name, $thumb_name, $middle_name, false );
	if ( $ret12 < 0 ) {
		return $ret12; 
	}

	$file_params = $this->get_file_params();

	if ( is_array($file_params) ) {
		$file_id_array = $this->update_files_from_params( $item_row, $file_params );
		$cont_id   = $this->get_array_value_by_key( $file_id_array, 'cont_id' );
		$thumb_id  = $this->get_array_value_by_key( $file_id_array, 'thumb_id' );
		$middle_id = $this->get_array_value_by_key( $file_id_array, 'middle_id' );
		$flash_id  = $this->get_array_value_by_key( $file_id_array, 'flash_id' );
		$docomo_id = $this->get_array_value_by_key( $file_id_array, 'docomo_id' );
	}

	if ( $cont_id == 0 ) {
		$this->update_all_file_duration( $item_row );
	}

	$row_update = $this->build_update_row_by_post( $item_row );

	if ( $cont_id > 0 ) {
		$row_update[ _C_WEBPHOTO_ITEM_FILE_CONT ] = $cont_id;
	}
	if ( $thumb_id > 0 ) {
		$row_update[ _C_WEBPHOTO_ITEM_FILE_THUMB ] = $thumb_id;
	}
	if ( $middle_id > 0 ) {
		$row_update[ _C_WEBPHOTO_ITEM_FILE_MIDDLE ] = $middle_id;
	}
	if ( $flash_id > 0 ) {
		$row_update[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id;
	}
	if ( $docomo_id > 0 ) {
		$row_update[ _C_WEBPHOTO_ITEM_FILE_VIDEO_DOCOMO ] = $docomo_id;
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

function get_array_value_by_key( $array, $key )
{
	return intval( 
		$this->_utility_class->get_array_value_by_key( $array, $key, 0 ) ) ;
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

function update_files_from_params( $item_row, $params )
{
	if ( !is_array($params) ) {
		return false;
	}

	$arr = array(
		'cont_id'   => $this->update_file_by_params( $item_row, $params, 'cont' ) ,
		'thumb_id'  => $this->update_file_by_params( $item_row, $params, 'thumb' ) ,
		'middle_id' => $this->update_file_by_params( $item_row, $params, 'middle' ) ,
		'flash_id'  => $this->update_file_by_params( $item_row, $params, 'flash' ) ,
		'docomo_id' => $this->update_file_by_params( $item_row, $params, 'docomo' ) ,
	);
	return $arr ;
}

function update_file_by_params( $item_row, $params, $name )
{
	$item_id = $item_row['item_id'] ;

	if ( ! isset( $params[ $name ] ) ) {
		return 0 ;
	}

	$param = $params[ $name ] ;

	if ( ! is_array($param) ) {
		return 0 ;
	}

	$file_row = $this->get_file_row_by_kind( $item_row, $param['kind'] );

// update if exists
	if ( is_array($file_row) ) {
		$file_id = $file_row['file_id'];

// remove old file
		$this->_photo_class->unlink_current_file( $file_row, $param );

		$ret = $this->_photo_class->update_file( $file_row, $param );
		if ( !$ret ) {
			$this->set_error( $this->_photo_class->get_errors() );
			return 0 ;
		}
		return $file_id;

// insert if new
	} else {
		$newid = $this->_photo_class->insert_file( $item_id, $param );
		if ( !$newid) {
			$this->set_error( $this->_photo_class->get_errors() );
		}
		return $newid ;
	}
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
			$row_update[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id ;
			$row_update['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

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
// file delete
//---------------------------------------------------------
function video_flash_delete( $item_row, $url_redirect )
{
	$this->file_delete_common(
		$item_row, _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH, $url_redirect, false );

	$item_id  = $item_row['item_id'] ;
	$item_row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	$ret = $this->_item_handler->update( $item_row );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n" ;
		$msg .= $this->_item_handler->get_format_error() ;
		redirect_header( $url_redirect, $this->_TIME_FAILED, $msg );
		exit();
	}

	redirect_header( $url_redirect, $this->_TIME_SUCCESS, $this->get_constant('DELETED') );
	exit();
}

function thumb_delete( $item_row, $url_redirect )
{
	$this->file_delete_common( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_THUMB, $url_redirect, true );
}

function middle_delete( $item_row, $url_redirect )
{
	$this->file_delete_common( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_MIDDLE, $url_redirect, true );
}

function file_delete_common( $item_row, $item_name, $url_redirect, $flag_redirect )
{
	$item_id = $item_row['item_id'] ;
	$file_id = $item_row[ $item_name ] ;

	$file_row = $this->_file_handler->get_row_by_id( $file_id );
	if ( ! is_array($file_row ) ) {
		redirect_header( $url, $this->_TIME_FAILED, 'No file record' ) ;
		exit() ;
	}

	$this->unlink_path( $file_row['file_path'] );

	$ret = $this->_file_handler->delete_by_id( $file_id );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n" ;
		$msg .= $this->_file_handler->get_format_error() ;
		redirect_header( $url_redirect, $this->_TIME_FAILED, $msg );
		exit();
	}

	if ( $flag_redirect ) {
		redirect_header( $url_redirect, $this->_TIME_SUCCESS, $this->get_constant('DELETED') );
		exit();
	}

	return true;
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