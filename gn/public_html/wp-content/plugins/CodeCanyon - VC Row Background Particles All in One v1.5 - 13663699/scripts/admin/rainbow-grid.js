/*
 * this is a modified recreation of @jackrugile's awesomepen:
 * Rainbow Grid http://codepen.io/jackrugile/details/bdwvMo/
 *
 * I have no credit over the idea, but the code is original and side option comes from this pen
 */

(function(wpvc_rainbow_grid) {    
    function loop() {
      
      window.requestAnimationFrame( loop );
      
      loop.step();
      loop.draw();
    }

    loop.step = function() {
      
      loop.step.spawn();
      loop.step.updateTick();
      
      lines.map( function( line ) { line.step(); } );
    }
    loop.draw = function() {
      
      linePart = 1 / lineMidPoints;
      s = gridSide;
      
      loop.draw.repaint();
      loop.draw.transform();
      
      lines.map( function( line ) { line.draw(); } );
      
      ctx.restore();
    }

    loop.step.spawn = function() {
      
      if( lines.length < lineMaxCount && Math.random() < lineSpawnProb )
        lines.push( new Line );
    }
    loop.step.updateTick = function() {
      
      ++tick;
    }
    loop.draw.repaint = function() {
      
      ctx.globalCompositeOperation = 'destination-out';
      ctx.fillStyle = 'rgba(0,0,0,alp)'.replace( 'alp', gridRepaintAlpha );
      ctx.fillRect( 0, 0, w, h );
      ctx.globalCompositeOperation = 'lighter';
    }
    loop.draw.transform = function() {
      
      ctx.save();
      var scaleFactor = 1 + Math.sin( tick * gridScalingInputMultiplier ) * gridScalingMultiplier;
      
      ctx.translate( gridCenterX, gridCenterY );
      ctx.rotate( tick * gridRotationVel );
      ctx.scale( scaleFactor, scaleFactor );
      
      ctx.lineWidth = .2;
    }

    function Line() {
      
      this.reset();
    }
    Line.prototype.reset = function() {
      
      this.head = new Vec( 0, 0 );
      this.path = [ this.head ];
      
      this.life = 0;
      this.hue = ( ( tick * gridHueChange ) % 360 ) |0;
    }

    Line.prototype.step = function() {
      
      ++this.life;
      
      this.step_increment();
      this.step_decrement();
    }
    Line.prototype.draw = function() {
      
      if( this.path.length === 0 ) return;
      
      var	x1 = this.path[ 0 ].x,
          y1 = this.path[ 0 ].y,
          x2, y2, dx, dy;
      
      for( var i = 1; i < this.path.length; ++i ) {
        // I start from 1 intentionally, so that I don't have to do any checkings
        
        ctx.strokeStyle = 'hsla(hue, 80%, 50%, alp)'
          .replace( 'hue', this.hue + ( Math.random() * lineHueVariation ) |0 )
          .replace( 'alp', lineAlpha / ( this.life / 80 ) );
        
        x2 = this.path[ i ].x;
        y2 = this.path[ i ].y;
        
        dx = ( x2 - x1 ) / lineMidPoints;
        dy = ( y2 - y1 ) / lineMidPoints;
        
        ctx.beginPath();
        ctx.moveTo( x1 * s + Math.random() * lineMidJitter - lineMidJitter / 2, y1 * s + Math.random() * lineMidJitter - lineMidJitter / 2 );
        
        for( var j = 1; j < lineMidPoints - 1; ++j )
          ctx.lineTo(
         // initial + step portion + jitter
            ( x1 + dx * j ) * s + Math.random() * lineMidJitter - lineMidJitter / 2,
            ( y1 + dy * j ) * s + Math.random() * lineMidJitter - lineMidJitter / 2
          );
        
        ctx.lineTo( x2 * s + Math.random() * lineMidJitter - lineMidJitter / 2, y2 * s + Math.random() * lineMidJitter - lineMidJitter / 2 );
        ctx.stroke();
        
        x1 = x2;
        y1 = y2;
      }
    }
    Line.prototype.step_increment = function() {
      
      if( Math.random() > lineIncrementProb ) return;
      
      var vec,
          lastHead = this.path[ this.path.length - 2 ];
      
      do {
        
        vec = randomPos( this.head );
      } while ( lastHead && vec.x === lastHead.x && vec.y === lastHead.y );
      
      this.head = vec;
      this.path.push( vec );
      
      if( this.path.length >= lineMaxLength ) this.path.shift();
    }
    Line.prototype.step_decrement = function() {
      
      if( this.life < lineSafeTime || Math.random() > lineDecrementProb ) return;
      
      this.path.length > 0 ?
        this.path.shift()
        :
        this.reset();
      
    }

    function Vec( x, y ) {
      
      this.x = x;
      this.y = y;
    }

    function randomPos( previous ) {
      
      var rad = radPart * ( ( Math.random() * gridSideNum ) |0 );
      
      return new Vec( Math.cos( rad ) + previous.x, Math.sin( rad ) + previous.y );
    }
    
    // Effect function
    var wp_run_function = function(element_this) {
        var selector = wpvc_rainbow_grid(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
        if (bgActive)
            selector.prev().css( 'background-color' , bgColor );
            
        w = rainbow_grid.width = window.innerWidth,
            h = rainbow_grid.height = window.innerHeight,
            ctx = rainbow_grid.getContext( '2d' ),
            
            lineMaxCount = selector.data('linemaxcount'),
            lineSpawnProb = selector.data('linespawnprob'),
            lineMaxLength = selector.data('linemaxlength'),
            lineIncrementProb = selector.data('lineincrementprob'),
            lineDecrementProb = selector.data('linedecrementprob'),
            lineSafeTime = selector.data('linesafetime'),
            lineMidJitter = selector.data('linemidjitter'),
            lineMidPoints = selector.data('linemidpoints'),
            lineHueVariation = selector.data('linehuevariation'),
            lineAlpha = selector.data('linealpha'),

            gridSideNum = selector.data('gridsidenum'),
            gridSide = selector.data('gridside'),
            gridRotationVel = selector.data('gridrotationvel'),
            gridScalingInputMultiplier = selector.data('gridscalinginputmultiplier'),
            gridScalingMultiplier = selector.data('gridscalingmultiplier'),
            gridHueChange = selector.data('gridhuechange'),
            gridRepaintAlpha = 0.1,
            gridCenterX = w/2,
            gridCenterY = h/2,
            
            tick = ( Math.random() * 360 ),
            lines = [],
            radPart = Math.PI * 2 / gridSideNum;
                
        // Start
        loop();
    }
    
    var wp_vc_rainbow_grid_function = function(element_this) {
        var selector = wpvc_rainbow_grid(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'rainbow_grid';
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
        
        wpvc_rainbow_grid('#rainbow_grid').addClass('vcrbp_particles');
        
        wp_run_function(element_this);
    }
    
    wpvc_rainbow_grid(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_rainbow_grid(document).find("div#wpvcrgp_vc_row").each(function() {
            wp_vc_rainbow_grid_function(this);
            wpvc_rainbow_grid(this).remove();
		} );
        
        wpvc_rainbow_grid(document).find("div#wpvcrgp_vc_column").each(function() {
            wp_vc_rainbow_grid_function(this);
            wpvc_rainbow_grid(this).remove();
		} );
    });
    
    wpvc_rainbow_grid("span.vc_general.vc_ui-button.vc_ui-button-shape-rounded.vc_ui-button-fw.vc_ui-button-action", window.parent.document).bind("click", function() {
        wpvc_admin_rainbow_grid();
    });
    
    wpvc_rainbow_grid("span.vc_general.vc_ui-button.vc_ui-button-action.vc_ui-button-shape-rounded.vc_ui-button-fw", window.parent.document).bind("click", function() {
        wpvc_admin_rainbow_grid();
    });
    
    var wpvc_admin_rainbow_grid = function() {
        wpvc_rainbow_grid(document).find(".vcrbp_particles").remove();

        setTimeout( function () {
            wpvc_rainbow_grid(document).find("div#wpvcrgp_vc_row").each(function() {
                wp_vc_rainbow_grid_function(this);
                wpvc_rainbow_grid(this).remove();
            } );
            
            wpvc_rainbow_grid(document).find("div#wpvcrgp_vc_column").each(function() {
                wp_vc_rainbow_grid_function(this);
                wpvc_rainbow_grid(this).remove();
            } );
        }, 2000 );
    };
})(jQuery);