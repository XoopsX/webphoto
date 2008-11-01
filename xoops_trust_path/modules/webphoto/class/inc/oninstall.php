<?php
// $Id: oninstall.php,v 1.10 2008/11/01 23:53:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// move config_update() to xoops_version.php
// _item_update()
// 2008-08-01 K.OHWADA
// changed _table_update() _groupperm_install()
// 2008-07-24 K.OHWADA
// BUG : undefined variable table_name
// 2008-07-01 K.OHWADA
// added _mime_update()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall
//=========================================================
class webphoto_inc_oninstall extends webphoto_inc_handler
{
	var $_table_item ;
	var $_table_mime ;
	var $_table_player;

	var $_IS_XOOPS_2018 = false;

	var $_msg_array = array();

	var $_TRUST_DIRNAME ;
	var $_TRUST_DIR;
	var $_MODULE_ID = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_oninstall()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_oninstall();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function install( $trust_dirname , &$module )
{
	global $ret ; // TODO :-D

	if ( ! is_array( $ret ) ) {
		$ret = array() ;
	}

	$this->_init( $trust_dirname , $module );
	$ret_code = $this->_exec_install();

	$msg_arr = $this->_get_msg_array();
	if ( is_array($msg_arr) && count($msg_arr) ) {
		foreach ( $msg_arr as $msg ) {
			$ret[] = $msg."<br />\n";
		}
	}

	return $ret_code;
}

function update( $trust_dirname , &$module )
{
	global $msgs ; // TODO :-D

	if ( ! is_array( $msgs ) ) {
		$msgs = array() ;
	}

	$this->_init( $trust_dirname , $module );
	$ret_code = $this->_exec_update();

	$msg_arr = $this->_get_msg_array();
	if ( is_array($msg_arr) && count($msg_arr) ) {
		foreach ( $msg_arr as $msg ) {
			$msgs[] = $msg;
		}
	}

	return $ret_code;
}

function uninstall( $trust_dirname , &$module )
{
	global $ret ; // TODO :-D

	if ( ! is_array( $ret ) ) {
		$ret = array() ;
	}

	$this->_init( $trust_dirname , $module );
	$ret_code = $this->_exec_uninstall();

	$msg_arr = $this->_get_msg_array();
	if ( is_array($msg_arr) && count($msg_arr) ) {
		foreach ( $msg_arr as $msg ) {
			$ret[] = $msg."<br />";
		}
	}

	return $ret_code;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _init( $trust_dirname , &$module )
{
	$dirname          = $module->getVar( 'dirname', 'n' );
	$this->_MODULE_ID = $module->getVar( 'mid',     'n' );

	$this->_TRUST_DIRNAME = $trust_dirname ;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH.'/modules/'. $trust_dirname ;

	$this->init_handler( $dirname );

	$this->_table_item   = $this->prefix_dirname( 'item' );
	$this->_table_mime   = $this->prefix_dirname( 'mime' );
	$this->_table_player = $this->prefix_dirname( 'player' );

// preload
	if ( defined("_C_WEBPHOTO_PRELOAD_XOOPS_2018") ) {
		$this->_IS_XOOPS_2018 = (bool)_C_WEBPHOTO_PRELOAD_XOOPS_2018 ;
	}
}

function _exec_install()
{
	// for Cube 2.1
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$name = 'Legacy.Admin.Event.ModuleInstall.' . ucfirst($this->_DIRNAME) . '.Success';
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( $name, 'webphoto_message_append_oninstall' ) ;
	}

	$this->_set_msg( "\n Install module extention ..." );

	$res = $this->_table_install();
	if ( ! $res ) { return false; }

	$this->_template_install();
	$this->_groupperm_install();

	return true ;
}

function _exec_update()
{
	// for Cube 2.1
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$name = 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($this->_DIRNAME) . '.Success';
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( $name, 'webphoto_message_append_onupdate' ) ;
	}

	$this->_set_msg( "\n Update module extention ..." );

	$this->_table_update();
	$this->_item_update();
	$this->_mime_update();
	$this->_template_update();

	return true ;
}

function _exec_uninstall()
{
	// for Cube 2.1
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$name = 'Legacy.Admin.Event.ModuleUninstall.' . ucfirst($this->_DIRNAME) . '.Success';
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( $name , 'webphoto_message_append_onuninstall' ) ;
	}

	$this->_set_msg( "\n Uninstall module extention ..." );

	$this->_table_uninstall();
	$this->_template_uninstall();

	return true ;
}

