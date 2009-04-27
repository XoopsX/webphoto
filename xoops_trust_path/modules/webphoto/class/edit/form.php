<?php
// $Id: form.php,v 1.6 2009/04/27 18:30:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-27 K.OHWADA
// build_script_edit_js()
// 2009-04-21 K.OHWADA
// Fatal error: Call to undefined method build_js_envelop()
// 2009-04-19 K.OHWADA
// build_form_param()
// 2009-03-15 K.OHWADA
// _SMALL_FIELD_NAME
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// 2009-01-04 K.OHWADA
// build_line_category() etc
// 2008-12-12 K.OHWADA
// $_UPLOADS_PATH
// 2008-11-29 K.OHWADA
// $_ROOT_EXTS_URL
// 2008-11-16 K.OHWADA
// set_xoops_groups()
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
// 2008-10-01 K.OHWADA
// use get_uploads_path()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// added preload_init()
// 2008-08-01 K.OHWADA
// added getInstance()
// tmppath -> tmpdir
// 2008-07-01 K.OHWADA
// used _TMP_PATH
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_form
//=========================================================
class webphoto_edit_form extends webphoto_lib_form
{
	var $_cat_handler;
	var $_item_handler;
	var $_gicon_handler;
	var $_config_class;
	var $_preload_class;
	var $_perm_class ;

	var $_cfg_gmap_apikey ;
	var $_cfg_width ;
	var $_cfg_height ;
	var $_cfg_fsize ;
	var $_cfg_makethumb ;
	var $_cfg_file_size ;
	var $_cfg_perm_item_read ;

	var $_has_deletable ;

	var $_is_japanese    = false;
	var $_checkbox_array = array();

	var $_FILED_COUNTER_1  = 1;
	var $_FILED_COUNTER_2  = 2;
	var $_FILED_COUNTER_4  = 4;

	var $_UPLOADS_PATH ;
	var $_MEDIAS_PATH ;
	var $_WORK_DIR ;
	var $_FILE_DIR ;
	var $_PHOTOS_PATH;
	var $_PHOTOS_DIR ;
	var $_PHOTOS_URL ;
	var $_THUMBS_PATH;
	var $_THUMBS_DIR;
	var $_THUMBS_URL;
	var $_CATS_PATH;
	var $_CATS_DIR;
	var $_CATS_URL;
	var $_MEDIAS_DIR;
	var $_MEDIAS_URL;
	var $_PLAYLISTS_DIR ;
	var $_PLAYLISTS_URL ;
	var $_TMP_DIR;
	var $_MAIL_DIR;
	var $_LOG_DIR;

	var $_ICONS_URL;
	var $_ICON_ROTATE_URL;
	var $_ROOT_EXTS_DIR;
	var $_ROOT_EXTS_URL;
	var $_LIBS_URL;

	var $_TAGS_SIZE = 80;

	var $_EMBED_TYPE_DEFAULT = _C_WEBPHOTO_EMBED_TYPE_DEFAULT ;
	var $_EDITOR_DEFAULT     = _C_WEBPHOTO_EDITOR_DEFAULT ;
	var $_ROTATE_DEFAULT     = _C_WEBPHOTO_ROTATE_DEFAULT ;

	var $_PHOTO_FIELD_NAME   = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO ;
	var $_THUMB_FIELD_NAME   = _C_WEBPHOTO_UPLOAD_FIELD_THUMB ;
	var $_MIDDLE_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_MIDDLE ;
	var $_SMALL_FIELD_NAME   = _C_WEBPHOTO_UPLOAD_FIELD_SMALL ;

