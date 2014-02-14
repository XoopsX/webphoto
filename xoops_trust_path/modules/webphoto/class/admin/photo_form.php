<?php
// $Id: photo_form.php,v 1.9 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// build_form_admin_edit()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_cat_selbox
// 2009-01-10 K.OHWADA
// _check_deadlink()
// 2008-11-29 K.OHWADA
// _WEBPHOTO_DSC_SET_TIME_UPDATE -> _WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE
// 2008-11-16 K.OHWADA
// ahref_file -> media_url_s 
// 2008-10-01 K.OHWADA
// submit -> item_manager
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// used preload_init()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_photo_form
//=========================================================
class webphoto_admin_photo_form extends webphoto_edit_form
{
	var $_show_class;
	var $_cat_selbox_class;
	var $_multibyte_class;

	var $_MAX_COL = 4 ;
	var $_PERPAGE = 20;
	var $_TITLE_LENGTH = 20;

	var $_get_catid;
	var $_get_pos;
	var $_get_txt;
	var $_get_mes;
	var $_get_userstart;

	var $_perpage;

	var $_COLOR_WHITE = '#FFFFFF';
	var $_COLOR_PINK  = '#FFE4E1';	/* mistrose */

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_photo_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );
	$this->init_pagenavi();

	$this->_cat_selbox_class =& webphoto_cat_selbox::getInstance();
	$this->_cat_selbox_class->init( $dirname , $trust_dirname );

	$this->_show_class   =& webphoto_show_photo::getInstance( $dirname , $trust_dirname );

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();

// preload
	$this->preload_init();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_photo_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// print_form
//---------------------------------------------------------
function print_form( $photo_count, $photo_rows, $perpage, $photonavinfo )
{
	$this->_get_catid = $this->_post_class->get_get_int('cat_id') ;
	$this->_get_pos   = $this->_post_class->get_get_int('pos');
	$this->_get_txt   = $this->_post_class->get_get_text('txt');
	$this->_get_mes   = $this->_post_class->get_get_text('mes');
	$this->_get_userstart = $this->_post_class->get_get_int('userstart'); 

	$onclick_off = ' onclick="with(document.MainForm){ for(i=0;i<length;i++){ if(elements[i].type==\'checkbox\'){ elements[i].checked=false; }}}" ';
	$onclick_on = ' onclick="with(document.MainForm){ for(i=0;i<length;i++){ if(elements[i].type==\'checkbox\'){ elements[i].checked=true; }}}" ';
	$onclick_delete = ' onclick="if(confirm(\''. _AM_WEBPHOTO_JS_REMOVECONFIRM .'\')){ document.MainForm.action.value=\'delete\'; submit(); }" ';

	$url_pictadd = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&amp;op=submit_form&amp;cat_id='. $this->_get_catid;

// --- print ---
	echo '<div style="border: 2px solid #2F5376; padding:8px; width:100%;" class="bg4">'."\n" ;
	echo '<p><font color="blue">'. $this->_get_mes ."</font></p>\n";

	$this->_print_form_search_table( $photo_count, $perpage );

	echo '<div align="center" style="margin:0px;">';
	echo $photonavinfo;
	echo "</div>\n";

	echo '<div align="right" style="margin:0px;">';
	echo '<a href="'.  $url_pictadd .'" >';
	echo $this->build_img_pictadd();
	echo _AM_WEBPHOTO_CAT_LINK_ADDPHOTOS;
	echo "</a></div>\n";

// --- form ---
	echo $this->build_form_tag( 'MainForm', $this->_THIS_URL, 'POST' );
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'fct', 'photomanager' );

// --- table 1 ---
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="4">'."\n";
	echo '<tr><td align="center" colspan="2">'."\n";

// --- table 2 ---
	echo '<table border="0" cellspacing="5" cellpadding="0" width="100%">'."\n";

// list part
	$col = 0 ;

	foreach ( $photo_rows as $row )
	{
		if( $col == 0 ) {
			echo "\t<tr>\n" ;
		}
	
		$this->_print_photo( $row );

		if ( ++ $col >= $this->_MAX_COL ) {
			echo "\t</tr>\n" ;
			$col = 0 ;
		}
	}

	echo "</table>\n";
