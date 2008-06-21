<?php
// $Id: imagemanager.php,v 1.1 2008/06/21 12:22:18 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_imagemanager
//=========================================================
class webphoto_main_imagemanager extends webphoto_inc_handler
{
	var $_config_class;
	var $_perm_class;

	var $_cat_table;
	var $_photo_table;

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

	$this->_cat_table   = $this->prefix_dirname( 'cat' );
	$this->_photo_table = $this->prefix_dirname( 'photo' );

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
			$total  = $this->get_photo_count_by_catid( $cat_id ) ;
		}
	}

	if ( $total > 0 ) {

		if ( $total > $num ) {
			$extra = "target=$target&amp;cat_id=$cat_id&amp;num=$num";
			$nav   = new XoopsPageNav( $total , $num , $start , 'start' , $extra ) ;
			$pagenav = $nav->renderNav() ;
		}

		$i = 1 ;

		$rows = $this->get_photo_rows_by_catid( $cat_id, $num , $start );
		foreach( $rows as $row )
		{
			extract( $row ) ;

			$photo_url = $photo_cont_url ;
			$thumb_url = $photo_thumb_url ;

			if ( $cfg_usesiteimg ) {
				$photo_url = str_replace( XOOPS_URL.'/' , '', $photo_cont_url );
				$thumb_url = str_replace( XOOPS_URL.'/' , '', $photo_thumb_url );
			}

			$xcodel  = "[{$URL}={$photo_url}][{$IMG} align=left]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcodec  = "[{$URL}={$photo_url}][{$IMG}]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcoder  = "[{$URL}={$photo_url}][{$IMG} align=right]{$thumb_url}[/{$IMG}][/{$URL}]";
			$xcodebl = "[{$IMG} align=left]{$photo_url}[/{$IMG}]";
			$xcodebc = "[{$IMG}]{$photo_url}[/{$IMG}]";
			$xcodebr = "[{$IMG} align=right]{$photo_url}[/{$IMG}]";

			$can_edit = ( $has_editable && ( $my_uid == $photo_uid || $is_module_admin ) ) ;

			$photos[] = array(
				'photo_id'     => $photo_id ,
				'cont_ext'     => $photo_cont_ext ,
				'cont_width'   => $photo_cont_width ,
				'cont_height'  => $photo_cont_height ,
				'thumb_width'  => $photo_thumb_width ,
				'thumb_height' => $photo_thumb_height ,
				'nicename'     => htmlspecialchars( $photo_title, ENT_QUOTES ) ,
				'src'          => $photo_thumb_url ,
				'can_edit'     => $can_edit ,
				'xcodel'       => $xcodel ,
				'xcodec'       => $xcodec ,
				'xcoder'       => $xcoder ,
				'xcodebl'      => $xcodebl ,
				'xcodebc'      => $xcodebc ,
				'xcodebr'      => $xcodebr ,
				'is_normal'    => $this->is_normal_ext( $photo_cont_ext ) ,
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

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function build_cat_options( $cat_id, $cat_tree )
{
	// select box for category
	$photo_counts = array() ;
	$catlist = $this->get_photo_catlist();

	if ( !is_array($catlist) || !count($catlist) ) {
		return null;
	}

	if ( !is_array($cat_tree) || !count($cat_tree) ) {
		return null;
	}

	foreach ( $catlist as $row ) {
		$photo_counts[ $row['photo_cat_id'] ] = $row['photo_sum'] ;
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

function get_photo_catlist( $limit=0 , $offset=0 )
{
	$sql  = 'SELECT photo_cat_id, COUNT(photo_id) AS photo_sum ';
	$sql .= ' FROM .'. $this->_photo_table ;
	$sql .= ' WHERE photo_status > 0 ';
	$sql .= ' GROUP BY photo_cat_id' ;
	$sql .= ' ORDER BY photo_cat_id' ;
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

function get_photo_count_by_catid( $cat_id )
{
	$sql  = 'SELECT COUNT(*) FROM '. $this->_photo_table ;
	$sql .= ' WHERE photo_cat_id='.intval($cat_id);
	$sql .= ' AND photo_status > 0 ';
	return $this->get_count_by_sql( $sql );
}

function get_photo_rows_by_catid( $cat_id, $limit=0 , $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_photo_table ;
	$sql .= ' WHERE photo_cat_id='.intval($cat_id);
	$sql .= ' AND photo_status > 0 ';
	$sql .= ' ORDER BY photo_time_update DESC';
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

// --- class end ---
}

?>