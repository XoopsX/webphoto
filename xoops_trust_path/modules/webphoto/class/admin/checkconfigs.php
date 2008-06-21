<?php
// $Id: checkconfigs.php,v 1.1 2008/06/21 12:22:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_checkconfigs
//=========================================================
class webphoto_admin_checkconfigs extends webphoto_base_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_checkconfigs( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );
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
	$cfg_makethumb   = $this->get_config_by_name('makethumb');
	$cfg_imagingpipe = $this->get_config_by_name('imagingpipe');
	$cfg_photospath  = $this->get_config_by_name('photospath');
	$cfg_thumbspath  = $this->get_config_by_name('thumbspath');
	$cfg_giconspath  = $this->get_config_by_name('giconspath');
	$cfg_tmppath     = $this->get_config_by_name('tmppath');
	$cfg_imagickpath = $this->_config_class->get_dir_by_name('imagickpath');
	$cfg_netpbmpath  = $this->_config_class->get_dir_by_name('netpbmpath');

// Initialize
	$netpbm_pipes = array( "jpegtopnm" , "giftopnm" , "pngtopnm" , 
		 "pnmtojpeg" , "pnmtopng" , "ppmquant" , "ppmtogif" ,
		 "pnmscale" , "pnmflip" ) ;

//
// ENVIRONTMENT CHECK
//
	echo "<h4>"._AM_WEBPHOTO_H4_ENVIRONMENT."</h4>\n" ;

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'safe_mode' ("._AM_WEBPHOTO_BOTHOK."): &nbsp; " ;
	$this->_print_on_off( ini_get( "safe_mode" ) );

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'file_uploads' ("._AM_WEBPHOTO_NEEDON."): &nbsp; " ;
	$this->_print_on_off( ini_get( "file_uploads" ), true );

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'register_globals' ("._AM_WEBPHOTO_BOTHOK."): &nbsp; " ;
	$this->_print_on_off( ini_get( "register_globals" ) );

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'upload_max_filesize': &nbsp; " ;
	$str = ini_get( "upload_max_filesize" ).' byte' ;
	$this->_print_green( $str );

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'post_max_size': &nbsp; " ;
	$str = ini_get( "post_max_size" ).' byte' ;
	$this->_print_green( $str );

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'open_basedir': &nbsp; " ;
	$rs = ini_get( "open_basedir" ) ;
	if ( $rs ) {
		$this->_print_green( $rs );
	} else {
		$this->_print_green( 'noting' );
	}

	echo _AM_WEBPHOTO_PHPDIRECTIVE." 'upload_tmp_dir': &nbsp; " ;
	$ini_upload_tmp_dir = ini_get("upload_tmp_dir");
	$tmp_dirs = explode( PATH_SEPARATOR , $ini_upload_tmp_dir ) ;

	foreach( $tmp_dirs as $dir ) 
	{
		if( $dir != "" && ( ! is_writable( $dir ) || ! is_readable( $dir ) ) ) {
			$msg = "Error: upload_tmp_dir ($dir) is not writable nor readable ." ;
			$this->_print_red( $msg );
			$error_upload_tmp_dir = true ;
		}
	}

	if ( empty( $error_upload_tmp_dir ) ) {
		$msg = 'ok '. $ini_upload_tmp_dir;
		$this->_print_green( $msg );
	}

//
// CONFIG CHECK
//
	echo "<h4>"._AM_WEBPHOTO_H4_CONFIG."</h4>\n" ;

