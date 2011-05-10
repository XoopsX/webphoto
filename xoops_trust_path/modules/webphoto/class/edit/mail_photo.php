<?php
// $Id: mail_photo.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// webphoto_lib_mail_parse -> webphoto_pear_mail_parse
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_maillog_handler
// 2009-01-10 K.OHWADA
// webphoto_edit_mail_photo -> webphoto_edit_mail_photo
// webphoto_edit_factory_create
// 2008-11-16 K.OHWADA
// set now to time_update
// 2008-11-08 K.OHWADA
// TMP_DIR -> MAIL_DIR
// 2008-08-24 K.OHWADA
// supported gps
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_mail_photo
//=========================================================
class webphoto_edit_mail_photo extends webphoto_edit_base
{
	var $_user_handler ;
	var $_maillog_handler ;
	var $_parse_class ;
	var $_check_class ;
	var $_unlink_class ;
	var $_factory_create_class;

	var $_cfg_allownoimage = false;

	var $_flag_mail_chmod  = true;
	var $_msg_level = 0 ;
	var $_flag_force_db = false;

	var $_SUBJECT_DEFAULT = 'No Subject';
	var $_TIME_FORMAT = 'Y/m/d H:i';
	var $_MAX_BODY    = 250;

	var $_FLAG_STRICT = true;
	var $_FLAG_UNLINK_FILE = true;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_mail_photo( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_factory_create_class 
		=& webphoto_edit_factory_create::getInstance( $dirname , $trust_dirname );
	$this->_user_handler    
		=& webphoto_user_handler::getInstance( $dirname, $trust_dirname );
	$this->_maillog_handler 
		=& webphoto_maillog_handler::getInstance( $dirname, $trust_dirname );
	$this->_check_class     
		=& webphoto_edit_mail_check::getInstance( $dirname, $trust_dirname );

	$this->_parse_class     =& webphoto_pear_mail_parse::getInstance();
	$this->_unlink_class    =& webphoto_edit_mail_unlink::getInstance( $dirname );

	$this->_parse_class->set_charset_local( _CHARSET );
	$this->_parse_class->set_internal_encoding();

	$this->_cfg_allownoimage = $this->get_config_by_name( 'allownoimage' );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_mail_photo( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set & get param
//---------------------------------------------------------
function set_flag_print_first_msg( $val )
{
	$this->_factory_create_class->set_flag_print_first_msg( $val ) ;
}

function set_msg_level( $val )
{
	$this->_msg_level = intval( $val );
	$this->_factory_create_class->set_msg_level( $val ) ;
}

function set_flag_strict( $val )
{
	$this->_FLAG_STRICT = (bool)$val;
	$this->_check_class->set_flag_strict( $val );
}

function set_mail_groups( $val )
{
	$this->_check_class->set_mail_groups( $val );
}

function set_flag_force_db( $val )
{
	$this->_flag_force_db = (bool)$val;
	$this->_factory_create_class->set_flag_force_db( $val );
}

//---------------------------------------------------------
// parse mail
//---------------------------------------------------------
function parse_mails( $file_arr )
{
	$param_arr = array() ;
	foreach ($file_arr as $data )
	{
		$param = $this->parse_single_mail( $data['maillog_id'], $data['file'] );
		if ( is_array($param) ) {
			$param_arr[] = $param ;
		}
	}
	return $param_arr;
}

function parse_single_mail( $maillog_id, $filename, $specified_array=null )
{
	if ( empty($filename) ) {
		$this->set_msg_level_admin( 'filename is empty', false, true );
		return false;
	}

	$file_path = $this->_MAIL_DIR.'/'.$filename ;

	if ( ! file_exists($file_path) ) {
		$msg = 'not exists : '.$file_path;
		$this->set_msg_level_admin( $msg, false, true );
		return false;
	}

	$mail = file_get_contents( $file_path );

	$this->_parse_class->parse_mail( $mail );
	$result = $this->_parse_class->get_result();

	$ret = $this->_check_class->check_mail( $result );
	$param = $this->_check_class->get_result();

	$param['maillog_id'] = $maillog_id;
	$param['filename']   = $filename;

	$param_maillog = $param;

	if ( !$ret ) {
		$reject_msgs = $this->_check_class->get_reject_msgs();
		$msg = $this->array_to_str( $reject_msgs, "\n" );
		$msg = nl2br( $this->sanitize($msg) );
		$this->set_msg_level_admin( 'Reject mail', true, true );
		$this->set_msg_level_admin( $msg, false, true  );

		if ( $this->_FLAG_STRICT ) {
			$param_maillog['status']  = _C_WEBPHOTO_MAILLOG_STATUS_REJECT;
			$param_maillog['comment'] = $msg;
			$this->update_maillog( $param_maillog );
			return false;
		}
	}

	$mail_from = $param['mail_from']; 
	$subject   = $param['subject']; 
	$attaches  = $param['attaches'];

	$msg = " $subject < $mail_from > ";
	$this->set_msg_level_admin( $msg, false, true );

	$attaches_new = $this->parse_attaches( $filename, $attaches, $specified_array );
	if ( is_array($attaches_new) && count($attaches_new) ) {
// rewrite attaches
		$param['attaches'] = $attaches_new;	

	} else {
		$msg = 'no attach file';
		$this->set_msg_level_admin( $msg, true, true );

		if ( ! $this->_cfg_allownoimage ) {
			$param_maillog['status']  = _C_WEBPHOTO_MAILLOG_STATUS_REJECT;
			$param_maillog['comment'] = $msg;
			$this->update_maillog( $param_maillog );
			return false;
		}
	}

	return $param ;
}

function parse_attaches( $mail_filename, $attaches_in, $specified_array=null )
{

	$attaches_new = array();

	if ( !is_array( $attaches_in ) ) {
		return null;
	}

	foreach ( $attaches_in as $attach )
	{
		$temp     = $attach;
		$filename = $attach['filename'];
		$content  = $attach['content'];
		$charset  = $attach['charset'] ;
		$type     = $attach['type'] ;
		$reject   = $attach['reject'] ;

		$file_save = null;
		$skip      = false;

		$this->set_msg_level_admin( $filename, false, true );

// specified or no reject
		if (( is_array($specified_array) && in_array( $filename, $specified_array ) ) ||
		    ( empty($specified_array) && empty($reject) )) { 

			$file_save = $this->build_save_name( $mail_filename, $filename );
			$file_path = $this->_MAIL_DIR.'/'.$file_save ;
			
			$this->_utility_class->write_file(
				$file_path, $content, 'wb', $this->_flag_mail_chmod );
			$reject = null;	// clear reject

// with reject
		} elseif ( $reject )  {
			$this->set_msg_level_admin( $reject, true, true );

// skip
		} else {
			$skip = true;
			$this->set_msg_level_admin( 'Skip', false, true );
		}

		$temp['file_save'] = $file_save;
		$temp['reject']    = $reject;
		$temp['skip']      = $skip;
		$remp['content']   = null;	// crear content

		$attaches_new[] = $temp;
	}

	return $attaches_new ;
}

function build_save_name( $mail, $attach )
{
	$str = $this->_utility_class->strip_ext( $mail ).'-'.$attach ;
	$str = $this->_utility_class->substitute_filename_to_underbar($str);
	$str = rawurlencode( $str );
	return $str;
}

//---------------------------------------------------------
// add photos
//---------------------------------------------------------
function add_photos_from_mail( $param_arr )
{
	$count = 0;
	foreach ( $param_arr as $param )
	{
		$num = $this->add_photos_from_single_mail( $param );
		$count += $num;
	}
	return $count;
}

function add_photos_from_single_mail( $param_in )
{
	if ( !is_array($param_in) || !count($param_in) ) {
		return 0;
	}

	$status  = null ;
	$comment = null ;
	$num     = 0 ;

	$mail_from     = $param_in['mail_from']; 
	$param_attach  = $param_in;
	$param_maillog = $param_in;

	$uid    = isset($param_in['uid'])    ? intval($param_in['uid'])    : -1;
	$cat_id = isset($param_in['cat_id']) ? intval($param_in['cat_id']) : -1;

	if ( $uid == -1 ) {
		$uid = $this->get_uid_from_mail( $mail_from );
		if ( empty($uid) ) {
			$msg = 'reject from : '.$from;
			$this->set_msg_level_admin( $msg, true, true );

			$param_maillog['status']  = _C_WEBPHOTO_MAILLOG_STATUS_REJECT;
			$param_maillog['comment'] = $msg;
			$this->update_maillog( $param_maillog );
			return 0;
		}
	}

	if ( $cat_id == -1 ) {
		$cat_id = $this->get_catid_from_mail( $mail_from );
	}

	$param_attach['uid']    = $uid ;
	$param_attach['cat_id'] = $cat_id ;

	list(  $id_array, $reject_files )
		= $this->add_photo_from_attaches( $param_attach );

// submit files
	if ( is_array($id_array) && count($id_array) ) {
		$num = count($id_array);

// partial files
		if ( is_array($reject_files) && count($reject_files) ) {
			$status  = _C_WEBPHOTO_MAILLOG_STATUS_PARTIAL ;
			$comment = $this->array_to_str( $reject_files, "\n" );

// all files
		} else {
			$status = _C_WEBPHOTO_MAILLOG_STATUS_SUBMIT ;
		}

// no file
	} else {
		$msg = 'no valid attached files';
		$this->set_msg_level_admin( $msg, true, true );

		$status  = _C_WEBPHOTO_MAILLOG_STATUS_REJECT;
		if ( is_array($reject_files) && count($reject_files) ) {
			$comment = $this->array_to_str( $reject_files, "\n" );
		} else {
			$comment = $msg;
		}
		$num =  0;
	}

	$param_maillog['status']   = $status ;
	$param_maillog['comment']  = $comment ;
	$param_maillog['id_array'] = $id_array ;
	$this->update_maillog( $param_maillog );

	return $num;
}

function add_photo_from_attaches( $param_in )
{
	$i = 0;
	$id_array     = array();
	$reject_files = array();

	$gmap_latitude  = 0 ;
	$gmap_longitude = 0 ;
	$gmap_zoom      = 0 ;

	$attaches   = $param_in['attaches'];
	$subject    = $param_in['subject']; 
	$body       = $param_in['body'];
	$datetime   = $param_in['datetime'];
	$gps        = $param_in['gps'];

	$time = time();

	if ( isset($gps['flag']) && $gps['flag'] ) {
		$gmap_latitude  = $gps['gmap_latitude'] ;
		$gmap_longitude = $gps['gmap_longitude'] ;
		$gmap_zoom      = $this->_GMAP_ZOOM ;
	}

	$item_row = $this->_item_handler->create( true );
	$item_row['item_time_create'] = $time ;
	$item_row['item_time_update'] = $time ;
	$item_row['item_title']       = $this->substitute_subject_if_empty( $subject );
	$item_row['item_cat_id']      = $param_in['cat_id'] ;
	$item_row['item_uid']         = $param_in['uid'] ;
	$item_row['item_description'] = $param_in['body'] ;
	$item_row['item_latitude']    = $gmap_latitude ;
	$item_row['item_longitude']   = $gmap_longitude ;
	$item_row['item_zoom']        = $gmap_zoom ;
	$item_row['item_status']      = _C_WEBPHOTO_STATUS_APPROVED ;

	$param_photo = array(
		'src_file'          => null ,
		'rotate'            => $param_in['rotate'] ,
		'flag_video_single' => true ,
	);

	// without attach
	if ( !is_array($attaches) || !count($attaches) ) {

// has body
		if ( $this->_cfg_allownoimage && ( $subject || $body ) ) {
			$newid = $this->create_item_from_param( $item_row, $param_photo ) ;
			$this->set_msg_level_user( null, false, true );
			if ( $newid > 0 ) {
				$id_array[] = $newid;
			}
		}

		return array( $id_array, $reject_files );
	}

// with atach
	foreach ( $attaches as $attach )
	{
		$filename  = $attach['filename'];
		$file_save = $attach['file_save'];
		$reject    = $attach['reject'] ;
		$skip      = $attach['skip'] ;

		if ( $skip ) {
			continue;
		}

		if ( $reject ) {
			$reject_files[] = $filename.' : '.$reject;
			continue;
		}

		$src_file = $this->_MAIL_DIR .'/'. $file_save ;

		if ( empty($subject) && $filename ) {
			$subject = $this->_utility_class->strip_ext( $filename );
		} elseif ( empty($subject) ) {
			$subject = $this->_SUBJECT_DEFAULT ;
		}

		if ( $i > 0 ) {
			$title = $subject .' - '. $i;
		} else {
			$title = $subject ;
		}

		$item_row['item_title']  = $title ;
		$param_photo['src_file'] = $src_file ;

		$newid = $this->create_item_from_param( $item_row, $param_photo );
		$this->set_msg_level_user( null, false, true );
		if ( $newid > 0 ) {
			$id_array[] = $newid;
		}

		if ( $this->_FLAG_UNLINK_FILE ) {
			$this->unlink_file( $src_file );
		}

		$i ++;
	}

	return array( $id_array, $reject_files );
}

function create_item_from_param( $item_row, $param )
{
	$this->_factory_create_class->create_item_from_param( $item_row, $param ) ;
	$item_row = $this->_factory_create_class->get_item_row() ;
	$this->set_msg( $this->_factory_create_class->get_main_msg() ) ;
	if ( isset( $item_row['item_id'] ) ) {
		return  $item_row['item_id'] ;
	}
	return 0 ;
}

function substitute_subject_if_empty( $str )
{
	if ( empty($str) ) {
		$str = $this->_SUBJECT_DEFAULT ;
	}
	return $str;
}

//---------------------------------------------------------
// maillog handler
//---------------------------------------------------------
function clear_maillog( $max )
{
	if ( $max <= 0 ) {
		return 0;	// no action
	}

	$num = $this->_maillog_handler->get_count_all() - $max;
	if ( $num <= 0 ) {
		return 0;	// no action
	}

	$id_array = $this->_maillog_handler->get_id_array_older( $num ) ;
	if ( !is_array($id_array) || !count($id_array) ) {
		return 0;	// no action
	}

	foreach ( $id_array as $id ) 
	{
		$row = $this->_maillog_handler->get_row_by_id( $id );
		if ( !is_array($row) ) {
			continue;
		}

		$this->_unlink_class->unlink_by_maillog_row( $row );
		$this->_maillog_handler->delete_by_id( $id, $this->_flag_force_db ) ;
	}

	$this->set_msg_level_admin( 'Clear maillog : '.$num , false, true );
	return $num;
}

function add_maillog( $file )
{
// insert
	$row = $this->_maillog_handler->create( true );
	$row['maillog_file'] = $file ;

	$newid = $this->_maillog_handler->insert( $row, $this->_flag_force_db );
	if ( !$newid ) {
		$this->set_msg_level_admin( 'DB error', true, true );
		$this->set_msg_level_admin( $this->_maillog_handler->get_format_error() );
		return false;
	}

	return $newid;
}

function update_maillog( $param )
{

// update
	$row = $this->_maillog_handler->get_row_by_id( $param['maillog_id'] );

	$row['maillog_time_update'] = time() ;
	$row['maillog_from']        = $param['mail_from'] ;
	$row['maillog_status']      = $param['status'] ;
	$row['maillog_subject']     = $param['subject'] ;
	$row['maillog_photo_ids']   = $this->build_maillog_photo_ids( $row, $param ) ;
	$row['maillog_body']        = $this->build_maillog_body( $param ) ;
	$row['maillog_attach']      = $this->build_maillog_attach( $param ) ;
	$row['maillog_comment']     = $this->build_maillog_comment( $row, $param ) ;

	$ret = $this->_maillog_handler->update( $row, $this->_flag_force_db );
	if ( !$ret ) {
		$this->set_msg_level_admin( 'DB error', true, true );
		$this->set_msg_level_admin( $this->_maillog_handler->get_format_error() );
		return false;
	}

	return true;
}

function build_maillog_body( $param )
{
	$body = isset($param['body']) ? $param['body'] : null;

	if ( strlen($body) > $this->_MAX_BODY ) {
		return $substr( $body, 0, $this->_MAX_BODY ) ;
	}
	return $body;
}

function build_maillog_attach( $param )
{
	$attaches = isset($param['attaches']) ? $param['attaches'] : null;

	if ( !is_array($attaches) || !count($attaches) ) {
		return null;
	}

	$arr = array();
	foreach ( $attaches as $attach ) {
		if ( $attach['filename'] ) {
			$arr[] = $attach['filename'];
		}
	}
	$str = $this->_maillog_handler->build_attach_array_to_str( $arr );
	return $str ;
}

function build_maillog_photo_ids( $row, $param )
{
	$id_array = isset($param['id_array']) ? $param['id_array'] : null;

	$current_id_array = $this->_maillog_handler->build_photo_ids_row_to_array( $row );

	if ( is_array($current_id_array) && count($current_id_array) &&
	     is_array($id_array) && count($id_array) ) {
		$update_id_array = array_unique( array_merge( $current_id_array, $id_array ) );
	} elseif ( is_array($current_id_array) && count($current_id_array) ) {
		$update_id_array = $current_id_array ;
	} elseif ( is_array($id_array) && count($id_array) ) {
		$update_id_array = $id_array ;
	} else {
		$update_id_array = null;
	}

	return $this->_maillog_handler->build_photo_ids_array_to_str( $update_id_array );
}

function build_maillog_comment( $row, $param )
{
	$comment = isset($param['comment'])  ? $param['comment']  : null;

	$update = $row['maillog_comment'];
	if ( $comment ) {
		$update .= date( $this->_TIME_FORMAT ). "\n" ;
		$update .= $comment . "\n";
	}
	return $update;
}

//---------------------------------------------------------
// user handler
//---------------------------------------------------------
function get_uid_from_mail( $from )
{
	$row = $this->_user_handler->get_cached_row_by_email( $from );
	if ( is_array($row) ) {
		return $row['user_uid'];
	}
	return false;
}

function get_catid_from_mail( $from )
{
	$cat_id = 0;

	$user_row = $this->_user_handler->get_cached_row_by_email( $from );
	if ( is_array($user_row) ) {
		$cat_id = $user_row['user_cat_id'];
	}

	$cat_row = $this->_cat_handler->get_cached_row_by_id( $cat_id );
	if ( is_array($cat_row) ) {
		return $cat_id;
	}

	$cat_rows = $this->_cat_handler->get_rows_all_asc( 1 );
	if ( is_array($cat_rows) ) {
		return $cat_rows[0]['cat_id'];
	}

	return false;
}

// --- class end ---
}

?>