//---------------------------------------------------------
// table handler
//---------------------------------------------------------
function _table_install()
{
	$sql_file_path = $this->_get_table_sql();
	if ( ! $sql_file_path ) { return true; }	// no action

	$prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME ;
	$this->_set_msg( "SQL file found at <b>". $this->sanitize($sql_file_path) ."</b>" );
	$this->_set_msg( "Creating tables..." );

	if( file_exists( XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ) ) {
		include_once XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ;
		$sqlutil =& new OldSqlUtility ;
	} else {
		include_once XOOPS_ROOT_PATH.'/class/database/sqlutility.php' ;
		$sqlutil =& new SqlUtility ;
	}

	$sql_query = trim( file_get_contents( $sql_file_path ) ) ;
	$sqlutil->splitMySqlFile( $pieces , $sql_query ) ;
	if ( !is_array( $pieces ) || !count( $pieces ) ) { return true; } 	// no action

	foreach ( $pieces as $piece ) 
	{
		$prefixed_query = $sqlutil->prefixQuery( $piece , $prefix_mod ) ;
		if( ! $prefixed_query ) {
			$this->_set_msg( "Invalid SQL <b>". $this->sanitize($piece) ."</b>" );
			return false ;
		}

// replace reserved words
		$sql = str_replace( '{DIRNAME}', $this->_DIRNAME, $prefixed_query[0] );

		$ret = $this->query( $sql );
		if ( ! $ret ) {
			$this->_set_msg( $this->get_db_error() ) ;
			return false ;
		}

		$table = $prefixed_query[4];
		$table_name_s = $this->sanitize( $prefix_mod. '_'. $table );

		if ( $this->_parse_create_table( $sql ) ) {
			$this->_set_msg( 'Table <b>'.  $table_name_s .'</b> created' );

		} else {
			$this->_set_msg( 'Data inserted to table <b>'. $table_name_s .'</b>' );
		}

	}

	return true;
}

function _table_update()
{
	$sql_file_path = $this->_get_table_sql();
	if ( ! $sql_file_path ) {
		return true;	// no action
	}

	$prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME ;

	if( file_exists( XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ) ) {
		include_once XOOPS_ROOT_PATH.'/class/database/oldsqlutility.php' ;
		$sqlutil =& new OldSqlUtility ;
	} else {
		include_once XOOPS_ROOT_PATH.'/class/database/sqlutility.php' ;
		$sqlutil =& new SqlUtility ;
	}

	$sql_query = trim( file_get_contents( $sql_file_path ) ) ;
	$sqlutil->splitMySqlFile( $pieces , $sql_query ) ;
	if ( !is_array( $pieces ) || !count( $pieces ) ) { 
		return true;  	// no action
	}

	$sql_array = array() ;

// get added table
	foreach ( $pieces as $piece ) 
	{
		$prefixed_query = $sqlutil->prefixQuery( $piece , $prefix_mod ) ;
		if( ! $prefixed_query ) {
			$this->_set_msg( "Invalid SQL <b>". $this->sanitize($piece) ."</b>" );
			return false ;
		}

		$sql = $prefixed_query[0];

// get create table
		$table = $this->_parse_create_table( $sql );
		if ( empty($table) ) {
			continue;
		}

// already exists
		if ( $this->exists_table( $table ) ) {
			continue;
		}

		$sql_array[ $table ] = $sql ;
	}

	if ( !is_array( $sql_array ) || !count( $sql_array ) ) { 
		return true;  	// no action
	}

	$this->_set_msg( "SQL file found at <b>". $this->sanitize($sql_file_path) ."</b>" );
	$this->_set_msg( "Creating tables..." );

// create added table
	foreach ( $sql_array as $table => $sql ) 
	{
		$ret = $this->query( $sql );
		if ( ! $ret ) {
			$this->_set_msg( $this->get_db_error() ) ;
			return false ;
		}

		$table_name_s = $this->sanitize( $table );
		$this->_set_msg( 'Table <b>'.  $table_name_s .'</b> created' );
	}

	return true;
}

