<?php
// $Id: item_manager.php,v 1.22 2009/12/24 06:32:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// mail_approve()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_flash_player
// 2009-05-28 K.OHWADA
// BUG: not show tag
// 2009-05-17 K.OHWADA
// _build_cat_title()
// 2009-05-05 K.OHWADA
// remove _build_form_common_param_admin()
// 2009-04-19 K.OHWADA
// print_form_admin() -> build_form_admin_with_template()
// Fatal error: Class 'webphoto_flashvar_form'
// 2009-03-15 K.OHWADA
// small_delete()
// 2009-02-20 K.OHWADA
// Fatal error: Call to undefined method create_item_row_submit_preview()
// 2009-01-25 K.OHWADA
// _print_form_admin_with_mode() -> _print_form_admin()
// 2009-01-10 K.OHWADA
// webphoto_photo_action -> webphoto_edit_action
// 2009-01-04 K.OHWADA
// webphoto_photo_misc_form
// 2008-12-12 K.OHWADA
// $ext_disp in _print_list_table()
// 2008-12-07 K.OHWADA
// _print_menu_link()
// 2008-11-29 K.OHWADA
// _list_status()
// _get_photo_url()
// 2008-11-16 K.OHWADA
// load_movie() -> build_movie()
// 2008-11-08 K.OHWADA
// webphoto_flash_log
// _thumb_delete()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_item_manager
//=========================================================
class webphoto_admin_item_manager extends webphoto_edit_action
{
	var $_vote_handler;
	var $_flashvar_handler;
	var $_playlist_class;
	var $_flash_class;
	var $_log_class ;
	var $_sort_class ;
	var $_admin_item_form_class;

	var $_sort_array      = null;
	var $_player_id       = 0 ;
	var $_player_title    = null;
	var $_alternate_class = 'even';

// preload
	var $_SHOW_FORM_ADMIN_EMBED    = true;
	var $_SHOW_FORM_ADMIN_EDITOR   = true;
	var $_SHOW_FORM_ADMIN_PLAYLIST = true;

	var $_PERPAGE_DEFAULT = 20;

	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_item_manager( $dirname , $trust_dirname )
{
	$this->webphoto_edit_action( $dirname , $trust_dirname );
	$this->set_flag_admin( true );
	$this->set_fct( 'item_manager' );

	$this->_log_class        =& webphoto_flash_log::getInstance( $dirname );

	$this->_vote_handler     =& webphoto_vote_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_flashvar_handler =& webphoto_flashvar_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_playlist_class   =& webphoto_playlist::getInstance(
		$dirname , $trust_dirname  );
	$this->_flash_class      =& webphoto_flash_player::getInstance( 
		$dirname , $trust_dirname );
	$this->_admin_item_form_class =& webphoto_admin_item_form::getInstance( 
		$dirname , $trust_dirname );

	$this->_sort_class =& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_sort_array = $this->_sort_class->photo_sort_array_admin();
	$this->_sort_class->set_photo_sort_array( $this->_sort_array );

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_item_manager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$action = $this->_get_action() ;

	$this->_init_form();

	switch ( $action ) 
	{
	case 'list_waiting':
		$this->_list_waiting();
		break;

	case 'list_offline':
		$this->_list_offline();
		break;

	case 'list_expired':
		$this->_list_expired();
		break;

	case 'submit_form':
		$this->_submit_form();
		break;

	case 'submit':
		$this->_submit();
		break;

	case 'modify_form': 
		$this->_modify_form();
		break;

	case 'modify':
		$this->_modify();
		break;

	case 'approve':
		$this->_approve();
		break;

	case 'confirm_form': 
		$this->_confirm_form();
		break;

	case 'delete':
		$this->_delete();
		break;

	case 'delete_all':
		$this->_delete_all();
		break;

	case 'video':
		$this->_video();
		break;

	case 'redo':
		$this->_redo();
		break;

	case 'thumb_delete':
		$this->_thumb_delete();
		exit();

	case 'middle_delete':
		$this->_middle_delete();
		exit();

	case 'small_delete':
		$this->_middle_small();
		exit();

	case 'flash_delete':
		$this->_flash_delete();
		exit();

	case 'flashvar_form': 
		$this->_flashvar_form();
		break;

	case 'flashvar_submit':
		$this->_flashvar_submit();
		break;

	case 'flashvar_modify':
		$this->_flashvar_modify();
		break;

	case 'flashvar_restore':
		$this->_flashvar_restore();
		break;

	case 'vote_stats':
		$this->_vote_stats();
		break;

	case 'delete_vote':
		$this->_delete_vote();
		break;

	case 'view_log':
		$this->_view_log();
		break;

	case 'empty_log':
		$this->_empty_log();
		break;	

	case 'refresh_cache':
		$this->_refresh_cache();
		break;

	case 'main':
	case 'menu':
	default:
		$this->_menu();
		break;
	}

}

function _get_action()
{
	$post_op            = $this->_post_class->get_post_get_text('op' );
	$post_conf_delete   = $this->_post_class->get_post_text('conf_delete' );
	$post_thumb_delete  = $this->_post_class->get_post_text('file_thumb_delete' );
	$post_middle_delete = $this->_post_class->get_post_text('file_middle_delete' );
	$post_small_delete  = $this->_post_class->get_post_text('file_small_delete' );
	$post_flash_delete  = $this->_post_class->get_post_text('flash_delete' );
	$post_restore       = $this->_post_class->get_post_text('restore' );

	if ( $post_conf_delete ) {
		return 'confirm_form';
	} elseif ( $post_thumb_delete ) {
		return 'thumb_delete';
	} elseif ( $post_middle_delete ) {
		return 'middle_delete';
	} elseif ( $post_small_delete ) {
		return 'small_delete';
	} elseif ( $post_flash_delete ) {
		return 'flash_delete';
	} elseif ( $post_restore ) {
		return 'flashvar_restore';
	} elseif ( $post_op ) {
		return $post_op;
	} 
	return '';
}

//---------------------------------------------------------
// menu
//---------------------------------------------------------
function _menu()
{
	xoops_cp_header();
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'ITEM_MANAGER' );

	$item_id = $this->_post_class->get_get_int('item_id');
	$start   = $this->_post_class->get_get_int('start');
	$sort    = $this->_post_class->get_get_text('sort');

