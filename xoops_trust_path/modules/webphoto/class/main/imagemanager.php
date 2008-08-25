<?php
// $Id: imagemanager.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

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
// class webphoto_main_imagemanager
//=========================================================
class webphoto_main_imagemanager extends webphoto_inc_handler
{
	var $_config_class;
	var $_perm_class;

	var $_cat_table;
	var $_item_table;
	var $_file_table;

	var $_DIRNAME;

	var $_XSIZE_SAMLL = 400;
	var $_YSIZE_SAMLL = 200;
	var $_XSIZE_LARGE = 600;
	var $_YSIZE_LARGE = 450;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_imagemanager( $dirname )
{
	$this->webphoto_inc_handler();
	$this->init_handler( $dirname );
	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );

	$this->_config_class =& webphoto_inc_config::getInstance();
	$this->_config_class->init( $dirname );

	$this->_perm_class =& webphoto_permission::getInstance( $dirname );

	$this->_cat_table  = $this->prefix_dirname( 'cat' );
	$this->_item_table = $this->prefix_dirname( 'item' );
	$this->_file_table = $this->prefix_dirname( 'file' );

	$this->_DIRNAME = $dirname;
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_imagemanager( $dirname );
	}
	return $instance;
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
	global $xoopsUser, $xoopsModule;

// checking isactive
	$module_handler =& xoops_gethandler('module');
	$xoopsModule =& $module_handler->getByDirname( $this->_DIRNAME );
	if ( empty($xoopsModule) || !$xoopsModule->getVar('isactive')) {
		die( _MODULENOEXIST ) ;
	}

	if (is_object($xoopsUser)) {
		$groups = $xoopsUser->getGroups() ;
	} else {
		$groups = XOOPS_GROUP_ANONYMOUS ;
	}

// checking permission
	$moduleperm_handler =& xoops_gethandler('groupperm');
	if ( !$moduleperm_handler->checkRight('module_read', $xoopsModule->getVar('mid'), $groups )) {
		die( _NOPERM ) ;
	}

	if ( empty( $_GET['target'] ) ) {
		exit ;
	}
}

function main()
{
	global $xoopsConfig , $xoopsUser, $xoopsModule;

	$mid    = 0;
	$my_uid = 0 ;
	$is_module_admin = false;

	if ( is_object($xoopsModule) ) {
		$mid = $xoopsModule->mid();
	}

	if (is_object($xoopsUser)) {
		$my_uid = $xoopsUser->getVar('uid') ;

		if ( $xoopsUser->isAdmin( $mid ) ) {
			$is_module_admin = true;
		}
	}

// Get variables
	$target = htmlspecialchars($_GET['target'], ENT_QUOTES);
	$cat_id = !isset($_GET['cat_id']) ? 0 : intval($_GET['cat_id']);
	$num    = empty( $_GET['num'] ) ? 10 : intval( $_GET['num'] ) ;
	$start  = empty( $_GET['start'] ) ? 0 : intval( $_GET['start'] ) ;

	$xsize = $this->_XSIZE_SAMLL;
	$ysize = $this->_YSIZE_SAMLL;
	$total  = 0 ;
	$photos = array();
	$cat_options = null;
	$pagenav = null;

// config
	$cfg_makethumb  = $this->_config_class->get_by_name( 'makethumb' );
	$cfg_usesiteimg = $this->_config_class->get_by_name( 'usesiteimg' );

// group permission
	$has_insertable = $this->_perm_class->has_insertable();
	$has_editable   = $this->_perm_class->has_editable();

	// use [siteimg] or [img]
	if ( $cfg_usesiteimg ) {
		// using links without XOOPS_URL
		$IMG = 'siteimg' ;
		$URL = 'siteurl' ;

	} else {
		// using links with XOOPS_URL
		$IMG = 'img' ;
		$URL = 'url' ;
	}

	$cat_tree = $this->get_cat_tree();

	if ( sizeof( $cat_tree ) > 0 ) {
		$xsize = $this->_XSIZE_LARGE;
		$ysize = $this->_YSIZE_LARGE;
		$cat_options = $this->build_cat_options( $cat_id, $cat_tree );

		if ( $cat_id > 0 ) {
			$total  = $this->get_item_count_by_catid( $cat_id ) ;
		}
	}

	if ( $total > 0 ) {

		if ( $total > $num ) {
			$extra = "target=$target&amp;cat_id=$cat_id&amp;num=$num";
			$nav   = new XoopsPageNav( $total , $num , $start , 'start' , $extra ) ;
			$pagenav = $nav->renderNav() ;
		}

		$i = 1 ;

		$item_rows = $this->get_item_rows_by_catid( $cat_id, $num , $start );
		foreach( $item_rows as $item_row )
		{
			$cont_row  = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
			$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

			if ( !is_array($cont_row) ) {
				continue;
			}

			if ( !is_array($thumb_row) ) {
				continue;
			}

			$item_id    = $item_row['item_id'];
			$item_uid   = $item_row['item_uid'];
			$item_title = $item_row['item_title'];
			$item_kind  = $item_row['item_kind'];

			$cont_url    = $cont_row['file_url'];
			$cont_width  = $cont_row['file_width'];
			$cont_height = $cont_row['file_height'];
			$cont_ext    = $cont_row['file_ext'];

			$thumb_url    = $thumb_row['file_url'];
			$thumb_width  = $thumb_row['file_width'];
			$thumb_height = $thumb_row['file_height'];

			if ( $cfg_usesiteimg ) {
				$cont_url  = str_replace( XOOPS_URL.'/' , '', $cont_url );
				$thumb_url = str_replace( XOOPS_URL.'/' , '', $thumb_url );
			}

			$xcodel  = "[{$URL}={$cont_url}][{$IMG} align=left]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcodec  = "[{$URL}={$cont_url}][{$IMG}]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcoder  = "[{$URL}={$cont_url}][{$IMG} align=right]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcodebl = "[{$IMG} align=left]{$cont_url}[/{$IMG}]";
			$xcodebc = "[{$IMG}]{$cont_url}[/{$IMG}]";
			$xcodebr = "[{$IMG} align=right]{$cont_url}[/{$IMG}]";

			$can_edit = ( $has_editable && ( $my_uid == $item_uid || $is_module_admin ) ) ;

			$photos[] = array(
				'photo_id'     => $item_id ,
				'cont_ext'     => $cont_ext ,
				'cont_width'   => $cont_width ,
				'cont_height'  => $cont_height ,
				'thumb_width'  => $thumb_width ,
				'thumb_height' => $thumb_height ,
				'nicename'     => htmlspecialchars( $item_title, ENT_QUOTES ) ,
				'src'          => $thumb_url ,
				'can_edit'     => $can_edit ,
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

		'sitename'    => $xoopsConfig['sitename'] ,
		'target'      => $target ,
		'dirname'     => $this->_DIRNAME ,
		'cat_id'      => $cat_id ,
		'can_add'     => ( $has_insertable && $cat_id ) ,
		'makethumb'   => $cfg_makethumb ,
		'xsize'       => $xsize ,
		'ysize'       => $ysize ,
		'cat_options' => $cat_options ,
		'image_total' => $total ,
		'pagenav'     => $pagenav ,

	);

	return array( $param, $photos );
}

function is_image_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_IMAGE ) {
		return true;
	}
	return false;
}