function _table_uninstall()
{
	$sql_file_path = $this->_get_table_sql();
	if ( ! $sql_file_path ) { return true; }	// no action

	$prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME ;

	$this->_set_msg( "SQL file found at <b>".$this->sanitize($sql_file_path)."</b>" );
	$this->_set_msg( "Deleting tables..." );

	$sql_lines = file( $sql_file_path ) ;

	foreach ( $sql_lines as $sql_line ) 
	{
	// get create table
		$table = $this->_parse_create_table( $sql_line );
		if ( empty($table) ) {
			continue;
		}

// BUG : undefined variable table_name
		$table_name = $prefix_mod. '_'. $table ;

		$table_name_s = $this->sanitize( $table_name );
		$sql = 'DROP TABLE '. $table_name ;

		$ret = $this->query($sql) ;
		if ( $ret ) {
			$this->_set_msg( 'Table <b>'. $table_name_s .'</b> dropped' );
		} else {
			$this->_set_msg( $this->highlight( 'ERROR: Could not drop table <b>'. $table_name_s .'<b>.' ) );
			$this->_set_msg( $this->get_db_error() ) ;
		}
	}

	return true;
}

function _get_table_sql()
{
	$sql_trust_path = $this->_TRUST_DIR  .'/sql/mysql.sql' ;
	$sql_root_path  = $this->_MODULE_DIR .'/sql/mysql.sql' ;

	if ( is_file( $sql_root_path ) ) {
		return $sql_root_path;
	} elseif( is_file( $sql_trust_path ) ) {
		return $sql_trust_path;
	}
	return false;
}

function _parse_create_table( $sql )
{
	if ( preg_match( '/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i' , $sql, $match ) ) {
		return $match[1];
	}
	return false;
}

//---------------------------------------------------------
// template handler
//---------------------------------------------------------
function _template_install()
{
	return $this->_template_common();
}

function _template_update()
{
	return $this->_template_common();
}

function _template_common()
{
	$this->_set_msg( "Updating tmplates ..." );

	$TPL_TRUST_PATH = $this->_TRUST_DIR  .'/templates';
	$TPL_ROOT_PATH  = $this->_MODULE_DIR .'/templates';

// read webphoto_xxx.html in root_path
	if ( $this->_IS_XOOPS_2018 ) {
		$tpl_path = $TPL_ROOT_PATH . '/';
		$prefix   = ''; 

// read xxx.html in trust_path
	} else {
		$tpl_path = $TPL_TRUST_PATH . '/';
		$prefix   = $this->_DIRNAME .'_'; 
	}

	// TEMPLATES
	$tplfile_handler =& xoops_gethandler( 'tplfile' ) ;

	$handler = @opendir( $tpl_path ) ;
	if ( ! $handler ) {
		xoops_template_clear_module_cache( $this->_MODULE_ID ) ;
		return true;
	}

	while( ( $file = readdir( $handler ) ) !== false ) 
	{
	// check file
		if ( !$this->_check_tpl_file( $file ) ) {
			continue ;
		}

	// use optional file, if exists
		$file_trust_path = $TPL_TRUST_PATH . '/' . $file ;
		$file_root_path  = $TPL_ROOT_PATH  . '/' . $file ;
		if ( is_file( $file_root_path ) ) {
			$file_path = $file_root_path;
		} elseif( is_file( $file_trust_path ) ) {
			$file_path = $file_trust_path;
		} else {
			continue;
		}

		$dirname_file   = $prefix . $file ;
		$dirname_file_s = $this->sanitize( $dirname_file );
		$mtime = intval( @filemtime( $file_path ) ) ;

	// set table
		$tplfile =& $tplfile_handler->create() ;
		$tplfile->setVar( 'tpl_source' , file_get_contents( $file_path ) , true ) ;
		$tplfile->setVar( 'tpl_refid' , $this->_MODULE_ID ) ;
		$tplfile->setVar( 'tpl_tplset' , 'default' ) ;
		$tplfile->setVar( 'tpl_file' , $dirname_file ) ;
		$tplfile->setVar( 'tpl_desc' , '' , true ) ;
		$tplfile->setVar( 'tpl_module' , $this->_DIRNAME ) ;
		$tplfile->setVar( 'tpl_lastmodified' , $mtime ) ;
		$tplfile->setVar( 'tpl_lastimported' , 0 ) ;
		$tplfile->setVar( 'tpl_type' , 'module' ) ;

		$ret1 = $tplfile_handler->insert( $tplfile );
		if ( $ret1 ) {
			$tplid = $tplfile->getVar( 'tpl_id' ) ;
			$this->_set_msg( ' &nbsp; Template <b>'. $dirname_file_s .'</b> added to the database. (ID: <b>'.$tplid.'</b>)' );

			// generate compiled file
			$ret2 = xoops_template_touch( $tplid );
			if ( $ret2 ) {
				$this->_set_msg( ' &nbsp; Template <b>'. $dirname_file_s .'</b> compiled.</span>' );
			} else {
				$this->_set_msg( $this->highlight( 'ERROR: Failed compiling template <b>'. $dirname_file_s .'</b>.' ) );
			}

		} else {
			$this->_set_msg( $this->highlight( 'ERROR: Could not insert template <b>'. $dirname_file_s .'</b> to the database.' ) );
		}

	}

	closedir( $handler ) ;
	xoops_template_clear_module_cache( $this->_MODULE_ID ) ;

	return true;
}

