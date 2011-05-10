<?php
// $Id: index.php,v 1.29 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// _AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM
// 2010-10-01 K.OHWADA
// _WAVS_DIR
// 2010-06-06 K.OHWADA
// change_check_module_version()
// 2010-02-15 K.OHWADA
// build_admin_footer()
// 2010-02-01 K.OHWADA
// _check_module_version()
// 2009-12-06 K.OHWADA
// change _print_file_check()
// 2009-10-25 K.OHWADA
// JPEGS_DIR
// 2009-04-19 K.OHWADA
// webphoto_lib_file_check
// 2009-04-10 K.OHWADA
// _print_timeline()
// 2009-03-15 K.OHWADA
// SMALLS_DIR
// 2009-01-25 K.OHWADA
// _SWFS_DIR
// 2009-01-10 K.OHWADA
// _PDFS_DIR
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-12-07 K.OHWADA
// window.close()
// 2008-12-06 K.OHWADA
// check_writable
// 2008-12-05 K.OHWADA
// $this->_workdir_class->init()
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

	$this->_update_check_class =& webphoto_admin_update_check::getInstance(
		$dirname , $trust_dirname );
	$this->_checkconfig_class =& webphoto_admin_checkconfigs::getInstance( 
		$dirname , $trust_dirname );
	$this->_workdir_class =& webphoto_inc_workdir::getSingleton( 
		$dirname, $trust_dirname );

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
		echo _AM_WEBPHOTO_PATHINFO_SUCCESS. "<br />\n";
		echo 'PATH_INFO : ' . $_SERVER["PATH_INFO"] ;
		echo "<br /><br />\n";
		echo '<input class="formButton" value="'. _CLOSE .'" type="button" onclick="javascript:window.close();" />';
		xoops_cp_footer();
		exit();
	}

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'CHECKCONFIGS' );

	$this->_print_check();
	$this->_checkconfig_class->check();
	$this->_print_file_check();
	$this->_print_timeline();
	$this->_print_command_url();

	echo $this->build_admin_footer();
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
	echo $this->_make_dir( $this->_LARGES_DIR );
	echo $this->_make_dir( $this->_MIDDLES_DIR );
	echo $this->_make_dir( $this->_SMALLS_DIR );
	echo $this->_make_dir( $this->_FLASHS_DIR );
	echo $this->_make_dir( $this->_DOCOMOS_DIR );
	echo $this->_make_dir( $this->_PDFS_DIR );
	echo $this->_make_dir( $this->_SWFS_DIR );
	echo $this->_make_dir( $this->_JPEGS_DIR );
	echo $this->_make_dir( $this->_MP3S_DIR );
	echo $this->_make_dir( $this->_WAVS_DIR );
	echo $this->_make_dir( $this->_CATS_DIR );
	echo $this->_make_dir( $this->_GICONS_DIR );
	echo $this->_make_dir( $this->_GSHADOWS_DIR );
	echo $this->_make_dir( $this->_QRS_DIR );
	echo $this->_make_dir( $this->_PLAYLISTS_DIR );
	echo $this->_make_dir( $this->_LOGOS_DIR );
	echo $this->_make_dir( $this->_WORK_DIR );
	echo $this->_make_dir( $this->_TMP_DIR );
	echo $this->_make_dir( $this->_MAIL_DIR );
	echo $this->_make_dir( $this->_LOG_DIR );
	echo $this->_make_dir( $this->_MEDIAS_DIR, false );

	$this->_workdir_file();

	if ( ! $this->_check_module_version() ) {
		$msg  = '<a href="'. $this->_get_module_update_url() .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		echo $this->build_error_msg( $msg, '', false );
	}

	echo $this->_update_check_class->build_msg();

	if ( $this->_cat_handler->get_count_all() == 0 ) {
		$msg  = '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=catmanager">';
		$msg .= _WEBPHOTO_ERR_MUSTADDCATFIRST ;
		$msg .= '</a><br />';
		$msg .= _WEBPHOTO_OR;
		$msg .= '<br />';
		$msg .= '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=import_myalbum">';
		$msg .= _AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM ;
		$msg .= '</a><br />';
		echo $this->build_error_msg( $msg, '', false );
	}

