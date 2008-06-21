<?php
// $Id: comment.inc.php,v 1.1 2008/06/21 12:29:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH into mainfile.php' ) ;

$MY_DIRNAME = basename( dirname( dirname( __FILE__ ) ) );

require XOOPS_ROOT_PATH.'/modules/'.$MY_DIRNAME.'/include/mytrustdirname.php' ; // set $mytrustdirname
require XOOPS_TRUST_PATH.'/modules/'.$MY_TRUST_DIRNAME.'/include/comment.inc.php' ;

?>