function _template_uninstall()
{
	// TEMPLATES (Not necessary because modulesadmin removes all templates)
}

function _check_tpl_file( $file )
{
// ignore . and ..
	if ( $this->_parse_first_char( $file ) == '.' ) {
		return false;
	}
// ignore 'index.htm'
	if (( $file == 'index.htm' )||( $file == 'index.html' )) {
		return false;
	}
// ignore not html
	if ( $this->_parse_ext( $file ) != 'html' ){
		return false;
	}
	return true; 
}

function _parse_first_char( $file )
{
	return substr( $file , 0 , 1 );
}

function _parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

//---------------------------------------------------------
// groupperm handler
//---------------------------------------------------------
function _groupperm_install()
{
	$this->_set_msg( 'Add records to table <b>'. $this->_db->prefix('groupperm') .'</b> ...' );

	$gperm_handler = xoops_gethandler("groupperm");

	$global_perms_array = array(
		_B_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPERINSERT | _B_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPEREDIT   | _B_WEBPHOTO_GPERM_EDITABLE ,
		_B_WEBPHOTO_GPERM_SUPERDELETE | _B_WEBPHOTO_GPERM_DELETABLE ,
		_B_WEBPHOTO_GPERM_RATEVIEW ,
		_B_WEBPHOTO_GPERM_RATEVOTE    | _B_WEBPHOTO_GPERM_RATEVIEW ,
		_B_WEBPHOTO_GPERM_TELLAFRIEND ,
		_B_WEBPHOTO_GPERM_TAGEDIT ,
		_B_WEBPHOTO_GPERM_MAIL ,
		_B_WEBPHOTO_GPERM_FILE ,
	) ;

	foreach( $global_perms_array as $perms_id ) 
	{
		$gperm =& $gperm_handler->create();
		$gperm->setVar("gperm_groupid", XOOPS_GROUP_ADMIN);
		$gperm->setVar("gperm_name",    _C_WEBPHOTO_GPERM_NAME );
		$gperm->setVar("gperm_modid",   $this->_MODULE_ID );
		$gperm->setVar("gperm_itemid",  $perms_id );
		$gperm_handler->insert($gperm) ;
		unset($gperm);
	}

	return true ;
}

//---------------------------------------------------------
// item table
//---------------------------------------------------------
function _item_update()
{
	$this->_item_add_column_external();
}

