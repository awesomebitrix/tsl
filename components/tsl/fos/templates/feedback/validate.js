$(document).ready(function() {
	$form = $('.validate-form');
	$form
		// Проверяем поле по кейапу и изменению
		.on('change keyup', 'input, textarea', validateField);
});

function validate(form) {
	var $f = $(form),
		result = true,
		$btn = $f.find('.btn[type="submit"]');

	$f.find('input[required], textarea[required]').each(function() {
		var $field = $(this),
			$parent = $field.parent(),
			$message = $parent.children('.message');
		if( $field.val() < 3 ) {
			$parent.removeClass('ok').addClass('error');
			if($message.length) {
				$message.text('Заполните это поле');
			} else {
				$field.after('<span class="message">Заполните это поле</span>');
			}
			result = false;
		} else {
			$parent.addClass('ok').removeClass('error');
			$message.remove();
		}
	});
	$f.find('input[type="email"]').each(function() {
		var $field = $(this),
			pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i,
			$parent = $field.parent(),
			$message = $parent.children('.message');

		if( $field.val().search(pattern) != 0 ) {
			$parent.removeClass('ok').addClass('error');
			if($message.length) {
				$message.text('Некоррентная эл. почта');
			} else {
				$field.after('<span class="message">Некоррентная эл. почта</span>');
			}
			result = false;
		} else {
			$parent.addClass('ok').removeClass('error');
			$message.remove();
		}
	});
	return result;
}

function validateField() {
	var $field = $(this),
		$f = $field.closest('.validate-form'),
		$parent = $field.parent(),
		$message = $parent.children('.message'),
		required = true,
		pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i,
		email = true;
	if($field.attr('required') != 'undefined') {
		if( $field.val().length >= 3 ) {
			required = true;
		} else {
			required = false;
		}
	}
	if($field.attr('type') == 'email') {
		if( $field.val().search(pattern) == 0 ) {
			email = true;
		} else {
			email = false;
		}
	}
	if(required && email) {
		$parent.addClass('ok').removeClass('error');
		$message.remove();
	} else {
		if( ! $parent.hasClass('error') ) {
			$parent.removeClass('ok');
			$message.remove();
		}
	}
}