/**
 * Module: VIN Filter
 * @desc	Manage the limitations of the VIN search input field
 */
const defaults = {
	debug:          false,
	targetElement:  document.getElementById('js-vin-input'),
	alertElement:   document.getElementById('js-vin-alert'),
	formElement:    document.getElementById('formsubmit'),
	buttonElement:  document.getElementById('js-vin-btn'),
	dismissElement: document.getElementById('js-vin-dismiss'),
	photoLookup: 	document.getElementById('plate_photo'),
	loadingModal:   document.getElementById('loading-modal'),
};

/**
 * Constructor
 * @param {Object} options Optional properties to override defaults
 */
function VINFilter(options) {
	this.options = $.extend({}, defaults, options);
	this.init();
}

/**
 * Setup
 */
VINFilter.prototype.init = function () {
	const self  = this,
		  d     = self.options.debug;

		  console.log(self.options);
	// return [ HTMLCollection array if a .class || HTML object if a #identifier ]
	const vin_form              = self.options.formElement,
		  vin_input             = self.options.targetElement,
		  vin_alert             = self.options.alertElement,
		  vin_error_msgs        = document.querySelectorAll('[data-error-type]'),
		  vin_dismiss           = self.options.dismissElement,
		  vin_submit            = self.options.buttonElement,
		  vin_photo_lookup      = self.options.photoLookup,
		  loading_modal      	= self.options.loadingModal;

	let   vin_scroll_pos        = vin_form.offsetParent,
		  vin_scroll_to         = vin_scroll_pos.offsetTop + 100;

	// single error (at a time) validation conditions
	const validations = {
		// set the property name as a [data-validation="{property}"] on the input field receiving validate()
		// goal is to return true, then try the next validation requirement (order matters)
		required: function(value) {
			return value !== '';
		},
		special: function(value) {
			return value.match(/[^a-zA-Z0-9]/g) == null;
		},
		restricted: function(value) {
			return value.match(/[IiOoQq]/g) == null;
		},
		quantity: function(value) {
			return value.length > 16;
		},
	};

	// dismiss all errors
	function cleanErrors() {
		d ? console.log('%c\u2713 %cCleared the ' + vin_error_msgs.length + ' alert messages', 'color: green', 'color: white') : null;

		for (let i = 0; i < vin_error_msgs.length; i++) {
			vin_error_msgs[i].setAttribute('data-validation-error', 'false');
		}
	}

	// scroll to input field when error occurs
	function scrollTo(element, to, duration) {
		if (duration <= 0) return;
		let difference  = to - element.scrollTop;
		let perTick     = difference / duration * 10;

		setTimeout(function() {
			element.scrollTop = element.scrollTop + perTick;
			if (element.scrollTop === to) return;
			scrollTo(element, to, duration - 10);
		}, 10);
	}

	// validation function
	function validate() {
		// verify the input is within the DOM...
		let instance    = 0,
			input       = vin_form.querySelectorAll('input');

		function validation(e) {
			// once declared within the DOM...
			while ( instance < input.length ) {
				let attr        = vin_input.getAttribute('data-validation'),
					rules       = attr ? attr.split(' ') : '',
					error       = 0,
					value       = vin_input.value,
					req1_pass   = validations.required(value),
					req2_pass   = validations.special(value),
					req3_pass   = validations.restricted(value),
					req4_pass   = validations.quantity(value);

				// while results are present on the target element
				while ( error < rules.length ) {
					if ( !validations[rules[error]](value) ) {
						e.preventDefault();

						// clean the errors before setting them (in case of repeated errors)
						cleanErrors();

						// scroll to the input field when error is detected
						scrollTo(document.documentElement, vin_scroll_to, 600);

						// set validation visual on elements
						vin_input.dataset.validationError = 'true';
						vin_alert.dataset.validationError = 'true';

						// create an array for the error messages; used for showing error specific messages
						let error_text = document.querySelectorAll('[data-error-type]');

						// not empty
						if (req1_pass === false) {
							d ? console.warn('%c\u2718 %cVIN Error: ' + 'required', 'color: red', 'color: white') : null;
							error_text[0].dataset.validationError = 'true';
						}

						// using special characters
						if (req2_pass === false) {
							d ? console.warn('%c\u2718 %cVIN Error: ' + 'special', 'color: red', 'color: white') : null;
							error_text[1].dataset.validationError = 'true';
						}

						// prohibited characters
						if (req3_pass === false) {
							d ? console.warn('%c\u2718 %cVIN Error: ' + 'prohibited', 'color: red', 'color: white') : null;
							error_text[2].dataset.validationError = 'true';
						}

						// quantity is at least 17
						if (req4_pass === false) {
							d ? console.warn('%c\u2718 %cVIN Error: ' + 'quantity', 'color: red', 'color: white') : null;
							error_text[3].dataset.validationError = 'true';
						}
						return false;
					}
					error++;
				}
				instance++;
			}

			e.preventDefault(); // enforce the prevention of the form submitting
			// conditional logic to support password protected vin search bypass of recaptcha
			if (document.location.href.indexOf('protected') === -1) { 
			    grecaptcha.execute(); // call reCAPTCHA
			} else {
				vin_form.submit(); 
			}
		}

		// pushed "Enter" key when input:focus
		vin_form.addEventListener('submit', function(e) {
			d ? console.log('%c\u2713 %c"Enter" submit intercepted', 'color: green', 'color: white') : null;

			validation(e);

		}, false);

		// clicks submit button
		vin_submit.addEventListener('click', function(e) {
			d ? console.log('%c\u2713 %c"Button" click submit intercepted', 'color: green', 'color: white') : null;

			validation(e);

		}, false);

		// clicks photo lookup
		vin_photo_lookup.addEventListener('change', function(e) {
			loading_modal.classList.add('active');
			document.body.classList.add('locked');
			vin_form.submit();
		}, false);
	}

	// add the function to the global scope if the VIN input is present in DOM
	if (vin_input) validate();

	// custom dismiss logic; bootstrap dismiss destroys DOMElement instance
	vin_dismiss.addEventListener('click', function() {
		d ? console.log('%c\u2713 %cDismissing alert', 'color: green', 'color: white') : null;

		// reset validation visuals
		vin_input.dataset.validationError = 'false';
		vin_alert.dataset.validationError = 'false';
	});
};

/**
 * Form submission for use by reCAPTCHA.
 */
VINFilter.prototype.submitForm = function () {
	this.options.formElement.submit();
};

/**
 * Public API
 */
module.exports = {
	init: function (opts) {
		return new VINFilter(opts);
	}
};