	$perpage = $this->_get_perpage();
	$orderby = $this->_sort_class->sort_to_orderby( $sort ) ;

	$total_all = $this->_item_handler->get_count_all();
	$item_rows = $this->_item_handler->get_rows_by_orderby( $orderby, $perpage, $start );

	$this->_admin_item_form_class->print_form_refresh_cache();
	echo "<br />\n";

	$this->_admin_item_form_class->print_form_select_item( $item_id, $sort );
	echo "<br />\n";

	$this->_print_menu_status( _C_WEBPHOTO_STATUS_WAITING, 'waiting', false );
	echo $this->build_check_waiting();
	echo "<br />\n";

	$this->_print_menu_status( _C_WEBPHOTO_STATUS_OFFLINE, 'offline', true );
	$this->_print_menu_status( _C_WEBPHOTO_STATUS_EXPIRED, 'expired', true );

	$this->_print_menu_link( 'vote_stats', _AM_WEBPHOTO_VOTE_STATS, true );
	$this->_print_menu_link( 'view_log',   _AM_WEBPHOTO_LOG_VIEW, true );
	echo "<br />\n";

	$this->_print_list_table( 'all', $item_rows );
	$this->_print_list_navi( $total_all, $perpage );

	xoops_cp_footer();
	exit();
}

function _get_perpage()
{
	$perpage = $this->_post_class->get_get_int('perpage');
	if ( $perpage <= 0 ) {
		$perpage = $this->_PERPAGE_DEFAULT ;
	}
	return $perpage ;
}

function _print_menu_status( $status, $mode, $flag_br )
{
	$url   = $this->_THIS_URL.'&amp;op=list_'.$mode ;
	$count = $this->_item_handler->get_count_status( $status );

	$str  = $this->_build_mene_link(
		$url, $this->get_admin_title( $mode ), false );
	$str .= " (". $count .") \n";
	if ( $flag_br ) {
		$str .= "<br />\n";
	}
	echo $str;
}

function _print_menu_link( $op, $title, $flag_br )
{
	$url = $this->_THIS_URL.'&amp;op='.$op ;
	echo $this->_build_mene_link( $url, $title, $flag_br );
}

function _build_mene_link( $url, $title, $flag_br )
{
	$str  = '- <a href="'. $url .'" >';
	$str .= $title ;
	$str .= "</a>";
	if ( $flag_br ) {
		$str .= "<br />\n";
	}
	return $str;
}

function _print_list_table( $mode, $item_rows )
{
	$this->_cat_handler->set_path_separator( ' ' );
	$kind_options = $this->_item_handler->get_kind_options();

	$player_url   = $this->_MODULE_URL.'/admin/index.php?fct=player_manager&amp;op=modPlayer&amp;player_id=' ;

	$FORM_NAME = 'item_manager';
	$action = $this->_MODULE_URL.'/admin/index.php';
	$onclick_all    = ' onclick="with(document.'. $FORM_NAME .'){for(i=0;i<length;i++){if(elements[i].type==\'checkbox\'){elements[i].checked=this.checked;}}}" ';
	$onclick_admin  = ' onclick="document.'. $FORM_NAME .'.op.value=\'approve\'; submit();" ';
	$onclick_delete = ' onclick="if(confirm(\''. _AM_WEBPHOTO_JS_REMOVECONFIRM .'\')){document.'. $FORM_NAME .'.op.value=\'delete_all\'; submit();}" ';

	$is_all     = false;
	$is_waiting = false;

	switch ( $mode )
	{
		case 'waiting':
			$is_waiting = true;
			break;

		case 'all':
		default:
			$is_all = true;
			break;
	}

	if ( $is_waiting ) {
		echo '<form name="'. $FORM_NAME .'" action="'. $action .'" method="post" >'."\n";
		echo '<input type="hidden" name="'. $this->get_token_name() .'" value="'. $this->get_token() .'" />'."\n";
		echo '<input type="hidden" name="fct" value="'. $this->_THIS_FCT .'" />'."\n";
		echo '<input type="hidden" name="op"  value="" />'."\n";
	}

// item table
	echo '<table border="1" cellspacing="0" cellpadding="1" style="font-size: 90%;">'."\n";
	echo '<tr class="head" align="center" colspan="13">'."\n";

	if ( $is_waiting ) {
		echo '<th width="5px">';
		echo '<input type="checkbox" name="dummy" '. $onclick_all .' />';
		echo "</th>\n";

	} else {
		echo '<th width="10%">'. $this->get_constant('ITEM_STATUS') .'</th>'."\n";
	}

	echo '<th>'. $this->get_constant('ITEM_ID') .'</th>'."\n";
	echo '<th width="18%">'. $this->get_constant('ITEM_TITLE') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_KIND') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_EXT') .'</th>'."\n";
	echo '<th>'. $this->get_constant('CATEGORY') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_PLAYER_ID') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_TIME_CREATE') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_TIME_UPDATE') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_HITS') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_VIEWS') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_RATING') .'</th>'."\n";
	echo '<th>'. $this->get_constant('ITEM_VOTES') .'</th>'."\n";
	echo '</tr>'."\n";

	foreach ( $item_rows as $row )
	{
		$item_id   = $row['item_id'];
		$cat_id    = $row['item_cat_id'];
		$player_id = $row['item_player_id'];
		$ext       = $row['item_ext'] ;
		
		if ( $ext ) {
			$ext_disp = $ext ;
		} else {
			$ext_disp = '---' ;
		}

		list( $is_online, $status_report, $status_link, $status_icon )
			= $this->_build_status( $row );

		$photo_url_s  = $this->sanitize( $this->_get_photo_url( $row, $is_online ) );
		$player_link  = '<a href="'. $player_url.'/'.$player_id .'" title="'. _AM_WEBPHOTO_PLAYER_MOD .'">';
		$player_link .= $player_id.'</a>'."\n";

		echo '<tr class="even" colspan="13">'."\n";
		echo '<td align="center">';

		if ( $is_waiting ) {
			echo '<input type="checkbox" name="ids[]" value="'. $item_id .'" />';

		} else {
//			echo $this->_build_link_icon( $item_id, 'modify_form', 'edit.png',   _EDIT );
//			echo $this->_build_link_icon( $item_id, 'delete',      'delete.png', _DELETE );
			echo '<a href="'.$status_link.'" title="'.$status_report.'">';
			echo $this->_build_img_icon( $status_icon );
			echo '</a>'."\n";
		}

		echo '</td>'."\n";

		echo '<td>';
		echo $this->_build_link_icon( $item_id, 'modify_form', 'edit.png',   _EDIT );
		echo $this->_build_ahref_onclick( $item_id, 'modify_form', _EDIT, $item_id );
		echo '</td>'."\n";

		echo '<td width="18%">';
		echo '<a href="'. $photo_url_s .'" title="'. _AM_WEBPHOTO_ITEM_LISTING .'" target="_blank">';
		echo $this->sanitize( $row['item_title'] ) ;
		echo '</a>'."\n";
		echo '</td>'."\n";

		echo '<td>'. $kind_options[ $row['item_kind'] ] .'</td>'."\n";
		echo '<td>'. $this->sanitize( $ext_disp ).'</td>'."\n";
		echo '<td nowrap="nowrap">'. $this->_build_cat_title( $cat_id ) .'</td>'."\n";
		echo '<td>'. $player_link.'</td>'."\n";
		echo '<td>'. $this->format_timestamp( $row['item_time_create'] , 'm' ).'</td>'."\n";
		echo '<td>'. $this->format_timestamp( $row['item_time_update'] , 'm' ).'</td>'."\n";
		echo '<td>'. $row['item_hits'] .'</td>'."\n";
		echo '<td>'. $row['item_views'] .'</td>'."\n";
		echo '<td>'. $row['item_rating'] .'</td>'."\n";

		echo '<td>';
		echo $this->_build_ahref_onclick( $item_id, 'vote_stats', _AM_WEBPHOTO_VOTE_STATS, $row['item_votes'] );
		echo '</td>'."\n";

		echo '</tr>'."\n";     
	}

	if ( $is_waiting ) {
		echo '<tr>';
		echo '<td colspan="13" align="left">';
		echo _AM_WEBPHOTO_LABEL_ADMIT;
		echo ' <input type="button" value="'. _AM_WEBPHOTO_BUTTON_ADMIT .'" '. $onclick_admin .' />';
		echo '</td>';
		echo "</tr>\n";

//		$delete = _DELETE. ' ('. _AM_WEBPHOTO_BUTTON_REFUSE .') ';
//		echo '<tr>';
//		echo '<td colspan="13" align="left">';
//		echo _AM_WEBPHOTO_LABEL_REMOVE ;
//		echo ' <input type="button" value="'. $delete .'" '. $onclick_delete .' />';
//		echo '</td>';
//		echo "</tr>\n";
	}

	echo "</table>\n";

	if ( $is_waiting ) {
		echo "</form>\n";
	}

	echo "<br />\n";
}

