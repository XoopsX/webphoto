<?php
// $Id: form_this.php,v 1.9 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
// class webphoto_form_this
//=========================================================
class webphoto_form_this extends webphoto_lib_form
{
	var $_cat_handler;
	var $_item_handler;
	var $_gicon_handler;
	var $_config_class;
	var $_preload_class;

	var $_is_japanese    = false;
	var $_checkbox_array = array();

	var $_FILED_COUNTER_1  = 1;
	var $_FILED_COUNTER_2  = 2;

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

	var $_TAGS_SIZE = 80;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_form_this( $dirname , $trust_dirname )
{
	$this->webphoto_lib_form( $dirname , $trust_dirname );

	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_item_handler =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler =& webphoto_file_handler::getInstance( $dirname );
	$this->_cat_handler  =& webphoto_cat_handler::getInstance(   $dirname );

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
	
	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_form_this( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// config
//---------------------------------------------------------
function get_config_by_name( $name )
{
	return $this->_config_class->get_by_name( $name );
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
	$all_name = $name .'_all';
	$id_name  = $name .'_ids';

	$groups = $this->get_cached_xoops_db_groups() ;
	$perms  = $this->get_group_perms_array_by_row_name( $this->get_row(), $name ) ;
	$all_yes = $this->get_all_yes_group_perms_by_key( $name );

	$text  = '';
	$text .= $this->build_input_checkbox_js_check_all( $all_name, $id_name );
	$text .= $this->get_constant('GROUP_PERM_ALL') ;
	$text .= "<br />\n";
	$text .= $this->build_form_checkbox_group_perms( $id_name, $groups, $perms, $all_yes );

	return $text;
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
//---------------------------------------------------------
function build_input_checkbox_js_check_all( $name, $id_name )
{
	$onclick = "webphoto_check_all(this, '". $id_name ."')";
	$extra   = 'onclick="'. $onclick .'"';
	return $this->build_input_checkbox_yes( $name, 0, $extra );
}

function build_js_check_all()
{
	$text = <<< END_OF_TEXT
/* edit form */
function webphoto_check_all(cbox, prefix) 
{
	var regexp = new RegExp("^" + prefix );
	var inputs = document.getElementsByTagName("input");
	for (i=0; i<inputs.length; i++) {
		var ele = inputs[i];
        if (ele.type == "checkbox" && ele.name.match(regexp)) {
			ele.checked = cbox.checked;
		}
	}
}
END_OF_TEXT;

	return $text."\n";
}

function build_js_envelop( $content )
{
	$text = <<< END_OF_TEXT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
END_OF_TEXT;

	return $text."\n";
}

//---------------------------------------------------------
// preload class
//---------------------------------------------------------
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

// --- class end ---
}

?>