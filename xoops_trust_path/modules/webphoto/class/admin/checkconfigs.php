<?php
// $Id: checkconfigs.php,v 1.16 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-01 K.OHWADA
// _module_version()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_jodconverter
// 2009-10-25 K.OHWADA
// webphoto_lib_lame
// 2009-08-08 K.OHWADA
// _build_db_character()
// 2009-01-25 K.OHWADA
// webphoto_jodconverter
// 2009-01-10 K.OHWADA
// xpdf version
// 2008-12-07 K.OHWADA
// _check_qr_code()
// 2008-11-16 K.OHWADA
// webphoto_lib_server_info
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
// BUG: ths -> this
// 2008-10-01 K.OHWADA
// use cfg_uploadspath
// 2008-08-01 K.OHWADA
// show Multibyte Extention
// tmppath -> tmpdir
// 2008-07-01 K.OHWADA
// added FFmpeg
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_checkconfigs
//=========================================================
class webphoto_admin_checkconfigs extends webphoto_base_this
{
	var $_server_class ;
	var $_multibyte_class ;

	var $_ini_safe_mode = 0;

	var $_MKDIR_MODE = 0777;
	var $_CHAR_SLASH = '/';
	var $_HEX_SLASH  = 0x2f;	// 0x2f = slash '/'

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_checkconfigs( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_server_class    =& webphoto_lib_server_info::getInstance();
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_checkconfigs( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function check()
{
	$this->_check_server();
	echo "<br />\n";
	$this->_check_mulitibyte_link();
	echo "<br />\n";
	$this->_check_pathinfo_link();
	echo "<br />\n";
	$this->_check_program();
	echo "<br />\n";
	$this->_check_directory();
	echo "<br />\n";
}

function _check_server()
{
	$on  = ' ( '. _AM_WEBPHOTO_NEEDON .' ) ' ;
	$off = ' ( '. _AM_WEBPHOTO_RECOMMEND_OFF .' ) ' ;

	echo "<h4>". _AM_WEBPHOTO_H4_ENVIRONMENT ."</h4>\n" ;
	echo $this->_server_class->build_server();
	echo $this->_module_version();

	echo '<h4>'. _AM_WEBPHOTO_MYSQL_CONFIG ."</h4>\n";
	$handler = new webphoto_lib_handler();
	echo $handler->build_config_character();

	echo "<h4>". _AM_WEBPHOTO_PHPDIRECTIVE ."</h4>\n";
	echo $this->_server_class->build_php_secure( $off );
	echo $this->_server_class->build_php_upload( $on );
	echo $this->_server_class->build_php_etc();
	echo $this->_server_class->build_php_exif();

	echo '<h4>'. _AM_WEBPHOTO_MULTIBYTE_CONFIG ."</h4>\n";
	echo $this->_multibyte_class->build_config_priority();
	echo "<br />\n";
	echo $this->_multibyte_class->build_config_mbstring();
	echo "<br />\n";
	echo $this->_multibyte_class->build_config_iconv();
}

function _module_version()
{
	$str  = "Webphoto: ";
	$str .= $this->_xoops_class->get_my_module_version( true );
	$str .= "<br />\n"; 
	return $str;
}

function _check_mulitibyte_link()
{
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=check_mb&amp;charset=UTF-8" target="_blank">';
	echo _AM_WEBPHOTO_MULTIBYTE_LINK;
	echo ' (UTF-8) </a><br />'."\n";
	if ( $this->_is_japanese ) {
		echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=check_mb&amp;charset=Shift_JIS" target="_blank">';
		echo _AM_WEBPHOTO_MULTIBYTE_LINK;
		echo ' (Shift_JIS) </a><br />'."\n";
	}
	echo " &nbsp; "._AM_WEBPHOTO_MULTIBYTE_DSC."<br />\n" ;
}

function _check_pathinfo_link()
{
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php/abc/" target="_blank">';
	echo _AM_WEBPHOTO_PATHINFO_LINK;
	echo '</a><br />'."\n";
	echo " &nbsp; "._AM_WEBPHOTO_PATHINFO_DSC."<br />\n" ;
}

function _check_program()
{
	$gd_class           =& webphoto_lib_gd::getInstance();
	$imagemagick_class  =& webphoto_lib_imagemagick::getInstance();
	$netpbm_class       =& webphoto_lib_netpbm::getInstance();
	$ffmpeg_class       =& webphoto_lib_ffmpeg::getInstance();
	$lame_class         =& webphoto_lib_lame::getInstance();
	$timidity_class     =& webphoto_lib_timidity::getInstance();
	$xpdf_class         =& webphoto_lib_xpdf::getInstance();
	$jodconverter_class 
		=& webphoto_jodconverter::getInstance( $this->_DIRNAME, $this->_TRUST_DIRNAME );

	$cfg_imagingpipe  = $this->get_config_by_name('imagingpipe');
	$cfg_use_ffmpeg   = $this->get_config_by_name('use_ffmpeg');
	$cfg_use_lame     = $this->get_config_by_name('use_lame');
	$cfg_use_timidity = $this->get_config_by_name('use_timidity');
	$cfg_use_xpdf     = $this->get_config_by_name('use_xpdf');
	$cfg_imagickpath  = $this->_config_class->get_dir_by_name('imagickpath');
	$cfg_netpbmpath   = $this->_config_class->get_dir_by_name('netpbmpath');
	$cfg_ffmpegpath   = $this->_config_class->get_dir_by_name('ffmpegpath');
	$cfg_lamepath     = $this->_config_class->get_dir_by_name('lamepath');
	$cfg_timiditypath = $this->_config_class->get_dir_by_name('timiditypath');
	$cfg_xpdfpath     = $this->_config_class->get_dir_by_name('xpdfpath');

	echo "<h4>"._AM_WEBPHOTO_H4_CONFIG."</h4>\n" ;

	echo "<b>"._AM_WEBPHOTO_PIPEFORIMAGES." : </b><br /><br />\n" ;

	echo "<b>GD</b><br />\n";
	list( $ret, $msg ) = $gd_class->version();
	$this->_print_ret_msg( $ret , $msg );
	echo "<br />\n";
	if ( $ret ) {
		echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=checkgd2" target="_blank">';
		echo _AM_WEBPHOTO_LNK_CHECKGD2;
		echo '</a><br />'."\n";
		echo " &nbsp; "._AM_WEBPHOTO_CHECKGD2."<br />\n" ;
	}
	echo "<br />\n";

	$this->_check_qr_code();
	echo "<br />\n";

	if (( $cfg_imagingpipe == _C_WEBPHOTO_PIPEID_IMAGICK ) ||
	      $cfg_imagickpath ) {
		echo "<b>ImageMagick</b><br />\n";
		echo "Path: ". $cfg_imagickpath ."<br />\n" ;
		list( $ret, $msg ) = $imagemagick_class->version( $cfg_imagickpath );
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";
	}

	if (( $cfg_imagingpipe == _C_WEBPHOTO_PIPEID_NETPBM ) ||
		  $cfg_netpbmpath ) {
		echo "<b>NetPBM</b><br />\n";
		echo "Path: ". $cfg_netpbmpath ."<br />\n" ;
		$arr = $netpbm_class->version( $cfg_netpbmpath );
		if ( is_array($arr) ) {
			foreach ( $arr as $ret_msg )
			{
				list( $ret, $msg ) = $ret_msg ;
				$this->_print_ret_msg( $ret , $msg );
			}
		}
		echo "<br />\n";
	}

	if ( $cfg_use_ffmpeg || $cfg_ffmpegpath ) {
		echo "<b>FFmpeg</b><br />\n";
		echo "Path: $cfg_ffmpegpath <br />\n" ;
		list( $ret, $msg ) = $ffmpeg_class->version( $cfg_ffmpegpath );
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";

	} else {
		echo "<b>FFmpeg</b> : not use <br /><br />\n";
	}

	if ( $cfg_use_lame || $cfg_lamepath ) {
		echo "<b>lame</b><br />\n";
		echo "Path: ". $cfg_lamepath ."<br />\n" ;
		list( $ret, $msg ) = $lame_class->version( $cfg_lamepath );
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";

	} else {
		echo "<b>lame</b> : not use <br /><br />\n";
	}

	if ( $cfg_use_timidity || $cfg_timiditypath ) {
		echo "<b>timidity</b><br />\n";
		echo "Path: ". $cfg_timiditypath ."<br />\n" ;
		list( $ret, $msg ) = $timidity_class->version( $cfg_timiditypath );
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";

	} else {
		echo "<b>timidity</b> : not use <br /><br />\n";
	}

	if ( $cfg_use_xpdf || $cfg_xpdfpath ) {
		echo "<b>xpdf</b><br />\n";
		echo "Path: ". $cfg_xpdfpath ."<br />\n" ;
		list( $ret, $msg ) = $xpdf_class->version( $cfg_xpdfpath );
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";

	} else {
		echo "<b>xpdf</b> : not use <br /><br />\n";
	}

	if ( $jodconverter_class->use_jod() ) {
		echo "<b>jodconverter</b><br />\n";
		echo "Java Path: ". $jodconverter_class->java_path() ."<br />\n" ;
		list( $ret, $msg ) = $jodconverter_class->version();
		$this->_print_ret_msg( $ret , $msg );
		echo "<br />\n";

	} else {
		echo "<b>jodconverter</b> : not use <br /><br />\n";
	}

}

function _check_qr_code()
{
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=check_qr" target="_blank">';
	echo _AM_WEBPHOTO_QR_CHECK_LINK ;
	echo '</a><br />'."\n";
	echo " &nbsp; "._AM_WEBPHOTO_QR_CHECK_DSC."<br />\n" ;

}

function _check_directory()
{
	$cfg_uploadspath = $this->get_config_by_name('uploadspath');
	$cfg_workdir     = $this->get_config_by_name('workdir');
	$cfg_file_dir    = $this->get_config_by_name('file_dir');

// BUG: ths -> this
	$this->_ini_safe_mode = ini_get( "safe_mode" );

	echo "<b>Directory : </b><br /><br />\n" ;

// uploads
	echo _AM_WEBPHOTO_DIRECTORYFOR_UPLOADS.': '.XOOPS_ROOT_PATH.$cfg_uploadspath.' &nbsp; ';
	$this->_check_path( $cfg_uploadspath );

// tmp
	echo _AM_WEBPHOTO_DIRECTORYFOR_TMP.': '. $cfg_workdir .' &nbsp; ' ;
	$this->_check_full_path( $cfg_workdir, true );

// file
	echo _AM_WEBPHOTO_DIRECTORYFOR_FILE.': '. $cfg_file_dir .' &nbsp; ' ;
	if ( $cfg_file_dir ) {
		$this->_check_full_path( $cfg_file_dir, true );
	} else {
		$this->_print_green( 'not set' );
		echo "<br />\n";
	}
}

function _check_path( $path )
{
	if ( ord( $path ) != $this->_HEX_SLASH ) {
		$this->_print_red( _AM_WEBPHOTO_ERR_FIRSTCHAR );

	} else {
		$this->_check_full_path( XOOPS_ROOT_PATH.$path );
	}
}

function _check_full_path( $full_path, $flag_root_path=false )
{
	$ret_code = true ;

	if( substr( $full_path , -1 ) == $this->_CHAR_SLASH ) {
		$this->_print_red( _AM_WEBPHOTO_ERR_LASTCHAR );
		$ret_code = false ;

	} elseif ( ! is_dir( $full_path ) ) {
		if ( $this->_ini_safe_mode ) {
			$this->_print_red( _AM_WEBPHOTO_ERR_PERMISSION );
			$ret_code = false ;

		} else {
			$rs = mkdir( $full_path , $this->_MKDIR_MODE ) ;
			if ( $rs ) {
				$this->_print_green( 'ok' );
			} else {
				$this->_print_red( _AM_WEBPHOTO_ERR_NOTDIRECTORY );
				$ret_code = false ;
			}
		}

	} elseif ( ! is_writable( $full_path ) || ! is_readable( $full_path ) ) {

		if ( $this->_ini_safe_mode ) {
			$this->_print_red( _AM_WEBPHOTO_ERR_READORWRITE );
			$ret_code = false ;

		} else {
			$rs = chmod( $full_path , $this->_MKDIR_MODE ) ;
			if ( $rs ) {
				$this->_print_green( 'ok' );
			} else {
				$this->_print_red( _AM_WEBPHOTO_ERR_READORWRITE );
				$ret_code = false ;
			}
		}

	} elseif ( $flag_root_path ) {
		if ( strpos( $full_path, XOOPS_ROOT_PATH ) === 0 ) {
			echo "<br />\n";
			$this->_print_red( _AM_WEBPHOTO_WARN_GEUST_CAN_READ );
			echo _AM_WEBPHOTO_WARN_RECOMMEND_PATH ."<br />\n" ;
		} else {
			$this->_print_green( 'ok' );
		}

	} else {
		$this->_print_green( 'ok' );
	}

	echo "<br />\n";

	return $ret_code ;
}

function _print_ret_msg( $ret , $msg )
{
	if ( !$ret ) {
		$msg = $this->_font_red( $msg );
	}
	echo $msg ;
	echo "<br />\n";
}

function _print_on_off( $val, $flag_red=false )
{
	echo $this->_server_class->build_on_off( $val, $flag_red )."<br />\n" ;
}

function _print_red( $str )
{
	echo $this->_font_red( $str )."<br />\n" ;
}

function _print_green( $str )
{
	echo $this->_server_class->font_green( $str )."<br />\n" ;
}

function _font_red( $str )
{
	return $this->_server_class->font_red( $str ) ;
}

// --- class end ---
}

?>