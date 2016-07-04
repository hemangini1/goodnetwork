/* 
Copyright (c) 2015 by Matei Copot (http://codepen.io/towc/full/jPrbQX/)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_rainbow_curves) {
    function anim(ctx) {
  
      window.requestAnimationFrame(function() {
            anim(ctx);
        });
      
      ctx.globalCompositeOperation = 'source-over';      
      if (bgActive)
        ctx.fillStyle = bgColor;
      else
        ctx.fillStyle = 'rgba( 0, 0, 0, alp )'.replace( 'alp', repaintAlpha );
      ctx.fillRect( 0, 0, w, h );
      ctx.globalCompositeOperation = 'lighter';
      
      ctx.lineWidth = lineWidth;
      
      ++frame;
      
      if( pairs.length < 100 && Math.random() < .3 ) pairs.push( new Pair );
      
      ctx.translate( cX, cY );
      pairs.map( function( pair ) { pair.step(ctx); } );
      ctx.translate( -cX, -cY );
    }

    function Pair() {
      
      this.reset();
    }
    Pair.prototype.reset = function() {
      
      var rad = Math.random() * Math.PI * 2,
          acc = Math.random() * .1 + .01,
          radDiff = Math.random() * radApart;
      
      if( Math.random() < .5 ) radDiff *= -1;
      
      this.midRad = rad + radDiff / 2;
      
      this.p1 = new Point( rad, acc );
      this.p2 = new Point( rad + radDiff, acc + Math.random() * accApart );
    }
    Pair.prototype.step = function(ctx) {
      
      if( this.p1.step() && this.p2.step() ) this.reset();
      
      ctx.strokeStyle = 'hsl( hue, 80%, 50% )'.replace( 'hue', this.midRad / ( Math.PI * 2 ) * 360 + frame%360 );
      
      ctx.beginPath();
      ctx.moveTo( this.p1.x |0, this.p1.y |0);
      ctx.quadraticCurveTo( (mx + Math.random() * jitter) |0, (my + Math.random() * jitter) |0, this.p2.x |0, this.p2.y |0);
      ctx.stroke();
    }
    function Point( rad, acc ) {
      
      this.x = 0;
      this.y = 0;
      this.vx = 0;
      this.vy = 0;
      this.ax = Math.cos( rad ) * acc;
      this.ay = Math.sin( rad ) * acc;
    }
    Point.prototype.step = function () {
      
      this.x += this.vx += this.ax;
      this.y += this.vy += this.ay;
      
      if( this.x < bx || this.x > bX || this.y < by || this.y > bY )
        return true; // dead check
    }

    // Effect function
    var wp_run_function = function(element_this, rainbow_curves) {
        var selector = wpvc_rainbow_curves(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
            
        var ctx = rainbow_curves.getContext( '2d' );
        w = rainbow_curves.width = window.innerWidth,
        h = rainbow_curves.height = window.innerHeight,
        
        repaintAlpha = selector.data('repaintalpha'),
        lineWidth = selector.data('linewidth'),
        radApart = selector.data('radapart'),
        accApart = selector.data('accapart'),
        jitter = selector.data('jitter'),
        
        mx = Math.random() * 200 - 100,
        my = Math.random() * 200 - 100,
       
        pairs = [],
        frame = 0;
        
        bx = -w*3.4; // boundary min x
        bX = w*3.4;
        by = -h*3.4;
        bY = h*3.4;
        
        cX = w/2;
        cY = h/2;
        
        var centerPoint = selector.data('centerpoint');
        if (centerPoint == 10)    
            centerPoint = Math.floor((Math.random() * 9) + 1);
            
        switch(centerPoint) {
            case 1:
            case 4:
            case 7:
                {
                    cX = 0;
                }
                break;
            case 3:
            case 6:
            case 9:
                {
                    cX = w;
                }
                break;
            default:
                cX = w / 2;
        }
        
        switch(centerPoint) {
            case 1:
            case 2:
            case 3:
                {
                    cY = 0;
                }
                break;
            case 7:
            case 8:
            case 9:
                {
                    cY = h;
                }
                break;
            default:
                cY = h / 2;
        }
        
        // Start
        ctx.translate( cX, cY );
        for( var i = 0; i < 200; ++i ){
          
         ++frame;
          
         if( pairs.length < 100 && Math.random() < .3 ) pairs.push( new Pair );
          
         pairs.map( function( pair ) { pair.step(ctx); } );
        }
        ctx.translate( -cX, -cY );
        
        anim(ctx);
        
        interactionActive = selector.data('interactionactive');
        if (interactionActive) {
            rainbow_curves.addEventListener( 'mousemove', function( e ) {
                mx = e.clientX - w/2;
                my = e.clientY - h/2;
            } );
            document.addEventListener( 'mousemove', function( e ) {
                mx = e.clientX - w/2;
                my = e.clientY - h/2;
            } );
        }

        window.addEventListener( 'resize', function() {

            w = rainbow_curves.width = window.innerWidth;
            h = rainbow_curves.height = window.innerHeight;

            bx = -w*3.4;
            bX = w*3.4;
            by = -h*3.4;
            bY = h*3.4;
            
            cX = w/2;
            cY = h/2;
            
            switch(centerPoint) {
                case 1:
                case 4:
                case 7:
                    {
                        cX = 0;
                    }
                    break;
                case 3:
                case 6:
                case 9:
                    {
                        cX = w;
                    }
                    break;
                default:
                    cX = w / 2;
            }
        
            switch(centerPoint) {
                case 1:
                case 2:
                case 3:
                    {
                        cY = 0;
                    }
                    break;
                case 7:
                case 8:
                case 9:
                    {
                        cY = h;
                    }
                    break;
                default:
                    cY = h / 2;
            }
        } );
    }
    
    var wp_vc_rainbow_curves_function = function(element_this) {
        var selector = wpvc_rainbow_curves(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'rainbow_curves';
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
        
        wpvc_rainbow_curves('#rainbow_curves').addClass('vcrbp_particles');
        
        wp_run_function(element_this, canvas);
    }
    
    wpvc_rainbow_curves(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_rainbow_curves(document).find("div#wpvcrcp_vc_row").each(function() {
            wp_vc_rainbow_curves_function(this);
            wpvc_rainbow_curves(this).remove();
		} );
        
        wpvc_rainbow_curves(document).find("div#wpvcrcp_vc_column").each(function() {
            wp_vc_rainbow_curves_function(this);
            wpvc_rainbow_curves(this).remove();
		} );
    });
})(jQuery);