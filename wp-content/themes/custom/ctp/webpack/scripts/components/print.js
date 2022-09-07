/**
 * Module: print
 * General print behaviors
 */

function init() {
    $('.js-print').on('click', function() {
        var targetRecallNumber = $(this).data('target-recall-number'),
            targetContainer = $('.recalls .content');

        if( targetRecallNumber ) {
            targetContainer = $('#recall__' + targetRecallNumber );
        }

        var popup = window.open();

        popup.document.write( targetContainer.html() );
        popup.document.write('<link rel="stylesheet"  href="' + urls.theme + '/dist/styles/print-styles.min.css" media="all" />');
        setTimeout( function() {
            popup.print();
        }, 100 );
    });
}

/**
 * Public API
 * @type {Object}
 */
module.exports = {
	init: init
};
