<?php
// $Id: imagemanager.php,v 1.10 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// get_file_extend_row_by_kind()
// 2009-11-11 K.OHWADA
// $trust_dirname 
// 2009-03-19 K.OHWADA
// strip_slash_from_head()
// 2009-02-20 K.OHWADA
// Fatal error: Call to undefined method: webphoto_inc_catlist->set_uploads_path()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// webphoto_inc_public
// 2008-11-29 K.OHWADA
// _build_file_image()
// 2008-09-13 K.OHWADA
// BUG: not show category list if there is not one photo
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_imagemanager
//=========================================================
class webphoto_main_imagemanager extends webphoto_inc_public
{
	var $_xoops_sitename  = null ;
	var $_xoops_mid       = 0 ;
	var $_xoops_uid       = 0 ;
	var $_is_module_admin = false;

	var $_cfg_makethumb ;
	var $_cfg_usesiteimg ;
	var $_cfg_uploadspath ;

	var $_has_insertable ;
	var $_has_editable ;

	var $_DIRNAME;

	var $_XSIZE_SAMLL = 400;
	var $_YSIZE_SAMLL = 200;
	var $_XSIZE_LARGE = 600;
	var $_YSIZE_LARGE = 450;

	var $_LANG_NO_CATEGORY = 'There are no category';

//	var $_ITEM_ORDERBY = 'item_time_update DESC, item_id DESC';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_imagemanager( $dirname , $trust_dirname )
{
	$this->webphoto_inc_public();
	$this->init_public( $dirname , $trust_dirname );

	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );

	$this->_init_xoops_module( $dirname ) ;
	$this->_init_xoops_param() ;
	$this->_init_xoops_config( $dirname );
	$this->_init_xoops_group_permission( $dirname , $trust_dirname );

