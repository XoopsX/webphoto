<?php
// $Id: cat_form.php,v 1.15 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_timeline_init
// 2010-03-14 K.OHWADA
// BUG: typo prem -> perm
// 2010-02-15 K.OHWADA
// $_FLAG_PERM_ADMIN
// 2010-02-01 K.OHWADA
// build_cat_list_form()
// 2009-12-06 K.OHWADA
// cat_group_id
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_gicon_handler
// 2009-05-17 K.OHWADA
// _build_children_list()
// 2009-04-27 K.OHWADA
// _build_script() -> build_script_edit_js()
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// _build_gmap_iframe()
// 2008-12-12 K.OHWADA
// _build_ele_perm_read()
// 2008-11-08 K.OHWADA
// _build_line_category_file()
// 2008-10-01 K.OHWADA
// submit -> item_manager
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_cat_form
//=========================================================
class webphoto_admin_cat_form extends webphoto_edit_form
{
	var $_gicon_handler;
	var $_timeline_init_class;

	var $_ini_use_cat_group_id;

	var $_FORM_NAME = 'catmanager';
	var $_THIS_FCT  = 'catmanager';
	var $_THIS_URL;
	var $_THIS_URL_EDIT;

	var $_IMG_HEIGHT_LIST = 20;
	var $_IMG_HEIGHT_FORM = 50;
	var $_SIZE_IMGPATH    = 80;
	var $_SIZE_WEIGHT     = 5;
	var $_GMAP_WIDTH      = '100%';
	var $_GMAP_HEIGHT     = '650px';
	var $_FLAG_PERM_ADMIN = false;

	var $_CAT_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_CATEGORY ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_cat_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_gicon_handler  
		=& webphoto_gicon_handler::getInstance( $dirname, $trust_dirname );

	$this->_timeline_init_class =& webphoto_timeline_init::getInstance( $dirname );

	$this->_THIS_URL      = $this->_MODULE_URL .'/admin/index.php?fct=catmanager';
	$this->_THIS_URL_EDIT = $this->_THIS_URL .'&amp;disp=edit&amp;cat_id=';

