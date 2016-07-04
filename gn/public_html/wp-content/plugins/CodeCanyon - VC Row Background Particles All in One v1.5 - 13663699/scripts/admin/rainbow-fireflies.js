/* 
Copyright (c) 2015 by Matei Copot (http://codepen.io/towc/pen/qdYWMX)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_rainbow_fireflies) {        
    function init() {
      
      if( first ){
        
        first = false;
        
        // spawn only a few, to get something for the preview
        // but not all of them at once
        for( var i = 0; i < Math.min( opts.particleCount, 10 ); ++i )
          particles.push( new Particle );
        
        loop();
        
      } else {
        
        particles.map( function( particle ) { particle.reset(); } );
      }
    }

    function loop(){
      
      window.requestAnimationFrame( loop );
      
      step();
      draw();
    }
    function step() {
      
      tick += opts.tickSpeed;
      
      if( particles.length < opts.particleCount && Math.random() < .1 )
        particles.push( new Particle );
      
      particles.map( function( particle ) { particle.step(); } );
    }
    function draw() {
      
      ctx.globalCompositeOperation = 'source-over';
      if (bgActive) 
        ctx.fillStyle = bgColor;
      else
        ctx.fillStyle = 'rgba(0,0,0,alp)'.replace( 'alp', opts.repaintAlpha );
      ctx.fillRect( 0, 0, w, h );
      ctx.globalCompositeOperation = 'lighter';
      
      particles.map( function( particle ) { particle.draw(); } );
    }

    function Particle() {
      
      this.connections = [];
      for( var i = 0; i < opts.connectionCount; ++i )
        this.connections.push( {} );
      
      this.reset();
    }
    Particle.prototype.reset = function() {
      
      // spawn particles on edges and calculate radiant coordinates
      if( Math.random() < .5 ){
        
        this.x = Math.random() * w;
        this.y = Math.random() < .5 ? 0 : h;
        
      } else {
        
        this.x = Math.random() < .5 ? 0 : w;
        this.y = Math.random() * h;
      }
      
      var dx = this.x - opts.cx,
          dy = this.y - opts.cy;
      
      this.rad = Math.atan( dy / dx );
      if( dx < 0 ) this.rad += Math.PI;
      
      this.cos = Math.cos( this.rad );
      this.sin = Math.sin( this.rad );
      
      this.len = Math.sqrt( dx*dx + dy*dy );
      
      this.behaviour = 'ray';
      
      // reset connections
      for( var i = 0; i < this.connections.length; ++i )
        this.resetConnection( i );
      
      this.hasDied = false;
    }
    Particle.prototype.step = function() {
      
      if( this.behaviour === 'ray' ) {
        
        this.len *= .99;
        
        if( this.len < 10 )
          this.hasDied = true;
        
        if( Math.random() < opts.particleCircleBehaviourProb )
          this.behaviour = 'circle';
        
      } else {
        
        this.rad += opts.particleAngularSpeed;
        this.cos = Math.cos( this.rad );
        this.sin = Math.sin( this.rad );
        
        if( Math.random() < opts.particleRayBehaviourProb )
          this.behaviour = 'ray';
      }
      
      this.x = opts.cx + this.cos * this.len;
      this.y = opts.cy + this.sin * this.len;
      
      for( var i = 0; i < this.connections.length; ++i ){
        
        --this.connections[ i ].life;
        if( this.connections[ i ].life < 0 )
          this.resetConnection( i );
      }
    }
    Particle.prototype.draw = function() {
      
      if( this.hasDied ) return this.reset();
      
      ctx.strokeStyle = 'hsl(hue,80%,50%)'.replace( 'hue', this.rad / Math.PI * 180 + tick );
      ctx.lineWidth = .1;
      
      for( var i = 0; i < this.connections.length; ++i ) {
        
        var conn = this.connections[ i ],
            sdx = ( conn.x - this.x ) / opts.connectionSplits,
            sdy = ( conn.y - this.y ) / opts.connectionSplits,
            x = this.x,
            y = this.y;
        
        for( var j = 0; j < opts.connectionSplits; ++j ){
          
          ctx.beginPath();
          ctx.moveTo( x, y );
          
          x = this.x + sdx * j + Math.random() * opts.connectionJitter * ( Math.random() < .5 ? 1 : -1 );
          y = this.y + sdy * j + Math.random() * opts.connectionJitter * ( Math.random() < .5 ? 1 : -1 );
          
          ctx.lineTo( x, y );
          ctx.stroke();
        }
      }
      
      if( this.behaviour === 'circle' ) {
        
        ctx.strokeStyle = 'white';
        ctx.lineWidth = .01;
        
        ctx.beginPath();
        ctx.arc( opts.cx, opts.cy, this.len, 0, Math.PI * 2 );
        ctx.stroke();
      }
      
    }
    Particle.prototype.resetConnection = function( i ) {
      
      this.connections[ i ].x = this.x +
        ( Math.random() < .5 ? -1 : 1 ) * Math.random() * opts.connectionSpanMultiplier * this.len;
      this.connections[ i ].y = this.y +
        ( Math.random() < .5 ? -1 : 1 ) * Math.random() * opts.connectionSpanMultiplier * this.len;
      
      this.connections[ i ].life = opts.connectionLife + Math.random() * opts.connectionAddedLife;
    }
    
    // Effect function
    var wp_run_function = function(element_this) {
        var selector = wpvc_rainbow_fireflies(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
        if (bgActive)
            selector.css( 'background-color' , bgColor );
            
        w = rainbow_fireflies.width = window.innerWidth,
        h = rainbow_fireflies.height = window.innerHeight,
        ctx = rainbow_fireflies.getContext( '2d' ),
        
        opts = {
          
          particleCount: selector.data('particlecount'),
          particleSpeed: selector.data('particlespeed'),
          particleAngularSpeed: selector.data('particleangularspeed'),
          particleRayBehaviourProb: selector.data('particleraybehaviourprob'),
          particleCircleBehaviourProb: selector.data('particlecirclebehaviourprob'),
          
          connectionCount: selector.data('connectioncount'),
          connectionLife: selector.data('connectionlife'),
          connectionAddedLife: selector.data('connectionaddedlife'),
          connectionSplits: selector.data('connectionsplits'),
          connectionJitter: selector.data('connectionjitter'),
          connectionSpanMultiplier: selector.data('connectionspanmultiplier'), // relative to length
          
          repaintAlpha: .1,
          tickSpeed: 1,
          
          cx: w / 2,
          cy: h / 2
        },
        
        tick = 0,
        first = true,
        particles = [];
                
        // Start
        init();
    }
    
    var wp_vc_rainbow_fireflies_function = function(element_this) {
        var selector = wpvc_rainbow_fireflies(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'rainbow_fireflies';
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
        
        wpvc_rainbow_fireflies('#rainbow_fireflies').addClass('vcrbp_particles');
        
        wp_run_function(element_this);
    }
    
    wpvc_rainbow_fireflies(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_rainbow_fireflies(document).find("div#wpvcrffp_vc_row").each(function() {
            wp_vc_rainbow_fireflies_function(this);
            wpvc_rainbow_fireflies(this).remove();
		} );
        
        wpvc_rainbow_fireflies(document).find("div#wpvcrffp_vc_column").each(function() {
            wp_vc_rainbow_fireflies_function(this);
            wpvc_rainbow_fireflies(this).remove();
		} );
    });
    
    wpvc_rainbow_fireflies("span.vc_general.vc_ui-button.vc_ui-button-shape-rounded.vc_ui-button-fw.vc_ui-button-action", window.parent.document).bind("click", function() {
        wpvc_admin_rainbow_fireflies();
    });
    
    wpvc_rainbow_fireflies("span.vc_general.vc_ui-button.vc_ui-button-action.vc_ui-button-shape-rounded.vc_ui-button-fw", window.parent.document).bind("click", function() {
        wpvc_admin_rainbow_fireflies();
    });
    
    var wpvc_admin_rainbow_fireflies = function() {
        wpvc_rainbow_fireflies(document).find(".vcrbp_particles").remove();

        setTimeout( function () {
            wpvc_rainbow_fireflies(document).find("div#wpvcrffp_vc_row").each(function() {
                wp_vc_rainbow_fireflies_function(this);
                wpvc_rainbow_fireflies(this).remove();
            } );
            
            wpvc_rainbow_fireflies(document).find("div#wpvcrffp_vc_column").each(function() {
                wp_vc_rainbow_fireflies_function(this);
                wpvc_rainbow_fireflies(this).remove();
            } );            
        }, 2000 );
    };
})(jQuery);