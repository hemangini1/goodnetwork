(function($) {
	'use strict';

	var JobifyWPJobManagerWith = {
		cache: {
			$document: $(document),
			$window: $(window)
		},

		init: function() {
			this.bindEvents();
		},

		bindEvents: function() {
			var self = this;

			this.cache.$document.on( 'ready', function() {
				self.initApplyWith();
			});
		},

		initApplyWith: function() {
			$( '.wp-job-manager-application-details' )
				.addClass( 'modal' )
				.on( 'wp-job-manager-application-details-show', function(e) {
					Jobify.App.popup({
						items : {
							src : $( e.delegateTarget )
						}
					});
				})
		}
	};

	JobifyWPJobManagerWith.init();

})(jQuery);