// --- table 2 end ---

	echo '</td></tr>';
	echo '<tr><td align="left">';
	echo $this->build_input_button( 'off', _AM_WEBPHOTO_BTN_SELECTNONE, $onclick_off );
	echo ' &nbsp; ';
	echo $this->build_input_button( 'on', _AM_WEBPHOTO_BTN_SELECTALL, $onclick_on );
	echo '</td>';
	echo '<td align="right">';
	echo $this->build_input_hidden( 'action', '' );
	echo _AM_WEBPHOTO_LABEL_REMOVE;
	echo ' ';
	echo $this->build_input_button( 'delete', _DELETE, $onclick_delete );
	echo '</td></tr></table>';
	echo "<br />\n";
// --- table 1 end ---

	$this->_print_form_edit_table( $perpage );

	echo "</form>\n" ;
// --- form ---

	echo "</div>\n" ;

}

//---------------------------------------------------------
// print_num_form
//---------------------------------------------------------
function _print_form_search_table( $photo_count, $perpage )
{
// Page Navigation
	$extra = "fct=photomanager&perpage=". $perpage. "&cat_id=". $this->_get_catid. "&txt=". urlencode($this->_get_txt);
	$this->_pagenavi_class->XoopsPageNav( $photo_count , $perpage , $this->_get_pos , 'pos' , $extra ) ;
	$navi = $this->_pagenavi_class->renderNav( 10 ) ;

	echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">';
	echo '<tr><td align="left">';

	$this->_print_form_search( $perpage );

	echo ' &nbsp; ';
	echo '</td>';
	echo '<td align="right">';
	echo $navi;
	echo ' &nbsp; ';
	echo '</td></tr></table>'."\n";

}

function _print_form_search( $perpage )
{
	echo '<form action="'. $this->_THIS_URL .'" method="GET">';
	echo $this->build_input_hidden( 'fct', 'photomanager' );
	echo $this->_build_num_ele_perpage_select( $perpage );
	echo $this->_build_num_ele_cat_select();
	echo $this->build_input_text( 'txt', $this->sanitize( $this->_get_txt ) );
	echo $this->build_input_submit( 'submit', _AM_WEBPHOTO_BUTTON_EXTRACT );
	echo $this->build_form_end();
}

function _build_num_ele_cat_select()
{
	return $this->_cat_selbox_class->build_selbox( 'cat_title', $this->_get_catid, '---', 'cat_id', 'submit();' );
}

function _build_num_ele_perpage_select( $perpage )
{
// Options for the number of photos in a display
	$numbers = explode( '|', _C_WEBPHOTO_CFG_OPT_PERPAGE );
	$options = '' ;

	foreach( $numbers as $number ) 
	{
		$number = intval( $number ) ;
		if ( $number < 1 ) { continue ; }

		$selected = $this->build_form_selected( $number, $perpage );
		$options .= '<option value="'. $number .'" '. $selected .' >';
		$options .= sprintf( _AM_WEBPHOTO_FMT_PHOTONUM, $number );
		$options .= "</option>\n" ;
	}

	$text  = '<select name="perpage" onchange="submit();" >';
	$text .= $options;
	$text .= "</select>\n";
	return $text;
}

