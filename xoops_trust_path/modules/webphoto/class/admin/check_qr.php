<?php
// $Id: check_qr.php,v 1.1 2008/12/10 19:10:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-06 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_check_qr
//=========================================================
class webphoto_admin_check_qr extends webphoto_base_this
{
	var $_TITLE = 'QR code check';
	var $_QR_MODULE_SIZE = 3;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_check_qr( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_check_qr( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	restore_error_handler() ;
	error_reporting( E_ALL ) ;

	$mode = $this->_post_class->get_get_int( 'mode' );

	$dir_err = false;
	$file    = '';
	$url     = '';

	if ( $mode == 0 ) {
		if ( is_dir( $this->_QRS_DIR ) && is_writable( $this->_QRS_DIR ) ) {
			$file = $this->_QRS_DIR.'/qr_test.png';
			$url  = $this->_QRS_URL.'/qr_test.png';
			if ( is_file( $file) ) {
				unlink( $file );
			}
		} else {
			$dir_err = true;
		}
	}

	if ( $mode == 2 ) {
		header("Content-type: image/png");
	}

	if ( $mode != 2 ) {
		echo $this->build_html_head( $this->_TITLE );
		echo $this->build_html_body_begin();
	}

	if ( $mode == 0 ) {
		echo _AM_WEBPHOTO_QR_CHECK_SUCCESS ."<br/><br/>\n";
		echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=check_qr&amp;mode=1">';
		echo _AM_WEBPHOTO_QR_CHECK_SHOW ;
		echo '</a><br />'."\n";
	}

	if ( $mode == 1 ) {
		echo '<b>'. _AM_WEBPHOTO_QR_CHECK_INFO .'</b>' ;
		echo "<br /><br />\n";
	}

	$data = 'test'.rand();
	$qrimage = new Qrcode_image;
	$qrimage->set_module_size( $this->_QR_MODULE_SIZE ); 
	$qrimage->qrcode_image_out( $data, 'png', $file );

	if ( $mode == 0 ) {
		echo "<br />\n";
		if ( $dir_err ) {
			echo "not writable <b>".  $this->_QRS_DIR ."</b><br />\n";
		} elseif ( ! is_file( $file ) ) {
			echo "not create QR file <br />\n";
		}
		echo '<img src="'. $url .'" >';
	}

	if ( $mode != 2 ) {
		echo "<br /><br />\n";
		echo '<input class="formButton" value="'. _CLOSE .'" type="button" onclick="javascript:window.close();" />';
		echo $this->build_html_body_end();
	}
}

// --- class end ---
}

?>