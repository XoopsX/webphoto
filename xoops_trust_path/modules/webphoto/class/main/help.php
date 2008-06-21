<?php
// $Id: help.php,v 1.1 2008/06/21 12:22:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_help
//=========================================================
class webphoto_main_help extends webphoto_base_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_help( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_help( $dirname , $trust_dirname );
	}
	return $instance;
}

// --- class end ---
}

?>