// Waiting Admission
	echo $this->build_check_waiting();

	echo "<br />\n";
}

function _check_module_version()
{
	$ver1 = $this->_xoops_class->get_my_module_version();
	$ver2 = $this->_xoops_class->get_module_info_version_by_dirname( $this->_DIRNAME, true );

	if ( intval($ver1) >= intval($ver2) ) {
		return true;
	}
	return false;
}

function _get_module_update_url()
{
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$str = XOOPS_URL ."/modules/legacy/admin/index.php?action=ModuleUpdate&amp;dirname=". $this->_DIRNAME;
	} else {
		$str = XOOPS_URL ."/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=". $this->_DIRNAME;
	}
	return $str;
}

function _make_dir( $dir, $check_writable=true )
{
	$not_dir = true ;
	if ( is_dir( $dir ) ) {
		$not_dir = false ;
		if ( $check_writable && is_writable( $dir ) ) {
			return ''; 
	 	} elseif ( !$check_writable ) {
			return ''; 
	 	}
	}

	if ( ini_get('safe_mode') ) {
		return $this->highlight( 'At first create & chmod 777 "'. $dir .'" by ftp or shell.' )."<br />\n";
	}

	if ( $not_dir ) {
		$ret = mkdir( $dir, $this->_MKDIR_MODE ) ;
		if ( !$ret ) {
			return $this->highlight( 'can not create directory : <b>'. $dir .'</b>' )."<br />\n";
		}
	}

	$ret = chmod( $dir, $this->_MKDIR_MODE ) ;
	if ( !$ret ) {
		return $this->highlight( 'can not change mode directory : <b>'. $dir .'</b> ', $this->_MKDIR_MODE )."<br />\n";
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
			$msg = 'ERROR same work dir';
			echo $this->build_error_msg( $msg, '', false );
			return false ;

// not yet
		case 0:
		default :
			break;
	}

	$byte = $this->_workdir_class->write_workdir( $this->_WORK_DIR );
	$file = $this->_workdir_class->get_filename();
	if ( $byte > 0 ) {
		echo "add work dir in workdir.txt <br />\n";
	} else {
		echo $this->highlight( 'can not write : <b>'. $file.'</b>' )."<br />\n";
	}

	return true;
}

function _print_file_check()
{
	$url = $this->_MODULE_URL.'/admin/index.php?fct=check_file';

	echo "<h4>". _AM_WEBPHOTO_FILE_CHECK ."</h4>\n";
	echo _AM_WEBPHOTO_FILE_CHECK_DSC ."<br /><br />\n";
	echo '<a href="'.$url.'">';
	echo _AM_WEBPHOTO_FILE_CHECK;
	echo "</a><br /><br/>\n";
}

function _print_timeline()
{
	$timeline_dirname = $this->get_config_by_name('timeline_dirname');
	$TIMELINE_DIR = XOOPS_TRUST_PATH.'/modules/'.$timeline_dirname;
	$version_file = $TIMELINE_DIR.'/include/version.php' ;
	$isactive     = $this->_xoops_class->get_module_value_by_dirname( 
		$timeline_dirname, 'isactive' );

	echo '<h4>'. _AM_WEBPHOTO_TIMELINE_MODULE ."</h4>\n";
	echo 'dirname : '. $timeline_dirname ."<br />\n";

// installed
	if ( $isactive ) {

// version file
		if ( file_exists( $version_file ) ) {
			include_once $version_file ;
			echo 'version : '. _C_TIMELINE_VERSION ."<br />\n";

// check version
			if ( _C_TIMELINE_VERSION < _C_WEBPHOTO_TIMELINE_VERSION ) {
				$msg = 'require version '. _C_WEBPHOTO_TIMELINE_VERSION .' or later';
				echo $this->highlight( $msg )."<br />\n";
			}

// not find version file
		} else {
			echo $this->highlight( 'not find version file' )."<br />\n";
		}

// not install
	} else {
		echo $this->highlight( _AM_WEBPHOTO_MODULE_NOT_INSTALL )."<br />\n";
	}

	echo "<br/>\n";
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