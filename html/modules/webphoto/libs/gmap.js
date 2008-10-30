/* ========================================================
 * $Id: gmap.js,v 1.2 2008/10/30 00:55:39 ohwada Exp $
 * http://code.google.com/apis/maps/index.html
 * ========================================================
 */

/* --------------------------------------------------------
 * change log
 * 2008-10-01
 *   add webphoto_gmap_on_load_marks()
 * 2008-02-12
 *   add webphoto_gmap_setMapType()
 *   change webphoto_gmap_use_type_control to webphoto_gmap_type_control
 * --------------------------------------------------------
 */

var webphoto_gmap_init_flag = false;

/* georss */
var webphoto_gmap_map_obj = null;
var webphoto_gmap_client_geocoder  = null;
var webphoto_gmap_location_marker  = null;
var webphoto_gmap_draggable_marker = null;
var webphoto_gmap_bounds      = null;
var webphoto_gmap_bounds_zoom = null;
var webphoto_gmap_drag_icon   = null;
var webphoto_gmap_base_icon   = null;
var webphoto_gmap_small_icon  = null;

/* japanese inverse geocoder */
var webphoto_gmap_address_jp;
var webphoto_gmap_pref_jp;
var webphoto_gmap_city_jp;
var webphoto_gmap_town_jp;
var webphoto_gmap_number_jp;

/* 37.0 -95.0 : Chetopa Kansas: center point of USA */
var webphoto_gmap_default_latitude  =  37.0;
var webphoto_gmap_default_longitude = -95.0;
var webphoto_gmap_default_zoom = 4;
var webphoto_gmap_location_marker_html = "Chetopa Kansas, USA";

var webphoto_gmap_geo_url = "";

var webphoto_gmap_zoom_max = 17;
var webphoto_gmap_zoom_geocode_default = 12;
var webphoto_gmap_zoom_accuracy = 12;
var webphoto_gmap_zoom_accuracy_tokyo_univ = 12;

var webphoto_gmap_xoops_langcode = "en";
var webphoto_gmap_lang_latitude  = "Latitude";
var webphoto_gmap_lang_longitude = "Longitude";
var webphoto_gmap_lang_zoom      = "Zoom Level";
var webphoto_gmap_lang_not_compatible = "Your browser cannot use GoogleMaps";
var webphoto_gmap_lang_no_match_place = "There are no place to match the address";
var webphoto_gmap_xoops_url    = "";
var webphoto_gmap_dirname      = "";
var webphoto_gmap_module_url   = "";
var webphoto_gmap_marker_url   = "";
var webphoto_gmap_opener_mode  = "";

var webphoto_gmap_map_control  = 'large';
var webphoto_gmap_map_type     = '';
var webphoto_gmap_geocode_kind = '';
var webphoto_gmap_type_control = 'default';

var webphoto_gmap_use_scale_control         = false;
var webphoto_gmap_use_overview_map_control  = true;
var webphoto_gmap_use_location_marker       = false;
var webphoto_gmap_use_location_marker_click = true;
var webphoto_gmap_use_draggable_marker = true;
var webphoto_gmap_use_search_marker    = true;
var webphoto_gmap_set_current_location = true;

var webphoto_gmap_use_nishioka_inverse    = false;
var webphoto_gmap_use_set_parent_location = false;
var webphoto_gmap_use_set_parent_address  = false;
var webphoto_gmap_use_get_parent_location = false;

var webphoto_gmap_info = new Array();
var webphoto_gmap_icon = new Array();

/* debug */
var webphoto_gmap_debug_geocoder_tokyo_univ = false;
var webphoto_gmap_debug_inverse_nishioka    = false;

/* --------------------------------------------------------
 * public functon
 * --------------------------------------------------------
 */