//---------------------------------------------------------
// print_photo
//---------------------------------------------------------
function _print_photo( $row )
{
	$cfg_thumb_width  = $this->_config_class->get_by_name( 'thumb_width' ); 
	$cfg_thumb_height = $this->_config_class->get_by_name( 'thumb_height' ); 

	$show = $this->_show_class->build_photo_show_light( $row );

	$photo_id       = $show['photo_id'];
	$photo_title    = $show['title'];
	$photo_status   = $show['status'];
	$media_url_s    = $show['media_url_s'];
	$thumb_src_s    = $show['img_thumb_src_s'];
	$thumb_width    = $show['img_thumb_width'];
	$thumb_height   = $show['img_thumb_height'];

	if ( strlen($photo_title) > $this->_TITLE_LENGTH ) {
		$photo_title = $this->_multibyte_class->sub_str( 
			$photo_title, 0, $this->_TITLE_LENGTH ).'...';
	} 

	$photo_title_s = $this->sanitize($photo_title);

	if ( $thumb_width && $thumb_height ) {
		$img = '<img src="'. $thumb_src_s. '" border="0" alt="'. $photo_title_s .'" title="'. $photo_title_s .'" width="'. $thumb_width .'" height="'. $thumb_height .'" />'."\n";
	} else {
		$img = '<img src="'. $thumb_src_s. '" border="0" alt="'. $photo_title_s .'" title="'. $photo_title_s .'" width="'. $cfg_thumb_width .'" />'."\n";
	}

	if ( $media_url_s ) {
		$link  = '<a href="'. $media_url_s .'" target="_blank">'."\n";
		$link .= $img . "</a>\n" ;
	} else {
		$link = $img ;
	}

// pink for wating addmission
	$bgcolor = $photo_status ? $this->_COLOR_WHITE : $this->_COLOR_PINK ;

	$editbutton     = $this->_build_edit_button( $photo_id );
	$deadlinkbutton = $this->_build_deadlink_button( $row );

	$pixel_gif_w = $this->build_img_pixel( $cfg_thumb_width, 1 );
	$pixel_gif_h = $this->build_img_pixel( 1, $cfg_thumb_height );

	echo '<td align="center" style="background-color:'. $bgcolor. '; margin: 0px; padding: 1px; border-width:0px 1px 1px 0px; border-style: solid; border-color:black;">';

// --- table ---
	echo '<table border="0" cellpadding="0" cellmargin="0">';

	echo '<tr>';
	echo '<td></td>';
	echo '<td>'. $pixel_gif_w .'</td>';
	echo '<td></td>';
	echo "</tr>\n";

	echo '<tr>';
	echo '<td>'. $pixel_gif_h .'</td>';
	echo '<td align="center">';
	echo $link ;
	echo '</td>';
	echo '<td>'. $pixel_gif_h .'</td>';
	echo "</tr>\n";

	echo '<tr>';
	echo '<td></td>';
	echo '<td align="center">';
	echo $editbutton .' '. $deadlinkbutton;
	echo ' <span style="font-size:10pt;">';
	echo $photo_title_s;
	echo '</span> ';
	echo '<input type="checkbox" name="ids[]" value="'. $photo_id .'" style="border:none;">';
	echo '</td>';
	echo '<td></td>';
	echo "</tr>\n";

	echo "</table>\n";
// --- table end ---

	echo '</td>';

}

function _build_edit_button( $id )
{
	$url_edit = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id='. $id;

	$button  = '<a href="'. $url_edit .'" target="_blank">';
	$button .= $this->build_img_edit();
	$button .= "</a> \n";
	return $button;
}

function _build_deadlink_button( $row )
{
	if ( $this->_check_deadlink( $row ) ) {
		return $this->build_img_deadlink();
	}
	return null ;
}

function _check_deadlink( $row )
{
// exist main photo
	if ( $this->exists_photo( $row ) ) {
		return false ;
	}
// others without main photo
	if ( $row['item_external_url'] ) {
		return false ;
	}
	if ( $row['item_embed_type'] ) {
		return false ;
	}
	if ( $row['item_playlist_type'] ) {
		return false ;
	}
	return true ;
}

//---------------------------------------------------------
// print_edit_table
//---------------------------------------------------------
function _print_form_edit_table( $perpage )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_photo.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_form_admin_edit(),
		$this->build_admin_param( $perpage ) ,
		$this->build_admin_language()
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_form_admin_edit()
{
	list( $show_user_list, $user_list, $new_uid_options )
		= $this->get_user_param( 0, $this->_get_userstart );

	$arr = array(
		'new_cat_id_options' => $this->new_cat_id_options() ,
		'new_datetime'       => $this->new_datetime() ,
		'new_time_update'    => $this->new_time_update() ,
		'new_text_array'     => $this->new_text_array() ,

		'new_uid_options'    => $new_uid_options ,
		'show_user_list'     => $show_user_list ,
		'user_list'          => $user_list ,
	);
	return $arr;
}

function new_cat_id_options()
{
	return $this->_cat_selbox_class->build_selbox_options( 
		'cat_title', 0 , _AM_WEBPHOTO_OPT_NOCHANGE ) ;
}

function new_datetime()
{
	return $this->get_mysql_date_today();
}

function new_time_update()
{
	return formatTimestamp( time(), _WEBPHOTO_DTFMT_YMDHI );
}

function new_text_array()
{
// preload
	$item_text_array = $this->_preload_class->exec_function( 'get_form_item_text_array' );

	if ( !is_array($item_text_array) ) {
		return null;
	}

	$arr = array();

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$show  = false;
		$title = '';
		$name  = '';

		$text_name = 'photo_text'.$i;

		if ( in_array( $text_name, $item_text_array) ) {
			$show  = true;
			$title = $this->get_constant( $text_name );
			$name  = 'new_text_'.$i ;
		}

		$arr[] = array(
			'num'     => $i ,
			'show'    => $show ,
			'title'   => $title ,
			'title_s' => $this->sanitize( $title ),
			'name'    => $name ,
		);
	}

	return $arr;
}

