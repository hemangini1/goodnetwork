/* 
Copyright (c) 2015 by Matei Copot (http://codepen.io/towc/details/GgGmrg/)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_organic_lines) {
    function Particle(hue){
      this.x = Math.random()*w;
      this.y = Math.random()*h;
      this.vx = (Math.random()-.5)*speed;
      this.vy = (Math.random()-.5)*speed;
      
      this.hue = hue;
    }
    Particle.prototype.update = function(){
      this.x += this.vx;
      this.y += this.vy;
      
      if(this.x < 0 || this.x > w) this.vx *= -1;
      if(this.y < 0 || this.y > h) this.vy *= -1;
    }

    function checkDist(a, b, dist){
      var x = a.x - b.x,
          y = a.y - b.y;
      
      return x*x + y*y <= dist*dist;
    }

    function anim(){
      window.requestAnimationFrame(anim);
      
      if (bgActive)
        ctx.fillStyle = bgColor;
      else
        ctx.fillStyle = 'rgba(0, 0, 0, .05)';
      ctx.fillRect(0, 0, w, h);
      
      for(var i = 0; i < particles.length; ++i){
        var p1 = particles[i];
        p1.update();
        
        for(var j = i+1; j < particles.length; ++j){
          var p2 = particles[j];
          if(checkDist(p1, p2, range)){
            ctx.strokeStyle = 'hsla(hue, 80%, 50%, alp)'
              .replace('hue', ((p1.hue  + p2.hue + 3)/2) % 360)
              .replace('alp', lineAlpha);
            ctx.beginPath();
            ctx.moveTo(p1.x, p1.y);
            ctx.lineTo(p2.x, p2.y);
            ctx.stroke();
          }
        }
      }
    }
   
    // Effect function
    var wp_run_function = function(element_this) {
        var selector = wpvc_organic_lines(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
                    
        w = organic_lines.width = window.innerWidth,
            h = organic_lines.height = window.innerHeight,
            ctx = organic_lines.getContext('2d'),
            
            count = (w*h/3000)|0,
            speed = selector.data('speed'),
            range = selector.data('range'),
            lineAlpha = selector.data('linealpha'),
            
            particles = [],
            huePart = 360/count;
        
        // Start

        for(var i = 0; i < count; ++i)
          particles.push(new Particle((huePart*i)|0));

        anim(); 
    }
    
    var wp_vc_organic_lines_function = function(element_this) {
        var selector = wpvc_organic_lines(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'organic_lines';
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
        
        wpvc_organic_lines('#organic_lines').addClass('vcrbp_particles');
        
        wp_run_function(element_this);        
    }
    
    wpvc_organic_lines(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_organic_lines(document).find("div#wpvcolp_vc_row").each(function() {
            wp_vc_organic_lines_function(this);
            wpvc_organic_lines(this).remove();
		} );
        
        wpvc_organic_lines(document).find("div#wpvcolp_vc_column").each(function() {
            wp_vc_organic_lines_function(this);
            wpvc_organic_lines(this).remove();
		} );
    });
})(jQuery);