function _print_list_navi( $total_all, $perpage )
{
	$op    = $this->_post_class->get_post_get_text('op' );
	$start = $this->_post_class->get_get_int('start');
	$sort  = $this->_post_class->get_get_text('sort');
	$navi_extra = 'fct='.$this->_THIS_FCT.'&amp;op='.$op.'&amp;sort='.$sort.'&amp;perpage='.$perpage;

	$pagenavi_class =& webphoto_lib_pagenavi::getInstance();
	$pagenavi_class->XoopsPageNav( $total_all, $perpage, $start, 'start', $navi_extra );
	echo $pagenavi_class->renderNav();
}

function _get_photo_url( $item_row, $is_online )
{
	$item_id      = $item_row['item_id'] ;
	$external_url = $item_row['item_external_url'] ;

	if ( $is_online ) {
		$url = $this->_MODULE_URL.'/index.php?fct=photo&photo_id='.$item_id ;
	} else {
		$url = $this->_get_cont_url( $item_row );
		if ( empty( $url ) ) {
			$url = $external_url ;
		}
	}
	return $url;
}

function _get_cont_url( $item_row )
{
	$url = null ;
	$cont_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	if ( is_array($cont_row) ) {
		$url  = $cont_row['file_url'] ;
		$path = $cont_row['file_path'] ;
		if ( $path ) {
			$url = XOOPS_URL .'/'. $path ;
		}
	}
	return $url;
}

function _build_status( $row )
{
	$item_id = $row['item_id'];
	$status  = $row['item_status'];
	$publish = $row['item_time_publish'];
	$expire  = $row['item_time_expire'];

	$is_online = false ;
	$report = '';
	$link   = '';
	$icon   = '';

	$photo_url  = $this->_MODULE_URL.'/index.php?fct=photo&amp;photo_id='.$item_id;
	$modify_url = $this->_THIS_URL.'&amp;op=modify_form&amp;item_id='.$item_id;

// online
	switch ( $status )
	{
	case _C_WEBPHOTO_STATUS_WAITING :
		$report = _WEBPHOTO_ITEM_STATUS_WAITING;
		$link   = $this->_THIS_URL.'&amp;op=list_waiting';
		$icon   = 'waiting.png';
		break;

	case _C_WEBPHOTO_STATUS_OFFLINE :
// Entry will Auto-Publish
		if ( ($publish > 0) && ($publish < time()) ) {
			$is_online = true ;
			$report = _AM_WEBPHOTO_STATUS_CHANGE.' : '. $this->format_timestamp($publish,'m');
			$link   = $photo_url ;
			$icon   = 'online.png';
			$this->_item_handler->update_status( $item_id, _C_WEBPHOTO_STATUS_UPDATED, true ) ;

		} else {
			$report = _AM_WEBPHOTO_STATUS_OFFLINE ;
			$link   = $this->_THIS_URL.'&amp;op=list_offline';
			$icon   = 'offline.png';   	           
		}
		break;

	case _C_WEBPHOTO_STATUS_EXPIRED :
		$report = _WEBPHOTO_ITEM_STATUS_EXPIRED.' : '. $this->format_timestamp($expire,'m');
		$link   = $this->_THIS_URL.'&amp;op=list_expired';
		$icon   = 'offline.png'; 
		break;

	case _C_WEBPHOTO_STATUS_APPROVED :
	case _C_WEBPHOTO_STATUS_UPDATED  :
	default :
// Entry has Expired
		if ( ($expire > 0) && ($expire < time()) ) {
			$report = _AM_WEBPHOTO_STATUS_CHANGE.' : '. $this->format_timestamp($expire,'m');
			$link   = $this->_THIS_URL.'&amp;op=list_expired';
			$icon   = 'offline.png';   
			$this->_item_handler->update_status( $item_id, _C_WEBPHOTO_STATUS_EXPIRED, true ) ;

// online
		} else {
			$is_online = true ;
			$report = _AM_WEBPHOTO_STATUS_ONLINE;
			$link   = $photo_url ;
			$icon   = 'online.png';
		}
		break;
	}

	return array( $is_online, $report, $link, $icon );
}

