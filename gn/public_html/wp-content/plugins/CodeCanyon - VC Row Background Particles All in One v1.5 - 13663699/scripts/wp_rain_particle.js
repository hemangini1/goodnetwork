/* 
Copyright (c) 2015 by Delapierre Rodolphe (http://codepen.io/Sheepeuh/pen/cFazd)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/ 

(function(wpvc_rain_particle) {  
    requestAnimFrame = (function() {
        return window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function(callback) {
            window.setTimeout(callback, 1000/60);
        };
    })();

    function Rain(X, Y, nombre) {
        if (!nombre) {
            nombre = nombreb;
        }
        while (nombre--) {
            if (multi == true) {
                particules.push( 
                {
                    vitesseX : (Math.random() * 0.25),
                    vitesseY : (Math.random() * 9) + 1,
                    X : X,
                    Y : Y,
                    alpha : 1,
                    couleur : "hsla(" + color + "," + saturation + "%, " + lightness + "%," + opacity + ")",
                })
            } else {
                particules.push( 
                {
                    vitesseX : (Math.random() * 0.25),
                    vitesseY : (Math.random() * 9) + 1,
                    X : X,
                    Y : Y,
                    alpha : 1,
                    couleur : rainColor,
                })
            }
        }
    }

    function explosion(X, Y, couleur, nombre) {
        if (!nombre) {
            nombre = nombrebase;
        }
        while (nombre--) {
            gouttes.push( 
            {
                vitesseX : (Math.random() * 4-2	),
                vitesseY : (Math.random() * -4 ),
                X : X,
                Y : Y,
                radius : 0.65 + Math.floor(Math.random() *1.6),
                alpha : 1,
                couleur : couleur
            })
        }
    }

    function rendu(ctx) {

        if (multi == true) {
            color = Math.random()*360;
        }

        ctx.save();
        
        // Wipe canvas
        ctx.clearRect(0, 0, width, height);
        if (bgActive) {
            ctx.fillStyle = bgColor;
            ctx.fillRect(0, 0, width, height);
        }
        
        var particuleslocales = particules;
        var goutteslocales = gouttes;
        var tau = Math.PI * 2;

        for (var i = 0, particulesactives; particulesactives = particuleslocales[i]; i++) {
                
            ctx.globalAlpha = particulesactives.alpha;
            ctx.fillStyle = particulesactives.couleur;
            ctx.fillRect(particulesactives.X, particulesactives.Y, particulesactives.vitesseY/4, particulesactives.vitesseY);
        }

        for (var i = 0, gouttesactives; gouttesactives = goutteslocales[i]; i++) {
                
            ctx.globalAlpha = gouttesactives.alpha;
            ctx.fillStyle = gouttesactives.couleur;
            
            ctx.beginPath();
            ctx.arc(gouttesactives.X, gouttesactives.Y, gouttesactives.radius, 0, tau);
            ctx.fill();
        }
        
        ctx.strokeStyle = "white";
        ctx.lineWidth   = 2;

        // if (objectType == "Umbrella") {
            // ctx.beginPath();
            // ctx.arc(mouse.X, mouse.Y+10, 138, 1 * Math.PI, 2 * Math.PI, false);
            // ctx.lineWidth = 3;
            // ctx.strokeStyle = "hsla(0,100%,100%,1)";
            // ctx.stroke();
        // }
        // if (objectType == "Cup") {
            // ctx.beginPath();
            // ctx.arc(mouse.X, mouse.Y+10, 143, 1 * Math.PI, 2 * Math.PI, true);
            // ctx.lineWidth = 3;
            // ctx.strokeStyle = "hsla(0,100%,100%,1)";
            // ctx.stroke();
        // }
        // if (objectType == "Circle") {
            // ctx.beginPath();
            // ctx.arc(mouse.X, mouse.Y+10, 138, 1 * Math.PI, 3 * Math.PI, false);
            // ctx.lineWidth = 3;
            // ctx.strokeStyle = "hsla(0,100%,100%,1)";
            // ctx.stroke();
        // }
        ctx.restore();
        
        // if (auto) {
            // color += speed;
            // if (color >=360) {
                // color = 0;
            // }
        // }
    }

    function update() {

        var particuleslocales = particules;
        var goutteslocales = gouttes;
        
        for (var i = 0, particulesactives; particulesactives = particuleslocales[i]; i++) {
            particulesactives.X += particulesactives.vitesseX;
            particulesactives.Y += particulesactives.vitesseY+5;
            if (particulesactives.Y > height-15) {
                particuleslocales.splice(i--, 1);
                explosion(particulesactives.X, particulesactives.Y, particulesactives.couleur);
            }
            // var umbrella = (particulesactives.X - mouse.X)*(particulesactives.X - mouse.X) + (particulesactives.Y - mouse.Y)*(particulesactives.Y - mouse.Y);
            // if (objectType == "Umbrella") {
                // if (umbrella < 20000 && umbrella > 10000 && particulesactives.Y < mouse.Y) {
                    // explosion(particulesactives.X, particulesactives.Y, particulesactives.couleur);
                    // particuleslocales.splice(i--, 1);
                // }
            // }
            // if (objectType == "Cup") {
                // if (umbrella > 20000 && umbrella < 30000 && particulesactives.X+138 > mouse.X && particulesactives.X-138 < mouse.X && particulesactives.Y > mouse.Y) {
                    // explosion(particulesactives.X, particulesactives.Y, particulesactives.couleur);
                    // particuleslocales.splice(i--, 1);
                // }
            // }
            // if (objectType == "Circle") {
                // if (umbrella < 20000) {
                    // explosion(particulesactives.X, particulesactives.Y, particulesactives.couleur);
                    // particuleslocales.splice(i--, 1);
                // }
            // }
        }

        for (var i = 0, gouttesactives; gouttesactives = goutteslocales[i]; i++) {
            gouttesactives.X += gouttesactives.vitesseX;
            gouttesactives.Y += gouttesactives.vitesseY;
            gouttesactives.radius -= 0.075;
            if (gouttesactives.alpha > 0) {
                gouttesactives.alpha -= 0.005;
            } else {
                gouttesactives.alpha = 0;
            }
            if (gouttesactives.radius < 0) {
                goutteslocales.splice(i--, 1);
            }
        }

        var i = rain;
        while (i--) {
            Rain(Math.floor((Math.random()*width)), -15);
        }
    }

    function Screenshot() {
        window.open(rain_particle.toDataURL());
    }

    // window.onload = function() {
        // var gui = new dat.GUI();
        // gui.add(controls, 'rain', 1, 10).name("Rain intensity").step(1);
        // gui.add(controls, 'alpha', 0.1, 1).name("Alpha").step(0.1);
        // gui.add(controls, 'color', 0, 360).name("Color").step(1).listen();
        // gui.add(controls, 'auto').name("Automatic color");
        // gui.add(controls, 'speed', 1, 10).name("Speed color").step(1);
        // gui.add(controls, 'multi').name("Multicolor");
        // gui.add(controls, 'saturation', 0, 100).name("Saturation").step(1);
        // gui.add(controls, 'lightness', 0, 100).name("Lightness").step(1);
        // gui.add(controls, 'opacity', 0.0, 1).name("Opacity").step(0.1);
        // gui.add(controls, 'objectType', ['Nothing','Circle','Umbrella', 'Cup']);
        // gui.add(window, 'Screenshot');
        // var Background = gui.addFolder('Background color');
        // Background.add(controls, 'red', 0, 255).step(1);
        // Background.add(controls, 'green', 0, 255).step(1);
        // Background.add(controls, 'blue', 0, 255).step(1);
    // };

    function boucle() {
        requestAnimFrame(boucle);
        update();
        rendu(ctx);
    };
    
    // Effect function
    var wp_run_function = function(element_this) {
        var selector = wpvc_rain_particle(element_this);
        
        // Get options
        bgActive = selector.data('bgactive');
        bgColor = selector.data('bgcolor');
        if (bgActive)
            selector.css( 'background-color' , bgColor );
            
        ctx = rain_particle.getContext('2d');

        width = 0;
        height = 0;
        mouse = {
            X : 0,
            Y : 0
        }
        
        particules = [];
        gouttes = [];
        nombrebase = 5;
        nombreb = 2;
        
        rain = selector.data('rain');
        // objectType = 'Nothing';
        // alpha = selector.data('alpha');
        color = 200;
        rainColor = selector.data('raincolor');
        // auto = selector.data('auto');
        opacity = 1;
        saturation = 100;
        lightness = 50;
        back = 100;
        // red = 0;
        // green = 0;
        // blue = 0;
        multi = selector.data('multi');
        // speed = selector.data('speed');

        window.onresize = function onresize() {
            if (wpvc_rain_particle('#rain_particle').length != 0) {
                width = rain_particle.width = window.innerWidth;
                height = rain_particle.height = window.innerHeight;
            }
        }

        window.onresize();

        window.onmousemove = function onmousemove(event) {
            mouse.X = event.clientX;
            mouse.Y = event.clientY;
        }
                
        // Start
        boucle();
    }
    
    var wp_vc_rain_particle_function = function(element_this) {
        var selector = wpvc_rain_particle(element_this);
        
        // Create canvas
        var canvas = document.createElement('canvas');
        canvas.id = 'rain_particle';
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
        
        wpvc_rain_particle('#rain_particle').addClass('vcrbp_particles');
        
        wp_run_function(element_this);
    }
    
    wpvc_rain_particle(document).ready(function() {
        // Check canvas support
        var canvasSupport = !!document.createElement('canvas').getContext;
        if (!canvasSupport) { return; }
        
        wpvc_rain_particle(document).find("div#wpvcrp_vc_row").each(function() {
            wp_vc_rain_particle_function(this);
            wpvc_rain_particle(this).remove();
		} );
        
        wpvc_rain_particle(document).find("div#wpvcrp_vc_column").each(function() {
            wp_vc_rain_particle_function(this);
            wpvc_rain_particle(this).remove();
		} );
    });
})(jQuery);