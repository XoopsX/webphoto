<?php
// $Id: mimetypes.php,v 1.9 2010/05/09 12:54:48 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-05-09 K.OHWADA
// Fatal error: Call to undefined method build_perms_with_separetor()
// 2010-02-15 K.OHWADA
// $_GLUE_ALLOWED
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime_handler
// 2009-10-25 K.OHWADA
// mime_kind
// 2008-12-12 K.OHWADA
// build_group_perms_by_post()
// 2008-08-24 K.OHWADA
// used build_perms_array_to_str()
// 2008-08-01 K.OHWADA
// added _print_show_allowed_mime_all()
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_mimetypes
//=========================================================
class webphoto_admin_mimetypes extends webphoto_base_this
{
	var $_mime_handler;

	var $_xoops_group_objs   = null;
	var $_allowed_mime_array = null;
	var $_image_online  = null;
	var $_image_offline = null;

	var $_ADMIN_MIME_PHP;
	var $_PERPAGE = 20;

	var $_STYLE_LEGEND  = 'font-weight:bold; color:#900;' ;
	var $_STYLE_PADDING  = 'padding:8px;' ;
	var $_STYLE_NAVI     = 'text-align:right; padding:8px;' ;
	var $_GLUE_ALLOWED   = ' ';	// space

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_mimetypes( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_mime_handler =& webphoto_mime_handler::getInstance( 
		$dirname , $trust_dirname );

	$this->_xoops_group_objs = $this->get_xoops_group_objs();

	$this->_ADMIN_MIME_PHP = $this->_MODULE_URL .'/admin/index.php?fct=mimetypes';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_mimetypes( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	switch ( $this->_get_op() )
	{
		case 'openurl':
			$this->_openurl();
			break;

		case 'update';
			$this->_update();
			break;

		case 'save':
			$this->_save();
			break;

		case 'saveall':
			$this->_saveall();
			break;

		case 'delete':
			$this->_delete();
		break;

		case 'delete_confirm':
			$this->_delete_confirm();
			break;

		case 'edit':
			$this->_print_edit_form();
			break;

		case 'main':
		case 'list':
		default:
			$this->_print_list();
			break;
	}

}

function _get_op()
{
	$op      = $this->_post_class->get_post_get_text('op');
	$delete  = $this->_post_class->get_post_text('delete');
	$confirm = $this->_post_class->get_post_int('confirm');

	if ( $delete ) {
		$op = 'delete';
	}
	if (( $op == 'delete' )&&($confirm == 0 ) ) {
		$op = 'delete_confirm';
	}
	return $op;
}

//---------------------------------------------------------
// save
//---------------------------------------------------------
function _save()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_mime_id  = $this->_post_class->get_post_int('mime_id');
	$post_mime_ext = $this->_post_class->get_post_text('mime_ext');

	if ( empty( $post_mime_ext ) ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, _AM_WEBPHOTO_MIME_NOT_ENTER_EXT );
		exit();
	}

	if ( $post_mime_id > 0 ) {
		$row = $this->_mime_handler->get_row_by_id($post_mime_id);
		if ( !is_array($row) ) {
			redirect_header( $this->_ADMIN_MIME_PHP, 3, _AM_WEBPHOTO_NO_RECORD );
			exit();
		}
	} else {
		$row = $this->_mime_handler->create( true );
	}

	$row['mime_ext']    = $this->_post_class->get_post_text('mime_ext');
	$row['mime_name']   = $this->_post_class->get_post_text('mime_name');
	$row['mime_type']   = $this->_post_class->get_post_text('mime_type');
//	$row['mime_ffmpeg'] = $this->_post_class->get_post_text('mime_ffmpeg');
	$row['mime_kind']   = $this->_post_class->get_post_int( 'mime_kind');
	$row['mime_option'] = $this->_post_class->get_post_text('mime_option');
	$row['mime_perms']  = $this->get_group_perms_str_by_post('mime_perms_ids');

	if ( $post_mime_id > 0 ) {
		$res = $this->_mime_handler->update($row);
	} else {
		$res = $this->_mime_handler->insert($row);
	}

	if ( !$res ) {
		$msg  = "DB Error <br/>\n";
		$msg .= $this->_mime_handler->get_format_error();
		redirect_header( $this->_ADMIN_MIME_PHP, 5, $msg );
		exit();
	}

	$msg = ($post_mime_id == 0) ? _AM_WEBPHOTO_MIME_CREATED : _AM_WEBPHOTO_MIME_MODIFIED;