function _build_link_icon( $item_id, $op, $icon, $title )
{
	$str = $this->_build_ahref_onclick( $item_id, $op, $title, $this->_build_img_icon( $icon ) ) ;
	return $str;
}

function _build_ahref_onclick( $item_id, $op, $title, $value )
{
	$url  = $this->_THIS_URL.'&amp;op='.$op.'&amp;item_id='.$item_id ;
	$str  = '<a href="'. $url .'" onClick="location=\''. $url .'\'" title="'. $title .'">';
	$str .= $value ;
	$str .= '</a>'."\n";
	return $str;
}

function _build_img_icon( $icon )
{
	$src = $this->_MODULE_URL.'/images/icons/'.$icon ;
	$str = '<img src="'. $src .'" border="0" />'."\n";
	return $str;
}

function _build_button( $op, $value )
{
	$onclick = "location='".$this->_THIS_URL."&amp;op=".$op."'" ;
	$str = '<input type="button" value="'. $value .'" onClick="'. $onclick .'" />'."\n";   
	return $str;
} 

function _build_cat_title( $cat_id )
{
	$row = $this->_cat_handler->get_row_by_id( $cat_id );
	if ( is_array($row) ) {
		$href = $this->_MODULE_URL .'/admin/index.php?fct=catmanager&amp;disp=edit&amp;cat_id='. $row['cat_id'] ;

		$title  = '<a href="'. $href .'">';
		$title .= $this->sanitize( $row['cat_title'] );
		$title .= '</a>';

	} else {
		$title = $this->highlight( $this->get_constant('ERR_INVALID_CAT') );
	}

	return $title;
}

//---------------------------------------------------------
// list waiting
//---------------------------------------------------------
function _list_waiting()
{
	$this->_list_status( _C_WEBPHOTO_STATUS_WAITING, 'waiting' );
}

function _list_offline()
{
	$this->_list_status( _C_WEBPHOTO_STATUS_OFFLINE, 'offline' );
}

function _list_expired()
{
	$this->_list_status( _C_WEBPHOTO_STATUS_EXPIRED, 'expired' );
}