	$this->_catlist_class 
		=& webphoto_inc_catlist::getSingleton( $dirname , $trust_dirname );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_main_imagemanager( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function get_template()
{
	$str = 'db:'. $this->_DIRNAME .'_main_imagemanager.html';
	return $str;
}

function check()
{
	if ( $this->_xoops_mid == 0 ) {
		die( _MODULENOEXIST ) ;
	}

// checking permission
	$moduleperm_handler =& xoops_gethandler('groupperm');
	if ( ! $moduleperm_handler->checkRight( 
		'module_read', $this->_xoops_mid, $this->_xoops_groups )) {
		die( _NOPERM ) ;
	}

	if ( empty( $_GET['target'] ) ) {
		exit ;
	}
}

function main()
{

// Get variables
	$target = $this->sanitize( $_GET['target'] );
	$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0 ;
	$num    = isset($_GET['num'])    ? intval($_GET['num'])    : 10 ;
	$start  = isset($_GET['start'])  ? intval($_GET['start'])  : 0 ;

	$xsize = $this->_XSIZE_SAMLL;
	$ysize = $this->_YSIZE_SAMLL;
	$total  = 0 ;
	$photos = array();
	$cat_options = null;
	$pagenav = null;

	$show_cat_form = false ;

	// use [siteimg] or [img]
	if ( $this->_cfg_usesiteimg ) {
		// using links without XOOPS_URL
		$IMG = 'siteimg' ;
		$URL = 'siteurl' ;

	} else {
		// using links with XOOPS_URL
		$IMG = 'img' ;
		$URL = 'url' ;
	}

	$cat_tree = $this->_catlist_class->get_cat_all_child_tree_array( 0 );

	if ( sizeof( $cat_tree ) > 0 ) {
		$show_cat_form = true ;

		$xsize = $this->_XSIZE_LARGE;
		$ysize = $this->_YSIZE_LARGE;
		$cat_options = $this->_build_cat_options( $cat_id, $cat_tree );

		if ( $cat_id > 0 ) {
			$total  = $this->get_item_count_for_imagemanager( $cat_id ) ;
		}
	}

	if ( $total > 0 ) {

		if ( $total > $num ) {
			$extra = "target=$target&amp;cat_id=$cat_id&amp;num=$num";
			$nav   = new XoopsPageNav( $total , $num , $start , 'start' , $extra ) ;
			$pagenav = $nav->renderNav() ;
		}

		$i = 1 ;

		$item_rows = $this->get_item_rows_for_imagemanager( $cat_id, $num , $start );
		foreach( $item_rows as $item_row )
		{
			$cont_row  = $this->get_file_extend_row_by_kind( 
				$item_row, _C_WEBPHOTO_FILE_KIND_CONT );
			$thumb_row = $this->get_file_extend_row_by_kind( 
				$item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

			if ( !is_array($cont_row) ) {
				continue;
			}

			if ( !is_array($thumb_row) ) {
				continue;
			}

			list( $cont_src_url, $cont_tag_url, $cont_width, $cont_height ) =
				$this->_build_file_image( $cont_row ) ;

			list( $thumb_src_url, $thumb_tag_url, $thumb_width, $thumb_height ) =
				$this->_build_file_image( $thumb_row ) ;

			$item_id    = $item_row['item_id'];
			$item_uid   = $item_row['item_uid'];
			$item_title = $item_row['item_title'];
			$item_kind  = $item_row['item_kind'];
			$cont_ext   = $cont_row['file_ext'];

			$xcodel  = "[{$URL}={$cont_tag_url}][{$IMG} align=left]{$thumb_tag_url}[/{$IMG}][/{$URL}]";
			$xcodec  = "[{$URL}={$cont_tag_url}][{$IMG}]{$thumb_tag_url}[/{$IMG}][/{$URL}]";
			$xcoder  = "[{$URL}={$cont_tag_url}][{$IMG} align=right]{$thumb_tag_url}[/{$IMG}][/{$URL}]";
			$xcodebl = "[{$IMG} align=left]{$cont_tag_url}[/{$IMG}]";
			$xcodebc = "[{$IMG}]{$cont_tag_url}[/{$IMG}]";
			$xcodebr = "[{$IMG} align=right]{$cont_tag_url}[/{$IMG}]";

			$photos[] = array(
				'photo_id'     => $item_id ,
				'cont_ext'     => $cont_ext ,
				'cont_width'   => $cont_width ,
				'cont_height'  => $cont_height ,
				'thumb_width'  => $thumb_width ,
				'thumb_height' => $thumb_height ,
				'nicename'     => $this->sanitize( $item_title ) ,
				'src'          => $thumb_src_url ,
				'can_edit'     => $this->_build_can_edit( $item_uid ) ,
				'xcodel'       => $xcodel ,
				'xcodec'       => $xcodec ,
				'xcoder'       => $xcoder ,
				'xcodebl'      => $xcodebl ,
				'xcodebc'      => $xcodebc ,
				'xcodebr'      => $xcodebr ,
				'is_normal'    => $this->is_image_kind( $item_kind ) ,
				'count'        => $i ,
			) ;

			$i ++;
		}
	}

	$param = array(
		'lang_align'      => _ALIGN ,
		'lang_add'        => _ADD ,
		'lang_close'      => _CLOSE ,
		'lang_left'       => _LEFT ,
		'lang_center'     => _CENTER ,
		'lang_right'      => _RIGHT ,
		'lang_imgmanager' => _IMGMANAGER ,
		'lang_image'      => _IMAGE ,
		'lang_imagename'  => _IMAGENAME ,
		'lang_addimage'   => _ADDIMAGE ,
		'lang_imagesize'  => _WEBPHOTO_CAPTION_IMAGEXYT ,
		'lang_refresh'    => _WEBPHOTO_CAPTION_REFRESH ,
		'lang_title_edit' => _WEBPHOTO_TITLE_EDIT ,

		'sitename'    => $this->_xoops_sitename ,
		'target'      => $target ,
		'dirname'     => $this->_DIRNAME ,
		'cat_id'      => $cat_id ,
		'can_add'     => ( $this->_has_insertable && $cat_id ) ,
		'makethumb'   => $this->_cfg_makethumb ,
		'xsize'       => $xsize ,
		'ysize'       => $ysize ,
		'cat_options' => $cat_options ,
		'image_total' => $total ,
		'pagenav'     => $pagenav ,

		'show_cat_form'    => $show_cat_form ,
		'lang_no_category' => $this->_LANG_NO_CATEGORY ,

	);

	return array( $param, $photos );
}

function _build_file_image( $file_row )
{
	$url    = null ;
	$width  = 0 ;
	$height = 0 ;

	if ( ! is_array($file_row) ) {
		return array( $url, $width, $height );
	}

	$url      = $file_row['file_url'] ;
	$path     = $file_row['file_path'] ;
	$width    = $file_row['file_width'] ;
	$height   = $file_row['file_height'] ;
	$full_url = $file_row['full_url'] ;
	$exists   = $file_row['full_path_exists'] ;

	$tag_path = $this->_strip_slash_from_head( $path );

	if ( $full_url && $exists ) {
		$src  = $full_url ;
	} elseif ( $url ) {
		$src = $url;
	} else {
		$src = '' ;
	}

	if ( $this->_cfg_usesiteimg && $tag_path ) {
		$tag  = $tag_path ;
	} elseif ( $this->_cfg_usesiteimg && url ) {
		$tag = str_replace( XOOPS_URL.'/' , '', $url );
	} elseif ( $path ) {
		$tag = $src ;
	}

	return array( $src, $tag, $width, $height );
}

function _build_can_edit( $item_uid )
{
	if ( ! $this->_has_editable ) {
		return false ;
	}
	if ( $this->_xoops_uid == $item_uid ) {
		return true ;
	}
	if ( $this->_is_module_admin ) {
		return true;
	}
	return false ;
}

function _strip_slash_from_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) == 0x2f ) {
		$str = substr($str, 1);
	}
	return $str;
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _build_cat_options( $cat_id, $cat_tree )
{
	if ( !is_array($cat_tree) || !count($cat_tree) ) {
		return null;
	}

// select box for category
// BUG: not show category list if there is not one photo
	$count_arr = array() ;
	$catlist = $this->get_item_catlist_for_imagemanager();

	if ( is_array($catlist) && count($catlist) ) {
		foreach ( $catlist as $item_row ) {
			$count_arr[ $item_row['item_cat_id'] ] = $item_row['photo_sum'] ;
		}
	}

	$options = '<option value="0">--</option>'."\n" ;

	foreach( $cat_tree as $cat ) 
	{
		$cid    = $cat['cat_id'] ;
		$prefix = str_replace( '.' , '--' , substr( $cat['prefix'] , 1 ) ) ;
		$count  = isset( $count_arr[ $cid ] ) ? $count_arr[ $cid ] : 0 ;
		$selected = ( $cat_id == $cid ) ? ' selected="selected" ' : null;

		$options .= '<option value="'. $cid .'" '. $selected .'>';
		$options .= $prefix . $cat['cat_title'] .' ('. $count .')';
		$options .= "</option>\n" ;
	}

	return $options;
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_xoops_module( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		if ( $module->getVar('isactive') ) {
			$this->_xoops_mid = $module->getVar( 'mid' );
		}
	}
}

function _init_xoops_param()
{
	global $xoopsConfig, $xoopsUser ;

	$this->_xoops_sitename = $xoopsConfig['sitename'] ;

	if (is_object($xoopsUser)) {
		$this->_xoops_uid = $xoopsUser->getVar('uid') ;

		if ( $xoopsUser->isAdmin( $this->_xoops_mid ) ) {
			$this->_is_module_admin = true;
		}
	}
}

function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_uploadspath = $config_handler->get_path_by_name( 'uploadspath' );
	$this->_cfg_makethumb   = $config_handler->get_by_name( 'makethumb' );
	$this->_cfg_usesiteimg  = $config_handler->get_by_name( 'usesiteimg' );
}

function _init_xoops_group_permission( $dirname , $trust_dirname )
{
	$perm_class =& webphoto_inc_group_permission::getSingleton( 
		$dirname , $trust_dirname );

	$this->_has_insertable = $perm_class->has_perm('insertable');
	$this->_has_editable   = $perm_class->has_perm('editable');
}

// --- class end ---
}

?>