function build_admin_param( $perpage )
{
	$arr = array(
		'perpage'    => $perpage ,
		'catid'      => $this->_get_catid ,
		'pos'        => $this->_get_pos ,
		'userstart'  => $this->_get_userstart ,
		'txt'        => $this->_get_txt ,
		'mes'        => $this->_get_mes ,
		'txt_encode' => urlencode( $this->_get_txt ) ,
		'mes_encode' => urlencode( $this->_get_mes ) ,
	);
	return $arr;
}

function build_admin_language()
{
	$arr = array(
		'lang_th_batchupdate'    => _AM_WEBPHOTO_TH_BATCHUPDATE ,
		'lang_js_updateconfirm'  => _AM_WEBPHOTO_JS_UPDATECONFIRM ,
		'lang_button_update'     => _AM_WEBPHOTO_BUTTON_UPDATE ,
	);
	return $arr;
}

function XXX_print_form_edit_table()
{
	$userstart = $this->_post_class->get_get('userstart'); 

// preload
	$item_text_array = $this->_preload_class->exec_function( 'get_form_item_text_array' );

	echo $this->build_table_begin();

	echo $this->build_line_title( _AM_WEBPHOTO_TH_BATCHUPDATE );
	echo $this->build_row_text( _WEBPHOTO_PHOTO_TITLE, 'new_title' );
	echo $this->build_row_text( _WEBPHOTO_PHOTO_PLACE, 'new_place' );
	echo $this->build_row_text( _WEBPHOTO_PHOTO_EQUIPMENT, 'new_equipment' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name = 'photo_text'.$i;
		if ( is_array($item_text_array) && in_array( $name, $item_text_array) ) {
			echo $this->build_row_text( 
				$this->get_constant( $name ), 
				str_replace( 'photo_', 'new_', $name ) );
		}
	}

	echo $this->build_row_textarea( _WEBPHOTO_PHOTO_DESCRIPTION, 'new_description' );
	echo $this->build_line_ele( _WEBPHOTO_CATEGORY,   $this->_build_ed_ele_category() );
	echo $this->build_line_ele( _WEBPHOTO_SUBMITTER,  $this->_build_ed_ele_submitter() );
	echo $this->build_line_ele( _WEBPHOTO_PHOTO_DATETIME,     $this->_build_ed_ele_datetime() );
	echo $this->build_line_ele( _WEBPHOTO_PHOTO_TIME_UPDATE,  $this->_build_ed_ele_time_update() );
	echo $this->build_line_ele( '', $this->_build_ed_ele_submit() );

	echo $this->build_table_end();

}

function _build_ed_ele_category()
{
	return $this->_cat_selbox_class->build_selbox( 'cat_title', 0 , _AM_WEBPHOTO_OPT_NOCHANGE, 'new_cat_id' ) ;
}

function _build_ed_ele_submitter()
{
	$list  = $this->get_xoops_user_list( $this->_USER_LIMIT, $this->_get_userstart );
	return $this->build_form_user_select( $list, 'new_uid', 0, true );
}

function _build_ed_ele_datetime()
{
	$name = 'new_datetime';
	$name_checkbox  = $name.'_checkbox';
	$date = $this->get_mysql_date_today();

	$text  = $this->build_input_checkbox_yes( $name_checkbox, $this->_C_NO );
	$text .= _WEBPHOTO_DSC_SET_DATETIME ."<br />\n";
	$text .= $this->build_input_text( $name, $date );

	return $text;
}

function _build_ed_ele_time_update()
{
	$name = 'new_time_update';
	$name_checkbox  = $name.'_checkbox';
	$date = formatTimestamp( time(), _WEBPHOTO_DTFMT_YMDHI );

	$text  = $this->build_input_checkbox_yes( $name_checkbox, $this->_C_NO );
	$text .= _WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE ."<br />\n";
	$text .= $this->build_input_text( $name, $date );

	return $text;
}

function _build_ed_ele_submit()
{
	$extra = ' onclick="return confirm('. _AM_WEBPHOTO_JS_UPDATECONFIRM .')" tabindex="1" ';
	return $this->build_input_submit( 'update', _AM_WEBPHOTO_BUTTON_UPDATE, $extra );
}

// --- class end ---
}

?>