function _list_status( $status, $mode )
{
	$start   = $this->_post_class->get_get_int('start');
	$perpage = $this->_get_perpage();

	$total = $this->_item_handler->get_count_status( $status );

	xoops_cp_header();
	echo $this->_build_bread_crumb();
	echo $this->build_admin_title( $mode );

	if ( $total == 0 ) {
		echo _AM_WEBPHOTO_ERR_NO_RECORD ."<br />\n";

	} else {
		$item_rows = $this->_item_handler->get_rows_status( $status, $perpage, $start );
		$this->_print_list_table( $mode, $item_rows );
		$this->_print_list_navi( $total, $perpage );
	}

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
function _submit_form()
{
	$mode = 'admin_submit' ;

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	$item_row = $this->create_item_row_default();

	$this->_print_form_embed(    $mode, $item_row );
	$this->_print_form_playlist( $mode, $item_row );
	$this->_print_form_editor(   $mode, $item_row );

	$this->_print_form_admin( $mode, $item_row );

	xoops_cp_footer();
	exit();
}

function _build_bread_crumb()
{
	return $this->build_admin_bread_crumb( 
		$this->get_admin_title( 'ITEM_MANAGER' ), $this->_THIS_URL );
}

//---------------------------------------------------------
// modify form
//---------------------------------------------------------
function _modify_form()
{
	$show_class =& webphoto_show_photo::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$mode = 'admin_modify' ;

	$item_row = $this->_get_item_row_or_redirect();

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	$this->set_param_modify_default( $item_row );

// BUG: not show tag
	$this->_init_form();

	$item_id     = $item_row['item_id'] ;
	$flashvar_id = $item_row['item_flashvar_id'] ;
	$kind        = $item_row['item_kind'] ;

// if use prem_level
	if ( $this->use_item_perm_level() ) {
		$perm = $this->build_item_perm_by_row( $item_row );
		$item_row['item_perm_read'] = $perm;

// if waiting
		if ( $this->is_waiting_status( $item_row['item_status'] ) ) {
			$item_row['item_perm_down'] = $perm;
		}
	}

	$flash_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;
	$flashvar_row = $this->_flashvar_handler->get_cached_row_by_id( $flashvar_id ) ;

	$table_url = $this->_MODULE_URL .'/admin/index.php?fct=item_table_manage&amp;op=form&amp;id='. $item_id ;

	echo $this->build_preview_template( 
		$show_class->build_photo_show( $item_row, $this->get_tag_name_array() ) ) ;
	echo "<br />\n";

	echo '<a href="'. $table_url .'">';
	echo $this->get_admin_title( 'ITEM_TABLE_MANAGE' ).' : '. $item_id ;
	echo "</a><br /><br />\n";

	$options = $this->_editor_class->build_list_options( true );

// for future
//	if ( $this->is_show_admin_form_editor( $options ) ) {
//		$param_editor = $param ;
//		$param_editor['options'] = $options ;
//		$this->_print_form_editor( $item_row, $param_editor );
//	}

	$this->_print_form_admin( $mode, $item_row );

	if ( is_array($flashvar_row) ) {
		$this->_print_form_flashvar( 'admin_item_modify', $flashvar_row );
		echo "<br />\n";

	} else {
		$url = $this->_THIS_URL.'&amp;op=flashvar_form&amp;item_id='. $item_id ;
		echo '<a href="'. $url .'">';
		echo '[ ADD FlashVar ]';
		echo "</a><br /><br />\n";
	}

	if ( $this->is_video_kind( $kind ) ) {
		$this->_print_form_redo( 'admin', $item_row, $flash_row );
	}

	if ( is_array($flashvar_row) ) {
		$this->_show_flash_player( $item_row );
	}

	xoops_cp_footer();
	exit();
}

function _get_item_row_or_redirect()
{
	$item_id  = $this->_post_class->get_post_get_int('item_id') ;
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( !is_array($item_row) ) {
		redirect_header( $this->_THIS_URL , $this->_TIME_FAILED , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit() ;
	}
	
	return $item_row ;
}

function _show_flash_player( $item_row )
{
	$movie = $this->_flash_class->build_movie_by_item_row( $item_row );

	echo "<br />\n";
	echo '<div align="center">'."\n";
	echo $movie; 
	echo "</div><br />\n";
	echo nl2br( $this->sanitize($movie) ) ; 
}

//---------------------------------------------------------
// delete confirm
//---------------------------------------------------------
function _confirm_form()
{
	$item_id  = $this->_post_class->get_post_get_int('item_id') ;
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( !is_array($item_row) ) {
		redirect_header( $this->_THIS_URL , $this->_TIME_FAILED , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit() ;
	}

	xoops_cp_header();

	echo $this->_build_bread_crumb();
	echo $this->build_form_delete_confirm_with_template( $item_row ) ;

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$this->_check_token_and_redirect();

	$ret = $this->_submit_exec();

// success
	if ( $ret == _C_WEBPHOTO_RET_SUCCESS )
	{
		$item_row = $this->get_created_row();
		$item_id  = $item_row['item_id'];

		list( $url, $time, $msg ) = $this->build_redirect( 
			$this->_build_redirect_param( 'admin_submit', $item_id, false ) ) ;

		redirect_header( $url, $time, $msg );
		exit();
	}

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	switch ( $ret )
	{
// video form
		case _C_WEBPHOTO_RET_VIDEO_FORM :
			$this->_print_form_video_thumb(
				'admin_submit', $this->get_created_row() );
			break;

// error
		case _C_WEBPHOTO_RET_ERROR :
			echo $this->get_format_error();
			$this->_print_form_admin( 
				'admin_submit', $this->create_item_row_preview() );
			break;
	}

	xoops_cp_footer();
	exit();
}

function _submit_exec()
{
	$this->get_post_param();
	$ret = $this->submit_exec();

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function _check_token_and_redirect()
{
	$url = $this->_THIS_URL ;
	$item_id  = $this->_post_class->get_post_get_int('item_id') ;
	if ( $item_id > 0 ) {
		$url = $this->_build_modify_form_url( $item_id ) ;
	}
	$this->check_token_and_redirect( $url, $this->_TIME_FAILED );
}

function _build_redirect_param( $mode, $item_id, $is_failed )
{
	switch ( $mode )
	{
		case 'admin_modify':
			$msg_success = $this->get_constant('DBUPDATED') ;
			break;

		case 'admin_submit':
		default :
			$msg_success = $this->get_constant('SUBMIT_RECEIVED') ;
			break;
	}

	$param = array(
		'is_failed'   => $is_failed ,
		'url_success' => $this->_build_modify_form_url( $item_id ) ,
		'msg_success' => $msg_success ,
	);
	return $param;
}

function _build_modify_form_url( $item_id )
{
	$url = $this->_THIS_URL.'&amp;op=modify_form&amp;item_id='. $item_id ;
	return $url ;
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function _modify()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();
	$item_id  = $item_row['item_id'] ;

	$ret = $this->modify( $item_row );

// success
	if ( $ret == _C_WEBPHOTO_RET_SUCCESS )
	{
		list( $url, $time, $msg ) = $this->build_redirect( 
			$this->_build_redirect_param( 'admin_modify', $item_id, false ) ) ;

		redirect_header( $url, $time, $msg );
		exit();
	}

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	switch ( $ret )
	{
// video form
		case _C_WEBPHOTO_RET_VIDEO_FORM :
			$this->_print_form_video_thumb( 
				'admin_modify', $this->get_updated_row() );
			break;

// error
		case _C_WEBPHOTO_RET_ERROR :
			echo $this->get_format_error();
			echo "<br />\n";
			$this->_print_form_admin( 'admin_modify', $item_row );
			break;
	}

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// approve
//---------------------------------------------------------
function _approve()
{
	$this->_check_token_and_redirect();
	$post_ids = $this->_post_class->get_post('ids');

	if ( is_array($post_ids) &&count($post_ids) ){
		$item_rows = $this->_item_handler->get_rows_by_id_array( $post_ids );
		foreach ( $item_rows as $row ) 
		{
			$row['item_status'] = _C_WEBPHOTO_STATUS_APPROVED ;
			if ( $this->use_item_perm_level() ) {
				$perm = $this->build_item_perm_by_row( $row );
				$row['item_perm_read'] = $perm;
				$row['item_perm_down'] = $perm;
			}
			$this->_item_handler->update( $row );
			$this->notify_new_photo( $row );
			$this->mail_approve( $row );
		}
		$msg = _AM_WEBPHOTO_ADMITTING;

	} else {
		$msg = _AM_WEBPHOTO_ERR_NO_SELECT;
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , $msg ) ;
	exit() ;
}

function build_item_perm_by_row( $row )
{
	$level  = $row['item_perm_level'];
	$cat_id = $row['item_cat_id'];
	return $this->_factory_create_class->build_item_perm_by_level_catid( 
		$level, $cat_id );
}

function use_item_perm_level()
{
	return $this->_factory_create_class->use_item_perm_level();
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();
	$item_id  = $item_row['item_id'] ;

	$ret = $this->delete( $item_row );

	$redirect_param = array(
		'is_failed'   => !$ret ,
		'url_success' => $this->_THIS_URL ,
		'url_failed'  => $this->_build_modify_form_url( $item_id ) , 
		'msg_success' => $this->get_constant('DELETED') ,
	);

	list( $url, $time, $msg ) = 
		$this->build_redirect( $redirect_param ) ;

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// delete all
//---------------------------------------------------------
function _delete_all()
{
	$this->_check_token_and_redirect();

	$post_ids = $this->_post_class->get_post('ids');
	if ( is_array($post_ids) &&count($post_ids) ){
		foreach( $post_ids as $id ) {
			$this->_delete_class->delete_photo_by_item_id( $id ) ;
		}
		$msg = $this->get_constant('DELETED');

	} else {
		$msg = _AM_WEBPHOTO_ERR_NO_SELECT;
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , $msg ) ;
	exit() ;
}

//---------------------------------------------------------
// video_thumb
//---------------------------------------------------------
function _video()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();
	$item_id  = $item_row['item_id'] ;

	$mode = $this->_post_class->get_post_text('mode');

// create video thumb
	$ret = $this->video_thumb( $item_row );

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( $mode, $item_id, !$ret ) ) ;

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// video redo exec
//---------------------------------------------------------
function _redo()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();

	$ret = $this->video_redo( $item_row );

// success
	if ( $ret == _C_WEBPHOTO_RET_SUCCESS )
	{
		list( $url, $time, $msg ) = $this->build_redirect( 
			$this->_build_redirect_param( 'admin_modify', $item_id, false ) ) ;

		redirect_header( $url, $time, $msg );
		exit();
	}

	$item_row = $this->_get_item_row_or_redirect();

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	switch ( $ret )
	{
// video form
		case _C_WEBPHOTO_RET_VIDEO_FORM :
			$this->_print_form_video_thumb( 'admin_modify', $item_row );
			break;

// error
		case _C_WEBPHOTO_RET_ERROR :
			echo $this->get_format_error();
			echo "<br />\n";
			$this->_print_form_admin( 'admin_modify', $item_row );
			break;
	}

	xoops_cp_footer();
	exit();
}


//---------------------------------------------------------
// thumb delete
//---------------------------------------------------------
function _thumb_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->thumb_delete( $item_row, $url_redirect );
}

function _middle_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->middle_delete( $item_row, $url_redirect );
}

function _small_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->small_delete( $item_row, $url_redirect );
}

function _flash_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->video_flash_delete( $item_row, $url_redirect );
}

