<?php
// $Id: index.php,v 1.3 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added DIR_TRUST_MOD_UPLOADS
// 2008-07-01 K.OHWADA
// added to check PATH_INFO
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_index
//=========================================================
class webphoto_admin_index extends webphoto_base_this
{
	var $_check_class;

	var $_GICONS_PATH;
	var $_GICONS_URL;
	var $_GICONS_DIR;

	var $_DIR_UPLOADS_MOD;
	var $_DIR_TRUST_MOD_UPLOADS;

	var $_MKDIR_MODE = 0777;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_index( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_check_class =& webphoto_admin_checkconfigs::getInstance( $dirname , $trust_dirname );

	$this->_GICONS_PATH = $this->_config_class->get_gicons_path();
	$this->_GICONS_URL  = XOOPS_URL       . $this->_GICONS_PATH;
	$this->_GICONS_DIR  = XOOPS_ROOT_PATH . $this->_GICONS_PATH;

	$this->_DIR_UPLOADS_MOD = XOOPS_ROOT_PATH .'/uploads/'. $dirname .'/';

	$this->_DIR_TRUST_MOD_UPLOADS 
		= XOOPS_TRUST_PATH .'/modules/'. $trust_dirname .'/uploads/'. $dirname .'/';

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_index( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	if ( isset($_SERVER["PATH_INFO"]) && $_SERVER["PATH_INFO"] ) {
		restore_error_handler() ;
		error_reporting( E_ALL ) ;
		echo "<b>". _AM_WEBPHOTO_PATHINFO_SUCCESS. "</b><br />\n";
		echo 'PATH_INFO : ' . $_SERVER["PATH_INFO"]. "<br />\n"; ;

		xoops_cp_footer();
		exit();
	}

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'CHECKCONFIGS' );

	$this->_print_check();
	$this->_check_class->check();

	xoops_cp_footer();
}

//---------------------------------------------------------
// check permission
//---------------------------------------------------------
function _print_check()
{
	if ( strpos( $this->_PHOTOS_DIR, $this->_DIR_UPLOADS_MOD ) !== false ) {
		 echo $this->_make_dir( $this->_DIR_UPLOADS_MOD );
	}

	if ( strpos( $this->_TMP_DIR, $this->_DIR_TRUST_MOD_UPLOADS ) !== false ) {
		 echo $this->_make_dir( $this->_DIR_TRUST_MOD_UPLOADS );
	}

	echo $this->_make_dir( $this->_PHOTOS_DIR );
	echo $this->_make_dir( $this->_THUMBS_DIR );
	echo $this->_make_dir( $this->_GICONS_DIR );
	echo $this->_make_dir( $this->_TMP_DIR );

	if ( $this->_cat_handler->get_count_all() == 0 ) {
		$msg  = '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=catmanager">';
		$msg .= _WEBPHOTO_ERR_MUSTADDCATFIRST ;
		$msg .= '</a>';
		echo $this->build_error_msg( $msg, '', false );
	}

// Waiting Admission
	$waiting = $this->_photo_handler->get_count_waiting();
	if ( $waiting > 0 ) {
		echo '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=admission" style="color:red;">';
		echo sprintf( _AM_WEBPHOTO_CAT_FMT_NEEDADMISSION , $waiting ) ;
		echo "</a><br />\n";
	}

	echo "<br />\n";
}

function _make_dir( $dir )
{
	if ( is_dir( $dir ) ) { return ''; }

	if ( ini_get('safe_mode') ) {
		return $this->highlight( 'At first create & chmod 777 "'. $dir .'" by ftp or shell.' )."<br />\n";
	}

	$ret = mkdir( $dir, $this->_MKDIR_MODE ) ;
	if ( !$ret ) {
		return $this->highlight( 'can not create directory : <b>'. $dir .'</b>' )."<br />\n";
	}

	$msg = 'create directory: <b>'. $dir .'</b>'."<br />\n";
	return $msg;
}

// --- class end ---
}

?>