function webphoto_gmap_load_marks() 
{
	if ( GBrowserIsCompatible() ) {
		webphoto_gmap_show_marks();
	} else {
		alert( webphoto_gmap_lang_not_compatible );
	}
}
function webphoto_gmap_load_get_location() 
{
	if ( GBrowserIsCompatible() ) {
		webphoto_gmap_show_get_location();
	} else {
		alert( webphoto_gmap_lang_not_compatible );
	}
}
function webphoto_gmap_load_get_location_marks() 
{
	if ( GBrowserIsCompatible() ) {
		webphoto_gmap_show_get_location_marks();
	} else {
		alert( webphoto_gmap_lang_not_compatible );
	}
}
function webphoto_gmap_load_search() 
{
	if ( GBrowserIsCompatible() ) {
		webphoto_gmap_show_search();
	} else {
		alert( webphoto_gmap_lang_not_compatible );
	}
}
function webphoto_gmap_load_georss() 
{
	if ( GBrowserIsCompatible() ) {
		webphoto_gmap_show_georss();
	} else {
		alert( webphoto_gmap_lang_not_compatible );
	}
}
function webphoto_gmap_searchAddress( addr )
{
	if ( webphoto_gmap_geocode_kind == 'latlng' ) {
		webphoto_gmap_geocoder_LatLng( addr )
	} else if ( webphoto_gmap_geocode_kind == 'tokyo_univ' ) {
		webphoto_gmap_geocoder_tokyoUniv( addr );
	} else {
		webphoto_gmap_geocoder_Locations( addr );
	}
}
function webphoto_gmap_setCenter( lat, lng, zoom )
{
	webphoto_gmap_map_obj.setCenter( new GLatLng( parseFloat( lat ) , parseFloat( lng ) ) );
	webphoto_gmap_map_obj.setZoom( Math.floor( zoom ) );
}

function webphoto_gmap_on_load_marks() 
{
	if ( webphoto_gmap_init_flag == false ) {
		webphoto_gmap_load_marks();
		webphoto_gmap_init_flag = true;
	}
}

function webphoto_gmap_on_get_location_marks() 
{
	if ( webphoto_gmap_init_flag == false ) {
		webphoto_gmap_load_get_location_marks();
		webphoto_gmap_init_flag = true;
	}
}

function webphoto_gmap_init_flag_true() 
{
	webphoto_gmap_init_flag = true;
}

/* --------------------------------------------------------
 * private functon
 * --------------------------------------------------------
 */

function webphoto_gmap_show_marks() 
{
	webphoto_gmap_initMap();
	webphoto_gmap_setCenter( webphoto_gmap_default_latitude, webphoto_gmap_default_longitude, webphoto_gmap_default_zoom );

/* MUST be setMapType() after setCenter() */
	webphoto_gmap_setMapType();
	webphoto_gmap_addOverviewMapControl();

	if ( webphoto_gmap_use_location_marker ) {
		webphoto_gmap_location_marker = new GMarker( webphoto_gmap_map_obj.getCenter() );
		webphoto_gmap_map_obj.addOverlay( webphoto_gmap_location_marker );

		if ( webphoto_gmap_use_location_marker_click ) {
			webphoto_gmap_clickMarker();
		}
	}

	for ( i=0 ; i<webphoto_gmap_info.length ; i++ ) {
		webphoto_gmap_map_obj.addOverlay( webphoto_gmap_createMarker_info( webphoto_gmap_info[i] ) );
	}
}
function webphoto_gmap_show_get_location() 
{
	webphoto_gmap_initMap();
	webphoto_gmap_initIcon();

	now_lat  = webphoto_gmap_default_latitude;
	now_lng  = webphoto_gmap_default_longitude;
	now_zoom = webphoto_gmap_default_zoom;
	now_addr = null;

	webphoto_gmap_client_geocoder = new GClientGeocoder();
	webphoto_gmap_moveendMap();

	parent_flag  = false;
	parent_param = webphoto_gmap_getParentLatitude();
	if ( parent_param ) {
		parent_flag = parent_param[0];
	}

/* if parent param is set */
	if( parent_flag ) {
		now_lat  = parent_param[1];
		now_lng  = parent_param[2];
		now_zoom = parent_param[3];
	}

	webphoto_gmap_setCenter( now_lat, now_lng, now_zoom );

	addr = webphoto_gmap_getParentAddress();
	if ( addr ) {
		document.getElementById("webphoto_gmap_search").value = addr.webphoto_gmap_htmlspecialchars();

/* if parent param is NOT set */
		if( parent_flag == false ) {
			webphoto_gmap_searchAddress(addr);
		}
	}

}
function webphoto_gmap_show_get_location_marks() 
{
	webphoto_gmap_initMap();
	webphoto_gmap_initIcon();

	now_lat  = webphoto_gmap_default_latitude;
	now_lng  = webphoto_gmap_default_longitude;
	now_zoom = webphoto_gmap_default_zoom;
	now_addr = null;

	webphoto_gmap_client_geocoder = new GClientGeocoder();
	webphoto_gmap_moveendMap();

	if ( webphoto_gmap_use_get_parent_location) {
		parent_flag  = false;
		parent_param = webphoto_gmap_getParentLatitude();
		if ( parent_param ) {
			parent_flag = parent_param[0];
		}

/* if parent param is set */
		if( parent_flag ) {
			now_lat  = parent_param[1];
			now_lng  = parent_param[2];
			now_zoom = parent_param[3];
		}
	}

	webphoto_gmap_setCenter( now_lat, now_lng, now_zoom );

	addr = webphoto_gmap_getParentAddress();
	if ( addr ) {
		document.getElementById("webphoto_gmap_search").value = addr.webphoto_gmap_htmlspecialchars();

/* if parent param is NOT set */
		if( parent_flag == false ) {
			webphoto_gmap_searchAddress(addr);
		}
	}

/* marks */
	for ( i=0 ; i<webphoto_gmap_info.length ; i++ ) {
		webphoto_gmap_map_obj.addOverlay( webphoto_gmap_createMarker_info( webphoto_gmap_info[i] ) );
	}

}
function webphoto_gmap_show_search() 
{
	webphoto_gmap_initMap();
	webphoto_gmap_initIcon();
	webphoto_gmap_client_geocoder = new GClientGeocoder();
	webphoto_gmap_moveendMap();
	webphoto_gmap_setCenter( webphoto_gmap_default_latitude, webphoto_gmap_default_longitude, webphoto_gmap_default_zoom );
	webphoto_gmap_setMapType();
	webphoto_gmap_addOverviewMapControl();

	now_addr = null;
	if ( document.getElementById("webphoto_gmap_search") != null ) {
		now_addr = document.getElementById("webphoto_gmap_search").value;
	}
	webphoto_gmap_searchAddress( now_addr );

}