function _delete_common()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();
	$item_id  = $item_row['item_id'] ;
	$url_redirect = $this->_build_modify_form_url( $item_id );
	return array($item_row, $url_redirect);
}

//---------------------------------------------------------
// flashvar form
//---------------------------------------------------------
function _flashvar_form()
{
	xoops_cp_header();
	echo $this->_build_bread_crumb();

	$item_id = $this->_post_class->get_get_int( 'item_id' );
	$flashvar_rows = $this->_flashvar_handler->get_rows_by_itemid( $item_id );

	if ( isset($flashvar_rows[0]) ) {
		$mode = 'admin_item_modify';
		$flashvar_row = $flashvar_rows[0];

	} else {
		$mode = 'admin_item_submit';
		$flashvar_row = $this->_flashvar_handler->create( true );
		$flashvar_row['flashvar_item_id'] = $item_id ;
	}

	$this->_print_form_flashvar( $mode, $flashvar_row );

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// flashvar submit
//---------------------------------------------------------
function _flashvar_submit()
{
// Fatal error: Class 'webphoto_flashvar_edit'
	$edit_class =& webphoto_edit_flashvar_edit::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();

	$ret1 = $edit_class->submit();
	if ( $ret1 == 0 ) {
		$item_row['item_flashvar_id'] = $edit_class->get_newid() ;
		$ret2 = $this->_item_handler->update( $item_row );
		if ( $ret2 ) {
			$ret = 0 ;
		} else {
			$this->set_error( $this->_item_handler->get_errors() );
			$ret = _C_WEBPHOTO_ERR_DB ;
		}
	} else {
		$ret = false;
	}

	list( $url, $time, $msg ) = $this->_build_flashvar_redirect( 
		$ret , 
		$edit_class->get_format_error(), 
		$edit_class->get_error_upload() );

	redirect_header( $url, $time, $msg );
	exit();
}

function _build_flashvar_redirect( $ret, $error, $error_upload )
{
	$time = 0 ;
	$msg  = null ;

	$item_id = $this->_post_class->get_post_int( 'item_id' );
	$url     = $this->_THIS_URL.'&amp;op=modify_form&amp;item_id='.$item_id;

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$time = $this->_TIME_FAILED ;
			$msg  = 'DB Error <br />';
			$msg .= $error ;
			break;

		case _C_WEBPHOTO_ERR_NO_FALSHVAR:
			$time = $this->_TIME_FAILED ;
			$msg  = _AM_WEBPHOTO_ERR_NO_RECORD.'<br />';
			$msg .= $error ;
			break;

		case 0:
			$msg = '';
			if ( $error_upload ) {
				$time = $this->_TIME_PENDING ;
				$msg .= $error ;
				$msg .= "<br />\n";
			} else {
				$time = $this->_TIME_SUCCESS ;
			}
			$msg  = $this->get_constant('DBUPDATED') ;
			break;
	}

	return array( $url, $time, $msg );
}

