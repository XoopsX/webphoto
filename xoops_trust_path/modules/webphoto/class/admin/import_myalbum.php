<?php
// $Id: import_myalbum.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_import -> webphoto_edit_import
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_import_myalbum
//=========================================================
class webphoto_admin_import_myalbum extends webphoto_edit_import
{
	var $_form_class;

	var $_src_dirname = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_import_myalbum( $dirname , $trust_dirname )
{
	$this->webphoto_edit_import( $dirname , $trust_dirname );

	$this->_form_class =& webphoto_admin_import_form::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_import_myalbum( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'IMPORT_MYALBUM' );

	echo "Import DB myalbum 2.88 to webphoto 0.10 or later <br /><br />\n";

	switch ( $this->get_post_op() ) 
	{
		case "import_category":
			if ( $this->_check() ) {
				$this->_import_category();
			}
			break;

		case "import_photo":
			if ( $this->_check() ) {
				$this->_import_photo();
			}
			break;

		case "import_vote":
			if ( $this->_check() ) {
				$this->_import_vote();
			}
			break;

		case "import_comment":
			if ( $this->_check() ) {
				$this->_import_comment();
			}
			break;

		case "confirm_truncate":
			if ( $this->check_token() ) {
				$this->_form_truncate( 'truncate' );
			}
			break;

		case "truncate":
			if ( $this->check_token() ) {
				$this->_truncate();
			}
			break;

		case 'menu':
		default:
			$this->_menu();
			break;
	}

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// menu
//---------------------------------------------------------
function _menu()
{
	$this->_check_cat_table()

?>
<br />
There are 5 steps. <br />
1. select myalbum module <br />
2. import category table <br />
3. import photo table <br />
4. import vote table <br />
5. import xoops comment table <br />
excute each <?php echo $this->_LIMIT; ?> records at a time <br />
<br />
<h4 style="color:#ff0000;">Warnig</h4>
Excute only once, after install. <br />
This program overwrite MySQL tables. <br />
<?php

	$this->_form_sel_myalbum();
	$this->_form_truncate( 'confirm_truncate' );

}

function _check_cat_table()
{
	if ( $this->_cat_handler->get_count_all() > 0 ) {
		$msg = 'already there are records in category table';
		echo $this->build_error_msg( $msg );
		echo "<br />\n";
		echo '<b>try to truncate tables</b><br />';
		echo "<br />\n";
	}
}

//---------------------------------------------------------
// import_category
//---------------------------------------------------------
function _import_category()
{
// === myalbum category table ===
//  cid int(5) unsigned NOT NULL auto_increment,
//  pid int(5) unsigned NOT NULL default '0',
//  title varchar(50) NOT NULL default '',
//  imgurl varchar(150) NOT NULL default '',
//  weight int(5) unsigned NOT NULL default 0,
//  depth int(5) unsigned NOT NULL default 0,
//  description text,
//  allowed_ext varchar(255) NOT NULL default 'jpg|jpeg|gif|png',

	echo "<h4>STEP 2 : import category table</h4>\n";
	$this->_print_src_dirname();

	$myalbum_rows = $this->_myalbum_handler->get_cat_rows();
	$total        = count($myalbum_rows);

	echo "There are $total categorys in myalbum<br /><br />\n";

	foreach ($myalbum_rows as $myalbum_row) 
	{
		$cid   = $myalbum_row['cid'];
		$title = $myalbum_row['title'];

		echo $cid.' : '.$this->sanitize($title)." <br />\n";

		$this->insert_category_from_myalbum( $cid, $myalbum_row );
	}

	$this->_form_photo();
}

//---------------------------------------------------------
// import_photo
//---------------------------------------------------------
function _import_photo()
{
// === myalbum link & text table ===
// --- link table ---
//  lid int(11) unsigned NOT NULL auto_increment,
//  cid int(5) unsigned NOT NULL default '0',
//  title varchar(255) NOT NULL default '',
//  ext varchar(10) NOT NULL default '',
//  res_x int(11) NOT NULL default '0',
//  res_y int(11) NOT NULL default '0',
//  submitter int(11) unsigned NOT NULL default '0',
//  status tinyint(2) NOT NULL default '0',
//  date int(10) NOT NULL default '0',
//  hits int(11) unsigned NOT NULL default '0',
//  rating double(6,4) NOT NULL default '0.0000',
//  votes int(11) unsigned NOT NULL default '0',
//  comments int(11) unsigned NOT NULL default '0',
//
// --- text table ---
//  lid int(11)
//  description text

	$offset = $this->get_post_offset();

	$max_middle_width  = $this->_config_class->get_by_name('middle_width');
	$max_middle_height = $this->_config_class->get_by_name('middle_height');
	$max_thumb_width   = $this->_config_class->get_by_name('thumb_width');
	$max_thumb_height  = $this->_config_class->get_by_name('thumb_height');

	$total        = $this->_myalbum_handler->get_photos_count_all();
	$myalbum_rows = $this->_myalbum_handler->get_photos_rows( $this->_LIMIT, $offset );

	$next = $this->_next;
	if ( $this->_next > $total ) {
		$next = $total;
	}

	echo "<h4>STEP 3: photo table</h4>\n";
	$this->_print_src_dirname();
	echo "There are $total photos in myalbum<br />\n";
	echo "Import $offset - $next th link <br /><br />";

	foreach ($myalbum_rows as $myalbum_row)
	{
		$lid   = $myalbum_row['lid'];
		$title = $myalbum_row['title'];
		$cid   = $myalbum_row['cid'];

		echo $lid.' : '.$this->sanitize($title);

		$this->add_photo_from_myalbum( $lid, $cid, $myalbum_row );

		echo "<br />\n";
	}

	if ( $total > $next ) {
		$this->_form_next_photo( $next );
	} else {
		$this->_form_votedate();
	}

}

//---------------------------------------------------------
// import_vote
//---------------------------------------------------------
function _import_vote()
{
// === myalbum votedata table ===
//  ratingid int(11) unsigned NOT NULL auto_increment,
//  lid int(11) unsigned NOT NULL default '0',
//  ratinguser int(11) unsigned NOT NULL default '0',
//  rating tinyint(3) unsigned NOT NULL default '0',
//  ratinghostname varchar(60) NOT NULL default '',
//  ratingtimestamp int(10) NOT NULL default '0',

	echo "<h4>STEP 4 : import vote table</h4>";
	$this->_print_src_dirname();

	$myalbum_rows = $this->_myalbum_handler->get_votedata_rows();
	$total        = count($myalbum_rows);

	echo "There are $total votedatas in myalbum<br /><br />\n";

	foreach ($myalbum_rows as $myalbum_row) 
	{
		$ratingid = $myalbum_row['ratingid'];
		$lid      = $myalbum_row['lid'];

		echo "$ratingid: $lid <br />\n";

		$this->insert_vote_from_myalbum( $ratingid, $lid, $myalbum_row );
	}

	$this->_form_comment();
}

//---------------------------------------------------------
// import_comment
//---------------------------------------------------------
function _import_comment()
{
	echo "<h4>STEP 5 : import xoopscomments table</h4>";
	$this->_print_src_dirname();

	$rows  = $this->_xoops_comments_handler->get_rows_by_modid( $this->_myalbum_mid );
	$total = count($rows);

	echo "There are $total comments <br /><br />\n";

	$this->insert_comments_from_src( 0, $rows );

	$this->print_finish();
}

//---------------------------------------------------------
// truncate
//---------------------------------------------------------
function _truncate()
{
	echo "<h4>Truncate category table</h4>";
	$this->_cat_handler->truncate_table();

	echo "<h4>Truncate item table</h4>";
	$this->_item_handler->truncate_table();

	echo "<h4>Truncate file table</h4>";
	$this->_file_handler->truncate_table();

	echo "<h4>Truncate vote table</h4>";
	$this->_vote_handler->truncate_table();

	echo "<h4>Delete recoeds in xoopscomments table</h4>";
	$this->_xoops_comments_handler->delete_all_by_modid( $this->_MODULE_ID );

	$this->print_finish();
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function _check()
{
// check exists myalbum module
	$ret = $this->_exists_myalbum_module();
	if ( !$ret ) {
		return false;
	}

	return $this->check_token();
}

function _exists_myalbum_module()
{
	$dirname = $this->_post_class->get_post_text('src_dirname');

// check exists myalbum module
	$ret = $this->init_myalbum( $dirname );
	if ( !$ret ) {
		$msg = $dirname.' module is not installed ';
		echo $this->build_error_msg( $msg );
		return false;
	}

	$this->_src_dirname = $dirname;
	return true;
}

function _print_src_dirname()
{
	echo 'from dirname : '.$this->_src_dirname."<br />\n";
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _form_sel_myalbum()
{
	$module_array = $this->_myalbum_handler->get_myalbum_module_array();
	if ( !is_array($module_array) || !count($module_array) ) {
		$msg = "myalbum module is not installed \n";
		echo $this->build_error_msg( $msg );
		return false;
	}

	$title  = 'STEP 1 : select myalbum module';
	echo "<h4>".$title."</h4>\n";

	$myalbum_dirname = $module_array[0]['dirname'];

	$options = array();
	foreach ( $module_array as $mod ) 
	{
		$dirname = $mod['dirname'];
		$name    = $mod['name'];
		$options[ $dirname ] = $dirname.' : '.$name;
	}

	$this->_form_class->print_form_sel_myalbum( $title, $options );
}

function _form_truncate( $op )
{
	$title  = 'STEP 0 : initalize';
	$submit = 'GO STEP 0';

	echo "<h4>".$title."</h4>\n";
	echo "Truncate tables.<br />\n";
	echo "If you want to redo <br /><br />\n";
	if ( $op == 'truncate' ) {
		echo $this->highlight( "Are you sure ?" )."<br />\n";
	}

	$this->_print_form_next($title, $op, $submit);
}

function _form_image()
{
	$title  = 'STEP 1 : photo images';
	$op     = 'import_image';
	$submit = 'GO STEP 1';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_category()
{
	$title  = 'STEP 2 : import category table';
	$op     = 'import_category';
	$submit = 'GO STEP 2';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_photo()
{
	$title  = 'STEP 3 : import photo table';
	$op     = 'import_photo';
	$submit = 'GO STEP 3';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_next_photo($offset)
{
	$title  = 'STEP 3 : import photo table';
	$submit = "GO next ".$this->_LIMIT." links";
	$op     = 'import_photo';

	echo "<br /><hr />\n";
	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit, $offset);
}

function _form_votedate()
{
	$title  = 'STEP 4 : import votedate table';
	$op     = 'import_vote';
	$submit = 'GO STEP 4';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_comment()
{
	$title  = 'STEP 5 : import comment table';
	$op     = 'import_comment';
	$submit = 'GO STEP 5';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _print_form_next( $title, $op, $submit_value, $offset=0 )
{
	echo "<br />\n";

	if ( $offset > 0 ) {
		$next = $offset + $this->_LIMIT;
		echo "Import ".$offset." - ".$next." th record<br />\n";
	}

// show form
	$param = array(
		'title'        => $title,
		'submit_value' => $submit_value,
	);

	$hidden_arr = array(
		'fct'    => 'import_myalbum' ,
		'op'     => $op,
		'limit'  => 0,
		'offset' => $offset,
		'src_dirname' => $this->_src_dirname,
	);

	$text = $this->_form_class->build_form_box_with_style( $param, $hidden_arr );
	echo $text;
}

// --- class end ---
}

?>