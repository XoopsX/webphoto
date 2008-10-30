<?php
// $Id: callback.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// http://code.jeroenwijering.com/trac/wiki/Flashvars3
// Only for the mediaplayer. 
// Set this to a serverside script that can process statistics. 
// The player will send it a POST every time an item starts/stops. 
// To send callbacks automatically to Google Analytics, 
// set this to urchin (if you use the old urchinTracker code) 
// or analytics (if you use the new pageTracker code). 
//
// The player returns $id, $title, $file, $state, $duration in POST variable
// $state (start/stop)
// $duration is set at stop
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_callback
//=========================================================
class webphoto_main_callback
{
	var $_post_class;

	var $_DIRNAME       = null;
	var $_TRUST_DIRNAME = null;
	var $_TMP_DIR       = null;
	var $_LOG_FILE      = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_callback( $dirname , $trust_dirname )
{
	$this->_init_xoops_config( $dirname );

	$this->_post_class   =& webphoto_lib_post::getInstance();

	$this->_DIRNAME       = $dirname ;
	$this->_TRUST_DIRNAME = $trust_dirname ;

	$this->_LOG_FILE = $this->_TMP_DIR.'/log.txt' ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_callback( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	if ( !is_writable($this->_LOG_FILE) ) {
		return ;
	}

	$fp = fopen($this->_LOG_FILE, 'a');
	if ( !$fp ) {
		return ;
	}

	$id       = $this->_post_class->get_post_int('id');
	$duration = $this->_post_class->get_post_int('duration');
	$title    = $this->_post_class->get_post_text('title');
	$file     = $this->_post_class->get_post_text('file');
	$state    = $this->_post_class->get_post_text('state');

	if ($state != 'start') {
		return ;
	}

	$http_referer = null;
	$remote_addr  = null;

	if ( isset($_SERVER['HTTP_REFERER']) ) {
		$http_referer = $_SERVER['HTTP_REFERER'];
	}

	if ( isset($_SERVER['REMOTE_ADDR']) ) {
		$remote_addr = $_SERVER['REMOTE_ADDR'];
	}

	$data  = formatTimestamp(time(),'m') .',';
	$data .= $http_referer .',';
	$data .= $remote_addr .',';
	$data .= $state .',';
	$data .= $id .',';
	$data .= $title .',';
	$data .= $file .',';
	$data .= $duration ;
	$data .= "\r\n";

	fwrite($fp, $data);
	fclose($fp);

}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_TMP_DIR = $config_handler->get_by_name( 'tmpdir' );
}

// --- class end ---
}

?>