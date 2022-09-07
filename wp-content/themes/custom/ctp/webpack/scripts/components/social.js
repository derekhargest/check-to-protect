/*
 *  Module: social
 */

var utilities = require('./utilities.js');

var defaults = {
	channelAttrName: "share-channel"
};

/**
 * Constructor
 * @param {Object} options Optional properties to override defaults
 */
function SocialShare(options) {
	this.options = $.extend({}, defaults, options);
	this.init();
}

/**
 * Setup
 */
SocialShare.prototype.init = function () {
	var self = this;
	// Grab all the elements with valid channel data attributes
	$('[data-' + self.options.channelAttrName + ']').each(function() {
		// on click trigger the appropriate share function
		$(this).on('click', function () {
			var data = $(this).data();
			var channel = data[ utilities.dashToCamel(self.options.channelAttrName) ];
			var url = self[channel + 'Share'](data);
			// if url is set open popup
			console.log("URL", url);
			var popup = (url) ? window.open(url, 'share', 'resizable=yes,scrollbars=yes,width=600,height=500') : false;
			return false;
		});
	});
}

/**
 * Builds Facebook share url
 * @return {String}
 */
SocialShare.prototype.facebookShare = function (details) {
	var u = details.u || document.location.href;
	var title = encodeURIComponent( details.title );
	return 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURI(u) + '&t=' + title;
}

/**
 * Builds Twitter share url
 * @return {String}
 */
SocialShare.prototype.twitterShare = function (details) {
	var url = details.url || document.location.href;
	var text = encodeURIComponent( details.text.substring(0, 140) ) || "";
	return 'http://twitter.com/share?url=' + encodeURI(url) + '&text=' + text;
}

/**
 * Builds Pinterest share url
 * @return {String}
 */
SocialShare.prototype.pinterestShare = function (details) {
	var url = details.url || document.location.href;
	var media = encodeURI(details.media) || "";
	var description = encodeURIComponent( details.description ) || "";
	return 'http://www.pinterest.com/pin/create/button/?url=' + encodeURI(url) + '&media=' + media + '&description=' + description;;
}

/**
 * Builds LinkedIn share url
 * @return {String}
 */
SocialShare.prototype.linkedinShare = function (details) {
	var url = details.url || document.location.href;
	var title = encodeURIComponent( details.title ) || "";
	var summary = encodeURIComponent( details.summary ) || "";
	var source = encodeURIComponent( details.source ) || "";
	return 'https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURI(url) + '&title=' + title + '&summary=' + summary + '&source=' + source;
}

/**
 * Builds Google+ share url
 * @return {String}
 */
SocialShare.prototype.googleplusShare = function (details) {
	var url = details.url || document.location.href;
	return 'https://plus.google.com/share?url=' + encodeURI(url);
}

/**
 * Public API
 * @type {Object}
 */
module.exports = {
	init: function (opts) {
		return new SocialShare(opts);
	}
};
