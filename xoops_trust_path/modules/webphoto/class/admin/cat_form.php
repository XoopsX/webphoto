<?php
// $Id: cat_form.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

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
// class webphoto_admin_cat_form
//=========================================================
class webphoto_admin_cat_form extends webphoto_form_this
{
	var $_gicon_handler;

	var $_ADMIN_CAT_PHP;

	var $_IMG_HEIGHT_LIST = 20;
	var $_IMG_HEIGHT_FORM = 50;
	var $_SIZE_IMGPATH    = 80;
	var $_SIZE_WEIGHT     = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_cat_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );

	$this->_gicon_handler  =& webphoto_gicon_handler::getInstance( $dirname );

	$this->_ADMIN_CAT_PHP = $this->_MODULE_URL .'/admin/index.php?fct=catmanager';
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
function print_form( $mode, $row )
{
	switch ($mode)
	{
		case 'edit':
			$title  = _AM_WEBPHOTO_CAT_MENU_EDIT;
			$action = 'update';
			$button = _EDIT;
			break;

		case 'new':
		default:
			$title  = _AM_WEBPHOTO_CAT_MENU_NEW;
			$action = 'insert';
			$button = _ADD;
			break;
	}

	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );

	$this->set_row( $row );

	echo $this->build_form_begin();

	echo $this->build_input_hidden( 'fct' ,   'catmanager' );
	echo $this->build_input_hidden( 'action' , $action );
	echo $this->build_row_hidden(   'cat_id' );

	echo $this->build_table_begin();
	echo $this->build_line_title( $title );

	echo $this->build_row_text(  _WEBPHOTO_CAT_TITLE,  'cat_title' );
	echo $this->build_line_ele(  _WEBPHOTO_CAT_IMG_PATH, $this->_build_ele_img_path() );
	echo $this->build_row_dhtml( _WEBPHOTO_CAT_DESCRIPTION,  'cat_description' );
	echo $this->build_row_text(  _WEBPHOTO_CAT_WEIGHT,  'cat_weight', $this->_SIZE_WEIGHT );
	echo $this->build_line_ele(  _WEBPHOTO_CAT_PERM_POST,  $this->_build_ele_perm_post() );
	echo $this->build_line_ele(  _AM_WEBPHOTO_CAT_TH_PARENT,  $this->_build_ele_selbox_pid() );

	if ( $cfg_gmap_apikey ) {
		echo $this->build_line_ele(  _WEBPHOTO_GMAP_ICON,  $this->_build_ele_gicon() );
	}

	echo $this->build_line_ele( '',  $this->_build_ele_button( $mode ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

function _build_ele_img_path()
{
	$name  = 'cat_img_path';
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text( $name, $value, $this->_SIZE_IMGPATH );
	$ele  .= "<br />\n";
	$ele  .= _AM_WEBPHOTO_DSC_CAT_IMGPATH;
	$ele  .= "<br />\n";
	$ele  .= $this->_build_img( $this->get_row(), $this->_IMG_HEIGHT_FORM  );
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

function _build_ele_perm_post()
{
	$perm_post = $this->get_row_by_key( 'cat_perm_post', null, false );
	$perm_array = $this->str_to_array( $perm_post, _C_WEBPHOTO_PERM_SEPARATOR );

	$all_name  = 'perm_post_allow_all';
	$all_value = _C_WEBPHOTO_NO ;
	if ( $perm_post == _C_WEBPHOTO_PERM_ALLOW_ALL ) {
		$all_value = _C_WEBPHOTO_YES ;
	}

	$text  = '';
	$text .= $this->build_input_checkbox_yes( $all_name, $all_value );
	$text .= _AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL;
	$text .= ' ';

	$group_objs = $this->get_xoops_group_objs();
	foreach ( $group_objs as $obj )
	{
		$groupid = $obj->getVar('groupid');
		$name  = 'perm_post['. $groupid .']';
		$value = intval( in_array( $groupid, $perm_array ) );
		if ( $all_value == 1 ) {
			$value = 1;
		}
		$text .= $this->build_input_checkbox_yes( $name, $value );
		$text .= $obj->getVar('name', 's');
		$text .= ' ';
	}
	return $text;
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
	echo '<th>'. _WEBPHOTO_CAT_TITLE .'</th>';
	echo '<th>'._AM_WEBPHOTO_CAT_TH_PHOTOS.'</th>';
	echo '<th>'._AM_WEBPHOTO_CAT_TH_OPERATION.'</th>';
	echo '<th>'._WEBPHOTO_CAT_WEIGHT.'</th>';
	echo '<th>'._AM_WEBPHOTO_CAT_TH_IMAGE.'</th>';
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
	echo '<a href="'. $this->_MODULE_URL .'/index.php?fct=submit&amp;cat_id='. $cat_id. '">';
	echo $this->build_img_pictadd();
	echo '</a>';
	echo "</td>\n";

	echo '<td class="'. $oddeven .'" nowrap="nowrap" align="center">';
	echo '<a href="'. $this->_ADMIN_CAT_PHP .'&amp;disp=edit&amp;cat_id='. $cat_id .'">';
	echo $this->build_img_catedit();
	echo '</a> &nbsp; ';
	echo '<a href="'. $this->_ADMIN_CAT_PHP .'&amp;disp=new&amp;cat_id='. $cat_id .'">';
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
	$imgsrc = $this->_cat_handler->build_show_imgurl( $row );
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

	echo $this->build_form_confirm( $hiddens, $this->_THIS_URL, _AM_WEBPHOTO_CATDEL_WARNING, _YES, _NO );

}

// --- class end ---
}

?>