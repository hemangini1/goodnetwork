(function(wpvc_particle) {
    window.count = 0;

    var wp_particle_function = function(element_this) {
        var selector = wpvc_particle(element_this);
        
        var minSpeedX = selector.data('minspeedx');
        var maxSpeedX = selector.data('maxspeedx');
        var minSpeedY = selector.data('minspeedy');
        var maxSpeedY = selector.data('maxspeedy');
        var directionX = selector.data('directionx');
        var directionY = selector.data('directiony');
        var density = selector.data('density');
        var dotColor = selector.data('dotcolor');
        var lineColor = selector.data('linecolor');
        var particleRadius = selector.data('particleradius');
        var lineWidth = selector.data('linewidth');
        var proximity = selector.data('proximity');
        var parallax = selector.data('parallax');
        var parallaxMultiplier = selector.data('parallaxmultiplier');
        var grabHover = selector.data('grabhover');
        var grabDistance = selector.data('grabdistance');
        
        selector.particleground({
            minSpeedX: minSpeedX/10,
            maxSpeedX: maxSpeedX/10,
            minSpeedY: minSpeedY/10,
            maxSpeedY: maxSpeedY/10,
            directionX: directionX,
            directionY: directionY,
            density: density,
            dotColor: dotColor,
            lineColor: lineColor,
            particleRadius: particleRadius,
            lineWidth: lineWidth,
            proximity: proximity,
            parallax: parallax,
            parallaxMultiplier: parallaxMultiplier, 
            grabHover: grabHover,
            grabDistance: grabDistance
        });
    }

    wpvc_particle(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_particle(document).find("div#wpvcpg_vc_row").each(function() {
            var selector = wpvc_particle(this);
            var prev = selector.prev();
            
            if (prev.hasClass("vc_row-full-width") && prev.prev().hasClass("vc_row")) {
                prev.prev().prepend(selector);
            } else {
                prev.prepend(selector);
            }
            selector.addClass('vcrbp_particles');
            wp_particle_function( this, 'wpvcpg_vc_row' );
		} );
        
        wpvc_particle(document).find("div#wpvcpg_vc_column").each(function() {
            var selector = wpvc_particle(this);
            var prev = selector.prev();
            prev.prepend(selector);
            selector.addClass('vcrbp_particles');
            wp_particle_function( this, 'wpvcpg_vc_column' );
		} );
    });    
    
    wpvc_particle("span.vc_general.vc_ui-button.vc_ui-button-shape-rounded.vc_ui-button-fw.vc_ui-button-action", window.parent.document).bind("click", function() {
        wpvc_admin_particle_function();
    });
    
    wpvc_particle("span.vc_general.vc_ui-button.vc_ui-button-action.vc_ui-button-shape-rounded.vc_ui-button-fw", window.parent.document).bind("click", function() {
        wpvc_admin_particle_function();
    });
    
    var wpvc_admin_particle_function = function() {
        wpvc_particle(document).find(".vcrbp_particles").remove();

        setTimeout( function () {
            wpvc_particle(document).find("div#wpvcpg_vc_row").each(function() {
                var selector = wpvc_particle(this);
                var prev = selector.prev();
                
                if (prev.hasClass("vc_row-full-width") && prev.prev().hasClass("vc_row")) {
                    prev.prev().prepend(selector);
                } else {
                    prev.prepend(selector);
                }
                selector.addClass('vcrbp_particles');
                wp_particle_function( this, 'wpvcpg_vc_row' );
            } );
            
            wpvc_particle(document).find("div#wpvcpg_vc_column").each(function() {
                var selector = wpvc_particle(this);
                var prev = selector.prev();
                prev.prepend(selector);
                selector.addClass('vcrbp_particles');
                wp_particle_function( this, 'wpvcpg_vc_column' );
            } );
        }, 2000 );
    }
})(jQuery);