	$this->_ini_use_cat_group_id = $this->get_ini('use_cat_group_id');
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_cat_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function print_form( $row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_cat.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_cat_form_by_row( $row, $param ),
		$this->build_cat_row( $row ) ,
		$this->build_admin_language()
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_cat_form_by_row( $row, $param )
{
	$mode   = $param['mode'] ;
	$parent = $param['parent'] ;

	$is_new  = false;
	$is_edit = false;

	switch ($mode)
	{
		case 'edit':
			$title   = _AM_WEBPHOTO_CAT_MENU_EDIT;
			$op      = 'update';
			$button  = _EDIT;
			$is_edit = true ;
			break;

		case 'new':
		default:
			$title   = _AM_WEBPHOTO_CAT_MENU_NEW;
			$op      = 'insert';
			$button  = _ADD;
			$is_new  = true ;
			break;
	}

	$this->set_row( $row );
	$child_rows = $this->get_child_rows();

	list( $show_parent, $parent_cat_id, $parent_cat_title_s ) 
		= $this->build_parent();

	list( $show_children, $children_list, $child_num ) 
		= $this->build_children_list( $child_rows );

	list( $show_parent_note, $parent_note_s )
		= $this->build_parent_note( $param );

	$param = array(
		'op'        => $op ,
		'is_edit'   => $is_edit ,

		'show_parent'         => $show_parent ,
		'show_children'       => $show_children ,
		'show_perm_child'     => $show_children ,
		'show_parent_note'    => $show_parent_note ,
		'show_gmap'           => $this->show_gmap() ,
		'show_child_num'      => $is_edit ,
		'show_cat_pid'        => $this->show_cat_pid() ,

// BUG: typo prem -> perm
		'show_cat_perm_read'  => $this->show_cat_perm_read() ,

		'show_cat_group_id'   => $this->show_cat_group_id( $is_edit ) ,

		'parent_cat_id'       => $parent_cat_id ,
		'parent_cat_title_s'  => $parent_cat_title_s ,
		'parent_note_s'       => $parent_note_s ,
		'children_list'       => $children_list ,
		'child_num'           => $child_num ,

		'cat_pid_options'         => $this->cat_pid_options() ,
		'cat_description_ele'     => $this->cat_description_ele() ,
		'cat_img_name_options'    => $this->cat_img_name_options() ,
		'cat_perm_read_checkboxs' => $this->cat_perm_read_checkboxs() ,
		'cat_perm_post_checkboxs' => $this->cat_perm_post_checkboxs() ,
		'cat_group_id_options'    => $this->cat_group_id_options() ,
		'cat_gicon_id_options'    => $this->cat_gicon_id_options() ,

		'img_src_s'   => $this->build_img_src() ,
		'js_img_path' => $this->build_js_img_path() ,

// timeline
		'show_timeline'              => $this->show_timeline() ,
		'cat_timeline_scale_options' => $this->cat_timeline_scale_options() ,

		'lang_title'  => $title ,
		'lang_button' => $button ,
		'lang_delete' => _DELETE ,
		'lang_cancel' => _CANCEL ,
	);

	return $param;
}

function get_child_rows()
{
	$cat_id = $this->get_row_by_key( 'cat_id' );
	$rows   = null ;
	if ( $cat_id > 0 ) {
		$rows = $this->_cat_handler->getChildTreeArray( $cat_id ) ;
	}
	return $rows;
}

function show_cat_pid()
{
	return $this->get_ini('use_cat_pid');
}

function show_cat_perm_read()
{
	if ( $this->_cfg_perm_cat_read > 0 ) {
		return true;
	}
	return false;
}

function show_cat_group_id( $is_edit )
{
	if ( $is_edit &&( $this->_cfg_perm_item_read > 0 ) && $this->_ini_use_cat_group_id ) {
		return true;
	}
	return false;
}

function show_gmap()
{
	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );
	if ( $cfg_gmap_apikey ) {
		return true;
	}
	return false;
}



function build_parent()
{
	$cat_pid = $this->get_row_by_key( 'cat_pid' );
	$row     = null ;

	$show        = false;
	$cat_id      = 0 ;
	$cat_title_s = null;

	if ( $cat_pid > 0 ) {
		$row = $this->_cat_handler->get_row_by_id( $cat_pid ) ;
	}

	if ( is_array($row) ) {
		$show        = true;
		$cat_id      = $row['cat_id'] ;
		$cat_title_s = $this->sanitize( $row['cat_title'] );
	}

	return array( $show, $cat_id, $cat_title_s );
}

function build_parent_note( $param )
{
	$mode   = $param['mode'] ;
	$parent = $param['parent'] ;

	$show = false;
	$str  = null ;

	if (( $mode != 'edit' ) && $parent ) {
		$show = true;
		$str  = sprintf( _AM_WEBPHOTO_CAT_PARENT_FMT, $this->sanitize( $parent ) ) ;
	}

	return array( $show, $str );
}

function build_children_list( $rows )
{
	$show = false;
	$arr  = array();
	$num  = 0;

	if ( !is_array($rows) || !count($rows) ) {
		return array( $show, $arr, $num );
	}

	$show = true;
	$num  = count($rows) ;

	foreach ( $rows as $row ) 
	{
		$arr[] = array(
			'cat_id'      => $row['cat_id'] ,
			'cat_title_s' => $this->sanitize( $row['cat_title'] ) ,
			'prefix'      => $this->_build_prefix( $row ) ,
		);
	}

	return array( $show, $arr, $num );
}

function cat_pid_options()
{
	$cid = $this->get_row_by_key( 'cat_id' );
	$pid = $this->get_row_by_key( 'cat_pid' );
	$options = $this->_cat_handler->build_id_options( true );
	$disabled_list = null;
	if ( $cid > 0 ) {
		$disabled_list = array( $cid ) ;
	}
	return $this->build_form_options( $pid, $options, $disabled_list );
}

function cat_description_ele()
{
	$name  = 'cat_description';
	$value = $this->get_row_by_key( $name );
	return $this->build_form_dhtml( $name, $value );
}

function cat_group_id_options()
{
	$value   = $this->get_row_by_key( 'cat_group_id' ) ;
	$options = $this->get_cached_xoops_db_groups( true ) ;
	$disabled_list = $this->get_system_groups() ;
	return $this->build_form_options( $value, $options, $disabled_list );
}

function cat_gicon_id_options()
{
	$name    = 'cat_gicon_id';
	$value   = $this->get_row_by_key( $name );
	$options = $this->_gicon_handler->get_sel_options( true );
	return $this->build_form_options( $value, $options );
}

function cat_img_name_options()
{
	$value = $this->get_row_by_key( 'cat_img_name' );
	$options = XoopsLists::getImgListAsArray( $this->_CATS_DIR );
	$options = array( '' => '---' ) + $options;
	return $this->build_form_options( $value, $options );
}

function cat_perm_read_checkboxs()
{
	return $this->build_group_perms_checkboxs_by_key( 
		'cat_perm_read', $this->_FLAG_PERM_ADMIN );
}

function cat_perm_post_checkboxs()
{
	return $this->build_group_perms_checkboxs_by_key( 
		'cat_perm_post', $this->_FLAG_PERM_ADMIN );
}

function build_img_src()
{
	$imgsrc_s = null;
	$row = $this->get_row();

	$imgsrc = $this->build_show_imgurl( $row );
	if ( $imgsrc ) {
		$imgsrc_s = $this->sanitize($imgsrc);
	}
	return $imgsrc_s;
}

function build_show_imgurl( $row )
{
	$img_name = $row['cat_img_name'] ;
	if ( $img_name ) {
		$url = $this->_CATS_URL .'/'. $img_name ;
	} else {
		$url = $this->_cat_handler->build_show_img_path( $row );
	}
	return $url;
}

function build_js_img_path()
{
	return $this->_utility_class->strip_slash_from_head( $this->_CATS_PATH );
}

function build_cat_row( $row )
{
	$arr = array();
	foreach ( $row as $k => $v )
	{
		$arr[ $k ]      = $v;
		$arr[ $k.'_s' ] = $this->sanitize( $v );
	}
	return $arr;
}

function build_admin_language()
{
	$arr = array(
// form
		'lang_cat_th_parent'  => _AM_WEBPHOTO_CAT_TH_PARENT ,
		'lang_cat_parent_cap' => _AM_WEBPHOTO_CAT_PARENT_CAP ,
		'lang_cat_child_cap'  => _AM_WEBPHOTO_CAT_CHILD_CAP ,
		'lang_cat_child_num'  => _AM_WEBPHOTO_CAT_CHILD_NUM ,
		'lang_cat_child_perm' => _AM_WEBPHOTO_CAT_CHILD_PERM ,
		'lang_cap_cat_select' => _AM_WEBPHOTO_CAP_CAT_SELECT , 
		'lang_dsc_cat_folder' => _AM_WEBPHOTO_DSC_CAT_FOLDER , 
		'lang_dsc_cat_path'   => _AM_WEBPHOTO_DSC_CAT_PATH ,
		'lang_parent'         => _AM_WEBPHOTO_PARENT ,

// list
		'lang_cat_th_photos'    => _AM_WEBPHOTO_CAT_TH_PHOTOS ,
		'lang_cat_th_operation' => _AM_WEBPHOTO_CAT_TH_OPERATION ,
		'lang_cat_th_image'     => _AM_WEBPHOTO_CAT_TH_IMAGE ,
		'lang_cat_link_edit'    => _AM_WEBPHOTO_CAT_LINK_EDIT ,
		'lang_cat_link_makesubcat' => _AM_WEBPHOTO_CAT_LINK_MAKESUBCAT ,
	);
	return $arr;
}

function _build_prefix( $row )
{
	return str_replace( '.' , ' --' , substr( $row['prefix'] , 1 ) ).' ' ;
}

//---------------------------------------------------------
// timeline
//---------------------------------------------------------
function show_timeline()
{
	return $this->_timeline_init_class->get_init();
}

function cat_timeline_scale_options()
{
	$scale   = $this->get_row_by_key( 'cat_timeline_scale' ) ;
	$value   = $this->_timeline_init_class->scale_to_unit( $scale ) ;
	$options = $this->_timeline_init_class->get_scale_options( true ) ;
	return $this->build_form_options( $value, $options );
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function print_list( $cat_tree_array )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_cat_list.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_cat_list_form( $cat_tree_array ),
		$this->build_admin_language()
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_cat_list_form( $cat_tree_array )
{
	$arr = array();
	foreach ( $cat_tree_array as $row )
	{
		$cat_id = $row['cat_id'];

		$tmp = $row;
		$tmp['tree_photo_num'] = $this->_item_handler->get_count_by_catid( $cat_id );
		$tmp['tree_prefix']    = $this->_build_prefix( $row );

		$imgurl = $this->build_show_imgurl( $row );
		if ( $imgurl ) {
			$tmp['img_show']   = true;
			$tmp['img_src']    = $imgurl;
			$tmp['img_height'] = $this->build_img_height( $row );
		}

		$arr[] = $tmp;
	}

	$param = array(
		'cat_list'     => $arr ,
		'show_cat_add' => $this->show_cat_pid() ,
	);
	return $param;
}

function build_img_height( $row )
{
	$height = $row['cat_orig_height'];
	if ( $height <= 0 ) {
		 $height = $this->_IMG_HEIGHT_LIST;
	} elseif ( $height > $this->_IMG_HEIGHT_LIST ) {
		 $height = $this->_IMG_HEIGHT_LIST;
	}
	return $height;
}

//---------------------------------------------------------
// del confirm
//---------------------------------------------------------
function print_del_confirm( $cat_id )
{
	$hiddens = array(
		'fct'    => 'catmanager' ,
		'action' => 'delete' ,
		'cat_id' => $cat_id ,
	);

	echo $this->build_form_confirm( 
		$hiddens, $this->_THIS_URL, _AM_WEBPHOTO_CATDEL_WARNING, _YES, _NO );

}

// --- class end ---
}

?>