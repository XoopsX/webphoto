<?php
// $Id: retrieve.php,v 1.1 2008/08/25 19:53:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-12 K.OHWADA
//=========================================================

$xoopsOption['nocommon'] = 1 ;

require '../../../mainfile.php' ;
if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

$MY_DIRNAME = basename( dirname( dirname( __FILE__ ) ) ) ;

require XOOPS_ROOT_PATH.'/modules/'.$MY_DIRNAME.'/include/mytrustdirname.php' ; // set $mytrustdirname
require XOOPS_TRUST_PATH.'/modules/'.$MY_TRUST_DIRNAME.'/bin/retrieve.php' ;

?>