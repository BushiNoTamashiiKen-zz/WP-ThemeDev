(function($) {
	_bogo = _bogo || {};

	$(function() {
		$('body.nav-menus-php .menu-item').bogoAddLocaleSelector();
	});

	$(document).ajaxSuccess(function(event, request, settings) {
		$('body.nav-menus-php .menu-item').bogoAddLocaleSelector();
	});

	$.fn.bogoAddLocaleSelector = function() {
		return this.each(function() {
			if (_bogo.hasSelector(this)) {
				return;
			}

			var id = $(this).attr('id').replace('menu-item-', '');
			var $selector = _bogo.selector(id);
			$(this).find('.menu-item-settings').prepend($selector);
		});
	}

	_bogo.hasSelector = function(elm) {
		return $(elm).is(':has(.bogo-locale-options)');
	}

	_bogo.selector = function(id) {
		var $selector = $('<fieldset class="bogo-locale-options"></fieldset>');

		if (_bogo.availableLanguages) {
			var $legend = $('<legend></legend>').append(_bogo.selectorLegend);
			$selector.append($legend);

			$.each(_bogo.availableLanguages, function(i, val) {
				var checked = false;

				if (! _bogo.locales[id] || -1 < $.inArray(i, _bogo.locales[id])) {
					checked = true;
				}

				$selector.append(_bogo.checkbox(id, i, checked));
			});
		}

		return $selector;
	}

	_bogo.checkbox = function(id, locale, checked) {
		var prefix = _bogo.cbPrefix || 'bogo-locale';
		var name_attr = prefix + '[' + id + '][]';
		var id_attr = 'edit-' + prefix + '-' + id + '-' + locale;

		var $cb = $('<input type="checkbox" />');
		$cb.attr('name', name_attr);
		$cb.attr('id', id_attr);
		$cb.attr('value', locale);
		$cb.prop('checked', checked);

		var $label = $('<label class="bogo-locale-option"></label>');
		$label.attr('for', id_attr);
		$label.append(_bogo.langName(locale));

		if (checked) {
			$label.addClass('checked');
		}

		return $label.prepend($cb);
	}

	_bogo.langName = function(locale) {
		return _bogo.availableLanguages[locale] || '';
	}

	$(function() {
		$('body.options-general-php select#WPLANG').each(function() {
			$(this).find('option[selected="selected"]').removeAttr('selected');
			var val = _bogo.defaultLocale || 'en_US';
			val = ( 'en_US' == val ? '' : val );
			$(this).find('option[value="' + val + '"]').first().attr('selected', 'selected');
		});
	});

	$(function() {
		$('#bogo-add-translation').click(function() {
			if (! _bogo.post_id) {
				return;
			}

			var locale = $('#bogo-translations-to-add').val();
			var rest_url = _bogo.rest_api.url
				+ 'posts/' + _bogo.post_id
				+ '/translations/' + locale;
			$('#bogo-add-translation').next('.spinner').css('visibility', 'visible');

			$.ajax({
				type: 'POST',
				url: rest_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader('X-WP-Nonce', _bogo.rest_api.nonce);
				}
			}).done(function(response) {
				var post = response[locale];

				if (! post) { return; }

				var $added = $('<a></a>').attr({
					href: post.edit_link,
					target: '_blank'
				}).html(post.title.rendered);

				$added = $('<li></li>').append($added).append(
					' [' + post.lang.name + ']');
				$('#bogo-translations').append($added);

				$('#bogo-translations-to-add option[value="' + locale + '"]').detach();

				if ($('#bogo-translations-to-add option').length < 1) {
					$('#bogo-add-translation-actions').detach();
				}
			}).always(function() {
				$('#bogo-add-translation').next('.spinner').css('visibility', 'hidden');
			});
		});
	});

})(jQuery);
