<?php
// $Id: cat_form.php,v 1.5 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
class webphoto_admin_cat_form extends webphoto_form_this
{
	var $_gicon_handler;

	var $_cfg_fsize ;
	var $_cfg_cat_width ;
	var $_cfg_perm_cat_read ;

	var $_FORM_NAME = 'catmanager';
	var $_THIS_FCT  = 'catmanager';
	var $_THIS_URL;

	var $_IMG_HEIGHT_LIST = 20;
	var $_IMG_HEIGHT_FORM = 50;
	var $_SIZE_IMGPATH    = 80;
	var $_SIZE_WEIGHT     = 5;

	var $_CAT_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_CATEGORY ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_cat_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );

	$this->_gicon_handler  =& webphoto_gicon_handler::getInstance( $dirname );

	$this->_cfg_fsize         = $this->_config_class->get_by_name( 'fsize' );
	$this->_cfg_cat_width     = $this->_config_class->get_by_name( 'cat_width' );
	$this->_cfg_perm_cat_read = $this->_config_class->get_by_name( 'perm_cat_read' );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct=catmanager';
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
	$mode   = $param['mode'] ;
	$parent = $param['parent'] ;

	$is_new  = false;
	$is_edit = false;

	switch ($mode)
	{
		case 'edit':
			$title   = _AM_WEBPHOTO_CAT_MENU_EDIT;
			$action  = 'update';
			$button  = _EDIT;
			$is_edit = true ;
			break;

		case 'new':
		default:
			$title   = _AM_WEBPHOTO_CAT_MENU_NEW;
			$action  = 'insert';
			$button  = _ADD;
			$is_new  = true ;
			break;
	}

	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );

	$this->set_row( $row );

	echo $this->_build_script();

	echo $this->build_form_upload( $this->_FORM_NAME );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct' ,   'catmanager' );
	echo $this->build_input_hidden( 'action' , $action );
	echo $this->build_row_hidden(   'cat_id' );

	echo $this->build_input_hidden( 'max_file_size', $this->_cfg_fsize );
	echo $this->build_input_hidden( 'fieldCounter',  $this->_FILED_COUNTER_1 );

	echo $this->build_table_begin();
	echo $this->build_line_title( $title );

	echo $this->build_row_text( $this->get_constant('CAT_TITLE'),
		'cat_title' );

	echo $this->build_line_ele( _AM_WEBPHOTO_CAT_TH_PARENT, 
		$this->_build_ele_selbox_pid() );

	echo $this->build_row_dhtml( $this->get_constant('CAT_DESCRIPTION'), 
		'cat_description' );

	echo $this->_build_line_category_file();

	echo $this->build_row_text( $this->get_constant('CAT_WEIGHT'), 
		'cat_weight', $this->_SIZE_WEIGHT );

	if ( $is_new && $parent ) {
		echo $this->build_line_ele( _AM_WEBPHOTO_CAT_PARENT_CAP , 
			$this->_build_ele_perm_parent( $parent ) );
	}

	if ( $this->_cfg_perm_cat_read > 0 ) {
		echo $this->build_line_ele( $this->get_constant('CAT_PERM_READ'), 
			$this->_build_ele_perm_read() );
	}

	echo $this->build_line_ele( $this->get_constant('CAT_PERM_POST'), 
		$this->_build_ele_perm_post() );

	if ( $is_edit ) {
		echo $this->build_line_ele( _AM_WEBPHOTO_CAT_CHILD_CAP , 
			$this->_build_ele_perm_child() );
	}

	if ( $cfg_gmap_apikey ) {
		echo $this->build_line_ele( $this->get_constant('GMAP_ICON'), 
			$this->_build_ele_gicon() );
	}

	echo $this->build_line_ele( '',  $this->_build_ele_button( $mode ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

function _build_line_category_file()
{
	return $this->build_line_cap_ele( 
		_AM_WEBPHOTO_CAP_CAT_SELECT, 
		_AM_WEBPHOTO_DSC_CAT_FOLDER, 
		$this->_build_ele_img() );
}

function _build_ele_img()
{
	$ele  = $this->_build_img_file();
	$ele .= "<br />\n";
	$ele .= $this->get_constant('OR') ;
	$ele .= "<br />\n";
	$ele .= $this->_build_img_select();
	$ele .= "<br />\n";
	$ele .= $this->get_constant('OR') ;
	$ele .= "<br />\n";
	$ele .= $this->_build_img_path() ;
	$ele .= "<br />\n";
	$ele .= $this->_build_img_show();
	return $ele;
}

function _build_img_file()
{
	$ele  = $this->get_constant( 'CAP_MAXPIXEL' ) .' ';
	$ele .= $this->_cfg_cat_width .' x ';
	$ele .= $this->_cfg_cat_width .' px';
	$ele .= "<br />\n";
	$ele .= $this->get_constant( 'DSC_PIXCEL_RESIZE' ) ;
	$ele .= "<br />\n";
	$ele .= $this->build_form_file( $this->_CAT_FIELD_NAME );
	$ele .= "<br />\n";
	return $ele;
}

function _build_img_select()
{
// xoops.js showImgSelected(imgId, selectId, imgDir, extra, xoopsUrl)
	$onchange = "showImgSelected('clogo', 'cat_img_name', '". $this->_CATS_PATH ."', '', '". XOOPS_URL ."')" ;
	$extra    = 'onchange="'. $onchange .'"';

	$name  = 'cat_img_name';
	$value = $this->get_row_by_key( $name );

	$options = XoopsLists::getImgListAsArray( $this->_CATS_DIR );
	array_unshift( $options, _NONE );

	$ele  = $this->get_constant( 'CAT_IMG_NAME' ) ;
	$ele .= "<br />\n";
	$ele .= $this->build_form_select( $name, $value, $options, 1, $extra );
	$ele .= "<br />\n";

	return $ele ;
}

function _build_img_path()
{
	$name  = 'cat_img_path';
	$value = $this->get_row_by_key( $name );

	$ele  = $this->get_constant( 'CAT_IMG_PATH' ) ;
	$ele .= "<br />\n";
	$ele .= _AM_WEBPHOTO_DSC_CAT_PATH ;
	$ele .= "<br />\n";
	$ele .= $this->build_input_text( $name, $value, $this->_SIZE_IMGPATH );
	$ele .= "<br />\n";

	return $ele ;
}

function _build_img_show()
{
	$ele = $this->_build_img( $this->get_row(), $this->_IMG_HEIGHT_FORM  );
	return $ele;
}

function _build_ele_selbox_pid()
{
	$pid = $this->get_row_by_key( 'cat_pid' );
	return $this->_cat_handler->build_selbox_pid( $pid );
}

function _build_ele_gicon()
{
	$name  = 'cat_gicon_id';
	$value = $this->get_row_by_key( $name );
	return $this->build_form_select(
		$name,  $value, $this->_gicon_handler->get_sel_options(), 1 );
}

function _build_ele_button( $mode )
{
	switch ($mode)
	{
		case 'edit':
			$button = _EDIT;
			break;

		case 'new':
		default:
			$button = _ADD;
			break;
	}

	$str  = $this->build_input_submit( 'submit', $button );
	$str .= ' ';
	if ( $mode == 'edit' ) {
		$str .= $this->build_input_submit( 'del_confirm',  _DELETE );
		$str .= ' ';
	}
	$str .= $this->build_input_reset(  'reset',  _CANCEL );
	return $str;
}

//---------------------------------------------------------
// permission
//---------------------------------------------------------
function _build_ele_perm_read()
{
	return $this->build_ele_group_perms_by_key( 'cat_perm_read' );
}

function _build_ele_perm_post()
{
	return $this->build_ele_group_perms_by_key( 'cat_perm_post');
}

function _build_ele_perm_parent( $parent )
{
	$str  = sprintf( _AM_WEBPHOTO_CAT_PARENT_FMT, $this->sanitize( $parent ) );
	return $str;
}

function _build_ele_perm_child()
{
	$cat_id = $this->get_row_by_key( 'cat_id' );
	$count  = 0 ;

	if ( $cat_id > 0 ) {
		$count = count( $this->_cat_handler->getAllChildId( $cat_id ) ) ;
	}

	$str  = _AM_WEBPHOTO_CAT_CHILD_NUM ;
	$str .= ' : '. $count ."<br />\n";

	if ( $count > 0 ) {
		$str .= $this->build_input_checkbox_yes( 'perm_child', _C_WEBPHOTO_YES ) ;
		$str .= ' ' ;
		$str .= _AM_WEBPHOTO_CAT_CHILD_PERM ;
	}
	return $str;
}

function _build_script()
{
	return $this->build_js_envelop( $this->build_js_check_all() );
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function print_list( $cat_tree_array )
{

// --- form ---
	echo "<form name='MainForm' action='' method='post' style='margin:10px;'>\n";
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'action', 'weight' );

// --- table ---
	echo '<table width="95%" class="outer" cellpadding="4" cellspacing="1">'."\n";

	echo '<tr valign="middle">';
	echo '<th>'. $this->get_constant('CAT_TITLE') .'</th>';
	echo '<th>'. _AM_WEBPHOTO_CAT_TH_PHOTOS .'</th>';
	echo '<th>'. _AM_WEBPHOTO_CAT_TH_OPERATION .'</th>';
	echo '<th>'. $this->get_constant('CAT_WEIGHT').'</th>';
	echo '<th>'. _AM_WEBPHOTO_CAT_TH_IMAGE .'</th>';
	echo '</tr>'."\n";

	foreach( $cat_tree_array as $row ) {
		$this->_print_line( $row );
	}

	echo '<tr class="foot">';
	echo '<td colspan="3"></td>';
	echo '<td colspan="2">';
	echo $this->build_input_submit( 'submit', _EDIT );
	echo '</td></tr>'."\n";

	echo "</table></form>\n" ;
// --- table form end ---

}

function _print_line( $row )
{
	$oddeven = $this->get_alternate_class();

	$cat_id  = intval( $row['cat_id'] ) ;
	$weight  = intval( $row['cat_weight'] ) ;
	$title_s = $this->sanitize( $row['cat_title'] ) ;

	$prefix  = str_replace( '.' , '&nbsp;--' , substr( $row['prefix'] , 1 ) ) ;

	$photos_num  = $this->_item_handler->get_count_by_catid( $cat_id );

	echo '<tr>';
	echo '<td class="'. $oddeven .'" width="100%">';
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=photomanager&amp;cat_id='. $cat_id .'">';
	echo $prefix .' &nbsp; '. $title_s .'</a>';
	echo "</td>\n";

	echo '<td class="'. $oddeven .'" nowrap="nowrap" align="right">';
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=photomanager&amp;cat_id='. $cat_id .'">';
	echo $photos_num;
	echo '</a> &nbsp; ';
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=item_manager&amp;op=submit_form&amp;cat_id='. $cat_id. '">';
	echo $this->build_img_pictadd();
	echo '</a>';
	echo "</td>\n";

	echo '<td class="'. $oddeven .'" nowrap="nowrap" align="center">';
	echo '<a href="'. $this->_THIS_URL .'&amp;disp=edit&amp;cat_id='. $cat_id .'">';
	echo $this->build_img_catedit();
	echo '</a> &nbsp; ';
	echo '<a href="'. $this->_THIS_URL .'&amp;disp=new&amp;cat_id='. $cat_id .'">';
	echo $this->build_img_catadd();
	echo '</a> &nbsp; ';
	echo "</td>\n";

	echo '<td class="'. $oddeven .'" align="center">';
	echo $this->build_input_hidden( 'oldweight['. $cat_id .']' , $weight );
	echo $this->build_input_text(   'weight['.    $cat_id .']' , $weight , $this->_SIZE_WEIGHT );
	echo "</td>\n";

	echo '<td class="'. $oddeven .'" align="center">';
	echo $this->_build_img( $row, $this->_IMG_HEIGHT_LIST ) ;
	echo "</td>\n";

	echo "</tr>\n" ;

}

function _build_img( $row, $max_height )
{
	$ret    = '';
	$imgsrc = $this->build_show_imgurl( $row );
	if ( $imgsrc ) {
		$imgsrc_s = $this->sanitize($imgsrc);
		$height = $row['cat_orig_height'];
		if ( $height <= 0 ) {
			 $height = $max_height;
		} elseif ( $height > $max_height ) {
			 $height = $max_height;
		}
		$ret  = '<a href="'.  $imgsrc_s .'" target="_blank">';
		$ret .= '<img src="'. $imgsrc_s .'" height="'. $height .'" />';
		$ret .= '</a>';
	}
	return $ret;
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