function webphoto_gmap_show_georss() 
{
	webphoto_gmap_initMap();
	webphoto_gmap_setCenter( webphoto_gmap_default_latitude, webphoto_gmap_default_longitude, webphoto_gmap_default_zoom );
	webphoto_gmap_setMapType();
	webphoto_gmap_addOverviewMapControl();

	var webphoto_gmap_geo_xml = new GGeoXml( webphoto_gmap_geo_url );
	webphoto_gmap_map_obj.addOverlay( webphoto_gmap_geo_xml );
}

/* init map */
function webphoto_gmap_initMap() 
{
	webphoto_gmap_module_url = webphoto_gmap_xoops_url  + '/modules/' + webphoto_gmap_dirname
	webphoto_gmap_marker_url = webphoto_gmap_module_url + '/images/markers';

	webphoto_gmap_map_obj = new GMap2( document.getElementById("webphoto_gmap_map") );

	if ( webphoto_gmap_map_control == 'large' ) {
		webphoto_gmap_map_obj.addControl( new GLargeMapControl() );
	} else if ( webphoto_gmap_map_control == 'small' ) {
		webphoto_gmap_map_obj.addControl( new GSmallMapControl() );
	} else if ( webphoto_gmap_map_control == 'zoom' ) {
		webphoto_gmap_map_obj.addControl( new GSmallZoomControl() );
	}

	if ( webphoto_gmap_type_control == 'default' ) {
		webphoto_gmap_map_obj.addControl( new GMapTypeControl() );
	} else if ( webphoto_gmap_type_control == 'physical' ) {
		webphoto_gmap_map_obj.addControl( new GMapTypeControl() );
		webphoto_gmap_map_obj.addMapType( G_PHYSICAL_MAP );
	}

	if ( webphoto_gmap_use_scale_control ) {
		webphoto_gmap_map_obj.addControl( new GScaleControl() );
	}
}
function webphoto_gmap_setMapType() 
{
	if ( webphoto_gmap_map_type == 'satellite' ) {
		webphoto_gmap_map_obj.setMapType( G_SATELLITE_MAP );
	} else if ( webphoto_gmap_map_type == 'hybrid' ) {
		webphoto_gmap_map_obj.setMapType( G_HYBRID_MAP );
	} else if ( webphoto_gmap_map_type == 'physical' ) {
		webphoto_gmap_map_obj.setMapType( G_PHYSICAL_MAP );
	}
}
function webphoto_gmap_addOverviewMapControl() 
{
	if ( webphoto_gmap_use_overview_map_control ) {
		webphoto_gmap_map_obj.addControl( new GOverviewMapControl() );
	}
}
function webphoto_gmap_initIcon() 
{
	webphoto_gmap_drag_icon = new GIcon();
	webphoto_gmap_drag_icon.image = webphoto_gmap_marker_url + "/marker_green_cross_s.png";
	webphoto_gmap_drag_icon.iconSize = new GSize(18, 30);
	webphoto_gmap_drag_icon.iconAnchor = new GPoint(9, 30);
	webphoto_gmap_drag_icon.infoWindowAnchor = new GPoint(9, 2);

	webphoto_gmap_base_icon = new GIcon();
	webphoto_gmap_base_icon.iconSize = new GSize(20, 34);
	webphoto_gmap_base_icon.iconAnchor = new GPoint(9, 34);
	webphoto_gmap_base_icon.infoWindowAnchor = new GPoint(9, 2);

	webphoto_gmap_small_icon = new GIcon();
	webphoto_gmap_small_icon.image = webphoto_gmap_marker_url + "/marker_small.png";
	webphoto_gmap_small_icon.iconSize = new GSize(12, 20);
	webphoto_gmap_small_icon.iconAnchor = new GPoint(5, 20);
	webphoto_gmap_small_icon.infoWindowAnchor = new GPoint(9, 2);
}

