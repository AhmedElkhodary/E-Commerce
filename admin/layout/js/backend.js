$(function(){
	'use strict';

	// trigger the selectboxit

	$("select").selectBoxIt({

		autoWidth: false
	});

	//confirmation message on button
	$('.confirm').click(function(){
		return confirm('Sure?');

	});

	// hide placeholder form focus
	$('[placeholder]').focus(function(){

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function(){

		$(this).attr('placeholder', $(this).attr('data-text'));

	});

});	