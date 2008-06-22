<?php
// $Id: exif.php,v 1.2 2008/06/22 05:26:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_exif
//=========================================================
class webphoto_lib_exif
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_exif()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_exif();
	}
	return $instance;
}

//---------------------------------------------------------
// encoding
//---------------------------------------------------------
function read_file( $filename )
{
	if ( !function_exists('exif_imagetype') || 
	     !function_exists('exif_read_data') ) {
		return false;
	}

// only JPEG
	if ( exif_imagetype($filename) != IMAGETYPE_JPEG ) {
		return false;
	}

	$exif = exif_read_data( $filename, 0, true );
	if ( !is_array($exif) || !count($exif) ) {
		return false;
	}

	$datetime     = '';
	$datetime_gnu = '';
	if ( isset( $exif['IFD0']['DateTime'] ) ) {
		$datetime = $exif['IFD0']['DateTime'];

// yyyy:mm:dd -> yyy-mm-dd
// http://www.gnu.org/software/tar/manual/html_node/General-date-syntax.html
		$datetime_gnu = preg_replace('/(\d{4}):(\d{2}):(\d{2})(.*)/', '$1-$2-$3$4', $datetime );
	}

	$maker = '';
	if ( isset(  $exif['IFD0']['Make'] ) ) {
		$maker = $exif['IFD0']['Make'];
	}

	$model = '';
	if ( isset(  $exif['IFD0']['Model'] ) ) {
		$model = $exif['IFD0']['Model'];
	}

	$equipment = '';
	if ( $maker && $model ) {
		if ( strpos( $model, $maker ) === false ) {
			$equipment = $maker.' '.$model;
		} else {
			$equipment = $model;
		}
	} elseif ( $maker ) {
		$equipment = $maker;
	} elseif ( $model ) {
		$equipment = $model;
	}

// set all data when has IFD0
	$str = '';
	if ( isset( $exif['IFD0'] ) ) {
		foreach ($exif as $key => $section) {
			foreach ($section as $name => $val) {
				$str .= $key .'.'. $name .': ';
				$str .= $this->str_replace_control_code( $val ) ."\n";
			}
		}
	}

	$arr = array(
		'datetime'     => $datetime,
		'maker'        => $maker,
		'model'        => $model,
		'datetime_gnu' => $datetime_gnu,
		'equipment'    => $equipment,
		'all_data'     => $str,
	);
	return $arr;
}

function print_info( $filename )
{
	if ( !function_exists('exif_read_data') ) {
		return false;
	}

	$exif = exif_read_data( $filename, 0, true );

	foreach ($exif as $key => $section) {
		foreach ($section as $name => $val) {
			echo "$key.$name: $val<br />\n";
		}
	}
}

//---------------------------------------------------------
// TAB \x09 \t
// LF  \xOA \n
// CR  \xOD \r
//---------------------------------------------------------
function str_replace_control_code( $str, $replace=' ' )
{
	$str = preg_replace('/[\x00-\x08]/', $replace, $str);
	$str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
	$str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
	$str = preg_replace('/[\x7F]/',      $replace, $str);
	return $str;
}

// --- class end ---
}

?>