function _item_add_column_external()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_external_url' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;

	$sql  .= "item_time_publish  INT(10) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_time_expire   INT(10) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_player_id   INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_flashvar_id INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_duration    INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_displaytype INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_onclick     INT(11) UNSIGNED NOT NULL DEFAULT '0', " ; 
	$sql  .= "item_views INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_chain INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_siteurl VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_artist  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_album   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_label   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_perm_down VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_external_url   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_external_thumb VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_embed_type  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_embed_src   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_feed  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_dir   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_cache VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_type INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_playlist_time INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_showinfo  VARCHAR(255) NOT NULL DEFAULT '' " ;

	$sql .= " )";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->_set_msg( 'Add item_external_type in <b>'. $this->_table_item .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

//---------------------------------------------------------
// mime table
//---------------------------------------------------------
function _mime_update()
{
	$this->_mime_add_column_ffmpeg();
	$this->_mime_add_record_asf_etc();
	$this->_mime_update_record_ffmpeg();
	$this->_mime_delete_record_asx();
}

function _mime_add_column_ffmpeg()
{

// return if already exists
	if ( $this->exists_column( $this->_table_mime, 'mime_ffmpeg' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_mime ;
	$sql .= " ADD mime_ffmpeg varchar(255) NOT NULL default '' ";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->_set_msg( 'Add mime_ffmpeg in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_add_record_asf_etc()
{
	$mime_list = array();

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Third Generation Partnership Project 2 File Format' ,
		'mime_ext'         => '3g2' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/3gpp2' ,
		'mime_perms'       => '&1&' ,
		'mime_ffmpeg'      => '-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Third Generation Partnership Project File Format' ,
		'mime_ext'         => '3gp' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/3gpp' ,
		'mime_perms'       => '&1&' ,
		'mime_ffmpeg'      => '-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Advanced Systems Format' ,
		'mime_ext'         => 'asf' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/x-ms-asf' ,
		'mime_perms'       => '&1&' ,
		'mime_ffmpeg'      => '-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Flash Video' ,
		'mime_ext'         => 'flv' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/x-flv application/octet-stream' ,
		'mime_perms'       => '&1&' ,
		'mime_ffmpeg'      => '-ar 44100' ,
	);

	foreach ( $mime_list as $mime_row ) 
	{
		$ext = $mime_row['mime_ext'];

// skip if already exists
		$row = $this->_mime_get_row_by_ext( $ext );
		if ( is_array($row) ) {
			continue;
		}

		$ret = $this->_mime_insert_record( $mime_row );
		if ( $ret ) {
			$this->_set_msg( 'Add '. $ext .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}
}

function _mime_update_record_ffmpeg()
{
	$list = array( 'avi', 'mov', 'mpeg', 'mpg', 'wmv' );

	foreach ( $list as $ext ) 
	{
		$row  = $this->_mime_get_row_by_ext( $ext );

// skip if already set
		if ( $row['mime_ffmpeg'] ) {
			continue;
		}

		$row['mime_ffmpeg'] = '-ar 44100' ;

		$ret = $this->_mime_update_record( $row );
		if ( $ret ) {
			$this->_set_msg( 'Update '. $ext .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}

}

function _mime_delete_record_asx()
{
	$row = $this->_mime_get_row_by_ext( 'asx' );
	if ( !is_array($row) ) {
		return true;	// no action
	}

	$ret = $this->_mime_delete_by_id( $row['mime_id'] );
	if ( $ret ) {
		$this->_set_msg( 'Delete asx in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not delete <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_insert_record( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '. $this->_table_mime .' (';

	$sql .= 'mime_time_create, ';
	$sql .= 'mime_time_update, ';
	$sql .= 'mime_name, ';
	$sql .= 'mime_ext, ';
	$sql .= 'mime_medium, ';
	$sql .= 'mime_type, ';
	$sql .= 'mime_perms, ';
	$sql .= 'mime_ffmpeg ';

	$sql .= ') VALUES ( ';

	$sql .= intval($mime_time_create).', ';
	$sql .= intval($mime_time_update).', ';
	$sql .= $this->quote($mime_name).', ';
	$sql .= $this->quote($mime_ext).', ';
	$sql .= $this->quote($mime_medium).', ';
	$sql .= $this->quote($mime_type).', ';
	$sql .= $this->quote($mime_perms).', ';
	$sql .= $this->quote($mime_ffmpeg).' ';

	$sql .= ')';

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

function _mime_update_record( $row )
{
	extract( $row ) ;

	$sql  = 'UPDATE '. $this->_table_mime .' SET ';

	$sql .= 'mime_time_create='.intval($mime_time_create).', ';
	$sql .= 'mime_time_update='.intval($mime_time_update).', ';
	$sql .= 'mime_name='.$this->quote($mime_name).', ';
	$sql .= 'mime_ext='.$this->quote($mime_ext).', ';
	$sql .= 'mime_medium='.$this->quote($mime_medium).', ';
	$sql .= 'mime_type='.$this->quote($mime_type).', ';
	$sql .= 'mime_perms='.$this->quote($mime_perms).', ';
	$sql .= 'mime_ffmpeg='.$this->quote($mime_ffmpeg).' ';

	$sql .= 'WHERE mime_id='.intval($mime_id);

	return $this->query( $sql );
}

function _mime_delete_by_id( $id )
{
	$sql  = 'DELETE FROM '. $this->_table_mime ;
	$sql .= ' WHERE mime_id='.intval( $id );
	return $this->query( $sql );
}

function _mime_get_row_by_ext( $ext )
{
	$sql  = 'SELECT * FROM '. $this->_table_mime ;
	$sql .= ' WHERE mime_ext='.$this->quote( $ext );
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function _set_msg( $msg )
{
// array type
	if ( is_array($msg) ) {
		foreach ( $msg as $m ) {
			$this->_msg_array[] = $m;
		}

// string type
	} else {
		$arr = explode("\n", $msg);
		foreach ( $arr as $m ) {
			$this->_msg_array[] = $m;
		}
	}
}

function _get_msg_array()
{
	return $this->_msg_array;
}

// --- class end ---
}

?>