	redirect_header( $this->_ADMIN_MIME_PHP, 1, $msg);
	exit();
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_mime_id = $this->_post_class->get_post_int('mime_id');

	$row = $this->_mime_handler->get_row_by_id( $post_mime_id );
	if ( !is_array($row) ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, _AM_WEBPHOTO_NO_RECORD );
		exit();
	}

	$res = $this->_mime_handler->delete($row);
	if ( !$res ) {
		$msg  = "DB Error <br/>\n";
		$msg .= $this->_mime_handler->get_format_error();
		redirect_header( $this->_ADMIN_MIME_PHP, 5, $msg );
		exit();
	}

	$msg = sprintf(_AM_WEBPHOTO_MIME_MIMEDELETED, $row['mime_name'] );
	redirect_header( $this->_ADMIN_MIME_PHP, 1, $msg );
	exit();
}

//---------------------------------------------------------
// delete cofirm
//---------------------------------------------------------
function _delete_confirm()
{
	$post_mime_id = $this->_post_class->get_post_int('mime_id');

	$row = $this->_mime_handler->get_row_by_id( $post_mime_id );
	if ( !is_array($row) ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, _AM_WEBPHOTO_NO_RECORD );
		exit();
	}

	xoops_cp_header();

	$hiddens = array(
		'op'      => 'delete',
		'mime_id' => $row['mime_id'],
		'confirm' => 1,
		'XOOPS_G_TICKET' => $this->get_token(),
	); 

	$msg = _AM_WEBPHOTO_MIME_DELETETHIS . "<br /><br />\n" . $row['mime_name'];
	xoops_confirm( $hiddens, $this->_ADMIN_MIME_PHP, $msg, _DELETE );

	xoops_cp_footer();
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_edit_form()
{
	$get_mime_id = $this->_post_class->get_get_int('mime_id');

	xoops_cp_header();
	echo $this->build_admin_bread_crumb( $this->get_admin_title( 'MIMETYPES' ), $this->_ADMIN_MIME_PHP );

	$this->_print_form_mimetype( $get_mime_id );

	xoops_cp_footer();
}

function _print_form_mimetype( $mime_id=0 )
{
	$mime_id = intval($mime_id);

	if ( $mime_id > 0 ) {
		$row = $this->_mime_handler->get_row_by_id($mime_id);
		if ( !is_array($row) ) {
			echo _AM_WEBPHOTO_NO_RECORD;
			return false;
		}
	} else {
		$row = $this->_mime_handler->create( true );

// Fatal error: Call to undefined method build_perms_with_separetor() 
		$row['mime_perms'] 
			= $this->_mime_handler->perm_str_with_separetor( XOOPS_GROUP_ADMIN ) ;

	}

	$this->_print_mime_form_mimetype( $row );
}

//---------------------------------------------------------
// print list
//---------------------------------------------------------
function _print_list()
{
	$get_group_id = $this->_post_class->get_get_int('group_id');
	$get_start    = $this->_post_class->get_get_int('start');

	$standard_groups = array( XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS );
	if ( empty($get_group_id) || in_array( $get_group_id, $standard_groups ) ) {
		$group_id = XOOPS_GROUP_ANONYMOUS ;
	} else {
		$group_id = $get_group_id ;
	}

	$image_edit    = $this->_build_image( 'edit',    _AM_WEBPHOTO_MIME_ICO_EDIT );
	$image_delete  = $this->_build_image( 'delete',  _AM_WEBPHOTO_MIME_ICO_DELETE );
	$this->_image_online  = $this->_build_image( 'online',  _AM_WEBPHOTO_MIME_ICO_ONLINE );
	$this->_image_offline = $this->_build_image( 'offline', _AM_WEBPHOTO_MIME_ICO_OFFLINE );

	$rows_all       = $this->_mime_handler->get_rows_all_orderby_ext();
	$mime_total_all = $this->_mime_handler->get_count_all();

	$mime_admin     = array();
	$mime_users     = array();
	$mime_anonymous = array();

	$this->_allowed_mime_array = array();

	$i = 0;
	$rows_sel = array();

	foreach ( $rows_all as $row ) 
	{
		$this->_build_allowed_mime_array_all(
			$row['mime_ext'], 
			$this->_mime_handler->build_perms_row_to_array( $row )
		);

		if (( $i >= $get_start )&&( $i < ($get_start + $this->_PERPAGE) ) ) {
			$rows_sel[] = $row;
		}

		$i ++;
	}

	xoops_cp_header();
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'MIMETYPES' );

	echo "<fieldset>";
	echo '<legend style="'. $this->_STYLE_LEGEND .'">' ;
	echo _AM_WEBPHOTO_MIME_MODIFYF ;
	echo "</legend>\n";
	echo '<div style="'. $this->_STYLE_PADDING .'">' ;
	echo _AM_WEBPHOTO_MIME_INFOTEXT ;
	echo "</div>\n";
	echo "</fieldset><br />\n";

	$this->_print_show_allowed_mime_all();