/* map moveend */
function webphoto_gmap_moveendMap() 
{
	GEvent.addListener(webphoto_gmap_map_obj, "moveend", function() {

		center = webphoto_gmap_map_obj.getCenter();
		xx = center.x;
		yy = center.y;
		zz = webphoto_gmap_map_obj.getZoom();

		if ( webphoto_gmap_use_set_parent_location ) {
			webphoto_gmap_setParentLatitude( yy, xx, zz );
		}

		if ( webphoto_gmap_set_current_location ) {
			current_location = webphoto_gmap_lang_latitude + ': ' + yy + ' / ' + webphoto_gmap_lang_longitude + ': ' + xx + ' / ' + webphoto_gmap_lang_zoom + ': ' + zz;
			document.getElementById("webphoto_gmap_current_location").innerHTML = current_location; 
		}

		if ( webphoto_gmap_use_nishioka_inverse ) {
			webphoto_gmap_inverse_nishioka( xx, yy );
		}

		if ( webphoto_gmap_use_draggable_marker ) {
			webphoto_gmap_showDraggableMarker();
		}

	} );

}

/* marker click */
function webphoto_gmap_clickMarker() 
{
	GEvent.addListener( webphoto_gmap_location_marker, "click", function() {
		webphoto_gmap_location_marker.openInfoWindowHtml( webphoto_gmap_location_marker_html );
	} );
}

/* draggable marker */
function webphoto_gmap_showDraggableMarker() 
{
	if ( webphoto_gmap_draggable_marker != null ) {
		webphoto_gmap_map_obj.removeOverlay( webphoto_gmap_draggable_marker );
	}
	webphoto_gmap_draggable_marker = new GMarker( 
		webphoto_gmap_map_obj.getCenter(), 
		{ icon:webphoto_gmap_drag_icon , draggable:true , bouncy:true , bounceGravity:0.5 } 
	); 
	webphoto_gmap_map_obj.addOverlay( webphoto_gmap_draggable_marker );
	webphoto_gmap_dragendMarker();
}
function webphoto_gmap_dragendMarker() 
{
	GEvent.addListener( webphoto_gmap_draggable_marker, "dragend", function() {
		window.setTimeout( function() {
			webphoto_gmap_map_obj.panTo( webphoto_gmap_draggable_marker.getPoint() );
		}, 1000 );
	});
}