function get_file_row_by_kind( $item_row, $kind )
{
	$id = $this->get_file_id_by_kind( $item_row, $kind );
	if ( $id > 0 ) {
		return $this->get_file_row_by_id( $id );
	}
	return false ;
}

function get_file_id_by_kind( $item_row, $kind )
{
	$name = 'item_file_id_'.$kind;
	if ( isset( $item_row[ $name ] ) ) {
		return  $item_row[ $name ] ;
	}
	return false ;
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function build_cat_options( $cat_id, $cat_tree )
{
	// select box for category
	$photo_counts = array() ;
	$catlist = $this->get_item_catlist();

	if ( !is_array($catlist) || !count($catlist) ) {
		return null;
	}

	if ( !is_array($cat_tree) || !count($cat_tree) ) {
		return null;
	}

	foreach ( $catlist as $item_row ) {
		$photo_counts[ $item_row['item_cat_id'] ] = $item_row['photo_sum'] ;
	}

	$cat_options = '<option value="0">--</option>'."\n" ;

	foreach( $cat_tree as $cat ) 
	{
		$category_id = $cat['cat_id'] ;
		$prefix = str_replace( '.' , '--' , substr( $cat['prefix'] , 1 ) ) ;
		$photo_count = isset( $photo_counts[ $category_id ] ) ? $photo_counts[ $category_id ] : 0 ;
		$selected = ( $cat_id == $category_id ) ? ' selected="selected" ' : null;

		$cat_options .= '<option value="'. $category_id .'" '. $selected .'>';
		$cat_options .= $prefix . $cat['cat_title'] .' ('. $photo_count .')';
		$cat_options .= "</option>\n" ;
	}

	return $cat_options;
}

function get_cat_tree()
{
	$cattree = new XoopsTree( $this->_cat_table , 'cat_id' , 'cat_pid' ) ;
	return $cattree->getChildTreeArray( 0 , 'cat_title' ) ;
}

function get_item_catlist( $limit=0 , $offset=0 )
{
	$sql  = 'SELECT item_cat_id, COUNT(item_id) AS photo_sum ';
	$sql .= ' FROM .'. $this->_item_table ;
	$sql .= ' WHERE item_status > 0 ';
	$sql .= ' GROUP BY item_cat_id' ;
	$sql .= ' ORDER BY item_cat_id' ;
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

function get_item_count_by_catid( $cat_id )
{
	$sql  = 'SELECT COUNT(*) FROM '. $this->_item_table ;
	$sql .= ' WHERE item_cat_id='.intval($cat_id);
	$sql .= ' AND item_status > 0 ';
	return $this->get_count_by_sql( $sql );
}

function get_item_rows_by_catid( $cat_id, $limit=0 , $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_item_table ;
	$sql .= ' WHERE item_cat_id='.intval($cat_id);
	$sql .= ' AND item_status > 0 ';
	$sql .= ' ORDER BY item_time_update DESC';
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

function get_file_row_by_id( $file_id )
{
	$sql  = 'SELECT * FROM .'. $this->_file_table ;
	$sql .= ' WHERE file_id='.intval($file_id);
	return $this->get_row_by_sql( $sql );
}

// --- class end ---
}

?>