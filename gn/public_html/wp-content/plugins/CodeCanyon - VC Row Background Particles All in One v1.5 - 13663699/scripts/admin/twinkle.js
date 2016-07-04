/* 
Copyright (c) 2015 by Tiffany Rayside (http://codepen.io/tmrDevelops/pen/aOYLWp)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_twinkle) {
    
    var prev = Date.now();

    function Obj(x,y,start){
        this.x = x;
        this.y = y;
        this.st = start;
    }

    Obj.t = 2500;

    Obj.prototype.size = function(t_){
        var _t = t_ - this.st;
        return _t*(Obj.t-_t)/(Obj.t/2*Obj.t/2);
    };

    Obj.prototype.disp = function(t_){
        var _t = t_- this.st;
        return _t>0 && _t<Obj.t;
    };

    var upd = function(){
        var curr = Date.now();
      
        while(curr>prev+n){
            var x,y;
            if(ms!==null){
                x = ms.x + (40*(0.5-Math.random())|0);
                y = ms.y + (40*(0.5-Math.random())|0);
            }else{
                x = w*Math.random()|0;
                y = h*Math.random()|0;
            }
            prev += n;
            objs.push(new Obj(x,y,prev));
        }
        while(objs.length && !objs[0].disp(curr)){
            objs.shift();
        }
        ctx.clearRect(0,0,c.width,c.height);
        // ctx.fillStyle = 'hsla(0,0%,0%,1)';
        // ctx.fillRect(0,0,c.width,c.height);
      
        objs.forEach(function(o){
            var s = o.size(curr);
            ctx.save();
            ctx.translate(o.x,o.y);
            ctx.scale(s,s);
            if (randomColor)
                ctx.shadowColor = 'hsla('+((prev/5)%360)+',100%,75%, .8)';
            else
                ctx.shadowColor = twinkleColor;
                
            ctx.shadowBlur = 15;
            
            if (randomColor)
                ctx.fillStyle = 'hsla('+((prev/5)%360)+',100%,65%, 1)';
            else
                ctx.fillStyle = twinkleColor;
            ctx.beginPath();
            ctx.moveTo(0,-20);
            ctx.quadraticCurveTo(0,0,-5,0);
            ctx.quadraticCurveTo(0,0,0,20);
            ctx.quadraticCurveTo(0,0,5,0);
            ctx.quadraticCurveTo(0,0,0,-20);
            ctx.closePath();
            ctx.fill();
            ctx.restore();
        });
    }

    window.requestAnimFrame = (function() {
      return window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function(callback) {
          window.setTimeout(callback, 1000 / 60);
        };
    })();

    var run = function(){
      window.requestAnimFrame(run);
      upd();
    }
  
    // Effect function
    var wp_run_function = function(element_this) {
        var selector = wpvc_twinkle(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor'); 
        randomColor = selector.data('twinklerandomcolor');
        twinkleColor = selector.data('twinklecolor'); 
        
        if (bgActive)
            wpvc_twinkle("#twinkle").css( 'background-color', bgColor );   
        
        c = document.getElementById('twinkle');
        ctx = c.getContext('2d');
        w = c.width = window.innerWidth;
        h = c.height = window.innerHeight;
        n = 50;
        ms = null;
        objs = [];

        // Start
        run();
    }
    
    var wp_vc_twinkle_function = function(element_this) {
        var selector = wpvc_twinkle(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'twinkle';
        canvas.style.display = 'block';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.position = 'absolute';
        canvas.style.left = '0';
        canvas.style.top = '0';
        
        if (selector.prev().hasClass("vc_row-full-width") && selector.prev().prev().hasClass("vc_row")) {
            selector.prev().prev().prepend(canvas);
            selector.prev().prev().css('position', 'relative');
        } else {
            selector.prev().prepend(canvas);
            selector.prev().css('position', 'relative');
        }
        
        wpvc_twinkle('#twinkle').addClass('vcrbp_particles');
        
        wp_run_function(element_this);        
    }
    
    wpvc_twinkle(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_twinkle(document).find("div#vcrbtp_vc_row").each(function() {
            wp_vc_twinkle_function(this);
            wpvc_twinkle(this).remove();
		} );       
        
        wpvc_twinkle(document).find("div#vcrbtp_vc_column").each(function() {
            wp_vc_twinkle_function(this);
            wpvc_twinkle(this).remove();
		} );       
    });
    
    wpvc_twinkle("span.vc_general.vc_ui-button.vc_ui-button-shape-rounded.vc_ui-button-fw.vc_ui-button-action", window.parent.document).bind("click", function() {
        wpvc_admin_twinkle();
    });
    
    wpvc_twinkle("span.vc_general.vc_ui-button.vc_ui-button-action.vc_ui-button-shape-rounded.vc_ui-button-fw", window.parent.document).bind("click", function() {
        wpvc_admin_twinkle();
    });
    
    var wpvc_admin_twinkle = function() {
        wpvc_twinkle(document).find(".vcrbp_particles").remove();

        setTimeout( function () {
            wpvc_twinkle(document).find("div#vcrbtp_vc_row").each(function() {
                wp_vc_twinkle_function(this);
                wpvc_twinkle(this).remove();
            } );
            wpvc_twinkle(document).find("div#vcrbtp_vc_column").each(function() {
                wp_vc_twinkle_function(this);
                wpvc_twinkle(this).remove();
            } );
        }, 2000 );
    };
})(jQuery);