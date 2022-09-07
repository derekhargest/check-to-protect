/*
 *  Module: Analytics
 */

const defaults = {
	attrName: "track-event",
	addGA: true,
	gaid: "",
    addGTM: true,
    gtmid: ""
};

/**
 * Constructor
 * @param {Object} options Optional properties to override defaults
 */
function Analytics(options) {
	this.options = $.extend({}, defaults, options);
	this.init();
}

/**
 * Setup
 */
Analytics.prototype.init = function () {
	const self = this;

	// add GA script if not yet in DOM
	if (self.options.addGA === true && !window.ga) {
		self.addGA();
	}
    
    // add GTM script
    if( self.options.addGTM === true && !window.google_tag_manager ) {
        self.addGTM();
    }

	// apply event tracking to all elements with appropriate data-attribute
	$('[data-' + self.options.attrName + ']').each(function () {
		$(this).on('click', function() {
			self.trackEvent(this);
		});
	});
};

/**
 * Adds GA script to DOM and triggers pageview event
 */
Analytics.prototype.addGA = function(i, s, o, g, r, a, m) {
	var self = this,
		i = i || window,
		s = s || document,
		o = o || 'script',
		g = g || '//www.google-analytics.com/analytics.js',
		r = r || 'ga';

	i['GoogleAnalyticsObject'] = r;
	i[r] = i[r] || function() {
		(i[r].q = i[r].q || []).push(arguments)
	}, i[r].l = 1 * new Date();
	a = s.createElement(o),
	m = s.getElementsByTagName(o)[0];
	a.async = 1;
	a.src = g;
	m.parentNode.insertBefore(a, m);

	/**
	 * Set up GA account and log pageview
	 */
	i.ga('create', self.options.gaid);
	i.ga('send', 'pageview');
};

/**
 * Adds GTM script to DOM
 */
Analytics.prototype.addGTM = function(w, d, s, l, i) {
	var self = this,
		w = w || window,
		d = d || document,
		s = s || 'script',
		l = l || 'dataLayer',
		i = i || self.options.gtmid;
    
    w[l]=w[l]||[];
    w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});

    var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';

    j.async=true;
    j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
};

/**
 * Triggers a GA custom event based on the triggering element's data
 * @param  {Object} el The element that triggered the event
 */
Analytics.prototype.trackEvent = function(el) {
	var self = this,
		$el = $(el),
		url = $el.attr("href"),
		data = $el.data(self.options.attrName);

	// If the data attribute isn't present exit
	if (!data) {
		return false;
	}

	// Turn the data string into an array
	data = data.split(",");

	// Trim whitespace from data
	$.each(data, function(i) {
		data[i] = this.trim(data[i]);
	});

	// Send data to GA: category, action, label (if link then href else another value), value (number)
	window.ga('send', 'event', data[0], data[1], (url || data[2]), (data[3] || 1));

	// If active link, delay so we can capture event then follow it
	// http://support.google.com/analytics/bin/answer.py?hl=en&answer=1136920
	if (url) {
		setTimeout(function() {
			return true;
		}, 100);
	}
};

/**
 * Public API
 */
module.exports = {
	init: function (opts) {
		return new Analytics(opts);
	}
};
