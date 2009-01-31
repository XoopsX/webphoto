/* ========================================================
 * $Id: gmap_block.js,v 1.1 2009/01/31 19:30:33 ohwada Exp $
 * http://code.google.com/apis/maps/index.html
 * ========================================================
 */

function webphoto_gmap_b_show( gmap, point, info_arr, show_map_control, show_map_type_control ) 
{
	if ( show_map_control == 1 ) {
		gmap.addControl( new GSmallMapControl() );
	}
	if ( show_map_type_control == 1 ) {
		gmap.addControl( new GMapTypeControl() );
	}
	gmap.setCenter( new GLatLng( parseFloat( point[0] ) , parseFloat( point[1] ) ) , Math.floor( point[2] ) );
	for( i=0 ; i<info_arr.length ; i++ ){
		gmap.addOverlay( webphoto_gmap_b_create_marker( info_arr[i] ) );
	}
}

function webphoto_gmap_b_create_marker( info ) 
{
	var marker = new GMarker( new GLatLng( parseFloat( info[0] ) , parseFloat( info[1] ) ) );
	GEvent.addListener( marker , "click" , function() {
		marker.openInfoWindowHtml( info[2] );
	});
	return marker;
}
