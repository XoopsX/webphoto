<?php
// $Id: groupperm.php,v 1.3 2009/12/24 06:32:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// class/lib/groupperm.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// main
//=========================================================

//---------------------------------------------------------
// webphoto
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/inc/gperm_def.php' );
webphoto_include_once( 'class/lib/groupperm.php' );
webphoto_include_once( 'class/lib/groupperm_form.php' );
webphoto_include_once( 'class/admin/groupperm_form.php' );
webphoto_include_once( 'class/admin/groupperm.php' );

$manager =& webphoto_admin_groupperm::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>