/* geocoder Locations */
function webphoto_gmap_geocoder_Locations( addr )
{
	if ( addr ) {
		webphoto_gmap_client_geocoder.getLocations( addr , function( response ) {
			if ( !response || response.Status.code != 200 ) {
				alert( webphoto_gmap_lang_no_match_place + "\n" + addr );
			} else {
				webphoto_gmap_geocoder_LocationsResponse( response );
			}
		} );
	}
}
function webphoto_gmap_geocoder_LocationsResponse( response )
{
/* clear all marker */
	webphoto_gmap_map_obj.clearOverlays();

	var length = response.Placemark.length;
	var webphoto_gmap_list = '<ol>';

	for(var i = 0; i< length; i++) {

/* location */
		place = response.Placemark[i];
		addr = place.address;
		lng  = place.Point.coordinates[0];
		lat  = place.Point.coordinates[1];
		zoom = place.AddressDetails.Accuracy + webphoto_gmap_zoom_accuracy;
		zoom = webphoto_gmap_maxZoom( zoom );

/* add marker */
		if ( webphoto_gmap_use_search_marker ) {
			webphoto_gmap_addMarker( i, lat, lng, zoom, addr );
		}
		webphoto_gmap_setBounds( i, lat, lng, zoom );
		webphoto_gmap_list += webphoto_gmap_getSearchList( i, lat, lng, zoom, addr );
	}

	webphoto_gmap_list += '</ol>';
	webphoto_gmap_setCenterBounds( length );
	document.getElementById("webphoto_gmap_list").innerHTML = webphoto_gmap_list;
}
function webphoto_gmap_addMarker( index, lat, lng, zoom, addr )
{
	icon = webphoto_gmap_createIcon( index );
	html = webphoto_gmap_getSearchHtml( index, lat, lng, zoom, addr );
	webphoto_gmap_map_obj.addOverlay( webphoto_gmap_createMarker( lat, lng, icon, html ) );
}
function webphoto_gmap_createIcon( index ) 
{
	letter = webphoto_gmap_getSmallLetter( index );

	if ( letter ) {
		var icon = new GIcon(webphoto_gmap_base_icon);
		icon.image = webphoto_gmap_marker_url + "/marker_" + letter + ".png";
	} else {
		var icon = new GIcon(webphoto_gmap_small_icon);
	}

	return icon;
}
function webphoto_gmap_createMarker( lat, lng, icon, html ) 
{
	var marker = new GMarker( new GLatLng( parseFloat( lat ) , parseFloat( lng ) ), icon );
	GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml( html );
	});
	return marker;
}
function webphoto_gmap_createMarker_info( info ) 
{
	var icon_id = parseInt( info[2] );

	var icon;
	if ( ( icon_id > 0  ) && ( webphoto_gmap_icon[ icon_id ] != null ) ){
		icon = webphoto_gmap_icon[ icon_id ];
	} else {
		icon = G_DEFAULT_ICON;
	}
	var marker = webphoto_gmap_createMarker( info[0], info[1], icon, info[3] ) ;
	return marker;
}
function webphoto_gmap_setBounds( index, lat, lng, zoom )
{
	var point = new GLatLng( parseFloat( lat ) , parseFloat( lng ) );
	if (( Math.floor( index ) == 0 )||( webphoto_gmap_bounds_zoom == null)) {
		webphoto_gmap_bounds_zoom = Math.floor( zoom );
		webphoto_gmap_bounds = new GLatLngBounds( point );
	}
	webphoto_gmap_bounds.extend( point );
}
function webphoto_gmap_setCenterBounds( length )
{
	var northEastPoint = webphoto_gmap_bounds.getNorthEast();
	var southWestPoint = webphoto_gmap_bounds.getSouthWest();
	lat = (northEastPoint.lat() + southWestPoint.lat()) / 2;
	lng = (northEastPoint.lng() + southWestPoint.lng()) / 2;

	zoom = webphoto_gmap_bounds_zoom;
	if ( length > 1 ) {
		zoom = webphoto_gmap_map_obj.getBoundsZoomLevel( webphoto_gmap_bounds );
	}

	webphoto_gmap_setCenter( lat, lng, zoom );
}
function webphoto_gmap_getSearchList( index, lat, lng, zoom, addr )
{
	html = webphoto_gmap_getSearchHtml( index, lat, lng, zoom, addr );
	list = '<li>' + html + '</li>' + "\n";
	return list;
}
function webphoto_gmap_getSearchHtml( index, lat, lng, zoom, addr)
{
	letter = webphoto_gmap_getCapitalLetter( index );
	if ( letter == '' ) {
		letter = index + 1;
	}

	func = "webphoto_gmap_setCenter(" + lat + ', '  + lng + ', ' + zoom + ")";
	link = '<a href="javascript:void(0)" onClick="' + func + '">' + addr.webphoto_gmap_htmlspecialchars() + '</a>';
	html = '<b>' + letter + '</b> ' + link;
	return html;
}
function webphoto_gmap_getCapitalLetter( index ) 
{
	var char = '';
	if (index < 26)
	{
		char = String.fromCharCode("A".charCodeAt(0) + index);
	}
	return char;
}
function webphoto_gmap_getSmallLetter( index ) 
{
	var char = '';
	if (index < 26)
	{
		char = String.fromCharCode("a".charCodeAt(0) + index);
	}
	return char;
}
function webphoto_gmap_maxZoom( z )
{
	if ( z > webphoto_gmap_zoom_max ) {
		z = webphoto_gmap_zoom_max;
	}
	return z;
}

