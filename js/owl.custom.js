( function( $ ){
	'use strict';

	jQuery( document ).ready( function(){
		
		jQuery('.doctors').owlCarousel( {
			items:4,
			mouseDrag: true,
			nav:true,
			margin:10,
		} );
	
	} );

}( jQuery ))