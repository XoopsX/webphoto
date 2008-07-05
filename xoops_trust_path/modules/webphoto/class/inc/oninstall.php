<?php
// $Id: oninstall.php,v 1.3 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added _mime_update()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall
//=========================================================
class webphoto_inc_oninstall extends webphoto_inc_handler
{
	var $_table_mime ;

	var $_is_xoops_2018 = false;

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
			$ret[] = $msg;
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

	$this->_table_mime = $this->prefix_dirname( 'mime' );

// preload
	$preload_file = $this->_TRUST_DIR  .'/preload/constants.php';

	if ( is_file( $preload_file ) ) {
		include_once $preload_file;
	}

	if ( defined("_C_WEBPHOTO_PRELOAD_XOOPS_2018") ) {
		$this->_is_xoops_2018 = true ;
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

	$this->_config_update();
	$this->_mime_update();
	$this->_table_update();
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

	$created_tables = array() ;
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

		if ( ! in_array( $table , $created_tables ) ) {
			$this->_set_msg( 'Table <b>'.  $table_name_s .'</b> created' );
			$created_tables[] = $table;

		} else {
			$this->_set_msg( 'Data inserted to table <b>'. $table_name_s .'</b>' );
		}

	}

	return true;
}

function _table_update()
{
	// TABLES (write here ALTER TABLE etc. if necessary)
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
		if( preg_match( '/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i' , $sql_line , $regs ) ) {
			$table_name   = $prefix_mod.'_'.$regs[1];
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
	if ( $this->_is_xoops_2018 ) {
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
// config table
//---------------------------------------------------------
function _config_update()
{
	// configs (Though I know it is not a recommended way...)
	$table_config = $this->_db->prefix("config");
	
	$check_sql = "SHOW COLUMNS FROM ". $table_config ." LIKE 'conf_title'" ;
	$row = $this->get_row_by_sql( $check_sql );
	if ( !is_array($row) ) { return false; }

	if ( $row['Type'] != 'varchar(30)' ) { return true; }

	$sql  = "ALTER TABLE ". $table_config;
	$sql .= " MODIFY `conf_title` varchar(255) NOT NULL default '', ";
	$sql .= " MODIFY `conf_desc`  varchar(255) NOT NULL default '' ";
	$ret = $this->query( $sql );
	if ( $ret ) {
		$this->_set_msg( 'Modify char length in <b>'. $table_config .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not modify <b>'. $table_config .'</b>.' ) );
		return false;
	}

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
// mime table
//---------------------------------------------------------
function _mime_update()
{
	$this->_mime_add_column_ffmpeg();
	$this->_mime_add_record_flv();
	$this->_mime_update_record_avi();
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

function _mime_add_record_flv()
{
// return if already exists
	$row = $this->_mime_get_row_by_ext( 'flv' );
	if ( is_array($row) ) {
		return true;
	}

	$row = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Flash Video' ,
		'mime_ext'         => 'flv' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/x-flv' ,
		'mime_perms'       => '&1&' ,
		'mime_ffmpeg'      => '' ,
	);

	$ret = $this->_mime_insert_record( $row );
	if ( $ret ) {
		$this->_set_msg( 'Add flv in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_update_record_avi()
{
	$row = $this->_mime_get_row_by_ext( 'avi' );

// return if already set
	if ( $row['mime_ffmpeg'] ) {
		return true;
	}

	$row['mime_ffmpeg'] = '-ar 44100' ;

	$ret = $this->_mime_update_record( $row );
	if ( $ret ) {
		$this->_set_msg( 'Update avi in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->_set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
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