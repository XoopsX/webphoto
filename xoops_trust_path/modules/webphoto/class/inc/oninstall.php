<?php
// $Id: oninstall.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall
//=========================================================
class webphoto_inc_oninstall extends webphoto_inc_handler
{
	var $_TRUST_DIRNAME ;
	var $_TRUST_DIR;
	var $_MODULE_ID = 0;

	var $_msg_array = array();

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
			$msgs[] = $msg."<br />\n";
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
			$ret[] = $msg."<br />\n";
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
}

function _exec_install()
{
	// for Cube 2.1
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$name = 'Legacy.Admin.Event.ModuleInstall.' . ucfirst($this->_DIRNAME) . '.Success';
		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add( $name, 'webphoto_message_append_oninstall' ) ;
	}

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

	$this->_config_update();
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
	$TPL_TRUST_PATH = $this->_TRUST_DIR  .'/templates';
	$TPL_ROOT_PATH  = $this->_MODULE_DIR .'/templates';

	// TEMPLATES
	$tplfile_handler =& xoops_gethandler( 'tplfile' ) ;

	$handler = @opendir( $TPL_TRUST_PATH . '/' ) ;
	if ( ! $handler ) {
		xoops_template_clear_module_cache( $this->_MODULE_ID ) ;
		return true;
	}

	while( ( $file = readdir( $handler ) ) !== false ) 
	{
	// ignore . and ..
		if( substr( $file , 0 , 1 ) == '.' ) { 
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

		$dirname_file   = $this->_DIRNAME .'_'. $file ;
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
			$this->_set_msg( 'Template <b>'. $dirname_file_s .'</b> added to the database. (ID: <b>'.$tplid.'</b>)' );

			// generate compiled file
			$ret2 = xoops_template_touch( $tplid );
			if ( $ret2 ) {
				$this->_set_msg( 'Template <b>'. $dirname_file_s .'</b> compiled.</span>' );
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
		$this->_set_msg( $this->highlight( 'ERROR: Could not modify <b>'. $config_table .'</b>.' ) );
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