	var $_THIS_IMAGEMANEGER_FCT = 'submit_imagemanager';
	var $_THIS_SUBMIT_FCT = 'submit';
	var $_THIS_EDIT_FCT   = 'edit';
	var $_THIS_ADMIN_FCT  = 'item_manager';
	var $_THIS_FILE_FCT   = 'submit_file';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_form( $dirname , $trust_dirname )
{
	$this->webphoto_lib_form( $dirname , $trust_dirname );

	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_item_handler =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler =& webphoto_file_handler::getInstance( $dirname );
	$this->_cat_handler  =& webphoto_cat_handler::getInstance(   $dirname );
	$this->_perm_class   =& webphoto_permission::getInstance( $dirname );

	$this->_cfg_gmap_apikey    = $this->_config_class->get_by_name( 'gmap_apikey' );
	$this->_cfg_width          = $this->_config_class->get_by_name( 'width' );
	$this->_cfg_height         = $this->_config_class->get_by_name( 'height' );
	$this->_cfg_fsize          = $this->_config_class->get_by_name( 'fsize' );
	$this->_cfg_makethumb      = $this->_config_class->get_by_name( 'makethumb' );
	$this->_cfg_file_size      = $this->_config_class->get_by_name( 'file_size' );
	$this->_cfg_perm_item_read = $this->_config_class->get_by_name( 'perm_item_read' );

	$this->_has_deletable = $this->_perm_class->has_deletable();

	$this->_UPLOADS_PATH = $this->_config_class->get_uploads_path();
	$this->_MEDIAS_PATH  = $this->_config_class->get_medias_path();
	$this->_WORK_DIR     = $this->_config_class->get_by_name( 'workdir' );
	$this->_FILE_DIR     = $this->_config_class->get_by_name( 'file_dir' );

	$this->_PHOTOS_PATH = $this->_UPLOADS_PATH.'/photos' ;
	$this->_THUMBS_PATH = $this->_UPLOADS_PATH.'/thumbs' ;
	$this->_CATS_PATH   = $this->_UPLOADS_PATH.'/categories' ;
	$playlists_path     = $this->_UPLOADS_PATH.'/playlists' ;

	$this->_PHOTOS_DIR    = XOOPS_ROOT_PATH . $this->_PHOTOS_PATH ;
	$this->_THUMBS_DIR    = XOOPS_ROOT_PATH . $this->_THUMBS_PATH ;
	$this->_CATS_DIR      = XOOPS_ROOT_PATH . $this->_CATS_PATH ;
	$this->_MEDIAS_DIR    = XOOPS_ROOT_PATH . $this->_MEDIAS_PATH ;
	$this->_PLAYLISTS_DIR = XOOPS_ROOT_PATH . $playlists_path ;

	$this->_PHOTOS_URL    = XOOPS_URL . $this->_PHOTOS_PATH ;
	$this->_THUMBS_URL    = XOOPS_URL . $this->_THUMBS_PATH ;
	$this->_CATS_URL      = XOOPS_URL . $this->_CATS_PATH ;
	$this->_MEDIAS_URL    = XOOPS_URL . $this->_MEDIAS_PATH ;
	$this->_PLAYLISTS_URL = XOOPS_URL . $playlists_path ;

	$this->_TMP_DIR   = $this->_WORK_DIR .'/tmp' ;
	$this->_MAIL_DIR  = $this->_WORK_DIR .'/mail' ;
	$this->_LOG_DIR   = $this->_WORK_DIR .'/log' ;

	$this->_ICONS_URL       = $this->_MODULE_URL .'/images/icons';
	$this->_ICON_ROTATE_URL = $this->_MODULE_URL .'/images/uploader';
	$this->_ROOT_EXTS_URL   = $this->_MODULE_URL .'/images/exts';
	$this->_ROOT_EXTS_DIR   = $this->_MODULE_DIR .'/images/exts';
	$this->_LIBS_URL        = $this->_MODULE_URL .'/libs';

	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// common
//---------------------------------------------------------
function build_form_param( $action=null, $fct=null )
{
	$arr = array_merge( 
		$this->build_base_param() , 
		$this->build_system_language() ,
		$this->get_lang_array()
	);

	if ( $action ) {
		$arr['action'] = $action;
	}
	if ( $fct ) {
		$arr['fct'] = $fct;
	}

	return $arr;
}

function build_base_param()
{
	$arr = array(
		'mydirname'        => $this->_DIRNAME ,
		'xoops_g_ticket'   => $this->get_token() ,
		'is_module_admin'  => $this->_is_module_admin ,
		'has_deletable'    => $this->_has_deletable ,

// for XOOPS 2.0.18
		'xoops_dirname'    => $this->_DIRNAME ,
		'xoops_modulename' => $this->xoops_module_name( 's' ) ,
	);

// config
	$config_array = $this->get_config_array();
	foreach ( $config_array as $k => $v ) {
		$arr[ 'cfg_'.$k ] = $v ;
	}

	return $arr;
}

function build_system_language()
{
	$arr = array(
		'lang_add'     => _ADD ,
		'lang_edit'    => _EDIT ,
		'lang_preview' => _PREVIEW ,
		'lang_cancel'  => _CANCEL ,
		'lang_delete'  => _DELETE ,
		'lang_close'   => _CLOSE ,
		'lang_yes'     => _YES , 
		'lang_no'      => _NO ,
	);
	return $arr;
}

function build_item_row( $row )
{
	$arr = array();
	foreach ( $row as $k => $v )
	{
		$arr[ $k ]      = $v;
		$arr[ $k.'_s' ] = $this->sanitize( $v );
	}
	return $arr;
}

//---------------------------------------------------------
// element
//---------------------------------------------------------
function set_checkbox( $val )
{
	$this->_checkbox_array = $val;
}

function get_checkbox_by_name( $name )
{
	if ( isset( $this->_checkbox_array[ $name ] ) ) {
		 return $this->_checkbox_array[ $name ];
	}
	return null;
}

function build_checkbox_checked( $name, $compare=1 )
{
	$val = $this->get_checkbox_by_name( $name );
	return $this->build_form_checked( $val, $compare );
}

//---------------------------------------------------------
// photo form
// submit.php submit_file.php etc
//---------------------------------------------------------
function ele_maxpixel( $has_resize )
{
	$text = $this->_cfg_width .' x '. $this->_cfg_height ."<br />\n" ;
	if ( $has_resize ) {
		$text .= $this->get_constant('DSC_PIXCEL_RESIZE');
	} else {
		$text .= $this->get_constant('DSC_PIXCEL_REJECT');
	}
	return $text;
}

function ele_maxsize()
{
	$size_desc = '';
	if( ! ini_get( 'file_uploads' ) ) {
		$size_desc = ' &nbsp; <b>"file_uploads" off</b>';
	}

	$text  = $this->format_filesize( $this->_cfg_fsize );
	$text .= $size_desc;
	return $text;
}

function ele_allowed_exts( $allowed_exts )
{
	$text = implode( ' ', $allowed_exts );
	return $text;
}

function item_cat_id_options()
{
	$value = $this->get_row_by_key( 'item_cat_id' );
	return $this->_cat_handler->build_options_with_perm_post( $value );
}

function item_description_dhtml()
{
	$name  = 'item_description';
	$value = $this->get_row_by_key( $name );
	return $this->build_form_dhtml( $name, $value );
}

function build_file_url_size( $file_row )
{
	list( $url, $width, $height ) =
		$this->_file_handler->build_show_file_image( $file_row, true ) ;

	return $url;
}

function get_item_editor( $flag )
{
	$value = $this->get_row_by_key( 'item_editor' );
	if ( $flag && empty($value) ) {
		$value = $this->_EDITOR_DEFAULT;
	}
	return $value;
}

function get_item_embed_type( $flag )
{
	$value = $this->get_row_by_key( 'item_embed_type' );
	if ( $flag && empty($value) ) {
		$value = $this->_EMBED_TYPE_DEFAULT;
	}
	return $value;
}

//---------------------------------------------------------
// build image
//---------------------------------------------------------
function build_img_pictadd()
{
	$str = '<img src="'. $this->_ICONS_URL.'/pictadd.png" width="18" height="15" border="0" alt="'. _WEBPHOTO_TITLE_ADDPHOTO .'" title="'. _WEBPHOTO_TITLE_ADDPHOTO .'" />'."\n" ;
	return $str;
}

function build_img_edit()
{
	$str = '<img src="'. $this->_ICONS_URL.'/edit.png" width="18" height="15" border="0" alt="' ._WEBPHOTO_TITLE_EDIT .'" title="'. _WEBPHOTO_TITLE_EDIT .'" />'."\n";
	return $str;
}

function build_img_deadlink()
{
	$str = '<img src="'. $this->_ICONS_URL.'/deadlink.png"  width="16" height="16" border="0" alt="'. _AM_WEBPHOTO_DEADLINKMAINPHOTO .'" title="'. _AM_WEBPHOTO_DEADLINKMAINPHOTO .'" />'."\n" ;
	return $str;
}

function build_img_pixel( $width, $height )
{
	$str = '<img src="'. $this->_ICONS_URL .'/pixel_trans.png" width="'. $width. '" height="'. $height .'" border="0" alt="" />';
	return $str;
}

function build_img_catadd()
{
	$str = '<img src="'. $this->_ICONS_URL .'/cat_add.png" width="18" height="15"  border="0" alt="'. _AM_WEBPHOTO_CAT_LINK_MAKESUBCAT .'" title="'. _AM_WEBPHOTO_CAT_LINK_MAKESUBCAT .'" />'."\n";
	return $str;
}

function build_img_catedit()
{
	$str = '<img src="'. $this->_ICONS_URL .'/cat_edit.png" width="18" height="15"  border="0" alt="'. _AM_WEBPHOTO_CAT_LINK_EDIT .'" title="'. _AM_WEBPHOTO_CAT_LINK_EDIT .'" />'."\n";
	return $str;
}

function exists_photo( $item_row )
{
	$cont_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	if ( is_array($cont_row) ) {
		$cont_path = $cont_row['file_path'];
	} else {
		return false;
	}

	if ( $cont_path  && is_readable( XOOPS_ROOT_PATH . $cont_path ) ) {
		return true;
	}
	return false;
}

function get_cached_file_row_by_kind( $item_row, $kind )
{
	$file_id = $this->_item_handler->build_value_fileid_by_kind( $item_row, $kind );
	if ( $file_id > 0 ) {
		return $this->_file_handler->get_cached_row_by_id( $file_id );
	}
	return null;
}

//---------------------------------------------------------
// group perms
//---------------------------------------------------------
function build_ele_group_perms_by_key( $name )
{
	$text  = $this->build_group_perms_check_all_by_key( $name );
	$text .= $this->get_constant('GROUP_PERM_ALL') ;
	$text .= "<br />\n";
	$text .= $this->build_group_perms_checkboxs_by_key( $name );
	return $text;
}

function build_group_perms_check_all_by_key( $name )
{
	$all_name = $name .'_all';
	$id_name  = $name .'_ids';
	return $this->build_input_checkbox_js_check_all( $all_name, $id_name );
}

function build_group_perms_checkboxs_by_key( $name )
{
	$id_name = $name .'_ids';
	$groups  = $this->get_cached_xoops_db_groups() ;
	$perms   = $this->get_group_perms_array_by_row_name( $this->get_row(), $name ) ;
	$all_yes = $this->get_all_yes_group_perms_by_key( $name );
	return $this->build_form_checkbox_group_perms( $id_name, $groups, $perms, $all_yes );
}

function get_group_perms_array_by_row_name( $row, $name )
{
	if ( isset( $row[ $name ] ) ) {
		return $this->get_group_perms_array( $row[ $name ] );
	} else {
		return array() ;
	}
}

function get_group_perms_array( $val )
{
	return $this->str_to_array( $val, $this->_PERM_SEPARATOR );
}

//---------------------------------------------------------
// java script
// admin/cat_form.php, mime_form.php
//---------------------------------------------------------
function build_script_edit_js()
{
	return $this->build_script_js_libs( 'edit.js' ) ;
}

function build_script_js_libs( $js )
{
	return $this->build_script_js( $this->_LIBS_URL .'/'. $js ) ;
}

function build_script_js( $src )
{
	$str = '<script src="'. $src .'" type="text/javascript"></script>'."\n";
	return $str;
}

function build_input_checkbox_js_check_all( $name, $id_name )
{
	$onclick = "webphoto_check_all(this, '". $id_name ."')";
	$extra   = 'onclick="'. $onclick .'"';
	return $this->build_input_checkbox_yes( $name, 0, $extra );
}

//---------------------------------------------------------
// preload class
//---------------------------------------------------------
function init_preload()
{
	$this->preload_init();
	$this->preload_constant();
}

function preload_init()
{
	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $this->_DIRNAME , $this->_TRUST_DIRNAME );
}

function preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();

	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

}

//---------------------------------------------------------
// config
//---------------------------------------------------------
function get_config_by_name( $name )
{
	return $this->_config_class->get_by_name( $name );
}

function get_config_array()
{
	return $this->_config_class->get_config_array();
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function build_form_user_select( $sel_name, $sel_value, $none=false )
{
	$list = $this->get_xoops_user_list();

	$opt = '';

	if ( $none ) {
		$opt .= '<option value="0">';
		$opt .= _AM_WEBPHOTO_OPT_NOCHANGE;
		$opt .= "</option>\n" ;
	}

	foreach ( $list as $uid => $uname_s )
	{
		$selected = $this->build_form_selected( $uid, $sel_value );
		$opt .= '<option value="'. $uid .'" '. $selected .' ">';
		$opt .= $uname_s;
		$opt .= "</option>\n";
	}

	$text  = '<select name="'. $sel_name .'">';
	$text .= $opt;
	$text .= "</select>\n";
	return $text;

}

//---------------------------------------------------------
// xoops class
//---------------------------------------------------------
function xoops_module_name( $format='s' )
{
	return $this->_xoops_class->get_my_module_name( $format );
}

// --- class end ---
}

?>