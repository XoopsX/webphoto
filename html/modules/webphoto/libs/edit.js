/* ========================================================
 * $Id: edit.js,v 1.3 2010/06/16 22:53:37 ohwada Exp $
 * ========================================================
 */

/* must define webphoto_gmap_disp_on in template */
function webphoto_check_all( cbox, prefix ) 
{
	var regexp = new RegExp("^" + prefix );
	var inputs = document.getElementsByTagName("input");
	for (i=0; i<inputs.length; i++) {
		var ele = inputs[i];
        if (ele.type == "checkbox" && ele.name.match(regexp)) {
			ele.checked = cbox.checked;
		}
	}
}
function webphoto_detail_checkbox_onoff( on )
{
	document.webphoto_edit.webphoto_form_detail_onoff.checked = on;
	webphoto_detail_disp_onoff( on )
}
function webphoto_gmap_checkbox_onoff( on )
{
	document.webphoto_edit.webphoto_form_gmap_onoff.checked = on;
	webphoto_gmap_disp_onoff( on )
}
function webphoto_detail_disp_onclick( checkbox ) 
{
	webphoto_detail_disp_onoff( checkbox.checked );
}
function webphoto_detail_disp_onoff( on ) 
{
	if ( on ) {
		document.getElementById("webphoto_detail").style.display = "block";
		webphoto_gmap_checkbox_onoff( on );
	} else{
		document.getElementById("webphoto_detail").style.display = "none";
	}
}
function webphoto_gmap_disp_onclick( checkbox ) 
{
	webphoto_gmap_disp_onoff( checkbox.checked ) ;
}
function webphoto_gmap_disp_onoff( on ) 
{
	if ( on ) {
		webphoto_gmap_disp_on();
	} else{
		webphoto_set_gmap_iframe('');
	}
}
function webphoto_set_gmap_iframe( html ) 
{
	document.getElementById("webphoto_gmap_iframe").innerHTML = html;
}
function webphoto_uploading()
{
	document.getElementById('webphoto_uploading').style.display = "block";
	document.getElementById('webphoto_submit_form').style.display = "none";
}