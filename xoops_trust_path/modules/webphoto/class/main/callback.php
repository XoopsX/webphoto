<?php
// $Id: callback.php,v 1.2 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// webphoto_flash_log
//---------------------------------------------------------

//---------------------------------------------------------
// http://code.jeroenwijering.com/trac/wiki/Flashvars3
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_callback
//=========================================================
class webphoto_main_callback extends webphoto_flash_log
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_callback( $dirname , $trust_dirname )
{
	$this->webphoto_flash_log( $dirname );
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
	$this->callback_log();
}

// --- class end ---
}

?>