// pipe
	echo _AM_WEBPHOTO_PIPEFORIMAGES.": \n" ;

	if ( $cfg_imagingpipe == _C_WEBPHOTO_PIPEID_IMAGICK ) {
		echo "ImageMagick<br />\n Path: $cfg_imagickpath<br />\n" ;
		exec( "{$cfg_imagickpath}convert --help" , $ret_array ) ;
		if( count( $ret_array ) < 1 ) {
			$msg = "Error: {$cfg_imagickpath}convert can't be executed" ;
			$this->_print_red( $msg );
		} else {
			echo " &nbsp; {$ret_array[0]} &nbsp; " ;
			$this->_print_green( 'ok' );
		}

	} elseif ( $cfg_imagingpipe == _C_WEBPHOTO_PIPEID_NETPBM ) {
		echo "NetPBM<br />\n Path: $cfg_netpbmpath<br />\n" ;
		foreach( $netpbm_pipes as $pipe ) {
			$ret_array = array() ;
			exec( "{$cfg_netpbmpath}$pipe --version 2>&1" , $ret_array ) ;
			if( count( $ret_array ) < 1 ) {
				$msg = "Error: {$cfg_netpbmpath}pnmscale can't be executed" ;
				$this->_print_red( $msg );
			} else {
				echo " &nbsp; {$ret_array[0]} &nbsp; " ;
				$this->_print_green( 'ok' );
			}
		}

	} else {
		echo "GD<br />\n" ;
		if( function_exists( 'gd_info' ) ) {
			$gd_info = gd_info() ;
			echo ' &nbsp; GD Version: '. $gd_info['GD Version'] ."<br />\n" ;
		}
		echo "<br />\n";
		echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=checkgd2" target="_blank">';
		echo _AM_WEBPHOTO_LNK_CHECKGD2;
		echo '</a><br />'."\n";
		echo " &nbsp; "._AM_WEBPHOTO_CHECKGD2."<br />\n" ;
	}

	echo "<br />\n" ;

// directory
// photos
	echo _AM_WEBPHOTO_DIRECTORYFOR_PHOTOS.': '.XOOPS_ROOT_PATH.$cfg_photospath.' &nbsp; ';
	$this->_check_directory( $cfg_photospath );

// thumbs
	echo _AM_WEBPHOTO_DIRECTORYFOR_THUMBS.': '.XOOPS_ROOT_PATH.$cfg_thumbspath.' &nbsp; ' ;
	$this->_check_directory( $cfg_photospath );

// gicons
	echo _AM_WEBPHOTO_DIRECTORYFOR_GICONS.': '.XOOPS_ROOT_PATH.$cfg_giconspath.' &nbsp; ' ;
	$this->_check_directory( $cfg_giconspath );

// tmp
	echo _AM_WEBPHOTO_DIRECTORYFOR_TMP.': '.XOOPS_ROOT_PATH.$cfg_tmppath.' &nbsp; ' ;
	$this->_check_directory( $cfg_tmppath );

}

function _check_directory( $path )
{
	$full_path = XOOPS_ROOT_PATH.$path;

	if( substr( $path , -1 ) == '/' ) {
		$this->_print_red( _AM_WEBPHOTO_ERR_LASTCHAR );

	} else if( ord( $path ) != 0x2f ) {
		$this->_print_red( _AM_WEBPHOTO_ERR_FIRSTCHAR );

	} else if( ! is_dir( $full_path ) ) {
		if( $safe_mode_flag ) {
			$this->_print_red( _AM_WEBPHOTO_PERMISSION );

		} else {
			$rs = mkdir( $full_path , 0777 ) ;
			if ( $rs ) {
				$this->_print_green( 'ok' );
			} else {
				$this->_print_red( _AM_WEBPHOTO_NOTDIRECTORY );
			}
		}

	} else if ( ! is_writable( $full_path ) || ! is_readable( $full_path ) ) {
		$this->_print_red( _AM_WEBPHOTO_READORWRITE );

	} else {
		$this->_print_green( 'ok' );
	}

	echo "<br />\n";
}

function _print_on_off( $val, $flag_red=false )
{
	if ( $val ) {
		$this->_print_green('on');
	} elseif ( $flag_red ) { 
		$this->_print_red('off');
	} else { 
		$this->_print_green('off');
	}
}

function _print_red( $str )
{
	echo '<font color="#FF0000"><b>'. $str .'</b></font>'."<br />\n" ;
}

function _print_green( $str )
{
	echo '<font color="#00FF00"><b>'. $str .'</b></font>'."<br />\n" ;
}

// --- class end ---
}

?>