//---------------------------------------------------------
// flashvar modify
//---------------------------------------------------------
function _flashvar_modify()
{
	$edit_class =& webphoto_flashvar_edit::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$this->_check_token_and_redirect();
	$ret = $edit_class->modify();

	list( $url, $time, $msg ) =  $this->_build_flashvar_redirect( 
		$ret, 
		$edit_class->get_format_error(), 
		$edit_class->get_error_upload() );

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// flashvar restore
//---------------------------------------------------------
function _flashvar_restore()
{
	$edit_class =& webphoto_flashvar_edit::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$this->_check_token_and_redirect();
	$ret = $edit_class->restore();

	list( $url, $time, $msg ) =  $this->_build_flashvar_redirect( 
		$ret, 
		$edit_class->get_format_error(), 
		$edit_class->get_error_upload() );

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// vote stats
//---------------------------------------------------------
function _vote_stats()
{
	$show_class =& webphoto_show_photo::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	$item_id   = $this->_post_class->get_get_int('item_id') ;
	$flag_item = false ;

	if ( $item_id > 0 ) {
		$item_row = $this->_item_handler->get_row_by_id( $item_id );
		if ( is_array($item_row) ) {
			$flag_item = true;
		}
	}

	echo '<h3>'. _AM_WEBPHOTO_VOTE_STATS .'</h3>'."\n";

	if ( ! $flag_item ) {
		$location = $this->_THIS_URL ."&amp;op=vote_stats&amp;item_id=" ;
		$onchange = "window.location='". $location ."'+this.value";
		$selbox   = $this->_item_handler->build_form_selbox( 'item_id', $item_id, 1, $onchange );

		echo _AM_WEBPHOTO_ITEM_SELECT .' ' ;  
		echo $selbox ;
		echo $this->_build_button( 'submit_form', _AM_WEBPHOTO_ITEM_ADD ) ;
		echo $this->_build_button( 'view_log',    _AM_WEBPHOTO_LOG_VIEW ) ;
		echo "<br /><br />\n";
	}

	$user_total  = 0 ;
	$guest_total = 0 ;

	if ( $flag_item ) {
		$total_all = $this->_vote_handler->get_count_by_photoid( $item_id );
		$user_rows = $this->_vote_handler->get_rows_user_by_photoid( $item_id );
		$guest_rows = $this->_vote_handler->get_rows_guest_by_photoid( $item_id );

	} else {
		$total_all = $this->_vote_handler->get_count_all();
		$user_rows = $this->_vote_handler->get_rows_user();
		$guest_rows = $this->_vote_handler->get_rows_guest();
	}

	if ( is_array($user_rows ) ) {
		$user_total = count($user_rows) ;
	}

	if ( is_array($guest_rows ) ) {
		$guest_total = count($guest_rows) ;
	}

	if ( $flag_item ) {
		echo $this->build_preview_template( 
			$show_class->build_photo_show( $item_row, $this->get_tag_name_array() ) ) ;
	}

	echo $this->_vote_build_title( _AM_WEBPHOTO_VOTE_ENTRY, $total_all ) ;
	echo $this->_vote_build_title( _AM_WEBPHOTO_VOTE_USER,  $user_total ) ;

	if ( $user_total > 0 ) {
		$this->_vote_print_user_table( $item_id, $user_rows ) ;
	}

	echo $this->_vote_build_title( _AM_WEBPHOTO_VOTE_GUEST, $guest_total ) ;

	if ( $guest_total > 0 ) {
		$this->_vote_print_guest_table( $item_id, $guest_rows );
	}

	xoops_cp_footer();
}

function _vote_print_user_table( $item_id, $user_rows )
{
	echo '<table border="1" cellspacing="0" cellpadding="1" style="font-size: 90%;">'."\n";
	echo '<tr align="center">';
	echo '<th>'. $this->get_constant( 'USER' ) .'</th>'."\n";
	echo '<th>'. $this->get_constant( 'VOTE_HOSTNAME' ) .'</th>'."\n";
	echo '<th>'. $this->get_constant( 'VOTE_RATING' ) .'</th>'."\n";
	echo '<th>'. _AM_WEBPHOTO_VOTE_USERAVG .'</th>'."\n";
	echo '<th>'. _AM_WEBPHOTO_VOTE_USERVOTES .'</th>'."\n";
	echo '<th>'. $this->get_constant( 'VOTE_TIME_UPDATE' ) .'</th>'."\n";
	echo '<th>'. _DELETE .'</td>';
	echo "</tr>\n";

	foreach ( $user_rows as $row )
	{
		$uid = $row['vote_uid'] ;

		$rows = $this->_vote_handler->get_rows_by_uid( $uid ) ;

		list ( $user_votes, $total, $user_rating )
			= $this->_vote_handler->calc_rating_by_uid( $uid ) ;

		$this->_alternate_class();

		echo '<tr>';
		echo $this->_vote_build_line(
			$this->_xoops_class->get_user_uname_from_id( $uid ) ) ;
		echo $this->_vote_build_line( $row['vote_hostname'] ) ;
		echo $this->_vote_build_line( $row['vote_rating'] ) ;
		echo $this->_vote_build_line( $user_rating ) ;
		echo $this->_vote_build_line( $user_votes ) ;
		echo $this->_vote_build_line(
			formatTimestamp( $row['vote_time_update'] ) ) ;
		echo $this->_vote_build_line(
			$this->_vote_text_form( $item_id, $row['vote_id'] ) , 'center' ) ;
		echo "</tr>\n";
	}

	echo "</table><br /><br />\n";
}

function _vote_print_guest_table( $item_id, $guest_rows )
{
	echo '<table border="1" cellspacing="0" cellpadding="1" style="font-size: 90%;">'."\n";
	echo '<tr align="center">';
	echo '<th>'. $this->get_constant( 'VOTE_HOSTNAME' ) .'</th>'."\n";
	echo '<th>'. $this->get_constant( 'VOTE_RATING' ) .'</th>'."\n";
	echo '<th>'. $this->get_constant( 'VOTE_TIME_UPDATE' ) .'</th>'."\n";
	echo '<th>'. _DELETE .'</th>';
	echo "</tr>\n";

	foreach ( $guest_rows as $row )
	{
		$this->_alternate_class();

		echo '<tr>';
		echo $this->_vote_build_line( $row['vote_hostname'] ) ;
		echo $this->_vote_build_line( $row['vote_rating'] ) ;
		echo $this->_vote_build_line( 
			formatTimestamp( $row['vote_time_update'] ) ) ;
		echo $this->_vote_build_line(
			$this->_vote_text_form( $item_id, $row['vote_id'] ) , 'center'  );
		echo "</tr>\n";

	}

	echo "</table><br /><br />\n";   
}

function _vote_build_title( $title, $total )
{
	$str  = '<strong>'. $title .'</strong>' ;
	$str .= ' ('. _AM_WEBPHOTO_VOTE_TOTAL .' : ' ;
	$str .= $total .' ) ';
	$str .= "<br /><br />\n";
	return $str;
}

function _vote_build_line( $value, $aling=null )
{
	$str  = $this->_vote_build_td( $aling );
	$str .= $value ;
	$str .= "</td>\n";
	return $str ;
}

function _vote_build_td( $aling=null )
{
	$extra = null;
	if ( $aling ) {
		$extra = 'aling="'.$aling.'"';
	}

	$str = '<td class="'. $this->_alternate_class .'" '. $extra .' >';
	return $str;
}

function _vote_text_form( $item_id, $vote_id )
{
	$str  = '<form action="index.php" method="post">';
	$str .= '<input type="hidden" name="fct" value="'. $this->_THIS_FCT .'" />';
	$str .= '<input type="hidden" name="op" value="delete_vote" />';
	$str .= '<input type="hidden" name="item_id" value="'. $item_id .'" />';
	$str .= '<input type="hidden" name="vote_id" value="'. $vote_id .'" />';
	$str .= '<input type="submit" value="X" />';
	$str .= '</form>';
	return $str;
}

function _alternate_class()
{
	if ( $this->_alternate_class == 'even' ) {
		$this->_alternate_class = 'odd';
	} else {
		$this->_alternate_class = 'even';
	}
}

//---------------------------------------------------------
// delete vote
//---------------------------------------------------------
function _delete_vote()
{
	$item_id  = $this->_post_class->get_post_get_int('item_id') ;
	$vote_id  = $this->_post_class->get_post_get_int('vote_id') ;

	$this->_vote_handler->delete_by_id( $vote_id );

	list( $votes, $total, $rating )
		= $this->_vote_handler->calc_rating_by_photoid( $item_id );

	$this->_item_handler->update_rating_by_id( $item_id, $votes, $rating );

	$url = $this->_THIS_URL .'&amp;op=vote_stats&item_id='. $item_id;
	redirect_header( $url, $this->_TIME_SUCCESS, _AM_WEBPHOTO_VOTE_DELETED );
	exit();
}

//---------------------------------------------------------
// view log
//---------------------------------------------------------
function _view_log()
{
	xoops_cp_header();
	echo $this->_build_bread_crumb();

	echo '<h3>'._AM_WEBPHOTO_LOG_VIEW.'</h3>'."\n";

	echo $this->_build_button( 'submit_form', _AM_WEBPHOTO_ITEM_ADD );
	echo $this->_build_button( 'empty_log',   _AM_WEBPHOTO_LOG_EMPT );
	echo "<br /><br />\n";

	$lines = $this->_log_class->read_log() ;
	if ( ! is_array($lines) ) {
		echo 'cannot open file : '. $this->_log_class->get_filename(). "<br />\n";
		return ;	// no action;
	}

	if ( count($lines) == 0 ) {
		echo "No log data <br />\n";
		return ;	// no action;
	}

	echo '<table border="1" cellspacing="0" cellpadding="1" style="font-size: 90%;">'."\n";

	echo '<tr>';
	echo '<th>'. $this->get_constant( 'LOGFILE_LINE' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_DATE' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_REFERER' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_IP' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_STATE' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_ID' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_TITLE' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_FILE' ) .'</th>';
	echo '<th>'. $this->get_constant( 'LOGFILE_DURATION' ) .'</th>';
	echo "</tr>\n";

	$number = 1;

	foreach ( $lines as $line )
	{
		echo '<tr class="odd">';
		echo '<td>' . $number. '</td>';
		echo '<td>' . $line[0] .'</td>';
		echo '<td>' . $line[1] .'</td>';
		echo '<td>' . $line[2] .'</td>';
		echo '<td>' . $line[3] .'</td>';
		echo '<td>' . $line[4] .'</td>';
		echo '<td>' . $line[5] .'</td>';
		echo '<td>' . $line[6] .'</td>';
		echo '<td>' . $line[7] .'</td>';
		echo "</tr>\n";
	
		$number ++ ;
	}

	echo "</table>\n";
}

function check_empty_log( $lines )
{
	$count = count($lines);

	if ( $count == 0 ) {
		return true ;
	}

	if (( $count == 1 ) && empty($lines[0]) ) {
		return true ;
	}

}

//---------------------------------------------------------
// empty log
//---------------------------------------------------------
function _empty_log()
{
	$this->_log_class->empty_log() ;

	$url = $this->_THIS_URL .'&amp;op=view_log';
	redirect_header( $url, $this->_TIME_SUCCESS , _AM_WEBPHOTO_LOG_EMPT );
	exit();
}

//---------------------------------------------------------
// refresh playlist cache
//---------------------------------------------------------
function _refresh_cache()
{
	$this->_playlist_class->refresh_cache_all();

	redirect_header($this->_THIS_URL, $this->_TIME_SUCCESS , _AM_WEBPHOTO_PLAYLIST_REFRESH );
	exit();
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _init_form()
{
	$this->init_admin_item_form();
	$this->init_misc_form();
}

function init_admin_item_form()
{
	$this->_admin_item_form_class->get_post_select_param();
	$this->_admin_item_form_class->set_fct( $this->_THIS_FCT ) ;
	$this->_admin_item_form_class->set_form_mode( $this->_FORM_MODE ) ;
	$this->_admin_item_form_class->set_form_action( $this->_FLAG_ADMIN ) ;
	$this->_admin_item_form_class->set_tag_name_array( $this->_tag_name_array ) ;
	$this->_admin_item_form_class->set_checkbox_array( $this->_checkbox_array ) ;
	$this->_admin_item_form_class->set_preview_name( $this->_preview_name ) ;
	$this->_admin_item_form_class->set_rotate( $this->_post_rotate ) ;
}

function _print_form_admin( $mode, $item_row )
{
	echo $this->_admin_item_form_class->build_form_admin_with_template( $mode, $item_row );
}

function _print_form_playlist( $mode, $item_row )
{
	if ( $this->_SHOW_FORM_ADMIN_PLAYLIST ) {
		echo $this->_admin_item_form_class->print_form_playlist( $mode, $item_row );
	}
}

function _print_form_embed( $mode, $item_row )
{
	if ( $this->_SHOW_FORM_ADMIN_EMBED ) {
		echo $this->_misc_form_class->build_form_embed_with_template( $mode, $item_row ) ;
	}
}

function _print_form_editor( $mode, $item_row )
{
	if ( $this->_SHOW_FORM_ADMIN_EDITOR ) {
		echo $this->_misc_form_class->build_form_editor_with_template( $mode, $item_row ) ;
	}
}

function _print_form_redo( $mode, $item_row, $flash_row )
{
	echo $this->_misc_form_class->build_form_redo_with_template( $mode, $item_row, $flash_row );
}

function _print_form_flashvar( $mode, $flashvar_row )
{
// Fatal error: Class 'webphoto_flashvar_form'
	$form_class =& webphoto_edit_flashvar_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$form_class->print_form( $mode, $flashvar_row );
	echo "<br />\n";
}

function _print_form_video_thumb( $mode, $item_row )
{
	echo $this->_misc_form_class->build_form_video_thumb_with_template( $mode, $item_row );
}

// --- class end ---
}

?>