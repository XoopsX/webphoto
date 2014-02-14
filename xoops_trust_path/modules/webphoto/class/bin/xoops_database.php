<?php
// $Id: xoops_database.php,v 1.1 2008/08/25 19:30:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class Database
// substitute for class XOOPS Database
// base on happy_linux/class/xoops_database.php
//=========================================================
class Database
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function Database()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if ( !isset($instance) ) 
	{
// Assigning the return value of new by reference is deprecated
		$instance = new mysql_database();
		if ( !$instance->connect() ) 
		{
			echo "<font color='red'>Unable to connect to database.</font><br />\n";
			die();
		}
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function prefix($tablename='')
{
	if ( $tablename != '' ) {
		return XOOPS_DB_PREFIX .'_'. $tablename;
	} else {
		return XOOPS_DB_PREFIX;
	}
}

//---------------------------------------------------------
}

?>