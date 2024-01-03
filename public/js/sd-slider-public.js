(function ( $ ) {
	'use strict';

	$(function () {

        var sd_slider = {
			init: function () {
				console.log(sd_obj);
            	let status = sd_obj.slider_status;
				if (status == "active" && $('.owl-carousel').length > 0) {
					$('.owl-carousel').owlCarousel({
						margin: 10,
						loop:true,
						autoplay: sd_obj.slider.autoplay,
						nav: sd_obj.slider.arrows,
						center: sd_obj.slider.center_mode,
						items: sd_obj.slider.items == "single" ? 1 : sd_obj.slider.slide_to_show,
						slideBy: sd_obj.slider.items == "single" ? 1 : sd_obj.slider.slide_to_scroll,
						dots: sd_obj.slider.bullets, 
					});
				}
				
			}
			
		}
        sd_slider.init();

    });

})( jQuery );
