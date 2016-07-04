/* Copyright (c) 2015, Vincent GarreauPermission is hereby granted, free of charge, to any person obtaining a copyof this software and associated documentation files (the "Software"), to dealin the Software without restriction, including without limitation the rightsto use, copy, modify, merge, publish, distribute, sublicense, and/or sellcopies of the Software, and to permit persons to whom the Software isfurnished to do so, subject to the following conditions:The above copyright notice and this permission notice shall be included inall copies or substantial portions of the Software.THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS ORIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THEAUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHERLIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS INTHE SOFTWARE.*/ (function(wpvclwp_particle) {    var wpvclwp_lightweight_effect_function = function(element_this, element_id) {                var selector = wpvclwp_particle(element_this);                particlesJS( element_id,                    {            "particles": {              "number": {                "value": selector.data('number'),                "density": {                  "enable": selector.data('activedensity'),                  "value_area": selector.data('density')                }              },              "color": {                "value": selector.data('color')              },              "shape": {                "type": selector.data('shapetype'),                "stroke": {                  "width": 0,                  "color": "#000000"                },                "polygon": {                  "nb_sides": selector.data('polygonsides')                }              },              "size": {                "value": selector.data('sizevalue'),                "random": selector.data('sizerandom'),                "anim": {                  "enable": false,                  "speed": 40,                  "size_min": 0.1,                  "sync": false                }              },              "opacity": {                "value": selector.data('opacityvalue'),                "random": selector.data('opacityrandom'),                "anim": {                  "enable": false,                  "speed": 1,                  "opacity_min": 0.1,                  "sync": false                }              },              "line_linked": {                "enable": selector.data('enablelinelinked'),                "distance": selector.data('linelinkeddistance'),                "color": selector.data('linelinkedcolor'),                "opacity": selector.data('linelinkedopacity'),                "width": selector.data('linelinkedwidth')              },              "move": {                "enable": selector.data('enablemove'),                "speed": selector.data('movespeed'),                "direction": selector.data('movedirection'),                "random": false,                "straight": false,                "out_mode": selector.data('moveoutmode'),                "attract": {                  "enable": selector.data('enableattract'),                  "rotateX": selector.data('attractrotatex'),                  "rotateY": selector.data('attractrotatey')                }              }            },            "interactivity": {              "detect_on": selector.data('interactiondetecton'),              "events": {                "onhover": {                  "enable": selector.data('interactionenableonhover'),                  "mode": selector.data('interactiononhovermode')                },                "onclick": {                  "enable": selector.data('interactionenableonclick'),                  "mode": selector.data('interactiononclickmode')                },                "resize": true              },              "modes": {                "grab": {                  "distance": selector.data('modegrabdistance'),                  "line_linked": {                    "opacity": selector.data('modegrablinelinkedopacity')                  }                },                "bubble": {                  "distance": selector.data('modebubbledistance'),                  "size": selector.data('modebubblesize'),                  "duration": selector.data('modebubbleduration'),                  "opacity": selector.data('modebubbleopacity'),                },                "repulse": {                  "distance": selector.data('moderepulsedistance'),                  "duration": selector.data('moderepulseduration')                },                "push": {                  "particles_nb": selector.data('modepushparticles')                },                "remove": {                  "particles_nb": selector.data('moderemoveparticles')                }              }            },            "retina_detect": true          }        );    }        wpvclwp_particle(document).ready(function() {        // Check canvas support        var canvasSupport = !!document.createElement('canvas').getContext;        if (!canvasSupport) { return; }                wpvclwp_particle(document).find("div#wpvclwp_vc_row").each(function() {            var selector = wpvclwp_particle(this);            var prev = selector.prev();                        if (prev.hasClass("vc_row-full-width") && prev.prev().hasClass("vc_row")) {                prev.prev().prepend(selector);            } else {                prev.prepend(selector);            }            wpvclwp_lightweight_effect_function( this, 'wpvclwp_vc_row' );		} );                wpvclwp_particle(document).find("div#wpvclwp_vc_column").each(function() {            var selector = wpvclwp_particle(this);            var prev = selector.prev();            prev.prepend(selector);            wpvclwp_lightweight_effect_function( this, 'wpvclwp_vc_column' );		} );    });    })(jQuery);	