// --- table begin ---
	echo "<table border='0' width='100%' cellpadding ='2' cellspacing='1' class='outer'>\n";

	echo "<tr>\n";
	echo "<th align='left'>" . _WEBPHOTO_MIME_ID . "</th>";
	echo "<th align='center'>" . _WEBPHOTO_MIME_EXT . "</th>";
	echo "<th align='center'>" . _WEBPHOTO_MIME_NAME . "</th>";
	echo "<th align='center'>";
	echo $this->get_xoops_group_name( XOOPS_GROUP_ADMIN );
	echo "</th>";
	echo "<th align='center'>";
	echo $this->get_xoops_group_name( XOOPS_GROUP_USERS );
	echo "</th>";
	echo "<th align='center'>";
	echo $this->get_xoops_group_name( $group_id );
	echo "</th>";
	echo "</tr>";

	foreach ( $rows_sel as $row ) 
	{
		$perm_array = $this->_mime_handler->build_perms_row_to_array( $row );
		$url = $this->_ADMIN_MIME_PHP .'&amp;op=edit&amp;mime_id='. $row['mime_id'] ;

		echo "<tr>";

		echo "<td align='center' class='even'>";
		echo '<a href="'. $url .'">';
		echo sprintf( "%03d", $row['mime_id'] );
		echo "</a> \n";
		echo "</td>";

		echo "<td align='center' class='even'>";
		echo  $this->sanitize( $row['mime_ext'] );
		echo "</td>";

		echo "<td class='even'>" ;
		echo $this->sanitize( $row['mime_name'] );
		echo "</td>";

		echo "<td align='center' width='10%' class='even'>";
		echo $this->_build_show_on_off( XOOPS_GROUP_ADMIN, $perm_array );
		echo "</td>";

		echo "<td align='center' width='10%' class='even'>";
		echo $this->_build_show_on_off( XOOPS_GROUP_USERS, $perm_array );
		echo "</td>";

		echo "<td align='center' width='10%' class='even'>";
		echo $this->_build_show_on_off( $group_id, $perm_array );
		echo "</td>";

		echo "</tr>\n";
	}

	echo "</table>\n ";
// --- table end ---

	$pagenavi_class =& webphoto_lib_pagenavi::getInstance();
	$pagenavi_class->XoopsPageNav( $mime_total_all, $this->_PERPAGE, $get_start, 'start', 'fct=mimetypes' );
	$navi = $pagenavi_class->renderNav();

	echo '<div style="'. $this->_STYLE_NAVI .'">' ;
	echo $navi;
	echo "</div>\n";

	xoops_cp_footer();
}

function _build_show_on_off( $id, $perm_array )
{
	$text = in_array( $id, $perm_array ) ? $this->_image_online : $this->_image_offline;
	return $text;
}

function _build_image( $icon, $alt )
{
	$text = '<img src="'. $this->_MODULE_URL.'/images/mime_icons/'. $icon .'.png" alt="'. $alt. '" align="middle">';
	return $text;
}

//---------------------------------------------------------
// show_allowed_mime_all
//---------------------------------------------------------
function _print_show_allowed_mime_all()
{
	echo '<fieldset>' ;
	echo '<legend style="'. $this->_STYLE_LEGEND .'">' ;
	echo _AM_WEBPHOTO_MIME_ALLOWED;
	echo "</legend>\n";

	foreach ( $this->_xoops_group_objs as $obj ) 
	{
		echo $this->_build_show_allowed_mime_single( 
			$obj->getVar('groupid'), $obj->getVar('name') );
	}

	$url = $this->_ADMIN_MIME_PHP .'&amp;op=edit' ;
	echo '<a href="'. $url .'">';
	echo _AM_WEBPHOTO_MIME_ADD_NEW ;
	echo "</a><br />\n";

	echo "</fieldset><br />\n";
}

