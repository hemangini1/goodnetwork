/* 
Copyright (c) 2015 by Matei Copot (http://codepen.io/towc/pen/WvLaKw)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_plasma) {    
    function init(ctx, cx, cy){        
        var rays = [];
        rays.length = 0;
        for( var i = 0; i < ray; ++i )
            rays.push( new Ray );
        
        loop(ctx, cx, cy, rays);
    }

    function loop(ctx, cx, cy, rays){
        
        window.requestAnimationFrame( function() {
            loop(ctx, cx, cy, rays);
        } );
        
        ++tick;
        
        ctx.globalCompositeOperation = 'source-over';
        ctx.shadowBlur = 0;
        if (bgActive)
            ctx.fillStyle = bgColor;
        else
            ctx.fillStyle = 'rgba(0,0,0,alp)'.replace( 'alp', repaintAlpha );
        ctx.fillRect( 0, 0, w, h );
        ctx.shadowBlur = shadowBlur;
        ctx.globalCompositeOperation = 'lighter';
        
        tickHueMultiplied = tickHueMultiplier * tick;
        
        rays.map( function( ray ){ ray.step(ctx, cx, cy); } );
    }

    function Ray(){
        
        this.circles = [ new Circle( 0 ) ];
        this.rot = Math.random() * Math.PI * 2;
        this.angularVel = Math.random() * rayAngularVelSpan * ( Math.random() < .5 ? 1 : -1 );
        this.angularAccWaveInput = Math.random() * Math.PI * 2;
        this.angularAccWaveInputIncrementer = rayAngularAccWaveInputBaseIncrementer + rayAngularAccWaveInputAddedIncrementer * Math.random();
        
        var security = 100,
                count = 0;
        
        while( --security > 0 && this.circles[ count ].radius < maxRadius )
            this.circles.push( new Circle( ++count ) );
    }
    Ray.prototype.step = function(ctx, cx, cy){
        
        // this is just messy, but if you take your time to read it properly you'll understand it pretty easily
        this.rot += 
            this.angularVel += Math.sin( 
                this.angularAccWaveInput += 
                    this.angularAccWaveInputIncrementer ) * rayAngularAccWaveMultiplier;
        
        var rot = this.rot,
                x = cx,
                y = cy;
        
        ctx.lineWidth = Math.min( .00001 / Math.abs( this.angularVel ), 10 / rayAngularVelLineWidthMultiplier ) * rayAngularVelLineWidthMultiplier;

        ctx.beginPath();
        ctx.moveTo( x, y );
        
        for( var i = 0; i < this.circles.length; ++i ){
            
            var circle = this.circles[ i ];
            
            circle.step();
            
            rot += circle.radiant;
            
            var x2 = cx + Math.sin( rot ) * circle.radius,
                    y2 = cy + Math.cos( rot ) * circle.radius,
                    
                    mx = ( x + x2 ) / 2,
                    my = ( y + y2 ) / 2;
            
            ctx.quadraticCurveTo( x, y, mx, my );
            
            x = x2;
            y = y2;
        }
        
        ctx.strokeStyle = ctx.shadowColor = 'hsl(hue,80%,50%)'.replace( 'hue', ( ( ( rot + this.rot ) / 2 ) % ( Math.PI * 2 ) ) / Math.PI * 30 + tickHueMultiplied );
        
        ctx.stroke();
    }

    function Circle( n ){
        
        this.radius = circleRadiusIncrementAcceleration * Math.pow( n, 2 );
        this.waveInputIncrementer = ( baseWaveInputIncrementer + addedWaveInputIncrementer * Math.random() ) * ( Math.random() < .5 ? 1 : -1 ) * circleNumWaveIncrementerMultiplier * n;
        this.waveInput = Math.random() * Math.PI * 2;
        this.radiant = Math.random() * radiantSpan * ( Math.random() < .5 ? 1 : -1 );
    }
    Circle.prototype.step = function(){
        
        this.waveInput += this.waveInputIncrementer;
        this.radiant = Math.sin( this.waveInput ) * radiantSpan;
    }
    
    // Effect function
    var wp_run_function = function(element_this, plasma) {
        var selector = wpvc_plasma(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
        
        var ctx = plasma.getContext('2d');
        w = plasma.width = window.innerWidth,
        h = plasma.height = window.innerHeight,

        ray = selector.data('ray'),
        maxRadius = Math.sqrt( w*w/4 + h*h/4 ),
        circleRadiusIncrementAcceleration = 2,
        radiantSpan = selector.data('radiantspan'),
        rayAngularVelSpan = .005,
        rayAngularVelLineWidthMultiplier = 60,
        rayAngularAccWaveInputBaseIncrementer = .03,
        rayAngularAccWaveInputAddedIncrementer = .02,
        rayAngularAccWaveMultiplier = .0003,
        baseWaveInputIncrementer = .01,
        addedWaveInputIncrementer = .01,
        circleNumWaveIncrementerMultiplier = .1,
        
        tickHueMultiplier = 1,
        shadowBlur = 0,
        repaintAlpha = .2,
        apply = init,
        
        tick = 0;
        
        var cx = w / 2;
        var cy = h / 2;
        
        var centerPoint = selector.data('centerpoint');
        if (centerPoint == 10)    
            centerPoint = Math.floor((Math.random() * 9) + 1);
        switch(centerPoint) {
            case 1:
            case 4:
            case 7:
                cx = 0;
                break;
            case 3:
            case 6:
            case 9:
                cx = w;
                break;
            default:
                cx = w / 2;
        }
        
        switch(centerPoint) {
            case 1:
            case 2:
            case 3:
                cy = 0;
                break;
            case 7:
            case 8:
            case 9:
                cy = h;
                break;
            default:
                cy = h / 2;
        }
        
        // Start            
        init(ctx, cx, cy);
        
        var interactionActive = selector.data('interactionactive');
        if (interactionActive) {
            plasma.addEventListener( 'click', function( e ) {
                var cx = e.clientX;
                var cy = e.clientY;
                init(ctx, cx, cy);
            } );
        }
        
        window.addEventListener( 'resize', function(){
            
            w = plasma.width = window.innerWidth;
            h = plasma.height = window.innerHeight;
            
            maxRadius = Math.sqrt( w*w/4 + h*h/4 );
            var cx = w / 2;
            var cy = h / 2;
            switch(centerPoint) {
                case 1:
                case 4:
                case 7:
                    cx = 0;
                    break;
                case 3:
                case 6:
                case 9:
                    cx = w;
                    break;
                default:
                    cx = w / 2;
            }
            
            switch(centerPoint) {
                case 1:
                case 2:
                case 3:
                    cy = 0;
                    break;
                case 7:
                case 8:
                case 9:
                    cy = h;
                    break;
                default:
                    cy = h / 2;
            }
            
            init(ctx, cx, cy);
        });
    }
    
    var wp_vc_plasma_function = function(element_this) {
        var selector = wpvc_plasma(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'plasma';
        canvas.style.display = 'block';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.position = 'absolute';
        canvas.style.left = '0';
        canvas.style.top = '0';
        
        if (selector.prev().hasClass("vc_row-full-width") && selector.prev().prev().hasClass("vc_row")) {
            selector.prev().prev().prepend(canvas);
        } else {
            selector.prev().prepend(canvas);
        }
        
        wpvc_plasma('#plasma').addClass('vcrbp_particles');
        
        wp_run_function(element_this, canvas);        
    }
    
    wpvc_plasma(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_plasma(document).find("div#wpvcpp_vc_row").each(function() {
            wp_vc_plasma_function(this);
            wpvc_plasma(this).remove();
		} );
        
        wpvc_plasma(document).find("div#wpvcpp_vc_column").each(function() {
            wp_vc_plasma_function(this);
            wpvc_plasma(this).remove();
		} );
    });
})(jQuery);