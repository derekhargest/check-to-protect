/*
 * Main entry point
 */
import 'bootstrap/js/dist/alert';
import 'bootstrap/js/dist/collapse';

import print        from './components/print';
import UI           from './components/ui';
import VINFilter    from './components/vin-filter';
import social       from './components/social.js';

/**
 * Initialize the app on DOM ready
 */
$(function() {
	print.init();
	UI.init();
	const vinFilter = VINFilter.init({
		debug: false,
	});
	window.recaptchaCallback = function (response) {
		vinFilter.submitForm();
	};
	social.init();
});