function _build_show_allowed_mime_single( $group_id, $name_s )
{
	$mimes = $this->_get_allowed_mime_array( $group_id );
	$url   = $this->_ADMIN_MIME_PHP .'&amp;op=list&amp;group_id='. $group_id ;

	$text  = '<a href="'. $url .'">';
	$text .= $name_s ;
	$text .= "</a><br />\n";

	$div = '<div style="'. $this->_STYLE_PADDING .'">';

	if ( is_array($mimes) && count($mimes) ) {
		$text .= $div . implode($this->_GLUE_ALLOWED, $mimes) . "</div>\n";
	} else {
		$text .= $div . _AM_WEBPHOTO_MIME_NOMIMEINFO . "</div>\n";
	}
	return $text;
}

function _build_allowed_mime_array_all( $mime_ext, $perm_array )
{
	foreach ( $this->_xoops_group_objs as $obj ) 
	{
		$this->_build_allowed_mime_array_single( 
			$obj->getVar('groupid'), $mime_ext, $perm_array );
	}
}

function _build_allowed_mime_array_single( $id, $mime_ext, $perm_array )
{
	$perm = in_array( $id, $perm_array );
	if ( $perm ) {
		$this->_allowed_mime_array[ $id ][] = $mime_ext;
	}
}

function _get_allowed_mime_array( $id )
{
	if ( isset( $this->_allowed_mime_array[ $id ] ) ) {
		return  $this->_allowed_mime_array[ $id ];
	}
	return null;
}

//---------------------------------------------------------
// admin_mime_form
//---------------------------------------------------------
function _print_mime_form_mimetype( $row )
{
	$mime_form =& webphoto_admin_mime_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$mime_form->print_form_mimetype( $row );
}

function _print_mime_form_mimefind()
{
	$mime_form =& webphoto_admin_mime_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$mime_form->print_form_mimefind();
}

//---------------------------------------------------------
// update 
// NOT use
//---------------------------------------------------------
function openurl()
{
	$post_fileext = $this->_post_class->get_post_text('fileext');
	$url = "http://filext.com/detaillist.php?extdetail=" . $post_fileext . "";
	if ( !headers_sent() ) {
		header("Location: $url");
	} else {
		echo "<meta http-equiv='refresh' content='0;url=$url target='_blank''>\r\n";
	}
}

function update()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_mime_id = $this->_post_class->get_post_int('mime_id');
	$post_admin   = $this->_post_class->get_post_int('admin');
	$post_user    = $this->_post_class->get_post_int('user');
	$get_start    = $this->_post_class->get_get_int('start');

	$row = $this->_mime_handler->get_row_by_id($mime_id);

	if ( $post_admin == 1 ) {
		if ($row['mime_admin'] == 1) {
			$row['mime_admin'] = 0;
		} else {
			$row['mime_admin'] = 1;
		}
	}

	if ( $post_user == 1 ) {
		if ($row['mime_user'] == 1) {
			$row['mime_user'] = 0;
		} else {
			$row['mime_user'] = 1;
		}
	}

	$res = $this->_mime_handler->insert($row);
	if (!$result) {
		$msg  = "DB Error <br/>\n";
		$msg .= $this->_mime_handler->get_format_error();
		redirect_header( $this->_ADMIN_MIME_PHP, 5, $msg );
		exit();
	}

	redirect_header( $this->_ADMIN_MIME_PHP.'&amp;start='.$get_start , 1, _AM_WEBPHOTO_MIME_MODIFIED );
	
}

function saveall()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_MIME_PHP, 3, $this->get_token_errors() );
		exit();
	}

	$post_admin    = $this->_post_class->get_post_int('admin');
	$post_user     = $this->_post_class->get_post_int('user');
	$post_type_all = $this->_post_class->get_post_int('type_all');

	if ( $post_admin == 1 ) {
		$res = $this->_mime_handler->update_admin_all( $post_type_all );
	} else {
		$res = $this->_mime_handler->update_user_all( $post_type_all );
	}

	if ( !$res ) {
		$msg  = "DB Error <br/>\n";
		$msg .= $this->_mime_handler->get_format_error();
		redirect_header( $this->_ADMIN_MIME_PHP, 5, $msg );
		exit();
	}

	redirect_header( $this->_ADMIN_MIME_PHP, 1, _AM_WEBPHOTO_MIME_MODIFIED);
}

// --- class end ---
}

?>