/* geocoder LatLng */
function webphoto_gmap_geocoder_LatLng( addr )
{
	if ( addr ) {
		webphoto_gmap_client_geocoder.getLatLng(addr, function( point ) {
			if ( !point ) {
				alert( webphoto_gmap_lang_no_match_place + "\n" + addr );
			} else {
				webphoto_gmap_map_obj.setCenter( point, Math.floor( webphoto_gmap_zoom_geocode_default ) );
			}
		} );
	}

}

/* set & get parent */
function webphoto_gmap_getParentLatitude() 
{
	lat  = 0;
	lng  = 0;
	zoom = 0;
	flag = false;

	if ( webphoto_gmap_opener_mode == 'self' ) {
		if ( document.getElementById("webphoto_gmap_latitude") != null ) {
			lat  = document.getElementById("webphoto_gmap_latitude").value;
		}
		if ( document.getElementById("webphoto_gmap_longitude") != null ) {
			lng  = document.getElementById("webphoto_gmap_longitude").value;
		}
		if ( document.getElementById("webphoto_gmap_zoom") != null ) {
			zoom = document.getElementById("webphoto_gmap_zoom").value;
		}
	} else if (( webphoto_gmap_opener_mode == 'opener' )&&( opener != null )) {
		if ( opener.document.getElementById("webphoto_gmap_latitude") != null ) {
			lat  = opener.document.getElementById("webphoto_gmap_latitude").value;
		}
		if ( opener.document.getElementById("webphoto_gmap_longitude") != null ) {
			lng  = opener.document.getElementById("webphoto_gmap_longitude").value;
		}
		if ( opener.document.getElementById("webphoto_gmap_zoom") != null ) {
			zoom = opener.document.getElementById("webphoto_gmap_zoom").value;
		}
	} else if (( webphoto_gmap_opener_mode == 'parent' )&&( parent != null )) {
		if ( parent.document.getElementById("webphoto_gmap_latitude") != null ) {
			lat  = parent.document.getElementById("webphoto_gmap_latitude").value;
		}
		if ( parent.document.getElementById("webphoto_gmap_longitude") != null ) {
			lng  = parent.document.getElementById("webphoto_gmap_longitude").value;
		}
		if ( parent.document.getElementById("webphoto_gmap_zoom") != null ) {
			zoom = parent.document.getElementById("webphoto_gmap_zoom").value;
		}
	}

/* if parent param is set */
	if( (lat != 0) || (lng != 0) || (zoom != 0) ) {
		flag = true;
	}

	arr = new Array(flag, lat, lng, zoom);
	return arr;
}

