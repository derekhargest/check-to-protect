/**
 * Module: Scroll within
 * @desc	smoothly scroll within a page for anchors (Skip to Content)
 * @url		https://goo.gl/JTpbXJ
 */
const defaults = {
	duration:       '',
	animateTarget:  '',
	offset:         ''
};

/**
 * Constructor
 * @param {Object} options Optional properties to override defaults
 */
function ScrollWithin(options) {
	this.options = $.extend({}, options);
	this.init();
}

/**
 * Setup
 */
ScrollWithin.prototype.init = function () {
	const self = this;

	function filterPath(string) {
		return string
			.replace(/^\//, '')
			.replace(/(index|default).[a-zA-Z]{3,4}$/, '')
			.replace(/\/$/, '');
	}

	const locationPath = filterPath(location.pathname);

	$( 'a[href*="#"]' ).each(function () {
		const thisPath  = filterPath(this.pathname) || locationPath;
		const hash      = this.hash;

		if ( $( "#" + hash.replace( /#/, '' ) ).length ) {

			if (locationPath === thisPath && (location.hostname === this.hostname || !this.hostname) && this.hash.replace(/#/, '')) {
				const $target = $(hash), target = this.hash;

				if (target) {
					$(this).on( "click", function (e) {
						e.preventDefault();

						$( self.options.animateTarget ).animate({ scrollTop: $target.offset().top - self.options.offset }, self.options.duration, function () {
							location.hash = target;

							if ( $target.is( ':focus' ) ) {
								return false;
							} else {
								$target.attr( 'tabindex', '-1' );
							}
						});
					});
				}
			}
		}
	});
};

/**
 * Public API
 */
module.exports = {
	init: function (opts) {
		return new ScrollWithin(opts);
	}
};
