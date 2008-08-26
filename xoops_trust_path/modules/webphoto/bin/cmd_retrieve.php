<?php
// $Id: cmd_retrieve.php,v 1.2 2008/08/26 11:40:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// php cmd_etrieve.php -path=XOOPS_ROOT_PATH -dirname=DIRNAME -pass=PASSWORD
//---------------------------------------------------------

//=========================================================
// start here
//=========================================================
$path    = null ;
$dirname = null ;
define('XOOPS_CHECK_PATH', 0);

// parse arg
if ( $_SERVER['argc'] > 1 ) {
	for ( $i=1; $i<$_SERVER['argc']; $i++ ) {
		if ( preg_match('/\-(.*)=(.*)/', $_SERVER['argv'][$i], $matches) ) {
			if ( $matches[1] == 'path' ) {
				 $path = $matches[2] ;
			} elseif ( $matches[1] == 'dirname' ) {
				 $dirname = $matches[2] ;
			}
		}
	}
}

$XOOPS_ROOT_PATH  = $path;
$MY_DIRNAME       = $dirname ;
$MY_TRUST_DIRNAME = basename( dirname( dirname( __FILE__ ) ) ) ;

$xoopsOption['nocommon'] = 1 ;
require $XOOPS_ROOT_PATH .'/mainfile.php' ;

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

if ( !defined("WEBPHOTO_TRUST_DIRNAME") ) {
	define("WEBPHOTO_TRUST_DIRNAME", $MY_TRUST_DIRNAME );
}
if ( !defined("WEBPHOTO_TRUST_PATH") ) {
	define("WEBPHOTO_TRUST_PATH", XOOPS_TRUST_PATH.'/modules/'.WEBPHOTO_TRUST_DIRNAME );
}

require XOOPS_TRUST_PATH.'/modules/'.$MY_TRUST_DIRNAME.'/bin/retrieve.php' ;

?>