function webphoto_gmap_setParentLatitude( latitude , longitude , zoom )
{
	if ( webphoto_gmap_opener_mode == 'self' ) {
		if ( document.getElementById("webphoto_gmap_latitude") != null) {
			document.getElementById( "webphoto_gmap_latitude" ).value = latitude;
		}
		if ( document.getElementById("webphoto_gmap_longitude") != null) {
			document.getElementById( "webphoto_gmap_longitude" ).value = longitude;
		}
		if ( document.getElementById("webphoto_gmap_zoom") != null) {
			document.getElementById( "webphoto_gmap_zoom" ).value = Math.floor( zoom );
		}
	} else if (( webphoto_gmap_opener_mode == 'opener' )&&( opener != null)) {
		if ( opener.document.getElementById("webphoto_gmap_latitude") != null) {
			opener.document.getElementById( "webphoto_gmap_latitude" ).value = latitude;
		}
		if ( opener.document.getElementById("webphoto_gmap_longitude") != null) {
			opener.document.getElementById( "webphoto_gmap_longitude" ).value = longitude;
		}
		if ( opener.document.getElementById("webphoto_gmap_zoom") != null) {
			opener.document.getElementById( "webphoto_gmap_zoom" ).value = Math.floor( zoom );
		}
	} else if (( webphoto_gmap_opener_mode == 'parent' )&&( parent != null)) {
		if ( parent.document.getElementById("webphoto_gmap_latitude") != null) {
			parent.document.getElementById( "webphoto_gmap_latitude" ).value = latitude;
		}
		if ( parent.document.getElementById("webphoto_gmap_longitude") != null) {
			parent.document.getElementById( "webphoto_gmap_longitude" ).value = longitude;
		}
		if ( parent.document.getElementById("webphoto_gmap_zoom") != null) {
			parent.document.getElementById( "webphoto_gmap_zoom" ).value = Math.floor( zoom );
		}
	}
}
function webphoto_gmap_getParentAddress()
{
	addr = '';

	if ( webphoto_gmap_opener_mode == 'self' ) {
		if ( document.getElementById("webphoto_gmap_address") != null ) {
			addr = document.getElementById("webphoto_gmap_address").value;
		}
	} else if (( webphoto_gmap_opener_mode == 'opener' )&&( opener != null )) {
		if ( opener.document.getElementById("webphoto_gmap_address") != null ) {
			addr = opener.document.getElementById("webphoto_gmap_address").value;
		}
	} else if (( webphoto_gmap_opener_mode == 'parent' )&&( parent != null )) {
		if ( parent.document.getElementById("webphoto_gmap_address") != null ) {
			addr = parent.document.getElementById("webphoto_gmap_address").value;
		}
	}

	return addr;
}
function webphoto_gmap_setParentAddress( addr )
{
	if (( addr != null )&&( addr != '' )) {
		if ( webphoto_gmap_opener_mode == 'self' ) {
			if ( document.getElementById("webphoto_gmap_address") != null) {
				document.getElementById("webphoto_gmap_address").value = addr.webphoto_gmap_htmlspecialchars();
			}
		} else if (( webphoto_gmap_opener_mode == 'opener' )&&( opener != null)) {
			if ( opener.document.getElementById("webphoto_gmap_address") != null) {
				opener.document.getElementById("webphoto_gmap_address").value = addr.webphoto_gmap_htmlspecialchars();
			}
		} else if (( webphoto_gmap_opener_mode == 'parent' )&&( parent != null)) {
			if ( parent.document.getElementById("webphoto_gmap_address") != null) {
				parent.document.getElementById("webphoto_gmap_address").value = addr.webphoto_gmap_htmlspecialchars();
			}
		}
	}
}

/* reference: mygmap module's mygwebphoto_gmap_map.js */
String.prototype.webphoto_gmap_htmlspecialchars = function() {
	var webphoto_gmap_tmp = this.toString();
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/\//g, "");
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/&/g, "&amp;");
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/"/g, "&quot;");
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/'/g, "&#39;");
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/</g, "&lt;");
	webphoto_gmap_tmp = webphoto_gmap_tmp.replace(/>/g, "&gt;");
	return webphoto_gmap_tmp;
}

/* --------------------------------------------------------
 * for japanese
 * --------------------------------------------------------
 */
