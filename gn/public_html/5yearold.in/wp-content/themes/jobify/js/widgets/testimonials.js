(function() {
  jQuery(function($) {
    var companies, testimonials;
    companies = function() {
      return $('.company-slider .testimonials-list').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        adaptiveHeight: true,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 3
            }
          }, {
            breakpoint: 992,
            settings: {
              slidesToShow: 2
            }
          }, {
            breakpoint: 768,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
    };
    testimonials = function() {
      $('.testimonial-slider .testimonials-list').slick({
        infinite: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        adaptiveHeight: true,
        responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 2
            }
          }, {
            breakpoint: 768,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
      return $('.testimonial-slider .testimonials-list').slick('setPosition');
    };
    companies();
    return testimonials();
  });

}).call(this);
