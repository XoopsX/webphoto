<?php
// $Id: redo_form.php,v 1.3 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// 2008-07-01 K.OHWADA
// added _build_ele_exif()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_redo_form
//=========================================================
class webphoto_admin_redo_form extends webphoto_edit_form
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_redo_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_redo_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// redothumbs
//---------------------------------------------------------
function print_form_redothumbs( $param )
{
	$cfg_width   = $this->_config_class->get_by_name('width');
	$cfg_height  = $this->_config_class->get_by_name('height');

	$this->set_row( $param );

	$cap_size = _AM_WEBPHOTO_TEXT_NUMBERATATIME."<br /><br /><span style='font-weight:normal'>"._AM_WEBPHOTO_LABEL_DESCNUMBERATATIME."</span>";
	$cap_resize =_AM_WEBPHOTO_RADIO_RESIZE.' ( '. $cfg_width .' x '. $cfg_width .' )';

	if( $param['counter'] && ( $param['counter'] < $param['size'] ) ) {
		$submit_button  = _AM_WEBPHOTO_FINISHED.' &nbsp; ';
		$submit_button .= '<a href="'. $this->_THIS_FCT_URL .'">';
		$submit_button .= _AM_WEBPHOTO_LINK_RESTART."</a>\n" ;
	} else {
		$submit_button = $this->build_input_submit( 'submit', _AM_WEBPHOTO_SUBMIT_NEXT );
	}

	echo $this->build_form_begin();
	echo $this->build_input_hidden('fct', 'redothumbs');
	echo $this->build_table_begin();

	echo $this->build_line_title( _AM_WEBPHOTO_FORM_RECORDMAINTENANCE );
	echo $this->build_row_text( _AM_WEBPHOTO_TEXT_RECORDFORSTARTING, 'start' );
	echo $this->build_row_text( $cap_size,                   'size' );
	echo $this->build_row_radio_yesno( _AM_WEBPHOTO_RADIO_FORCEREDO, 'forceredo' );
	echo $this->build_row_radio_yesno( _AM_WEBPHOTO_RADIO_REMOVEREC, 'removerec' );
	echo $this->build_row_radio_yesno( $cap_resize,         'resize' );
	echo $this->build_line_ele( _AM_WEBPHOTO_CAP_REDO_EXIF, $this->_build_ele_exif() );

	echo $this->build_line_ele( '', $submit_button );

	echo $this->build_table_end();
	echo $this->build_form_end(); 
}

function _build_ele_exif()
{
	$value = $this->get_row_by_key( 'exif' );
	$options = array(
		_NO => 0 ,
		_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY    => 1 ,
		_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS => 2,
	);
	return $this->build_form_radio( 'exif', $value, $options );
}

// --- class end ---
}

?>