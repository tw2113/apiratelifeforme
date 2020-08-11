(function () {
	"use strict";

	if (typeof zerospam.key != 'undefined') {
		var form_el = document.getElementById( 'commentform' );

		if ( null !== form_el ) {
			form_el.addEventListener(
				'submit',
				function (evt) {
					var hidden_el = document.createElement( 'input' );

					hidden_el.type = 'hidden';
					hidden_el.name = 'wpzerospam_key';
					hidden_el.value = zerospam.key;
					form_el.appendChild( hidden_el );
				}
			);

		}
	}
})();
