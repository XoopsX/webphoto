<?php
// $Id: index.php,v 1.7 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// webphoto_inc_workdir
// 2008-10-01 K.OHWADA
// use PLAYLISTS_DIR
// 2008-08-24 K.OHWADA
// added _print_check_update()
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
	var $_checkconfig_class;
	var $_update_check_class;
	var $_workdir_class ;

	var $_DIR_TRUST_MOD_UPLOADS;
	var $_FILE_INSTALL ;

	var $_MKDIR_MODE = 0777;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_index( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_update_check_class  =& webphoto_admin_update_check::getInstance( $dirname , $trust_dirname );
	$this->_checkconfig_class   =& webphoto_admin_checkconfigs::getInstance( $dirname , $trust_dirname );
	$this->_workdir_class       =& webphoto_inc_workdir::getInstance( $dirname, $trust_dirname );

	$this->_DIR_TRUST_MOD_UPLOADS 
		= XOOPS_TRUST_PATH .'/modules/'. $trust_dirname .'/uploads/'. $dirname .'/';

	$this->_FILE_INSTALL = $this->_TRUST_DIR .'/uploads/install.txt' ;
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
	echo $this->_update_check_class->build_msg();
	$this->_checkconfig_class->check();
	$this->_print_command_url();

	xoops_cp_footer();
}

//---------------------------------------------------------
// check permission
//---------------------------------------------------------
function _print_check()
{
	echo $this->_make_dir( $this->_UPLOADS_DIR );
	echo $this->_make_dir( $this->_PHOTOS_DIR );
	echo $this->_make_dir( $this->_THUMBS_DIR );
	echo $this->_make_dir( $this->_MIDDLES_DIR );
	echo $this->_make_dir( $this->_CATS_DIR );
	echo $this->_make_dir( $this->_GICONS_DIR );
	echo $this->_make_dir( $this->_GSHADOWS_DIR );
	echo $this->_make_dir( $this->_FLASHS_DIR );
	echo $this->_make_dir( $this->_QRS_DIR );
	echo $this->_make_dir( $this->_PLAYLISTS_DIR );
	echo $this->_make_dir( $this->_LOGOS_DIR );
	echo $this->_make_dir( $this->_MEDIAS_DIR );
	echo $this->_make_dir( $this->_WORK_DIR );
	echo $this->_make_dir( $this->_TMP_DIR );
	echo $this->_make_dir( $this->_MAIL_DIR );
	echo $this->_make_dir( $this->_LOG_DIR );

	$this->_workdir_file();

	if ( $this->_cat_handler->get_count_all() == 0 ) {
		$msg  = '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=catmanager">';
		$msg .= _WEBPHOTO_ERR_MUSTADDCATFIRST ;
		$msg .= '</a>';
		echo $this->build_error_msg( $msg, '', false );
	}

// Waiting Admission
	echo $this->build_check_waiting();

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

function _workdir_file()
{
	$match = $this->_workdir_class->read_workdir( $this->_WORK_DIR );
	switch ( $match )
	{
// complete match
		case 2 :
			return true;

// unmatch
		case 1 :
			$msg = 'ERROR same tmp dir';
			echo $this->build_error_msg( $msg, '', false );
			return false ;

// not yet
		case 0:
		default :
			break;
	}

	$this->_workdir_class->write_workdir( $this->_WORK_DIR );
	echo "add tmp dir in workdir.txt <br />\n";

	return true;
}

function _print_command_url()
{
	$pass = $this->get_config_by_name('bin_pass');
	$url  = $this->_MODULE_URL . '/bin/retrieve.php?pass='.$pass;

	echo '<h4>'. _AM_WEBPHOTO_TITLE_BIN ."</h4>\n";
	echo '<a href="'.$url.'">';
	echo _AM_WEBPHOTO_TEST_BIN .': bin/retrieve.php';
	echo "</a><br /><br/>\n";
}

// --- class end ---
}

?>