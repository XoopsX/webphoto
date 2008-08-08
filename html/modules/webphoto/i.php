<?php
// $Id: i.php,v 1.1 2008/08/08 04:57:32 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

require '../../mainfile.php' ;
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

$MY_DIRNAME = basename( dirname( __FILE__ ) ) ;

require XOOPS_ROOT_PATH.'/modules/'.$MY_DIRNAME.'/include/mytrustdirname.php' ; // set $mytrustdirname
require XOOPS_TRUST_PATH.'/modules/'.$MY_TRUST_DIRNAME.'/main/i.php' ;

?>