/* japanese inverse geocoder */
function webphoto_gmap_geocoder_tokyoUniv( addr )
{
	if ( addr ) {
		var url = webphoto_gmap_module_url + '/tokyo_univ_geocode.php?query=' + encodeURI( addr );

		GDownloadUrl( url , function( data , responseCode ) {
			if( webphoto_gmap_debug_geocoder_tokyo_univ ) {
				alert( data );
			}
			if( responseCode == 200 ) {
				xml = GXml.parse( data );

/* fixed javascript errors with IE6 by souhalt */
				if ( xml.documentElement != null ) {
					candidate = xml.documentElement.getElementsByTagName("candidate");
					if ( candidate.length == 0 ) {
						alert( webphoto_gmap_lang_no_match_place + "\n" + addr );
					} else {
						webphoto_gmap_geocoder_tokyoUnivResponse( xml );
					}
				} else {
					alert( webphoto_gmap_lang_no_match_place + "\n" + addr );
				}

			}
		});
	}
}
function webphoto_gmap_geocoder_tokyoUnivResponse( xml )
{
/* clear all marker */
	webphoto_gmap_map_obj.clearOverlays();

	var candidate = xml.documentElement.getElementsByTagName("candidate");
	var iconf = xml.documentElement.getElementsByTagName("iConf")[0].firstChild.nodeValue;
	var length = candidate.length;

	var webphoto_gmap_list = '<ol>';

	iconf = Math.floor( iconf );
	if ( iconf >= 2 && iconf <= 5 ) {
		zoom = iconf + webphoto_gmap_zoom_accuracy_tokyo_univ;
	} else {
		zoom = webphoto_gmap_zoom_geocode_default;
	}
	zoom = webphoto_gmap_maxZoom( zoom );

	for(var i = 0; i< length; i++) {

/* location */
		place = candidate[i];
		addr = place.getElementsByTagName("address")[0].firstChild.nodeValue;
		lat  = place.getElementsByTagName('latitude')[0].firstChild.nodeValue;
		lng  = place.getElementsByTagName('longitude')[0].firstChild.nodeValue;

/* add marker */
		if ( webphoto_gmap_use_search_marker ) {
			webphoto_gmap_addMarker( i, lat, lng, zoom, addr );
		}
		webphoto_gmap_setBounds( i, lat, lng, zoom );
		webphoto_gmap_list += webphoto_gmap_getSearchList( i, lat, lng, zoom, addr );
	}

	webphoto_gmap_setCenterBounds( length );
	webphoto_gmap_list += '</ol>';
	document.getElementById("webphoto_gmap_list").innerHTML = webphoto_gmap_list;
}

/* japanese inverse geocoder */
function webphoto_gmap_inverse_nishioka( lon, lat )
{
	var url = webphoto_gmap_module_url + '/nishioka_inverse.php?lon=' + lon + '&lat=' + lat;

	GDownloadUrl( url , function( data , responseCode ) {
		if( webphoto_gmap_debug_inverse_nishioka ) {
			alert( data );
		}
		if( responseCode == 200 ) {
			var xml = GXml.parse( data );

/* fixed javascript errors with IE6 by souhalt */
			if ( xml.documentElement != null ) {
				webphoto_gmap_inverse_nishiokaResponse( xml );
			}

		}
	});
}

function webphoto_gmap_inverse_nishiokaResponse( xml )
{
	webphoto_gmap_address_jp = null;
	webphoto_gmap_pref_jp    = null;
	webphoto_gmap_city_jp    = null;
	webphoto_gmap_town_jp    = null;
	webphoto_gmap_number_jp  = null;

	var error = null;
	var addr  = null;

	if ( xml.documentElement.getElementsByTagName("address")[0] != null) {
		webphoto_gmap_address_jp = xml.documentElement.getElementsByTagName("address")[0].firstChild.nodeValue;
	}
	if ( xml.documentElement.getElementsByTagName("pref")[0] != null) {
		webphoto_gmap_pref_jp = xml.documentElement.getElementsByTagName("pref")[0].firstChild.nodeValue;
	}
	if ( xml.documentElement.getElementsByTagName("city")[0] != null) {
		webphoto_gmap_city_jp = xml.documentElement.getElementsByTagName("city")[0].firstChild.nodeValue;
	}
	if ( xml.documentElement.getElementsByTagName("town")[0] != null) {
		webphoto_gmap_town_jp = xml.documentElement.getElementsByTagName("town")[0].firstChild.nodeValue;;
	}
	if ( xml.documentElement.getElementsByTagName("number")[0] != null) {
		webphoto_gmap_number_jp = xml.documentElement.getElementsByTagName("number")[0].firstChild.nodeValue;
	}
	if ( xml.documentElement.getElementsByTagName("Message")[0] != null) {
		error = xml.documentElement.getElementsByTagName("Message")[0].firstChild.nodeValue;
	}

	if ( webphoto_gmap_address_jp != null ) {
		addr = webphoto_gmap_address_jp;
	} else if ( error != null ) { 
		addr = error;
	} else {
		addr = "unknown";
	}
	document.getElementById("webphoto_gmap_current_address").innerHTML = addr;

	if ( webphoto_gmap_use_set_parent_address ) {
		webphoto_gmap_setParentAddress( webphoto_gmap_address_jp );
	}
}

/* set location*/
function webphoto_gmap_setParentCenterLocation()
{
	center = webphoto_gmap_map_obj.getCenter();
	xx = center.x;
	yy = center.y;
	zz = webphoto_gmap_map_obj.getZoom();
	webphoto_gmap_setParentLatitude( yy, xx, zz );
}
