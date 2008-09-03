<?php
// $Id: WebphotoD3commentContent.class.php,v 1.1 2008/09/03 02:44:53 ohwada Exp $

//=========================================================
// webphoto module
// 2008-09-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// WebphotoD3commentContent
// a class for d3forum comment integration
//=========================================================
class WebphotoD3commentContent extends D3commentAbstract 
{

function fetchSummary( $link_id )
{
	$mydirname = $this->mydirname ;
	if ( preg_match( '/[^0-9a-zA-Z_-]/' , $mydirname ) ) die( 'Invalid mydirname' ) ;

	$db =& Database::getInstance() ;
	$myts =& MyTextsanitizer::getInstance() ;

	$module_handler =& xoops_gethandler( 'module' ) ;
	$module =& $module_handler->getByDirname( $mydirname ) ;

	// query
	$sql  = 'SELECT * FROM '. $db->prefix( $mydirname.'_item' );
	$sql .= ' WHERE item_id='. intval( $link_id ) ;
	$sql .= ' AND item_status > 0 ';
	$item_row = $db->fetchArray( $db->query( $sql ) ) ;
	if ( empty( $item_row ) ) {
		return '' ;
	}

	// dare to convert it irregularly
	$summary = str_replace( '&amp;' , '&' , htmlspecialchars( xoops_substr( strip_tags( $item_row['item_description'] ) , 0 , 255 ) , ENT_QUOTES ) ) ;

	$ret = array(
		'dirname'     => $mydirname ,
		'module_name' => $module->getVar( 'name' ) ,
		'subject'     => $myts->makeTboxData4Show( $item_row['item_title'] ) ,
		'uri'         => XOOPS_URL .'/modules/'. $mydirname .'/index.php?fct=photo&photo_id='. intval( $link_id ) ,
		'summary'     => $summary ,
	) ;

	return $ret;
}

function validate_id( $link_id )
{
	$db =& Database::getInstance() ;

	// query
	$sql  = 'SELECT COUNT(*) FROM '. $db->prefix( $this->mydirname.'_item' );
	$sql .= ' WHERE item_id='. intval( $link_id ) ;
	$sql .= ' AND item_status > 0 ';

	list( $count ) = $db->fetchRow( $db->query( $sql ) ) ;
	if ( $count <= 0 ) {
		return false ;
	}
	return $link_id ;
}

function onUpdate( $mode , $link_id , $forum_id , $topic_id , $post_id = 0 )
{
	$db =& Database::getInstance() ;

	$sql1  = 'SELECT COUNT(*) FROM ';
	$sql1 .= $db->prefix( $this->d3forum_dirname.'_posts' ) .' p ';
	$sql1 .= ' LEFT JOIN ';
	$sql1 .= $db->prefix( $this->d3forum_dirname.'_topics' ) .' t ';
	$sql1 .= ' ON t.topic_id=p.topic_id ';
	$sql1 .= ' WHERE t.forum_id='. intval( $forum_id );
	$sql1 .= ' AND t.topic_external_link_id='. intval( $link_id );

	list( $count ) = $db->fetchRow( $db->query( $sql1 ) ) ;

	$sql2  = 'UPDATE '. $db->prefix( $this->mydirname.'_item' );
	$sql2 .= ' SET item_comments='. intval( $count );
	$sql2 .= ' WHERE item_id='. intval( $link_id );

	return $db->queryF( $sql2 ) ;
}

// --- class end ---
}

?>