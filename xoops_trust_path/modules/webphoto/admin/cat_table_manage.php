<?php
// $Id: cat_table_manage.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/manage.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/admin/cat_table_manage.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_admin_cat_table_manage::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();

exit();
// --- main end ---

?>